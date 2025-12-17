@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li>
                <span class="pagination-link disabled" aria-disabled="true">
                    <i class="fas fa-chevron-right ml-2"></i>
                    السابق
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="pagination-link"
                   rel="prev"
                   aria-label="Go to previous page">
                    <i class="fas fa-chevron-right ml-2"></i>
                    السابق
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $maxLinks = 5; // عدد الروابط المراد عرضها

            // حساب الصفحات المراد عرضها
            if ($lastPage <= $maxLinks) {
                // إذا كان عدد الصفحات أقل من أو يساوي 5، عرض جميع الصفحات
                $startPage = 1;
                $endPage = $lastPage;
            } else {
                // حساب الصفحات حول الصفحة الحالية
                $half = floor($maxLinks / 2);
                $startPage = max(1, $currentPage - $half);
                $endPage = min($lastPage, $startPage + $maxLinks - 1);

                // تعديل البداية إذا كنا في النهاية
                if ($endPage - $startPage < $maxLinks - 1) {
                    $startPage = max(1, $endPage - $maxLinks + 1);
                }
            }
        @endphp

        {{-- عرض "..." في البداية إذا لزم الأمر --}}
        @if ($startPage > 1)
            <li>
                <a href="{{ $paginator->appends(request()->except('page'))->url(1) }}" class="pagination-link" aria-label="Go to page 1">1</a>
            </li>
            @if ($startPage > 2)
                <li>
                    <span class="pagination-link disabled">...</span>
                </li>
            @endif
        @endif

        {{-- عرض الصفحات --}}
        @for ($page = $startPage; $page <= $endPage; $page++)
            @if ($page == $currentPage)
                <li>
                    <span class="pagination-link active" aria-current="page">{{ $page }}</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->appends(request()->except('page'))->url($page) }}"
                       class="pagination-link"
                       aria-label="Go to page {{ $page }}">
                        {{ $page }}
                    </a>
                </li>
            @endif
        @endfor

        {{-- عرض "..." في النهاية إذا لزم الأمر --}}
        @if ($endPage < $lastPage)
            @if ($endPage < $lastPage - 1)
                <li>
                    <span class="pagination-link disabled">...</span>
                </li>
            @endif
            <li>
                <a href="{{ $paginator->appends(request()->except('page'))->url($lastPage) }}" class="pagination-link" aria-label="Go to page {{ $lastPage }}">{{ $lastPage }}</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="pagination-link"
                   rel="next"
                   aria-label="Go to next page">
                    التالي
                    <i class="fas fa-chevron-left mr-2"></i>
                </a>
            </li>
        @else
            <li>
                <span class="pagination-link disabled" aria-disabled="true">
                    التالي
                    <i class="fas fa-chevron-left mr-2"></i>
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif

