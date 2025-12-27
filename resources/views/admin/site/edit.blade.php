@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

<x-admin.edit-form
    title="تعديل إعدادات الموقع"
    :action="route('admin.site.update')"
    :back-route="route('admin.site.index')"
    submit-text="حفظ التعديلات"
    layout="default"
>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- وصف مختصر عن الموقع -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <i class="fas fa-book"></i>
                        الوصف المختصر عن الموقع
                    </h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="form-group">
                        <label class="form-label">الوصف</label>
                        <textarea name="about_story"
                                  rows="6"
                                  class="form-control @error('about_story') is-invalid @enderror"
                                  placeholder="اكتب نصاً مختصراً يصف الموقع وسيظهر في صفحة (عن الموقع)...">{{ old('about_story', $settings->about_story) }}</textarea>
                        @error('about_story')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- معلومات التواصل -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <i class="fas fa-address-card"></i>
                        معلومات التواصل
                    </h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email"
                               name="contact_email"
                               class="form-control @error('contact_email') is-invalid @enderror"
                               value="{{ old('contact_email', $settings->contact_email) }}"
                               placeholder="info@myjourney.sy">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="text"
                               name="contact_phone"
                               class="form-control @error('contact_phone') is-invalid @enderror"
                               value="{{ old('contact_phone', $settings->contact_phone) }}"
                               placeholder="+963 11 123 4567">
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">العنوان</label>
                        <input type="text"
                               name="contact_address"
                               class="form-control @error('contact_address') is-invalid @enderror"
                               value="{{ old('contact_address', $settings->contact_address) }}"
                               placeholder="دمشق، سوريا">
                        @error('contact_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">أوقات الدوام</label>
                        <textarea name="working_hours"
                                  rows="4"
                                  class="form-control @error('working_hours') is-invalid @enderror"
                                  placeholder="الأحد - الخميس: 9 صباحاً - 5 مساءً">{{ old('working_hours', is_array($settings->working_hours) ? json_encode($settings->working_hours, JSON_UNESCAPED_UNICODE) : $settings->working_hours) }}</textarea>
                        <small class="text-gray-500">يمكنك كتابة أوقات الدوام كنص عادي</small>
                        @error('working_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- روابط التواصل الاجتماعي -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <i class="fas fa-share-alt"></i>
                        روابط مواقع التواصل الاجتماعي
                    </h3>
                </div>
                <div class="card-body space-y-4">
                    @foreach([
                        'facebook' => ['icon' => 'fab fa-facebook-f', 'label' => 'فيسبوك', 'placeholder' => 'https://facebook.com/myjourney'],
                        'twitter' => ['icon' => 'fab fa-twitter', 'label' => 'تويتر', 'placeholder' => 'https://twitter.com/myjourney'],
                        'instagram' => ['icon' => 'fab fa-instagram', 'label' => 'إنستغرام', 'placeholder' => 'https://instagram.com/myjourney'],
                        'youtube' => ['icon' => 'fab fa-youtube', 'label' => 'يوتيوب', 'placeholder' => 'https://youtube.com/myjourney'],
                        'linkedin' => ['icon' => 'fab fa-linkedin-in', 'label' => 'لينكد إن', 'placeholder' => 'https://linkedin.com/company/myjourney'],
                        'whatsapp' => ['icon' => 'fab fa-whatsapp', 'label' => 'واتساب', 'placeholder' => '963991234567'],
                    ] as $key => $info)
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                <i class="{{ $info['icon'] }}"></i>
                                {{ $info['label'] }}
                            </label>
                            <input type="{{ $key === 'whatsapp' ? 'text' : 'url' }}"
                                   name="social_{{ $key }}"
                                   class="form-control @error('social_' . $key) is-invalid @enderror"
                                   value="{{ old('social_' . $key, $settings->{'social_' . $key}) }}"
                                   placeholder="{{ $info['placeholder'] }}">
                            @error('social_' . $key)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- السياسات -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <i class="fas fa-file-contract"></i>
                        السياسات والشروط
                    </h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="form-group">
                        <label class="form-label">الشروط والأحكام</label>
                        <div id="terms-editor" style="height: 200px; margin-bottom: 50px;">
                            {!! old('terms_and_conditions', $settings->terms_and_conditions) !!}
                        </div>
                        <textarea name="terms_and_conditions" id="terms_content" style="display: none;">{{ old('terms_and_conditions', $settings->terms_and_conditions) }}</textarea>
                        @error('terms_and_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">سياسة الخصوصية</label>
                        <div id="privacy-editor" style="height: 200px; margin-bottom: 50px;">
                            {!! old('privacy_policy', $settings->privacy_policy) !!}
                        </div>
                        <textarea name="privacy_policy" id="privacy_content" style="display: none;">{{ old('privacy_policy', $settings->privacy_policy) }}</textarea>
                        @error('privacy_policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">سياسة ملفات التعريف</label>
                        <div id="cookie-editor" style="height: 200px; margin-bottom: 50px;">
                            {!! old('cookie_policy', $settings->cookie_policy) !!}
                        </div>
                        <textarea name="cookie_policy" id="cookie_content" style="display: none;">{{ old('cookie_policy', $settings->cookie_policy) }}</textarea>
                        @error('cookie_policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('admin.site.index') }}" class="btn btn-outline">
                إلغاء
    </div>
</x-admin.edit-form>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // تهيئة محررات Quill
    const termsQuill = new Quill('#terms-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    const privacyQuill = new Quill('#privacy-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    const cookieQuill = new Quill('#cookie-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // تحديث الحقول المخفية قبل الإرسال
    document.getElementById('site-settings-form').addEventListener('submit', function(e) {
        document.getElementById('terms_content').value = termsQuill.root.innerHTML;
        document.getElementById('privacy_content').value = privacyQuill.root.innerHTML;
        document.getElementById('cookie_content').value = cookieQuill.root.innerHTML;
    });
</script>
@endpush

