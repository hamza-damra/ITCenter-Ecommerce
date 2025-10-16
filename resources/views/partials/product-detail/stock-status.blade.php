{{-- Stock Status Component --}}
<div class="stock-status {{ $product->stock_status === 'out_of_stock' ? 'out-of-stock' : '' }}">
    @if($product->stock_status === 'in_stock')
        <i class="fas fa-check-circle"></i>
        <span>{{ __('In Stock - Ready to Ship') }}</span>
    @else
        <i class="fas fa-times-circle"></i>
        <span>{{ __('Out of Stock') }}</span>
    @endif
</div>
