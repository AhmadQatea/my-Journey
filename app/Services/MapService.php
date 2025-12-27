<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapService
{
    /**
     * حساب المسار بين نقاط متعددة باستخدام OSRM
     */
    public function calculateRoute(array $coordinates): ?array
    {
        if (count($coordinates) < 2) {
            return null;
        }

        try {
            // تحويل الإحداثيات إلى تنسيق OSRM (lng,lat)
            $waypoints = array_map(function ($coord) {
                return [$coord['lng'], $coord['lat']];
            }, $coordinates);

            // بناء URL لـ OSRM
            $coordinatesString = implode(';', array_map(function ($point) {
                return $point[0].','.$point[1];
            }, $waypoints));

            // استخدام OSRM Demo Server (يمكن استبداله بخادم خاص)
            $osrmUrl = "https://router.project-osrm.org/route/v1/driving/{$coordinatesString}?overview=full&geometries=geojson&steps=true";

            $response = Http::timeout(10)->get($osrmUrl);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['code']) && $data['code'] === 'Ok' && isset($data['routes'][0])) {
                    $route = $data['routes'][0];

                    return [
                        'geometry' => $route['geometry'] ?? null,
                        'distance' => $route['distance'] ?? 0, // بالمتر
                        'duration' => $route['duration'] ?? 0, // بالثواني
                        'steps' => $route['legs'] ?? [],
                    ];
                }
            }

            Log::warning('OSRM API returned error', ['response' => $response->body()]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error calculating route', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * جلب الفنادق القريبة من المسار باستخدام Overpass API
     */
    public function getNearbyHotels(array $coordinates, float $radius = 5000): array
    {
        if (empty($coordinates)) {
            return [];
        }

        try {
            // إنشاء bounding box من الإحداثيات
            $lats = array_column($coordinates, 'lat');
            $lngs = array_column($coordinates, 'lng');

            $minLat = min($lats) - 0.1;
            $maxLat = max($lats) + 0.1;
            $minLng = min($lngs) - 0.1;
            $maxLng = max($lngs) + 0.1;

            // Overpass QL query للبحث عن الفنادق
            $query = "
                [out:json][timeout:25];
                (
                  node[\"tourism\"=\"hotel\"]({$minLat},{$minLng},{$maxLat},{$maxLng});
                  way[\"tourism\"=\"hotel\"]({$minLat},{$minLng},{$maxLat},{$maxLng});
                  relation[\"tourism\"=\"hotel\"]({$minLat},{$minLng},{$maxLat},{$maxLng});
                );
                out center;
            ";

            $response = Http::timeout(30)
                ->asForm()
                ->post('https://overpass-api.de/api/interpreter', [
                    'data' => $query,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                $hotels = [];

                if (isset($data['elements'])) {
                    foreach ($data['elements'] as $element) {
                        $lat = $element['lat'] ?? ($element['center']['lat'] ?? null);
                        $lng = $element['lon'] ?? ($element['center']['lon'] ?? null);

                        if ($lat && $lng) {
                            // حساب المسافة من أقرب نقطة في المسار
                            $minDistance = $this->calculateMinDistance([$lat, $lng], $coordinates);

                            if ($minDistance <= $radius) {
                                $hotels[] = [
                                    'name' => $element['tags']['name'] ?? 'فندق بدون اسم',
                                    'name:ar' => $element['tags']['name:ar'] ?? null,
                                    'lat' => $lat,
                                    'lng' => $lng,
                                    'distance' => round($minDistance),
                                    'address' => $element['tags']['addr:full'] ?? $element['tags']['addr:street'] ?? null,
                                    'phone' => $element['tags']['phone'] ?? null,
                                    'website' => $element['tags']['website'] ?? null,
                                ];
                            }
                        }
                    }
                }

                // ترتيب الفنادق حسب المسافة
                usort($hotels, function ($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });

                return array_slice($hotels, 0, 20); // إرجاع أقرب 20 فندق
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching nearby hotels', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * حساب المسافة الدنيا من نقطة إلى مجموعة نقاط
     */
    private function calculateMinDistance(array $point, array $coordinates): float
    {
        $minDistance = PHP_FLOAT_MAX;

        foreach ($coordinates as $coord) {
            $distance = $this->haversineDistance($point[0], $point[1], $coord['lat'], $coord['lng']);
            if ($distance < $minDistance) {
                $minDistance = $distance;
            }
        }

        return $minDistance;
    }

    /**
     * حساب المسافة بين نقطتين باستخدام Haversine formula
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // نصف قطر الأرض بالمتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * تحويل الإحداثيات من string إلى array
     */
    public static function parseCoordinates(?string $coordinates): ?array
    {
        if (! $coordinates || empty(trim($coordinates))) {
            return null;
        }

        $coords = explode(',', trim($coordinates));
        $lat = trim($coords[0] ?? '');
        $lng = trim($coords[1] ?? '');

        if ($lat && $lng && is_numeric($lat) && is_numeric($lng)) {
            return [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ];
        }

        return null;
    }
}
