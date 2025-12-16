<?php

// app/Http/Controllers/Vip/TripController.php

namespace App\Http\Controllers\Vip;

use App\Http\Controllers\Controller;
use App\Http\Requests\VipTripRequest;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    //     public function __construct()
    //     {
    //         $this->middleware('auth');
    //         $this->middleware('role:vip_user');
    //         $this->authorizeResource(Trip::class, 'trip');
    //     }

    //     /**
    //      * Display a listing of the resource.
    //      */
    //     public function index(Request $request)
    //     {
    //         $query = Trip::where('created_by', auth()->id())
    //             ->with(['governorate', 'category'])
    //             ->when($request->filled('search'), function ($q) use ($request) {
    //                 $q->where('title', 'like', '%' . $request->search . '%')
    //                   ->orWhere('description', 'like', '%' . $request->search . '%');
    //             })
    //             ->when($request->filled('status'), function ($q) use ($request) {
    //                 $q->where('status', $request->status);
    //             });

    //         $stats = [
    //             'total' => Trip::where('created_by', auth()->id())->count(),
    //             'pending' => Trip::where('created_by', auth()->id())->where('status', 'معلقة')->count(),
    //             'accepted' => Trip::where('created_by', auth()->id())->where('status', 'مقبولة')->count(),
    //             'rejected' => Trip::where('created_by', auth()->id())->where('status', 'مرفوضة')->count(),
    //             'bookings' => Trip::where('created_by', auth()->id())->sum('bookings_count'),
    //         ];

    //         $trips = $query->latest()->paginate(15);

    //         return view('vip.trips.index', compact('trips', 'stats'));
    //     }

    //     /**
    //      * Show the form for creating a new resource.
    //      */
    //     public function create()
    //     {
    //         $governorates = Governorate::all();
    //         $categories = Category::active()->get();
    //         $types = ['داخل المحافظة', 'عدة محافظات'];

    //         return view('vip.trips.create-edit', compact('governorates', 'categories', 'types'));
    //     }

    //     /**
    //      * Store a newly created resource in storage.
    //      */
    //     public function store(VipTripRequest $request)
    //     {
    //         DB::beginTransaction();
    //         try {
    //             $data = $request->validated();
    //             $data['source_type'] = 'vip_user';
    //             $data['created_by'] = auth()->id();
    //             $data['status'] = 'معلقة'; // جميع رحلات VIP تكون معلقة حتى المراجعة
    //             $data['available_seats'] = $data['max_persons'];

    //             // معالجة الصور
    //             if ($request->hasFile('images')) {
    //                 $images = [];
    //                 foreach ($request->file('images') as $image) {
    //                     $path = $image->store('vip/trips/images', 'public');
    //                     $images[] = $path;
    //                 }
    //                 $data['images'] = $images;
    //             }

    //             // معالجة المميزات
    //             if ($request->filled('features')) {
    //                 $data['features'] = array_filter($request->features);
    //             }

    //             $trip = Trip::create($data);

    //             DB::commit();

    //             // إرسال إشعار للمسؤولين
    //             // Notification::send(User::role(['admin', 'trip_manager'])->get(), new NewVipTripCreated($trip));

    //             return redirect()->route('vip.trips.index')
    //                 ->with('success', 'تم إنشاء الرحلة بنجاح وتم إرسالها للمراجعة.');

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('error', 'حدث خطأ أثناء إنشاء الرحلة: ' . $e->getMessage());
    //         }
    //     }

    //     /**
    //      * Display the specified resource.
    //      */
    //     public function show(Trip $trip)
    //     {
    //         $this->authorize('view', $trip);

    //         $trip->load(['governorate', 'category', 'bookings.user']);
    //         return view('vip.trips.show', compact('trip'));
    //     }

    //     /**
    //      * Show the form for editing the specified resource.
    //      */
    //     public function edit(Trip $trip)
    //     {
    //         $this->authorize('update', $trip);

    //         // يمكن التعديل فقط إذا كانت الرحلة معلقة
    //         if ($trip->status !== 'معلقة') {
    //             return redirect()->route('vip.trips.index')
    //                 ->with('error', 'لا يمكن تعديل الرحلة بعد قبولها أو رفضها.');
    //         }

    //         $governorates = Governorate::all();
    //         $categories = Category::active()->get();
    //         $types = ['داخل المحافظة', 'عدة محافظات'];

    //         return view('vip.trips.create-edit', compact('trip', 'governorates', 'categories', 'types'));
    //     }

    //     /**
    //      * Update the specified resource in storage.
    //      */
    //     public function update(VipTripRequest $request, Trip $trip)
    //     {
    //         $this->authorize('update', $trip);

    //         DB::beginTransaction();
    //         try {
    //             $data = $request->validated();

    //             // حفظ الصور القديمة وإضافة الجديدة
    //             $oldImages = $trip->images ?? [];
    //             if ($request->hasFile('images')) {
    //                 $newImages = [];
    //                 foreach ($request->file('images') as $image) {
    //                     $path = $image->store('vip/trips/images', 'public');
    //                     $newImages[] = $path;
    //                 }
    //                 $data['images'] = array_merge($oldImages, $newImages);
    //             }

    //             // معالجة المميزات
    //             if ($request->filled('features')) {
    //                 $data['features'] = array_filter($request->features);
    //             }

    //             // عند التعديل، تصبح الرحلة معلقة مجدداً للمراجعة
    //             $data['status'] = 'معلقة';

    //             $trip->update($data);

    //             DB::commit();

    //             // إرسال إشعار للمسؤولين
    //             // Notification::send(User::role(['admin', 'trip_manager'])->get(), new VipTripUpdated($trip));

    //             return redirect()->route('vip.trips.index')
    //                 ->with('success', 'تم تحديث الرحلة بنجاح وتم إرسالها للمراجعة مرة أخرى.');

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('error', 'حدث خطأ أثناء تحديث الرحلة: ' . $e->getMessage());
    //         }
    //     }

    //     /**
    //      * Remove the specified resource from storage.
    //      */
    //     public function destroy(Trip $trip)
    //     {
    //         $this->authorize('delete', $trip);

    //         DB::beginTransaction();
    //         try {
    //             // حذف الصور من التخزين
    //             if ($trip->images) {
    //                 foreach ($trip->images as $image) {
    //                     Storage::disk('public')->delete($image);
    //                 }
    //             }

    //             $trip->delete();

    //             DB::commit();

    //             return redirect()->route('vip.trips.index')
    //                 ->with('success', 'تم حذف الرحلة بنجاح.');

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return redirect()->back()
    //                 ->with('error', 'حدث خطأ أثناء حذف الرحلة: ' . $e->getMessage());
    //         }
    //     }
}
