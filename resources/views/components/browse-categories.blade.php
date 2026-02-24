@props(['categories' => []])

@php
    $isRtl = is_rtl();
    $locale = app()->getLocale();
@endphp

@if(!empty($categories))
<div class="browse-categories-wrapper">
    <button type="button" class="browse-categories-btn" id="browseCatsBtnToggle" onclick="toggleBrowseCategories(event)">
        <i class="fas fa-th-large"></i>
        <span>{{ $locale === 'ar' ? 'تصفح الفئات' : ($locale === 'he' ? 'עיון בקטגוריות' : 'Browse Categories') }}</span>
        <i class="fas fa-chevron-down ms-2 toggle-icon" style="font-size: 0.8rem;"></i>
    </button>
    
    <div class="browse-categories-dropdown" id="browseCatsDropdown" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="bc-header">
            <h4>{{ $locale === 'ar' ? 'تصفح الفئات' : ($locale === 'he' ? 'עיון בקטגוריות' : 'Browse Categories') }}</h4>
            <button type="button" class="bc-close-btn" onclick="closeBrowseCategories()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="bc-body">
            <ul class="bc-list">
                @foreach($categories as $cat)
                @php
                    $isCurrent = $cat['is_current'] ?? false;
                @endphp
                <li class="bc-item">
                    <a href="{{ $cat['url'] }}" class="bc-link {{ $isCurrent ? 'active' : '' }}">
                        <span class="bc-name" style="flex: 1;">{{ $cat['name'] }}</span>
                        <span class="bc-count">({{ $cat['total_count'] ?? 0 }})</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
.browse-categories-wrapper {
    position: relative;
    display: inline-block;
    z-index: 50;
    margin-right: 0.5rem;
    margin-left: 0.5rem;
}
.browse-categories-btn {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #1e293b;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    height: 38px;
}
.browse-categories-btn:hover, .browse-categories-btn.active {
    border-color: #2762f3;
    color: #2762f3;
    background: rgba(39, 98, 243, 0.05);
}
.toggle-icon {
    transition: transform 0.3s ease;
}
.browse-categories-btn.active .toggle-icon {
    transform: rotate(180deg);
}

.browse-categories-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    {{ $isRtl ? 'right: 0;' : 'left: 0;' }}
    width: 260px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: all 0.25s ease;
    z-index: 99;
}
.browse-categories-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.bc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e2e8f0;
}
.bc-header h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}
.bc-close-btn {
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-size: 1rem;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bc-close-btn:hover {
    color: #ef4444;
}
.bc-body {
    padding: 0.5rem 0;
    max-height: 300px;
    overflow-y: auto;
}
/* Scrollbar settings */
.bc-body::-webkit-scrollbar { width: 5px; }
.bc-body::-webkit-scrollbar-track { background: transparent; }
.bc-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.bc-body::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.bc-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.bc-item {
    border-bottom: 1px solid #f1f5f9;
}
.bc-item:last-child {
    border-bottom: none;
}
.bc-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.6rem 1rem;
    text-decoration: none;
    color: #334155;
    transition: background 0.2s;
}
.bc-link:hover {
    background: #f8fafc;
}
.bc-name {
    font-weight: 600;
    font-size: 0.9rem;
}
.bc-count {
    color: #94a3b8;
    font-size: 0.85rem;
}
.bc-link.active .bc-name {
    color: #dc2626; /* matches the red in screenshot */
}
</style>

<script>
window.toggleBrowseCategories = function(e) {
    if(e) e.stopPropagation();
    const dropdown = document.getElementById('browseCatsDropdown');
    const btn = document.getElementById('browseCatsBtnToggle');
    if(!dropdown) return;
    
    if(dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        if(btn) btn.classList.remove('active');
    } else {
        dropdown.classList.add('show');
        if(btn) btn.classList.add('active');
    }
}

window.closeBrowseCategories = function() {
    const dropdown = document.getElementById('browseCatsDropdown');
    const btn = document.getElementById('browseCatsBtnToggle');
    if(dropdown) dropdown.classList.remove('show');
    if(btn) btn.classList.remove('active');
}

document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.browse-categories-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        window.closeBrowseCategories();
    }
});
</script>
@endif
