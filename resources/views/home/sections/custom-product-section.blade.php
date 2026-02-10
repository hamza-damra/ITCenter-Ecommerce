{{-- Custom Product Section --}}
@php
    $sectionProducts = $section->products()
        ->with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
        ->where('is_active', true)
        ->limit($section->getSetting('max_products', 8))
        ->get();
@endphp

@if($sectionProducts->count() > 0)
    <div class="container" @if($section->getSetting('background_color')) style="background-color: {{ $section->getSetting('background_color') }};" @endif>
        <x-horizontal-product-scroller
            :products="$sectionProducts"
            :title="$section->title"
            :autoScroll="(bool) $section->getSetting('auto_scroll', false)"
            :autoScrollInterval="(int) $section->getSetting('auto_scroll_interval', 5000)"
            :cardsToScroll="(int) $section->getSetting('cards_to_scroll', 1)"
            :cartProductIds="$cartProductIds ?? []"
            containerId="custom-section-{{ $section->id }}"
        />
    </div>
@endif
