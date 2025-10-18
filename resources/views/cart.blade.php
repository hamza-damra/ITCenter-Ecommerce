@extends('layouts.app')

@section('title', __('messages.cart') . ' - IT Center')

@section('content')
<style>
    /* Cart Page Styles */
    .cart-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem;
        min-height: calc(100vh - 200px);
    }

    .cart-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #4169E1;
    }

    .cart-header h1 {
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
    }

    .cart-header i {
        font-size: 2rem;
        color: #4169E1;
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 968px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
    }

    /* Cart Items Section */
    .cart-items-section {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .cart-item {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: all 0.3s;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        background: #fff;
    }

    .cart-item:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .cart-item-image {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        text-decoration: none;
        display: block;
    }

    .cart-item-title:hover {
        color: #4169E1;
    }

    .cart-item-price {
        font-size: 1.3rem;
        color: #4169E1;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .quantity-btn {
        background: #f5f5f5;
        border: 1px solid #ddd;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        font-size: 1.1rem;
        color: #333;
    }

    .quantity-btn:hover {
        background: #4169E1;
        color: #fff;
        border-color: #4169E1;
    }

    .quantity-display {
        min-width: 40px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .cart-item-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        white-space: nowrap;
    }

    .remove-btn {
        background: #ff4444;
        color: #fff;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .remove-btn:hover {
        background: #cc0000;
        transform: scale(1.05);
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-cart i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .empty-cart h2 {
        font-size: 1.8rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-cart p {
        color: #999;
        margin-bottom: 2rem;
    }

    .continue-shopping-btn {
        background: #4169E1;
        color: #fff;
        padding: 10px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 400;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
    }

    .continue-shopping-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(65, 105, 225, 0.4);
        background: #1E90FF;
    }
    .continue-shopping-btn i {
        font-size: 15px;
        margin-top: 11px;
        padding-right: 5px;
    }

    /* Cart Summary */
    .cart-summary {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #666;
    }

    .summary-row.total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        padding-top: 1rem;
        border-top: 2px solid #f0f0f0;
        margin-top: 1rem;
    }

    .summary-row.total .amount {
        color: #4169E1;
    }

    .checkout-btn {
        width: 100%;
        background: #4169E1;
        color: #fff;
        padding: 1.2rem;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .checkout-btn:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.4);
    }

    .continue-shopping-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: #666;
        text-decoration: none;
        margin-top: 1rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .continue-shopping-link:hover {
        color: #4169E1;
    }

    /* Loading Spinner */
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #4169E1;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Update animation */
    .updating {
        opacity: 0.6;
        pointer-events: none;
    }

    .cart-item-total.updating::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #4169E1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    /* RTL Support */
    [dir="rtl"] .cart-content {
        direction: rtl;
    }

    [dir="rtl"] .cart-item {
        direction: rtl;
    }

    [dir="rtl"] .summary-row {
        direction: rtl;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .cart-container {
            padding: 2rem 1rem;
        }

        .cart-header h1 {
            font-size: 2rem;
        }

        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
        }

        .cart-item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .cart-summary {
            position: static;
        }
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <i class="fas fa-shopping-cart"></i>
        <h1>{{ __('messages.shopping_cart') }}</h1>
    </div>

    @if($cartItems->isEmpty())
        <div class="cart-items-section">
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>{{ __('messages.cart_empty') }}</h2>
                <p>{{ __('messages.cart_empty_description') }}</p>
                <a href="{{ route('products') }}" class="continue-shopping-btn">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.continue_shopping') }}
                </a>
            </div>
        </div>
    @else
        <div class="cart-content">
            <div class="cart-items-section">
                @foreach($cartItems as $item)
                    <div class="cart-item" data-product-id="{{ $item->product_id }}">
                        <div class="cart-item-image">
                            @if($item->product->images->isNotEmpty())
                                @php
                                    $imagePath = $item->product->images->first()->image_path;
                                    $imageUrl = (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://'))
                                        ? $imagePath
                                        : asset('storage/' . $imagePath);
                                @endphp
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $item->product->{'name_' . current_locale()} }}">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}"
                                     alt="{{ $item->product->{'name_' . current_locale()} }}">
                            @endif
                        </div>

                        <div class="cart-item-details">
                            <a href="{{ route('product.detail', $item->product->slug) }}" class="cart-item-title">
                                {{ $item->product->{'name_' . current_locale()} }}
                            </a>
                            <div class="cart-item-price">
                                ${{ number_format($item->price, 2) }}
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn decrease-qty" data-product-id="{{ $item->product_id }}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="quantity-display">{{ $item->quantity }}</span>
                                <button class="quantity-btn increase-qty" data-product-id="{{ $item->product_id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="cart-item-actions">
                            <div class="cart-item-total">
                                ${{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                            <button class="remove-btn" data-product-id="{{ $item->product_id }}">
                                <i class="fas fa-trash"></i>
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <h3 class="summary-title">{{ __('messages.order_summary') }}</h3>
                
                <div class="summary-row">
                    <span>{{ __('messages.subtotal') }}</span>
                    <span class="amount" id="subtotal-amount">${{ number_format($total, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>{{ __('messages.shipping') }}</span>
                    <span class="amount">{{ __('messages.calculated_at_checkout') }}</span>
                </div>
                
                <div class="summary-row total">
                    <span>{{ __('messages.total') }}</span>
                    <span class="amount" id="total-amount">${{ number_format($total, 2) }}</span>
                </div>
                
                <button class="checkout-btn">
                    <i class="fas fa-lock"></i>
                    {{ __('messages.proceed_to_checkout') }}
                </button>
                
                <a href="{{ route('products') }}" class="continue-shopping-link">
                    <i class="fas fa-arrow-{{ is_rtl() ? 'right' : 'left' }}"></i>
                    {{ __('messages.continue_shopping') }}
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Increase quantity
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const cartItem = this.closest('.cart-item');
            const quantityDisplay = cartItem.querySelector('.quantity-display');
            const currentQty = parseInt(quantityDisplay.textContent);
            
            updateQuantity(productId, currentQty + 1, cartItem);
        });
    });

    // Decrease quantity
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const cartItem = this.closest('.cart-item');
            const quantityDisplay = cartItem.querySelector('.quantity-display');
            const currentQty = parseInt(quantityDisplay.textContent);
            
            if (currentQty > 1) {
                updateQuantity(productId, currentQty - 1, cartItem);
            }
        });
    });

    // Remove item
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const cartItem = this.closest('.cart-item');
            
            if (confirm('{{ __('messages.confirm_remove_cart') }}')) {
                removeFromCart(productId, cartItem);
            }
        });
    });

    // Update quantity function
    function updateQuantity(productId, quantity, cartItem) {
        // Add updating class for visual feedback
        const itemTotal = cartItem.querySelector('.cart-item-total');
        itemTotal.classList.add('updating');
        
        fetch(`/cart/update/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity display
                const quantityDisplay = cartItem.querySelector('.quantity-display');
                quantityDisplay.textContent = quantity;
                
                // Update item total
                const price = parseFloat(cartItem.querySelector('.cart-item-price').textContent.replace('$', '').replace(',', ''));
                const newItemTotal = price * quantity;
                itemTotal.textContent = '$' + newItemTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                
                // Remove updating class
                itemTotal.classList.remove('updating');
                
                // Update cart summary totals
                updateCartSummary();
                
                // Update cart count in header
                updateCartCount();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            itemTotal.classList.remove('updating');
            alert('{{ __('messages.error_updating_cart') }}');
        });
    }

    // Remove from cart function
    function removeFromCart(productId, cartItem) {
        fetch(`/cart/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from DOM with animation
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(-100px)';
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        // Reload to show empty cart state
                        location.reload();
                    } else {
                        // Update cart summary totals
                        updateCartSummary();
                        
                        // Update cart count in header
                        updateCartCount();
                    }
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('messages.error_removing_cart') }}');
        });
    }

    // Update cart summary totals
    function updateCartSummary() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const itemTotalText = item.querySelector('.cart-item-total').textContent;
            const itemTotal = parseFloat(itemTotalText.replace('$', '').replace(',', ''));
            total += itemTotal;
        });
        
        // Format the total with commas
        const formattedTotal = '$' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Update subtotal and total in summary
        const subtotalElement = document.getElementById('subtotal-amount');
        const totalElement = document.getElementById('total-amount');
        
        if (subtotalElement) {
            subtotalElement.textContent = formattedTotal;
        }
        if (totalElement) {
            totalElement.textContent = formattedTotal;
        }
    }

    // Update cart count in header
    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartCountBadge = document.getElementById('cart-count');
                if (cartCountBadge) {
                    cartCountBadge.textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Checkout button
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            alert('{{ __('messages.checkout_coming_soon') }}');
        });
    }
});
</script>
@endsection
