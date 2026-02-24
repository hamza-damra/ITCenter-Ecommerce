{{-- Category Browser Component --}}
{{-- Fixed button + slide-out panel showing all categories with expandable children --}}

@php
    $isRtl = is_rtl();
    $locale = app()->getLocale();
    $dir = locale_direction();

    // Load ALL active parent categories with their active children
    try {
        $browseCategories = \App\Models\Category::with([
            'children' => function ($query) {
                $query->where('is_active', true)->orderBy('position')->orderBy('name_en');
            },
            'children.children' => function ($query) {
                $query->where('is_active', true)->orderBy('position')->orderBy('name_en');
            }
        ])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('position')
        ->orderBy('name_en')
        ->get();
    } catch (\Exception $e) {
        $browseCategories = collect([]);
    }
@endphp

@if($browseCategories->count() > 0)
{{-- Fixed Browse Button --}}
<button type="button" class="cb-toggle-btn" id="cbToggleBtn" aria-label="{{ __t('messages.categories') }}" onclick="toggleCategoryBrowser()" style="position:fixed; top:50%; {{ $isRtl ? 'right' : 'left' }}:0; transform:translateY(-50%); z-index:1060;">
    <i class="fas fa-bars cb-toggle-icon" id="cbToggleIcon"></i>
    <span class="cb-toggle-text">{{ $locale === 'ar' ? 'تصفح الفئات' : ($locale === 'he' ? 'עיון בקטגוריות' : 'Browse Categories') }}</span>
</button>

{{-- Overlay --}}
<div class="cb-overlay" id="cbOverlay" onclick="closeCategoryBrowser()"></div>

{{-- Slide-out Panel --}}
<div class="cb-panel" id="cbPanel" dir="{{ $dir }}">
    {{-- Panel Header --}}
    <div class="cb-header">
        <div class="cb-header-title">
            <i class="fas fa-th-large"></i>
            <span>{{ $locale === 'ar' ? 'تصفح الفئات' : ($locale === 'he' ? 'עיון בקטגוריות' : 'Browse Categories') }}</span>
        </div>
        <button type="button" class="cb-close-btn" onclick="closeCategoryBrowser()" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Category List --}}
    <div class="cb-body">
        <ul class="cb-list">
            @foreach($browseCategories as $parentCat)
                @php
                    $activeChildren = $parentCat->children->where('is_active', true);
                    $hasChildren = $activeChildren->count() > 0;
                @endphp
                <li class="cb-item {{ $hasChildren ? 'cb-has-children' : '' }}">
                    <div class="cb-item-row">
                        <a href="{{ route('category.show', $parentCat->slug) }}" class="cb-item-link">
                            @if($parentCat->icon)
                                <i class="{{ $parentCat->icon }} cb-item-icon"></i>
                            @else
                                <i class="fas fa-folder cb-item-icon"></i>
                            @endif
                            <span class="cb-item-name">{{ $parentCat->name }}</span>
                        </a>
                        @if($hasChildren)
                            <button type="button" class="cb-expand-btn" onclick="toggleCbChildren(this)" aria-label="Expand">
                                <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }} cb-chevron"></i>
                            </button>
                        @endif
                    </div>

                    @if($hasChildren)
                        <ul class="cb-children" style="display: none;">
                            @foreach($activeChildren->sortBy('position') as $childCat)
                                @php
                                    $grandChildren = $childCat->children ?? collect([]);
                                    $activeGrandChildren = $grandChildren->where('is_active', true);
                                    $hasGrandChildren = $activeGrandChildren->count() > 0;
                                @endphp
                                <li class="cb-child-item {{ $hasGrandChildren ? 'cb-has-children' : '' }}">
                                    <div class="cb-item-row">
                                        <a href="{{ route('category.show', [$parentCat->slug, $childCat->slug]) }}" class="cb-child-link">
                                            <span class="cb-child-name">{{ $childCat->name }}</span>
                                        </a>
                                        @if($hasGrandChildren)
                                            <button type="button" class="cb-expand-btn" onclick="toggleCbChildren(this)" aria-label="Expand">
                                                <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }} cb-chevron"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if($hasGrandChildren)
                                        <ul class="cb-children" style="display: none;">
                                            @foreach($activeGrandChildren->sortBy('position') as $grandChild)
                                                <li class="cb-grandchild-item">
                                                    <a href="{{ route('category.show', [$parentCat->slug, $childCat->slug, $grandChild->slug]) }}" class="cb-grandchild-link">
                                                        <span class="cb-grandchild-name">{{ $grandChild->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Panel Footer --}}
    <div class="cb-footer">
        <a href="{{ route('categories') }}" class="cb-view-all">
            <i class="fas fa-th-list"></i>
            <span>{{ $locale === 'ar' ? 'عرض جميع الفئات' : ($locale === 'he' ? 'הצג את כל הקטגוריות' : 'View All Categories') }}</span>
        </a>
    </div>
