@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details: ' . $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Profile -->
    <x-card title="Profile Information">
        <div class="text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold">{{ $user->name }}</h3>
            <p class="text-gray-500">{{ $user->email }}</p>
            <div class="mt-2">
                <x-badge variant="{{ $user->is_active ? 'success' : 'danger' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </x-badge>
                @if($user->email_verified_at)
                <x-badge variant="success">Verified</x-badge>
                @else
                <x-badge variant="warning">Unverified</x-badge>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="font-medium">Phone:</span>
                <span>{{ $user->phone ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Member Since:</span>
                <span>{{ $user->created_at->format('M d, Y') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Last Login:</span>
                <span>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Total Bookings:</span>
                <span class="font-semibold text-blue-600">{{ $user->bookings_count }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Total Articles:</span>
                <span class="font-semibold text-green-600">{{ $user->articles_count }}</span>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            @if(!$user->is_active)
            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="flex-1">
                @csrf
                <x-button type="submit" variant="success" class="w-full">
                    <i class="fas fa-check"></i> Activate User
                </x-button>
            </form>
            @else
            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="flex-1">
                @csrf
                <x-button type="submit" variant="warning" class="w-full">
                    <i class="fas fa-pause"></i> Deactivate User
                </x-button>
            </form>
            @endif

            @if(!$user->email_verified_at)
            <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="flex-1">
                @csrf
                <x-button type="submit" variant="info" class="w-full">
                    <i class="fas fa-envelope"></i> Verify Email
                </x-button>
            </form>
            @endif
        </div>
    </x-card>

    <!-- User Statistics -->
    <x-card title="User Statistics" class="lg:col-span-2">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card text-center">
                <div class="stat-number text-blue-600">{{ $user->bookings_count }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>

            <div class="stat-card text-center">
                <div class="stat-number text-green-600">{{ $user->articles_count }}</div>
                <div class="stat-label">Articles Written</div>
            </div>

            <div class="stat-card text-center">
                <div class="stat-number text-purple-600">{{ $completedBookings }}</div>
                <div class="stat-label">Completed Trips</div>
            </div>

            <div class="stat-card text-center">
                <div class="stat-number text-orange-600">${{ number_format($totalSpent, 2) }}</div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <h4 class="font-semibold mb-3">Recent Bookings</h4>
        <div class="space-y-3">
            @foreach($recentBookings as $booking)
            <div class="flex items-center justify-between p-3 border rounded-lg">
                <div class="flex items-center gap-3">
                    <img src="{{ asset($booking->trip->images->first()->path ?? 'images/default-trip.jpg') }}"
                         alt="{{ $booking->trip->title }}" class="w-12 h-12 rounded object-cover">
                    <div>
                        <h4 class="font-medium">{{ $booking->trip->title }}</h4>
                        <p class="text-sm text-gray-500">{{ $booking->trip->city->name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-green-600">${{ number_format($booking->total_price, 2) }}</div>
                    <x-badge variant="{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                        {{ ucfirst($booking->status) }}
                    </x-badge>
                </div>
            </div>
            @endforeach

            @if($user->bookings_count == 0)
            <p class="text-gray-500 text-center py-4">No bookings yet.</p>
            @endif
        </div>

        @if($user->bookings_count > 3)
        <div class="mt-4 text-center">
            <x-button href="{{ route('admin.bookings.index', ['user' => $user->id]) }}" variant="outline-primary" size="sm">
                View All Bookings
            </x-button>
        </div>
        @endif
    </x-card>
</div>

<!-- Recent Articles -->
<x-card title="Recent Articles" class="mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($recentArticles as $article)
        <div class="bg-white rounded-lg border overflow-hidden">
            <img src="{{ asset($article->featured_image) }}" alt="{{ $article->title }}"
                 class="w-full h-40 object-cover">
            <div class="p-4">
                <h4 class="font-bold text-lg mb-2">{{ Str::limit($article->title, 50) }}</h4>
                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($article->excerpt, 80) }}</p>
                <div class="flex justify-between items-center">
                    <x-badge variant="info">{{ $article->status }}</x-badge>
                    <span class="text-sm text-gray-500">{{ $article->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($user->articles_count == 0)
    <p class="text-gray-500 text-center py-8">No articles written yet.</p>
    @endif

    @if($user->articles_count > 3)
    <div class="mt-4 text-center">
        <x-button href="{{ route('admin.articles.index', ['author' => $user->id]) }}" variant="outline-primary" size="sm">
            View All Articles
        </x-button>
    </div>
    @endif
</x-card>

<!-- Activity Timeline -->
<x-card title="Activity Timeline" class="mt-6">
    <div class="space-y-4">
        @foreach($activities as $activity)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-{{ $activity->type_icon }} text-sm"></i>
                </div>
                <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
            </div>
            <div class="flex-1 pb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium">{{ $activity->description }}</h4>
                        <p class="text-sm text-gray-500">{{ $activity->details }}</p>
                    </div>
                    <span class="text-sm text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($activities->hasPages())
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
    @endif
</x-card>
@endsection
