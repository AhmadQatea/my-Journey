<?php

namespace App\Http\Controllers\Admin;

use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GovernorateController extends AdminController
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5); // تقليل عدد العناصر في كل صفحة للاختبار
        $governorates = Governorate::withCount(['touristSpots', 'trips'])->latest()->paginate($perPage)->withQueryString();

        return view('admin.governorates.index', compact('governorates'));
    }

    public function create()
    {
        return view('admin.governorates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'featured_image' => 'required|image|max:2048',
        ]);

        $data = $request->except('featured_image');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('governorates', 'public');
        }

        Governorate::create($data);

        return redirect()->route('admin.governorates.index')->with('success', 'تم إنشاء المحافظة بنجاح');
    }

    public function show(Governorate $governorate)
    {
        $governorate->load(['touristSpots', 'trips']);

        return view('admin.governorates.show', compact('governorate'));
    }

    public function edit(Governorate $governorate)
    {
        return view('admin.governorates.edit', compact('governorate'));
    }

    public function update(Request $request, Governorate $governorate)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name,' . $governorate->id,
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'featured_image' => 'sometimes|image|max:2048',
        ]);

        $data = $request->except('featured_image');

        if ($request->hasFile('featured_image')) {
            if ($governorate->featured_image) {
                Storage::disk('public')->delete($governorate->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('governorates', 'public');
        }

        $governorate->update($data);

        return redirect()->route('admin.governorates.index')->with('success', 'تم تحديث المحافظة بنجاح');
    }

    public function destroy(Governorate $governorate)
    {
        if ($governorate->featured_image) {
            Storage::disk('public')->delete($governorate->featured_image);
        }

        $governorate->delete();

        return redirect()->route('admin.governorates.index')->with('success', 'تم حذف المحافظة بنجاح');
    }

    public function activate(Governorate $governorate)
    {
        // يمكن إضافة منطق التفعيل هنا إذا كان هناك حقل status
        return back()->with('success', 'تم تفعيل المحافظة بنجاح');
    }

    public function deactivate(Governorate $governorate)
    {
        // يمكن إضافة منطق إلغاء التفعيل هنا إذا كان هناك حقل status
        return back()->with('success', 'تم إلغاء تفعيل المحافظة بنجاح');
    }
}