</div>

<style>
/* ═══ Category Browser - Fixed Toggle Button ═══ */
.cb-toggle-btn {
    position: fixed;
    top: 50%;
    {{ $isRtl ? 'right' : 'left' }}: 0;
    transform: translateY(-50%);
    z-index: 1030;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 18px;
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
    color: #ffffff;
    border: none;
    {{ $isRtl ? 'border-radius: 12px 0 0 12px' : 'border-radius: 0 12px 12px 0' }};
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 2px 4px 16px rgba(37, 99, 235, 0.35);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    writing-mode: horizontal-tb;
}
.cb-toggle-btn:hover {
    {{ $isRtl ? 'right' : 'left' }}: 0;
    padding-{{ $isRtl ? 'left' : 'right' }}: 24px;
    box-shadow: 4px 6px 24px rgba(37, 99, 235, 0.45);
}
.cb-toggle-btn .cb-toggle-text {
    display: none;
}
.cb-toggle-btn:hover .cb-toggle-text {
    display: inline;
}
.cb-toggle-icon {
    font-size: 16px;
    transition: transform 0.3s ease;
}

/* ═══ Overlay ═══ */
.cb-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
.cb-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* ═══ Slide-out Panel ═══ */
.cb-panel {
    position: fixed;
    top: 0;
    {{ $isRtl ? 'right' : 'left' }}: 0;
    width: 340px;
    max-width: 85vw;
    height: 100vh;
    background: #ffffff;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
    transform: translateX({{ $isRtl ? '100%' : '-100%' }});
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.cb-panel.active {
    transform: translateX(0);
}

/* ═══ Panel Header ═══ */
.cb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
    color: #ffffff;
    flex-shrink: 0;
}
.cb-header-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
}
.cb-header-title i {
    font-size: 18px;
}
.cb-close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: background 0.2s ease;
}
.cb-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* ═══ Panel Body ═══ */
.cb-body {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
}
.cb-body::-webkit-scrollbar {
    width: 5px;
}
.cb-body::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.cb-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* ═══ Category List ═══ */
.cb-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.cb-item {
    border-bottom: 1px solid #f1f5f9;
}
.cb-item:last-child {
    border-bottom: none;
}
.cb-item-row {
    display: flex;
    align-items: center;
}
.cb-item-link {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    color: #334155;
    text-decoration: none;
    font-size: 14.5px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.cb-item-link:hover {
    background: #f8fafc;
    color: #2563eb;
}
.cb-item-icon {
    width: 22px;
    text-align: center;
    font-size: 16px;
    color: #64748b;
    transition: color 0.2s ease;
}
.cb-item-link:hover .cb-item-icon {
    color: #2563eb;
}
.cb-expand-btn {
    background: none;
    border: none;
    padding: 14px 16px;
    cursor: pointer;
    color: #94a3b8;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cb-expand-btn:hover {
    color: #2563eb;
}
.cb-chevron {
    font-size: 12px;
    transition: transform 0.3s ease;
}
.cb-expand-btn.expanded .cb-chevron {
    transform: rotate(90deg);
}

/* ═══ Children list ═══ */
.cb-children {
    list-style: none;
    margin: 0;
    padding: 0;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}
.cb-child-item {
    border-bottom: 1px solid #e8eef7;
}
.cb-child-item:last-child {
    border-bottom: none;
}
.cb-child-link {
    display: flex;
    align-items: center;
    padding: 11px 20px 11px {{ $isRtl ? '20px' : '48px' }};
    {{ $isRtl ? 'padding-right: 48px' : '' }};
    color: #475569;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.2s ease;
    flex: 1;
}
.cb-child-link:hover {
    background: #eff6ff;
    color: #2563eb;
}
.cb-child-link::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #cbd5e1;
    margin-{{ $isRtl ? 'left' : 'right' }}: 10px;
    flex-shrink: 0;
    transition: background 0.2s ease;
}
.cb-child-link:hover::before {
    background: #2563eb;
}

