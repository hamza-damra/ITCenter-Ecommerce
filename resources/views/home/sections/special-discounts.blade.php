{{-- Special Discounts & Offers - HORIZONTAL SCROLLER --}}
@if(isset($specialDiscounts) && $specialDiscounts->count() > 0)
    <div class="container">
        <x-horizontal-product-scroller :products="$specialDiscounts" title="{{ __t('messages.special_discounts') }}"
            :viewMoreUrl="route('products', ['filter' => 'sale'])" :autoScroll="true" :autoScrollInterval="4500"
            :cardsToScroll="1" :cartProductIds="$cartProductIds" :showDiscountPercentage="true"
            containerId="special-discounts-scroller" />
    </div>
@endif
