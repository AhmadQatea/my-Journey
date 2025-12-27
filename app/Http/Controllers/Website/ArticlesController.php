<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Feedback;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all'); // all = جميع المقالات

        // مقالات مميزة من المسؤولين فقط
        $featuredArticles = Article::query()
            ->published()
            ->where('created_by_admin', true)
            ->with(['adminCreator', 'trip'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // جميع المقالات المنشورة مع الفلترة
        $articlesQuery = Article::query()
            ->published()
            ->with(['user', 'trip']);

        switch ($filter) {
            // جميع المقالات
            case 'all':
                $articlesQuery->latest('created_at');
                break;

                // من قبل المسؤول
            case 'admin':
                $articlesQuery
                    ->where('created_by_admin', true)
                    ->latest('created_at');
                break;

                // من قبل المستخدمين
            case 'users':
                $articlesQuery
                    ->where(function ($query) {
                        $query->whereNull('created_by_admin')
                            ->orWhere('created_by_admin', false);
                    })
                    ->latest('created_at');
                break;

                // الأعلى تقييماً (كل المقالات)
            case 'top-rated':
                $articlesQuery
                    ->orderByDesc('rating')
                    ->orderByDesc('views_count');
                break;
        }

        $articles = $articlesQuery->paginate(9)->withQueryString();

        // آخر آراء/ملاحظات المستخدمين لعرضها في سلايدر "آراء المسافرين"
        $testimonials = Feedback::query()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('website.pages.articleandfeefback', [
            'featuredArticles' => $featuredArticles,
            'articles' => $articles,
            'currentFilter' => $filter,
            'testimonials' => $testimonials,
        ]);
    }

    public function show(Article $article): View
    {
        // لا نعرض إلا المقالات المنشورة في الصفحة العامة
        if ($article->status !== 'منشورة') {
            abort(404);
        }

        // زيادة عدد المشاهدات
        $article->increment('views_count');

        $article->load(['trip.governorate', 'user', 'adminCreator']);

        return view('website.pages.article-show', [
            'article' => $article,
        ]);
    }
}
