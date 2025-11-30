<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Booking;
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

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        // فقط الرحلات التي تم تأكيد حجزها يمكن كتابة مقالات عنها
        $bookedTrips = Booking::where('user_id', Auth::id())
            ->where('status', 'مؤكدة')
            ->with('trip')
            ->get()
            ->pluck('trip');

        return view('articles.create', compact('bookedTrips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
        ]);

        // التحقق من أن المستخدم قد حجز هذه الرحلة
        $hasBooking = Booking::where('user_id', Auth::id())
            ->where('trip_id', $request->trip_id)
            ->where('status', 'مؤكدة')
            ->exists();

        if (! $hasBooking) {
            return back()->with('error', 'يجب أن تكون قد حجزت هذه الرحلة سابقاً لكتابة مقال عنها');
        }

        $articleData = [
            'user_id' => Auth::id(),
            'trip_id' => $request->trip_id,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
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

        Article::create($articleData);

        return redirect()->route('my-articles')
            ->with('success', 'تم إنشاء المقال بنجاح، بانتظار المراجعة من المسؤول');
    }
}
