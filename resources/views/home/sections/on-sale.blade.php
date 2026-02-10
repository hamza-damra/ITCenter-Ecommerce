{{-- On Sale Products - HORIZONTAL SCROLLER --}}
@if(isset($onSaleProducts) && $onSaleProducts->count() > 0)
    <div class="container">
        <x-horizontal-product-scroller :products="$onSaleProducts" title="{{ __t('messages.on_sale') }}"
            :viewMoreUrl="route('products', ['filter' => 'sale'])" :autoScroll="true" :autoScrollInterval="5000"
            :cardsToScroll="1" :cartProductIds="$cartProductIds" :hideSaleBadge="true" containerId="on-sale-scroller" />
    </div>
@endif
