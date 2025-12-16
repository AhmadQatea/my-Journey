<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TouristSpot;
use App\Models\Trip;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:big_boss|site_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        // إذا كان الطلب AJAX، أرجع JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الفئة بنجاح.',
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // التحقق من وجود رحلات مرتبطة بهذه الفئة
        $tripsCount = Trip::whereJsonContains('category_ids', $category->id)->count();
        if ($tripsCount > 0) {
            return redirect()->back()
                ->with('error', "لا يمكن حذف هذه الفئة لأنها مرتبطة بـ {$tripsCount} رحلة.");
        }

        // التحقق من وجود أماكن سياحية مرتبطة بهذه الفئة
        $touristSpotsCount = TouristSpot::whereJsonContains('category_ids', $category->id)->count();
        if ($touristSpotsCount > 0) {
            return redirect()->back()
                ->with('error', "لا يمكن حذف هذه الفئة لأنها مرتبطة بـ {$touristSpotsCount} مكان سياحي.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح.');
    }
}
