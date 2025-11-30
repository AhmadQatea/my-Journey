<?php

namespace App\Http\Controllers\Admin;

use App\Models\Governorate;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TripController extends AdminController
{
    public function index()
    {
        $trips = Trip::with(['governorate', 'creator'])->latest()->paginate(15);

        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        $governorates = Governorate::all();

        return view('admin.trips.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'governorate_id' => 'required|exists:governorates,id',
            'trip_type' => 'required|in:داخل المحافظة,عدة محافظات',
            'duration_hours' => 'required|integer|min:1',
            'start_time' => 'required',
            'max_persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'included_places' => 'required|array|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:2048',
        ]);

        $tripData = $request->except('images');
        $tripData['included_places'] = $request->included_places;
        $tripData['status'] = 'مقبولة'; // مقبولة تلقائياً إذا أنشأها المسؤول

        // رفع الصور
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('trips', 'public');
        }
        $tripData['images'] = $imagePaths;

        Trip::create($tripData);

        return redirect()->route('admin.trips.index')->with('success', 'تم إنشاء الرحلة بنجاح');
    }

    public function edit(Trip $trip)
    {
        $governorates = Governorate::all();

        return view('admin.trips.edit', compact('trip', 'governorates'));
    }

    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'governorate_id' => 'required|exists:governorates,id',
            'trip_type' => 'required|in:داخل المحافظة,عدة محافظات',
            'duration_hours' => 'required|integer|min:1',
            'start_time' => 'required',
            'max_persons' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'included_places' => 'required|array|min:1',
            'images' => 'sometimes|array',
            'images.*' => 'image|max:2048',
        ]);

        $tripData = $request->except('images');
        $tripData['included_places'] = $request->included_places;

        // تحديث الصور إذا تم رفع جديدة
        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            foreach ($trip->images as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('trips', 'public');
            }
            $tripData['images'] = $imagePaths;
        }

        $trip->update($tripData);

        return redirect()->route('admin.trips.index')->with('success', 'تم تحديث الرحلة بنجاح');
    }

    public function approve(Trip $trip)
    {
        $trip->update(['status' => 'مقبولة']);

        return back()->with('success', 'تم قبول الرحلة بنجاح');
    }

    public function reject(Trip $trip)
    {
        $trip->update(['status' => 'مرفوضة']);

        return back()->with('success', 'تم رفض الرحلة بنجاح');
    }

    public function destroy(Trip $trip)
    {
        // حذف الصور
        foreach ($trip->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $trip->delete();

        return back()->with('success', 'تم حذف الرحلة بنجاح');
    }
}
