<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with(['user', 'trip', 'adminCreator']);

        // Filtering
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('content', 'like', '%'.$request->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('full_name', 'like', '%'.$request->search.'%')
                            ->orWhere('email', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('trip', function ($tripQuery) use ($request) {
                        $tripQuery->where('title', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $articles = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Article::count(),
            'pending' => Article::where('status', 'معلقة')->count(),
            'published' => Article::where('status', 'منشورة')->count(),
            'rejected' => Article::where('status', 'مرفوضة')->count(),
        ];

        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->get();
        $users = User::all();

        return view('admin.articles.index', compact('articles', 'stats', 'trips', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with('governorate')->get();
        $offers = Offer::where('status', 'مفعل')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->with(['trip.governorate'])
            ->get();

        return view('admin.articles.create', compact('trips', 'offers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $data = $request->validated();

        // إذا كان المقال من المسؤول، يكون منشور مباشرة
        // user_id غير مطلوب - المقالات العامة لا تحتاج user_id
        $data['user_id'] = null; // مقالات المسؤولين عامة ولا تحتاج user_id
        $data['status'] = 'منشورة';
        $data['created_by_admin'] = true;
        $data['created_by_admin_id'] = Auth::id();
        $data['confirmed_by_admin_id'] = Auth::id(); // عند الإنشاء من المسؤول، يعتبر مؤكد تلقائياً

        // معالجة الصور
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('articles', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        $article = Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'تم إنشاء المقال بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['user', 'trip.governorate', 'adminCreator', 'adminConfirmer']);

        // زيادة عدد المشاهدات
        $article->increment('views_count');

        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with('governorate')->get();
        $offers = Offer::where('status', 'مفعل')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->with(['trip.governorate'])
            ->get();

        return view('admin.articles.edit', compact('article', 'trips', 'offers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();

        // إذا كان المقال من المسؤول، user_id يكون null
        if ($article->created_by_admin) {
            $data['user_id'] = null;
        }

        // إذا تم رفض المقال، يجب إضافة سبب الرفض
        if (isset($data['status']) && $data['status'] === 'مرفوضة' && empty($data['rejection_reason'])) {
            return redirect()->back()
                ->withErrors(['rejection_reason' => 'سبب الرفض مطلوب عند رفض المقال.'])
                ->withInput();
        }

        // معالجة الصور الجديدة
        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            if ($article->images) {
                foreach ($article->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('articles', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        } else {
            // الاحتفاظ بالصور القديمة
            $data['images'] = $article->images;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'تم تحديث المقال بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // حذف الصور
        if ($article->images) {
            foreach ($article->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'تم حذف المقال بنجاح.');
    }

    /**
     * الموافقة على المقال
     */
    public function approve(Request $request, Article $article)
    {
        $article->update([
            'status' => 'منشورة',
            'rejection_reason' => null,
            'confirmed_by_admin_id' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'تم الموافقة على المقال بنجاح.');
    }

    /**
     * رفض المقال
     */
    public function reject(Request $request, Article $article)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $article->update([
            'status' => 'مرفوضة',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()
            ->with('success', 'تم رفض المقال بنجاح.');
    }
}
