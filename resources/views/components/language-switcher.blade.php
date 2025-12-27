@php
    $currentLocale = app()->getLocale();
    $otherLocale = $currentLocale === 'ar' ? 'en' : 'ar';
    $currentLanguage = $currentLocale === 'ar' ? __('messages.arabic') : __('messages.english');
    $otherLanguage = $otherLocale === 'ar' ? __('messages.arabic') : __('messages.english');
@endphp

<div class="language-switcher">
    <div class="language-dropdown">
        <button class="language-btn" type="button" id="languageToggle">
            <i class="fas fa-globe"></i>
            <span class="language-text">{{ $currentLanguage }}</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="language-menu" id="languageMenu">
            <a href="{{ route('language.switch', $otherLocale) }}" 
               class="language-option {{ $otherLocale === 'ar' ? 'rtl' : 'ltr' }}"
               data-locale="{{ $otherLocale }}">
                <span class="language-flag">
                    @if($otherLocale === 'ar')
                        🇸🇦
                    @else
                        🇬🇧
                    @endif
                </span>
                <span class="language-name">{{ $otherLanguage }}</span>
            </a>
        </div>
    </div>
</div>

<style>
.language-switcher {
    position: relative;
    display: inline-block;
}

.language-dropdown {
    position: relative;
}

.language-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--gray-100, #f3f4f6);
    border: 1px solid var(--gray-300, #d1d5db);
    border-radius: var(--radius-md, 8px);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    color: var(--gray-700, #374151);
    font-weight: 500;
}

.language-btn:hover {
    background: var(--gray-200, #e5e7eb);
    border-color: var(--primary, #4361ee);
    color: var(--primary, #4361ee);
}

.language-btn i.fa-chevron-down {
    font-size: 0.75rem;
    transition: transform 0.3s ease;
}

.language-dropdown.active .language-btn i.fa-chevron-down {
    transform: rotate(180deg);
}

.language-menu {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: white;
    border: 1px solid var(--gray-300, #d1d5db);
    border-radius: var(--radius-md, 8px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-width: 150px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    overflow: hidden;
}

.language-dropdown.active .language-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.language-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: var(--gray-700, #374151);
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--gray-100, #f3f4f6);
}

.language-option:last-child {
    border-bottom: none;
}

.language-option:hover {
    background: var(--primary, #4361ee);
    color: white;
}

.language-option.rtl {
    direction: rtl;
    text-align: right;
}

.language-option.ltr {
    direction: ltr;
    text-align: left;
}

.language-flag {
    font-size: 1.25rem;
}

.language-name {
    font-weight: 500;
    font-size: 0.875rem;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .language-btn {
        background: var(--gray-800, #1f2937);
        border-color: var(--gray-700, #374151);
        color: var(--gray-200, #e5e7eb);
    }

    .language-btn:hover {
        background: var(--gray-700, #374151);
        border-color: var(--primary, #4361ee);
    }

    .language-menu {
        background: var(--gray-800, #1f2937);
        border-color: var(--gray-700, #374151);
    }

    .language-option {
        color: var(--gray-200, #e5e7eb);
        border-bottom-color: var(--gray-700, #374151);
    }

    .language-option:hover {
        background: var(--primary, #4361ee);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .language-text {
        display: none;
    }

    .language-btn {
        padding: 0.5rem;
        min-width: 40px;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const languageToggle = document.getElementById('languageToggle');
    const languageMenu = document.getElementById('languageMenu');
    const languageDropdown = document.querySelector('.language-dropdown');

    if (languageToggle && languageMenu) {
        // Toggle menu
        languageToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            languageDropdown.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!languageDropdown.contains(e.target)) {
                languageDropdown.classList.remove('active');
            }
        });

        // Close menu when selecting a language
        const languageOptions = document.querySelectorAll('.language-option');
        languageOptions.forEach(option => {
            option.addEventListener('click', function() {
                languageDropdown.classList.remove('active');
            });
        });
    }
});
</script>

