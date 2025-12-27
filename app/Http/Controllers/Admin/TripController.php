<?php

// app/Http/Controllers/Admin/TripController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TripRequest;
use App\Mail\TripRejectedMail;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\TouristSpot;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:big_boss|site_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Trip::with(['governorate', 'creator', 'adminCreator'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('source_type'), function ($q) use ($request) {
                $q->where('source_type', $request->source_type);
            })
            ->when($request->filled('governorate_id'), function ($q) use ($request) {
                $q->where('governorate_id', $request->governorate_id);
            })
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->whereJsonContains('category_ids', (int) $request->category_id);
            })
            ->when($request->filled('trip_type'), function ($q) use ($request) {
                $q->where('trip_type', $request->trip_type);
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('start_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('start_date', '<=', $request->date_to);
            });

        // إحصائيات
        $stats = [
            'total' => Trip::count(),
            'pending' => Trip::where('status', 'معلقة')->count(),
            'accepted' => Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])->count(),
            'rejected' => Trip::where('status', 'مرفوضة')->count(),
            'active' => Trip::where('status', 'قيد التفعيل')->count(),
            'featured' => Trip::where('is_featured', true)->count(),
            'vip_trips' => Trip::where('source_type', 'vip_user')->count(),
        ];

        $perPage = $request->get('per_page', 5);
        $trips = $query->latest()->paginate($perPage)->withQueryString();
        $governorates = Governorate::all();
        $categories = Category::all();

        return view('admin.trips.index', compact('trips', 'stats', 'governorates', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $governorates = Governorate::all();
        $categories = Category::all();
        $types = ['داخل المحافظة', 'عدة محافظات'];

        return view('admin.trips.create', compact('governorates', 'categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TripRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['source_type'] = 'admin';
            // المسؤولون ليسوا في جدول users، لذا نضع null
            $data['created_by'] = null;
            // حفظ معلومات المسؤول الذي أنشأ الرحلة
            $data['created_by_admin'] = true;
            $data['created_by_admin_id'] = Auth::guard('admin')->id();
            $data['status'] = 'مقبولة'; // الرحلات التي ينشئها المسؤولون مقبولة (مفعلة) تلقائياً
            $data['available_seats'] = $data['max_persons']; // عند الإنشاء، المقاعد المتاحة = العدد الأقصى

            // معالجة الصور
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('trips/images', 'public');
                    $images[] = $path;
                }
                $data['images'] = $images;
            }

            // معالجة المميزات
            if ($request->filled('features')) {
                $data['features'] = array_filter($request->features);
            }

            // معالجة المحافظات التي سنمر بها
            if ($request->filled('passing_governorates')) {
                $data['passing_governorates'] = $request->passing_governorates;
            } else {
                $data['passing_governorates'] = null;
            }

            // معالجة التصنيفات
            if ($request->filled('category_ids')) {
                $data['category_ids'] = $request->category_ids;
            } else {
                $data['category_ids'] = [];
            }

            $trip = Trip::create($data);

            DB::commit();

            // إرسال إشعار للمسؤولين
            // Notification::send(User::role('admin')->get(), new NewTripCreated($trip));

            return redirect()->route('admin.trips.index')
                ->with('success', 'تم إنشاء الرحلة بنجاح وتم إرسالها للمراجعة.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الرحلة: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load(['governorate', 'creator', 'adminCreator', 'bookings.user']);

        return view('admin.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $governorates = Governorate::all();
        $categories = Category::all();
        $types = ['داخل المحافظة', 'عدة محافظات'];

        return view('admin.trips.edit', compact('trip', 'governorates', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TripRequest $request, Trip $trip)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // معالجة الصور
            $existingImages = $request->input('existing_images', []);
            $oldImages = $trip->images ?? [];

            // حذف الصور المحذوفة
            $imagesToKeep = [];
            foreach ($oldImages as $oldImage) {
                if (in_array($oldImage, $existingImages)) {
                    $imagesToKeep[] = $oldImage;
                } else {
                    // حذف الصورة من التخزين
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // إضافة الصور الجديدة
            if ($request->hasFile('images')) {
                $newImages = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('trips/images', 'public');
                    $newImages[] = $path;
                }
                $data['images'] = array_merge($imagesToKeep, $newImages);
            } else {
                $data['images'] = $imagesToKeep;
            }

            // معالجة المميزات
            if ($request->filled('features')) {
                $data['features'] = array_filter($request->features);
            } else {
                $data['features'] = [];
            }

            // معالجة المحافظات التي سنمر بها
            if ($request->filled('passing_governorates')) {
                $data['passing_governorates'] = $request->passing_governorates;
            } else {
                $data['passing_governorates'] = null;
            }

            // معالجة التصنيفات
            if ($request->filled('category_ids')) {
                $data['category_ids'] = $request->category_ids;
            } else {
                $data['category_ids'] = [];
            }

            $trip->update($data);

            DB::commit();

            return redirect()->route('admin.trips.index')
                ->with('success', 'تم تحديث الرحلة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الرحلة: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        DB::beginTransaction();
        try {
            // حذف الصور من التخزين
            if ($trip->images) {
                foreach ($trip->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $trip->delete();

            DB::commit();

            return redirect()->route('admin.trips.index')
                ->with('success', 'تم حذف الرحلة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الرحلة: '.$e->getMessage());
        }
    }

    /**
     * حذف مجموعة من الرحلات
     */
    public function destroySelected(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:trips,id',
        ]);

        DB::beginTransaction();
        try {
            $trips = Trip::whereIn('id', $request->selected_ids)->get();

            foreach ($trips as $trip) {
                // حذف الصور من التخزين
                if ($trip->images) {
                    foreach ($trip->images as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
                $trip->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف '.count($trips).' رحلة بنجاح.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * تغيير حالة الرحلة مع إرسال رسالة عند الرفض
     */
    public function changeStatus(Request $request, Trip $trip)
    {
        // للرحلات التي ينشئها المسؤولون: يمكن التغيير بين "مقبولة" و "قيد التفعيل" فقط
        // للرحلات التي ينشئها مستخدمون VIP: يمكن التغيير بين جميع الحالات
        if ($trip->source_type == 'admin') {
            $request->validate([
                'status' => 'required|in:مقبولة,قيد التفعيل',
            ]);

            $trip->update([
                'status' => $request->status,
            ]);

            return redirect()->back()
                ->with('success', 'تم تغيير حالة الرحلة إلى '.$request->status);
        }

        // للرحلات التي ينشئها مستخدمون VIP
        $request->validate([
            'status' => 'required|in:معلقة,مقبولة,مرفوضة',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $trip->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            $updateData = [
                'status' => $newStatus,
            ];

            // إضافة سبب الرفض فقط عند الرفض
            if ($newStatus == 'مرفوضة') {
                $updateData['rejection_reason'] = $request->reason;
            } else {
                $updateData['rejection_reason'] = null;
            }

            $trip->update($updateData);

            // إرسال بريد إلكتروني عند رفض رحلة من مستخدم VIP
            if ($newStatus == 'مرفوضة' && $trip->created_by) {
                $user = User::find($trip->created_by);
                if ($user) {
                    Mail::to($user->email)->send(new TripRejectedMail($trip, $request->reason));
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تغيير حالة الرحلة إلى '.$newStatus.
                       ($newStatus == 'مرفوضة' ? ' وتم إرسال إشعار للمستخدم' : ''));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير الحالة: '.$e->getMessage());
        }
    }

    /**
     * تمييز الرحلة
     */
    public function toggleFeatured(Trip $trip)
    {
        $trip->update(['is_featured' => ! $trip->is_featured]);

        $message = $trip->is_featured ? 'تم تمييز الرحلة' : 'تم إلغاء تمييز الرحلة';

        return redirect()->back()->with('success', $message);
    }

    /**
     * إجراءات جماعية على الرحلات
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:accept,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:trips,id',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $trips = Trip::whereIn('id', $request->ids)->get();
            $action = $request->action;
            $count = 0;

            foreach ($trips as $trip) {
                if ($action == 'delete') {
                    // حذف الصور من التخزين
                    if ($trip->images) {
                        foreach ($trip->images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                    $trip->delete();
                    $count++;

                } elseif ($action == 'accept') {
                    // القبول فقط للرحلات التي ينشئها مستخدمون VIP
                    if ($trip->source_type == 'vip_user') {
                        $trip->update(['status' => 'مقبولة']);
                        $count++;
                    }

                } elseif ($action == 'reject') {
                    // الرفض فقط للرحلات التي ينشئها مستخدمون VIP
                    if ($trip->source_type == 'vip_user') {
                        $trip->update([
                            'status' => 'مرفوضة',
                            'rejection_reason' => $request->reason,
                        ]);

                        // إرسال بريد إلكتروني عند رفض رحلة من مستخدم VIP
                        if ($trip->created_by) {
                            $user = User::find($trip->created_by);
                            if ($user) {
                                Mail::to($user->email)->send(new TripRejectedMail($trip, $request->reason));
                            }
                        }
                        $count++;
                    }
                }
            }

            DB::commit();

            $message = '';
            if ($action == 'delete') {
                $message = 'تم حذف '.$count.' رحلة بنجاح';
            } elseif ($action == 'accept') {
                $message = 'تم قبول '.$count.' رحلة بنجاح';
            } elseif ($action == 'reject') {
                $message = 'تم رفض '.$count.' رحلة بنجاح وتم إرسال إشعارات للمستخدمين';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنفيذ الإجراء: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get tourist spots by governorates (AJAX)
     */
    public function getTouristSpotsByGovernorates(Request $request)
    {
        try {
            $governorateIds = [];

            // جلب المحافظات من query parameters (array format)
            if ($request->has('governorate_ids')) {
                $ids = $request->input('governorate_ids', []);

                // إذا كان string، نحوله إلى array
                if (is_string($ids)) {
                    $ids = explode(',', $ids);
                }

                // التأكد من أنه array
                if (is_array($ids)) {
                    $governorateIds = array_merge($governorateIds, $ids);
                }
            }

            // إضافة المحافظة الرئيسية إذا كانت موجودة
            if ($request->filled('main_governorate_id')) {
                $mainId = $request->input('main_governorate_id');
                if (!in_array($mainId, $governorateIds)) {
                    $governorateIds[] = $mainId;
                }
            }

            // إزالة التكرار والقيم الفارغة وتحويل إلى integers
            $governorateIds = array_filter(
                array_unique(
                    array_map('intval', $governorateIds)
                ),
                function ($id) {
                    return $id > 0;
                }
            );

            if (empty($governorateIds)) {
                return response()->json([
                    'success' => true,
                    'message' => 'لا توجد محافظات محددة',
                    'tourist_spots' => [],
                    'count' => 0,
                ], 200);
            }

            // جلب الأماكن السياحية
            $touristSpots = TouristSpot::whereIn('governorate_id', $governorateIds)
                ->with('governorate')
                ->orderBy('name')
                ->get()
                ->map(function ($spot) {
                    return [
                        'id' => $spot->id,
                        'name' => $spot->name,
                        'governorate_id' => $spot->governorate_id,
                        'governorate_name' => $spot->governorate->name ?? 'غير محدد',
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الأماكن السياحية بنجاح',
                'tourist_spots' => $touristSpots,
                'count' => $touristSpots->count(),
                'governorate_ids' => $governorateIds,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching tourist spots: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الأماكن السياحية',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'tourist_spots' => [],
                'count' => 0,
            ], 500);
        }
    }
}
