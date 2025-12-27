<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('user_id', Auth::id())
            ->with('trip')
            ->latest()
            ->paginate(10);

        return view('website.user.article.index', compact('articles'));
    }

    public function create()
    {
        // فقط الرحلات التي تم تأكيد حجزها يمكن كتابة مقالات عنها
        $bookedTrips = Booking::where('user_id', Auth::id())
            ->where('status', 'مؤكدة')
            ->with('trip')
            ->get()
            ->pluck('trip')
            ->filter();

        return view('website.user.article.create', compact('bookedTrips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => ['nullable', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:100'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
        ]);

        // إذا تم اختيار رحلة، التحقق من أن المستخدم قد حجزها
        if ($request->trip_id) {
            $hasBooking = Booking::where('user_id', Auth::id())
                ->where('trip_id', $request->trip_id)
                ->where('status', 'مؤكدة')
                ->exists();

            if (! $hasBooking) {
                return back()->with('error', 'يجب أن تكون قد حجزت هذه الرحلة سابقاً لكتابة مقال عنها');
            }
        }

        $articleData = [
            'user_id' => Auth::id(),
            'trip_id' => $request->trip_id,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating ?? null,
            'status' => 'معلقة',
        ];

        // رفع الصور إذا وجدت
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('articles', 'public');
            }
            $articleData['images'] = $imagePaths;
        }

        $article = Article::create($articleData);

        // إرسال إشعار للمسؤولين
        NotificationService::notifyNewArticle($article);

        return redirect()->route('my-articles')
            ->with('success', 'تم إنشاء المقال بنجاح، بانتظار المراجعة من المسؤول');
    }

    public function show(Article $article)
    {
        // هذه الصفحة خاصة بصاحب المقال داخل لوحة المستخدم
        if ($article->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا المقال');
        }

        $article->load(['trip.governorate', 'user']);

        return view('website.user.article.show', compact('article'));
    }

    public function edit(Article $article)
    {
        // التحقق من أن المقال يخص المستخدم الحالي
        if ($article->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا المقال');
        }

        // فقط المقالات المعلقة أو المرفوضة يمكن تعديلها
        if ($article->status === 'منشورة') {
            return redirect()->route('articles.show', $article)
                ->with('error', 'لا يمكن تعديل المقال المنشور');
        }

        // جلب الرحلات المحجوزة
        $bookedTrips = Booking::where('user_id', Auth::id())
            ->where('status', 'مؤكدة')
            ->with('trip')
            ->get()
            ->pluck('trip')
            ->filter();

        return view('website.user.article.edit', compact('article', 'bookedTrips'));
    }

    public function update(Request $request, Article $article)
    {
        // التحقق من أن المقال يخص المستخدم الحالي
        if ($article->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا المقال');
        }

        // فقط المقالات المعلقة أو المرفوضة يمكن تعديلها
        if ($article->status === 'منشورة') {
            return redirect()->route('articles.show', $article)
                ->with('error', 'لا يمكن تعديل المقال المنشور');
        }

        $request->validate([
            'trip_id' => ['nullable', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:100'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
        ]);

        // إذا تم اختيار رحلة، التحقق من أن المستخدم قد حجزها
        if ($request->trip_id) {
            $hasBooking = Booking::where('user_id', Auth::id())
                ->where('trip_id', $request->trip_id)
                ->where('status', 'مؤكدة')
                ->exists();

            if (! $hasBooking) {
                return back()->with('error', 'يجب أن تكون قد حجزت هذه الرحلة سابقاً لكتابة مقال عنها');
            }
        }

        $articleData = [
            'trip_id' => $request->trip_id,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating ?? null,
            'status' => 'معلقة', // إعادة تعيين الحالة للمراجعة
        ];

        // رفع الصور الجديدة إذا وجدت
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('articles', 'public');
            }
            // دمج الصور الجديدة مع القديمة
            $existingImages = $article->images ?? [];
            $articleData['images'] = array_merge($existingImages, $imagePaths);
        }

        $article->update($articleData);

        // إرسال إشعار للمسؤولين إذا كان المقال مرفوضاً سابقاً
        if ($article->status === 'مرفوضة') {
            NotificationService::notifyNewArticle($article);
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'تم تحديث المقال بنجاح، بانتظار المراجعة من المسؤول');
    }

    public function destroy(Article $article)
    {
        // التحقق من أن المقال يخص المستخدم الحالي
        if ($article->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا المقال');
        }

        // حذف الصور من التخزين
        if ($article->images) {
            foreach ($article->images as $image) {
                if (file_exists(storage_path('app/public/'.$image))) {
                    unlink(storage_path('app/public/'.$image));
                }
            }
        }

        $article->delete();

        return redirect()->route('my-articles')
            ->with('success', 'تم حذف المقال بنجاح');
    }
}
