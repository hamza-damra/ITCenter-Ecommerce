{{-- Product Price Component --}}
<div class="product-price">
    <span class="current-price">${{ number_format($product->final_price, 2) }}</span>
    
    @if($product->is_on_sale)
        <span class="original-price">${{ number_format($product->price, 2) }}</span>
        <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
    @endif
</div>
