{{-- Bestsellers - HORIZONTAL SCROLLER --}}
@if(isset($bestsellerProducts) && $bestsellerProducts->count() > 0)
    <div class="container">
        <x-horizontal-product-scroller :products="$bestsellerProducts" title="{{ __t('messages.best_sellers') }}"
            :viewMoreUrl="route('products', ['filter' => 'bestseller'])" :autoScroll="true" :autoScrollInterval="6000"
            :cartProductIds="$cartProductIds" :hideSaleBadge="true" containerId="bestsellers-scroller" />
    </div>
@endif
