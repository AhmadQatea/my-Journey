@extends('layouts.admin')

@section('title', 'Deals Management')
@section('page-title', 'Deals Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Special Deals & Offers</h3>
        <a href="{{ route('admin.deals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Deal
        </a>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($deals as $deal)
            <div class="deal-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="deal-image relative">
                    <img src="{{ asset($deal->image) }}" alt="{{ $deal->title }}" class="w-full h-48 object-cover">
                    <div class="deal-badge absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                        {{ $deal->discount_percentage }}% OFF
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-lg mb-2">{{ $deal->title }}</h4>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($deal->description, 80) }}</p>
                    
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($deal->discounted_price, 2) }}</span>
                            <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($deal->original_price, 2) }}</span>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                            {{ $deal->trip->city->name }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                        <div>
                            <i class="fas fa-calendar"></i>
                            {{ $deal->start_date->format('M d') }} - {{ $deal->end_date->format('M d, Y') }}
                        </div>
                        <div>
                            <i class="fas fa-users"></i>
                            {{ $deal->bookings_count }}/{{ $deal->max_capacity }}
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="badge badge-{{ $deal->is_active ? 'success' : 'danger' }}">
                            {{ $deal->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div class="action-buttons">
                            <a href="{{ route('admin.deals.edit', $deal) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="deleteDeal({{ $deal->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $deals->links() }}
        </div>
    </div>
</div>

<!-- Deal Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-number">{{ $totalDeals }}</div>
        <div class="stat-label">Total Deals</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-bolt"></i>
        </div>
        <div class="stat-number">{{ $activeDeals }}</div>
        <div class="stat-label">Active Deals</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-number">{{ $totalDealBookings }}</div>
        <div class="stat-label">Deal Bookings</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-number">${{ number_format($totalDealRevenue, 2) }}</div>
        <div class="stat-label">Deal Revenue</div>
    </div>
</div>

<!-- Performance Chart -->
<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">Deals Performance</h3>
    </div>
    <div class="card-body">
        <canvas id="dealsChart" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script>
const dealsCtx = document.getElementById('dealsChart').getContext('2d');
const dealsChart = new Chart(dealsCtx, {
    type: 'bar',
    data: {
        labels: @json($deals->pluck('title')),
        datasets: [{
            label: 'Bookings',
            data: @json($deals->pluck('bookings_count')),
            backgroundColor: '#3b82f6',
            borderColor: '#2563eb',
            borderWidth: 1
        }, {
            label: 'Revenue ($)',
            data: @json($deals->pluck('revenue')),
            backgroundColor: '#10b981',
            borderColor: '#059669',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});

function deleteDeal(dealId) {
    if (confirm('Are you sure you want to delete this deal?')) {
        // Implement delete deal
        console.log('Delete deal:', dealId);
    }
}
</script>

<style>
.deal-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.deal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.deal-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@endpush
@endsection