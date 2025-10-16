{{-- Action Buttons Component --}}
<div class="action-buttons">
    <button 
        class="btn-add-cart" 
        {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
        aria-label="{{ __('Add to cart') }}"
    >
        <i class="fas fa-shopping-cart"></i>
        {{ $product->stock_status === 'out_of_stock' ? __('Out of Stock') : __('Add to Cart') }}
    </button>
    
    <button 
        class="btn-buy-now" 
        {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
        aria-label="{{ __('Buy now') }}"
    >
        {{ $product->stock_status === 'out_of_stock' ? __('Unavailable') : __('Buy Now') }}
    </button>
    
    <button 
        class="btn-wishlist"
        aria-label="{{ __('Add to wishlist') }}"
    >
        <i class="far fa-heart"></i>
    </button>
</div>
