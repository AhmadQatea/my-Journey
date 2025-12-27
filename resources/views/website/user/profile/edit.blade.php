@extends('website.user.layouts.user')

@section('title', 'تعديل الملف الشخصي - MyJourney')

@push('styles')
<style>
.profile-edit-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.profile-edit-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.profile-edit-header-section h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.profile-edit-header-section p {
    font-size: 1rem;
    opacity: 0.9;
}

.profile-edit-form {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.profile-edit-form::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.form-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-section h3 {
    color: #0f172a;
    font-weight: 800;
    font-size: 1.25rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-section h3 i {
    color: #667eea;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    color: #0f172a;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group label i {
    color: #667eea;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    color: #1e293b;
    background: #ffffff;
    transition: all 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.avatar-upload-section {
    text-align: center;
}

.current-avatar {
    position: relative;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.current-avatar img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.current-avatar-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    font-size: 4rem;
    color: white;
    margin: 0 auto 1.5rem;
}

.avatar-upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
}

.avatar-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.avatar-upload-btn input[type="file"] {
    display: none;
}

.avatar-preview {
    margin-top: 1rem;
    display: none;
}

.avatar-preview img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

</style>
@endpush

@section('content')
<div class="profile-edit-page">
    <!-- Header Section -->
    <div class="profile-edit-header-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>
                    <i class="fas fa-edit"></i>
                    تعديل الملف الشخصي
                </h1>
                <p>تحديث معلوماتك الشخصية</p>
            </div>
            <a href="{{ route('profile.show') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                <i class="fas fa-arrow-right"></i>
                <span>العودة</span>
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">
        @csrf
        @method('PUT')

        <!-- Avatar Section -->
        <div class="form-section">
            <h3>
                <i class="fas fa-image"></i>
                صورة الملف الشخصي
            </h3>
            <div class="avatar-upload-section">
                <div class="current-avatar">
                    @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->full_name }}" id="current-avatar">
                    @else
                        <div class="current-avatar-placeholder" id="current-avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <label class="avatar-upload-btn">
                    <i class="fas fa-camera"></i>
                    <span>اختر صورة جديدة</span>
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="previewAvatar(this)">
                </label>
                <div class="avatar-preview" id="avatar-preview">
                    <img id="preview-image" src="" alt="معاينة الصورة">
                </div>
            </div>
        </div>

        <!-- Name Section -->
        <div class="form-section">
            <h3>
                <i class="fas fa-user"></i>
                المعلومات الشخصية
            </h3>
            <div class="form-group">
                <label for="full_name">
                    <i class="fas fa-signature"></i>
                    الاسم الكامل
                </label>
                <input type="text" id="full_name" name="full_name" class="form-input" 
                       value="{{ old('full_name', $user->full_name) }}" 
                       placeholder="أدخل اسمك الكامل" required>
                @error('full_name')
                    <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
            <a href="{{ route('profile.show') }}" class="btn btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // إخفاء الصورة الحالية أو placeholder
            const currentAvatar = document.getElementById('current-avatar');
            const currentPlaceholder = document.getElementById('current-avatar-placeholder');
            const preview = document.getElementById('avatar-preview');
            const previewImage = document.getElementById('preview-image');
            
            if (currentAvatar) {
                currentAvatar.style.display = 'none';
            }
            if (currentPlaceholder) {
                currentPlaceholder.style.display = 'none';
            }
            
            // إظهار معاينة الصورة الجديدة
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

