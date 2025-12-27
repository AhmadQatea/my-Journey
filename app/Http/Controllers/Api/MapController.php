<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Services\MapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function __construct(
        private MapService $mapService
    ) {}

    /**
     * حساب المسار لرحلة معينة
     */
    public function getTripRoute(Trip $trip): JsonResponse
    {
        // تحميل العلاقات
        $trip->load(['departureGovernorate', 'governorate']);

        // تحويل الإحداثيات إلى تنسيق مناسب
        $coordinates = [];
        $places = [];
        $departurePoint = null;

        // إضافة نقطة الانطلاق إذا كانت موجودة
        if ($trip->departureGovernorate && $trip->departureGovernorate->latitude && $trip->departureGovernorate->longitude) {
            $departurePoint = [
                'lat' => (float) $trip->departureGovernorate->latitude,
                'lng' => (float) $trip->departureGovernorate->longitude,
            ];
            $coordinates[] = $departurePoint;
            $places[] = [
                'id' => 'departure',
                'name' => $trip->departureGovernorate->name.' - نقطة الانطلاق',
                'lat' => $departurePoint['lat'],
                'lng' => $departurePoint['lng'],
                'governorate' => $trip->departureGovernorate->name,
                'type' => 'departure',
            ];
        }

        // جلب الأماكن السياحية المضمنة في الرحلة
        $touristSpots = collect();
        if ($trip->included_places) {
            $placeIds = array_filter($trip->included_places, 'is_numeric');
            if (! empty($placeIds)) {
                $touristSpots = \App\Models\TouristSpot::whereIn('id', $placeIds)
                    ->whereNotNull('coordinates')
                    ->where('coordinates', '!=', '')
                    ->with('governorate')
                    ->get();
            }
        }

        // إضافة الأماكن السياحية
        foreach ($touristSpots as $spot) {
            $coords = $this->mapService->parseCoordinates($spot->coordinates);
            if ($coords) {
                $coordinates[] = $coords;
                $places[] = [
                    'id' => $spot->id,
                    'name' => $spot->name,
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                    'governorate' => $spot->governorate->name ?? null,
                    'type' => 'tourist_spot',
                ];
            }
        }

        if (empty($coordinates)) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد إحداثيات صحيحة',
            ], 404);
        }

        // حساب المسار
        $route = $this->mapService->calculateRoute($coordinates);

        if (! $route) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في حساب المسار',
            ], 500);
        }

        // جلب الفنادق القريبة
        $hotels = $this->mapService->getNearbyHotels($coordinates, 10000); // 10 كم

        return response()->json([
            'success' => true,
            'route' => [
                'geometry' => $route['geometry'],
                'distance' => round($route['distance'] / 1000, 2), // بالكيلومتر
                'duration' => round($route['duration'] / 60, 0), // بالدقائق
                'duration_hours' => round($route['duration'] / 3600, 1), // بالساعات
            ],
            'places' => $places,
            'hotels' => $hotels,
            'departure' => $departurePoint,
        ]);
    }

    /**
     * حساب المسار من إحداثيات مخصصة
     */
    public function calculateCustomRoute(Request $request): JsonResponse
    {
        $request->validate([
            'coordinates' => 'required|array|min:2',
            'coordinates.*.lat' => 'required|numeric',
            'coordinates.*.lng' => 'required|numeric',
        ]);

        $coordinates = $request->coordinates;
        $route = $this->mapService->calculateRoute($coordinates);

        if (! $route) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في حساب المسار',
            ], 500);
        }

        // جلب الفنادق القريبة
        $hotels = $this->mapService->getNearbyHotels($coordinates, 10000);

        return response()->json([
            'success' => true,
            'route' => [
                'geometry' => $route['geometry'],
                'distance' => round($route['distance'] / 1000, 2),
                'duration' => round($route['duration'] / 60, 0),
                'duration_hours' => round($route['duration'] / 3600, 1),
            ],
            'hotels' => $hotels,
        ]);
    }
}
