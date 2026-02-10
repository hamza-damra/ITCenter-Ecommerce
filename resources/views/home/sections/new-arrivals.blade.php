{{-- New Arrivals - HORIZONTAL SCROLLER --}}
@if(isset($newProducts) && $newProducts->count() > 0)
    <div class="container">
        <x-horizontal-product-scroller :products="$newProducts" title="{{ __t('messages.new_arrivals') }}"
            :viewMoreUrl="route('products')" :autoScroll="true" :autoScrollInterval="5000" :cardsToScroll="2"
            :cartProductIds="$cartProductIds" :hideSaleBadge="true" containerId="new-arrivals-scroller" />
    </div>
@endif
