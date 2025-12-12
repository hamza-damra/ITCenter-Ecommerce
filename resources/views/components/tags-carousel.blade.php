@props(['tags' => [], 'activeTag' => null, 'showAll' => true])

@php
    $isRtl = is_rtl();
    $locale = app()->getLocale();
    $currentUrl = url()->current();
    $currentParams = request()->except(['tag', 'page']);
@endphp

@if(count($tags) > 0)
<div class="tags-carousel-section">
    <div class="tags-carousel-container">
        <div class="tags-carousel-header">
            <h3 class="tags-carousel-title">
                <i class="fas fa-tags"></i>
                {{ __('messages.filter_by_tags') ?? 'Filter by Tags' }}
            </h3>
            @if($activeTag)
                <a href="{{ url()->current() }}?{{ http_build_query($currentParams) }}" class="clear-tag-filter">
                    <i class="fas fa-times"></i>
                    {{ __('messages.clear_filter') ?? 'Clear Filter' }}
                </a>
            @endif
        </div>
        
        <div class="tags-carousel-wrapper">
            <button class="tags-scroll-btn tags-scroll-prev" aria-label="Scroll left">
                <i class="fas fa-chevron-{{ $isRtl ? 'right' : 'left' }}"></i>
            </button>
            
            <div class="tags-carousel-track">
                @if($showAll)
                    <a href="{{ url()->current() }}?{{ http_build_query($currentParams) }}" 
                       class="tag-chip {{ !$activeTag ? 'active' : '' }}"
                       style="--tag-color: #64748b;">
                        <i class="fas fa-th-large"></i>
                        <span>{{ __('messages.all_products') ?? 'All Products' }}</span>
                    </a>
                @endif
                
                @foreach($tags as $tag)
                    @php
                        $tagParams = array_merge($currentParams, ['tag' => $tag->slug]);
                        $isActive = $activeTag && $activeTag->slug === $tag->slug;
                    @endphp
                    <a href="{{ url()->current() }}?{{ http_build_query($tagParams) }}" 
                       class="tag-chip {{ $isActive ? 'active' : '' }}"
                       style="--tag-color: {{ $tag->color ?? '#3b82f6' }};">
                        @if($tag->icon)
                            <i class="{{ $tag->icon }}"></i>
                        @else
                            <span class="tag-dot" style="background: {{ $tag->color ?? '#3b82f6' }};"></span>
                        @endif
                        <span>{{ $tag->name }}</span>
                        <span class="tag-count">{{ $tag->count ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
            
            <button class="tags-scroll-btn tags-scroll-next" aria-label="Scroll right">
                <i class="fas fa-chevron-{{ $isRtl ? 'left' : 'right' }}"></i>
            </button>
        </div>
    </div>
</div>

<style>
.tags-carousel-section {
    margin-bottom: 2rem;
    padding: 0;
}

.tags-carousel-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    border: 1px solid #e2e8f0;
}

.tags-carousel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.tags-carousel-title {
    font-size: 1rem;
    font-weight: 600;
    color: #334155;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tags-carousel-title i {
    color: #2762f3;
}

.clear-tag-filter {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.85rem;
    color: #ef4444;
    text-decoration: none;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    background: rgba(239, 68, 68, 0.1);
    transition: all 0.3s ease;
}

.clear-tag-filter:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}

.tags-carousel-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
}

.tags-scroll-btn {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.tags-scroll-btn:hover {
    background: #2762f3;
    color: white;
    border-color: #2762f3;
    transform: scale(1.05);
}

.tags-scroll-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.tags-carousel-track {
    display: flex;
    gap: 0.625rem;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 0.25rem 0;
    flex: 1;
}

.tags-carousel-track::-webkit-scrollbar {
    display: none;
}

.tag-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #475569;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.tag-chip:hover {
    border-color: var(--tag-color, #3b82f6);
    color: var(--tag-color, #3b82f6);
    background: rgba(59, 130, 246, 0.05);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tag-chip.active {
    background: var(--tag-color, #3b82f6);
    border-color: var(--tag-color, #3b82f6);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.tag-chip.active:hover {
    background: var(--tag-color, #3b82f6);
    color: white;
    filter: brightness(1.1);
}

.tag-chip i {
    font-size: 0.9rem;
}

.tag-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.tag-count {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.125rem 0.5rem;
    background: rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    color: inherit;
}

.tag-chip.active .tag-count {
    background: rgba(255, 255, 255, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .tags-carousel-container {
        padding: 1rem;
        border-radius: 12px;
    }
    
    .tags-scroll-btn {
        display: none;
    }
    
    .tags-carousel-track {
        padding: 0.5rem 0;
    }
    
    .tag-chip {
        padding: 0.5rem 0.875rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.tags-carousel-track');
    const prevBtn = document.querySelector('.tags-scroll-prev');
    const nextBtn = document.querySelector('.tags-scroll-next');
    
    if (!track || !prevBtn || !nextBtn) return;
    
    const scrollAmount = 200;
    const isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
    
    function updateButtons() {
        const scrollLeft = Math.abs(track.scrollLeft);
        const maxScroll = track.scrollWidth - track.clientWidth;
        
        prevBtn.disabled = scrollLeft <= 5;
        nextBtn.disabled = scrollLeft >= maxScroll - 5;
    }
    
    prevBtn.addEventListener('click', () => {
        track.scrollBy({ left: isRtl ? scrollAmount : -scrollAmount, behavior: 'smooth' });
    });
    
    nextBtn.addEventListener('click', () => {
        track.scrollBy({ left: isRtl ? -scrollAmount : scrollAmount, behavior: 'smooth' });
    });
    
    track.addEventListener('scroll', updateButtons);
    window.addEventListener('resize', updateButtons);
    
    // Initial check
    setTimeout(updateButtons, 100);
});
</script>
@endif
