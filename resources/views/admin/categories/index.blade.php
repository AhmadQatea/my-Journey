{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', __('messages.manage_categories'))
@section('page-title', __('messages.manage_categories'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.manage_categories') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.manage_all_categories') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Create Form -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_new_category') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">{{ __('messages.category_name') }} *</label>
                            <input type="text"
                                   name="name"
                                   id="categoryName"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: بحرية' : 'Example: Marine' }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-plus ml-1"></i>
                            {{ __('messages.add_category') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="card-title">{{ __('messages.categories_list') }}</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('messages.total_categories') }}: {{ $categories->count() }} {{ __('messages.category') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($categories->count() > 0)
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.category_name') }}</th>
                                        <th>{{ __('messages.trips_count') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th class="w-32">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-semibold text-gray-900 dark:text-gray">
                                                    {{ $category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info text-xs">
                                                    {{ $category->tripsCount() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $category->created_at->format('Y/m/d') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons justify-center">
                                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('{{ __('messages.are_you_sure') }} {{ __('messages.delete') }} {{ __('messages.category') }} {{ $category->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="action-btn delete"
                                                                title="{{ __('messages.delete') }}"
                                                                data-tooltip="{{ __('messages.delete') }}">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h4 class="empty-state-title">{{ __('messages.no_categories_found') }}</h4>
                            <p class="empty-state-description">
                                {{ app()->getLocale() === 'ar' ? 'لم يتم إضافة أي فئات بعد. ابدأ بإضافة أول فئة.' : 'No categories have been added yet. Start by adding the first category.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle form submission with AJAX
document.getElementById('categoryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    const nameInput = form.querySelector('input[name="name"]');

    // Disable button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جاري الإضافة...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reset form
            nameInput.value = '';
            nameInput.classList.remove('is-invalid');

            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success mb-4';
            successAlert.innerHTML = '<i class="fas fa-check-circle ml-1"></i> ' + data.message;
            form.parentElement.insertBefore(successAlert, form);

            // Remove success message after 3 seconds
            setTimeout(() => {
                successAlert.remove();
            }, 3000);

            // Reload page to show new category
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // Remove any existing error messages
        const existingError = nameInput.parentElement.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }

        // Show validation errors
        if (error.errors && error.errors.name) {
            nameInput.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = Array.isArray(error.errors.name) ? error.errors.name[0] : error.errors.name;
            nameInput.parentElement.appendChild(errorDiv);
        } else {
            const errorMessage = error.message || 'حدث خطأ أثناء إضافة الفئة. يرجى المحاولة مرة أخرى.';
            alert(errorMessage);
        }

        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});
</script>
@endpush
