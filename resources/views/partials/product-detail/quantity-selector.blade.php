{{-- Quantity Selector Component --}}
<div class="quantity-section">
    <label class="quantity-label">{{ __('Quantity:') }}</label>
    <div class="quantity-selector">
        <div class="quantity-controls">
            <button 
                class="quantity-btn" 
                onclick="decreaseQuantity()" 
                {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
                aria-label="{{ __('Decrease quantity') }}"
            >
                -
            </button>
            <input 
                type="number" 
                class="quantity-input" 
                value="1" 
                min="1" 
                max="{{ $product->track_stock ? $product->stock_quantity : 999 }}" 
                id="quantity" 
                {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
                aria-label="{{ __('Product quantity') }}"
            >
            <button 
                class="quantity-btn" 
                onclick="increaseQuantity()" 
                {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
                aria-label="{{ __('Increase quantity') }}"
            >
                +
            </button>
        </div>
    </div>
</div>
