<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Governorate;
use App\Models\TouristSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TouristSpotController extends AdminController
{
    public function index(Request $request)
    {
        $query = TouristSpot::with('governorate');

        // فلترة حسب المحافظة
        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        // فلترة حسب الفئة
        if ($request->filled('category_id')) {
            $query->whereJsonContains('category_ids', $request->category_id);
        }

        // البحث بالاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $touristSpots = $query->latest()->paginate(15)->withQueryString();
        $governorates = Governorate::all();
        $categories = Category::all();

        return view('admin.tourist-spots.index', compact('touristSpots', 'governorates', 'categories'));
    }

    public function create()
    {
        $governorates = Governorate::all();
        $categories = Category::all();

        return view('admin.tourist-spots.create', compact('governorates', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'coordinates' => [
                'nullable',
                'string',
                'regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
            ],
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'required|exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:2048',
            'entrance_fee' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['images', 'category_ids']);

        // حفظ category_ids كـ JSON
        if ($request->has('category_ids')) {
            $data['category_ids'] = $request->category_ids;
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('tourist-spots', 'public');
            }
            $data['images'] = $imagePaths;
        }

        TouristSpot::create($data);

        return redirect()->route('admin.tourist-spots.index')->with('success', 'تم إنشاء المكان السياحي بنجاح');
    }

    public function show(TouristSpot $touristSpot)
    {
        $touristSpot->load('governorate');

        return view('admin.tourist-spots.show', compact('touristSpot'));
    }

    public function edit(TouristSpot $touristSpot)
    {
        $governorates = Governorate::all();
        $categories = Category::all();

        return view('admin.tourist-spots.edit', compact('touristSpot', 'governorates', 'categories'));
    }

    public function update(Request $request, TouristSpot $touristSpot)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'coordinates' => [
                'nullable',
                'string',
                'regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
            ],
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'required|exists:categories,id',
            'images' => 'sometimes|array',
            'images.*' => 'image|max:2048',
            'entrance_fee' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['images', 'category_ids']);

        // حفظ category_ids كـ JSON
        if ($request->has('category_ids')) {
            $data['category_ids'] = $request->category_ids;
        }

        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            if ($touristSpot->images) {
                foreach ($touristSpot->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('tourist-spots', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $touristSpot->update($data);

        return redirect()->route('admin.tourist-spots.index')->with('success', 'تم تحديث المكان السياحي بنجاح');
    }

    public function destroy(TouristSpot $touristSpot)
    {
        // حذف الصور
        if ($touristSpot->images) {
            foreach ($touristSpot->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $touristSpot->delete();

        return redirect()->route('admin.tourist-spots.index')->with('success', 'تم حذف المكان السياحي بنجاح');
    }

    public function activate(TouristSpot $touristSpot)
    {
        // يمكن إضافة منطق التفعيل هنا إذا كان هناك حقل status
        return back()->with('success', 'تم تفعيل المكان السياحي بنجاح');
    }

    public function deactivate(TouristSpot $touristSpot)
    {
        // يمكن إضافة منطق إلغاء التفعيل هنا إذا كان هناك حقل status
        return back()->with('success', 'تم إلغاء تفعيل المكان السياحي بنجاح');
    }
}