/* ═══ Grandchildren list ═══ */
.cb-grandchild-item {
    border-bottom: 1px solid #eef2f7;
}
.cb-grandchild-item:last-child {
    border-bottom: none;
}
.cb-grandchild-link {
    display: flex;
    align-items: center;
    padding: 9px 20px 9px {{ $isRtl ? '20px' : '72px' }};
    {{ $isRtl ? 'padding-right: 72px' : '' }};
    color: #64748b;
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    transition: all 0.2s ease;
}
.cb-grandchild-link:hover {
    background: #eff6ff;
    color: #2563eb;
}
.cb-grandchild-link::before {
    content: '–';
    margin-{{ $isRtl ? 'left' : 'right' }}: 8px;
    color: #cbd5e1;
    flex-shrink: 0;
}

/* ═══ Panel Footer ═══ */
.cb-footer {
    flex-shrink: 0;
    padding: 12px 20px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}
.cb-view-all {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: #eff6ff;
    color: #2563eb;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.cb-view-all:hover {
    background: #dbeafe;
    color: #1d4ed8;
}

/* ═══ Responsive ═══ */
@media (max-width: 768px) {
    .cb-toggle-btn {
        top: auto;
        bottom: 80px;
        padding: 10px 14px;
        font-size: 13px;
        border-radius: {{ $isRtl ? '10px 0 0 10px' : '0 10px 10px 0' }};
    }
    .cb-toggle-btn:hover .cb-toggle-text {
        display: none;
    }
    .cb-panel {
        width: 300px;
    }
}

/* Hide on very small screens if sidebar is open */
@media (max-width: 400px) {
    .cb-panel {
        width: 100vw;
        max-width: 100vw;
    }
}
</style>

<script>
function toggleCategoryBrowser() {
    const panel = document.getElementById('cbPanel');
    const overlay = document.getElementById('cbOverlay');
    const btn = document.getElementById('cbToggleBtn');
    const icon = document.getElementById('cbToggleIcon');

    const isOpen = panel.classList.contains('active');

    if (isOpen) {
        closeCategoryBrowser();
    } else {
        panel.classList.add('active');
        overlay.classList.add('active');
        btn.style.opacity = '0';
        btn.style.pointerEvents = 'none';
        document.body.style.overflow = 'hidden';
    }
}

function closeCategoryBrowser() {
    const panel = document.getElementById('cbPanel');
    const overlay = document.getElementById('cbOverlay');
    const btn = document.getElementById('cbToggleBtn');

    panel.classList.remove('active');
    overlay.classList.remove('active');
    btn.style.opacity = '';
    btn.style.pointerEvents = '';
    document.body.style.overflow = '';
}

function toggleCbChildren(button) {
    const item = button.closest('.cb-item, .cb-child-item');
    const childList = item.querySelector(':scope > .cb-children');

    if (!childList) return;

    const isExpanded = childList.style.display !== 'none';

    if (isExpanded) {
        childList.style.display = 'none';
        button.classList.remove('expanded');
    } else {
        childList.style.display = '';
        button.classList.add('expanded');
    }
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryBrowser();
    }
});

// Ensure button is visible after page load (fallback for CSS conflicts)
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('cbToggleBtn');
    if (btn) {
        var cs = window.getComputedStyle(btn);
        if (cs.display === 'none' || cs.visibility === 'hidden' || cs.opacity === '0' || cs.position !== 'fixed') {
            btn.style.cssText = 'position:fixed !important; top:50% !important; {{ $isRtl ? "right" : "left" }}:0 !important; transform:translateY(-50%) !important; z-index:1060 !important; display:flex !important; visibility:visible !important; opacity:1 !important;';
        }
    }
});
</script>
@endif
