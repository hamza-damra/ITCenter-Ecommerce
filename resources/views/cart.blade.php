@extends('layouts.app')

@section('title', __('messages.cart') . ' - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">

<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    /* Cart Page Styles */
    .cart-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: var(--space-12) var(--space-8);
        min-height: calc(100vh - 200px);
    }

    .cart-header {
        display: flex;
        align-items: center;
        gap: var(--space-4);
        margin-bottom: var(--space-8);
        padding-bottom: var(--space-6);
        border-bottom: 2px solid #e2e8f0;
    }

    .cart-header h1 {
        font-size: var(--text-4xl);
        color: var(--text-primary);
        font-weight: 700;
        margin: 0;
    }

    .cart-header i {
        font-size: var(--text-3xl);
        color: var(--primary-blue);
    }

    .cart-content {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: var(--space-8);
        align-items: start;
    }

    @media (max-width: 968px) {
        .cart-content {
            grid-template-columns: 1fr;
            gap: var(--space-6);
        }
    }

    /* Cart Items Section */
    .cart-items-section {
        background: var(--bg-card);
        border-radius: var(--radius-xl);
        padding: var(--space-8);
        box-shadow: var(--shadow-md);
        transition: all var(--transition-bounce);
    }

    .cart-items-section:hover {
        box-shadow: var(--shadow-lg);
    }

    .cart-item {
        display: flex;
        flex-direction: column;
        gap: var(--space-4);
        padding: var(--space-6);
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-4);
        transition: all var(--transition-bounce);
        box-shadow: var(--shadow-sm);
        background: var(--bg-card);
    }

    @media (min-width: 769px) {
        .cart-item {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .cart-item-top {
            flex: 1;
        }

        .cart-item-actions {
            flex-direction: column;
            align-items: flex-end;
        }
    }

    .cart-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-color: var(--primary-blue);
    }

    .cart-item-top {
        display: flex;
        gap: var(--space-6);
        align-items: flex-start;
    }

    .cart-item-image {
        width: 120px;
        height: 120px;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all var(--transition-normal);
    }

    .cart-item:hover .cart-item-image {
        transform: scale(1.02);
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: var(--space-2);
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-size: 1rem;
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
        font-size: 1.2rem;
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
        min-width: 180px;
    }

    .cart-item-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        white-space: nowrap;
        min-width: 150px;
        text-align: center;
    }

    .remove-btn {
        background: #4169E1;
        color: #fff;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        min-width: 140px;
        white-space: nowrap;
    }

    .remove-btn:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(65, 105, 225, 0.3);
    }

    .remove-btn i {
        font-size: 0.85rem;
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-cart i {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .empty-cart h2 {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-cart p {
        color: #999;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .continue-shopping-btn {
        background: #4169E1;
        color: #fff;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
    }

    .continue-shopping-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(65, 105, 225, 0.4);
        background: #1E90FF;
    }
    .continue-shopping-btn i {
        font-size: 1rem;
        margin-{{ is_rtl() ? 'left' : 'right' }}: 0.5rem;
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
        padding: 1rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
    }

    .checkout-btn:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(65, 105, 225, 0.4);
    }

    .checkout-btn i {
        font-size: 0.9rem;
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
        font-size: 0.95rem;
        transition: color 0.3s;
    }

    .continue-shopping-link:hover {
        color: #4169E1;
    }

    .continue-shopping-link i {
        font-size: 0.9rem;
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

    /* Mobile Responsive - Tablet */
    @media (max-width: 968px) {
        .cart-content {
            grid-template-columns: 1fr;
            gap: var(--space-6);
        }
        
        .cart-summary {
            position: static;
            order: -1;
        }
    }

    /* Mobile Responsive - Phone */
    @media (max-width: 768px) {
        .cart-container {
            padding: 1.5rem 1rem;
        }

        .cart-header {
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }

        .cart-header h1 {
            font-size: 1.5rem;
        }

        .cart-header i {
            font-size: 1.5rem;
        }

        .cart-items-section {
            padding: 1rem;
            border-radius: 12px;
        }

        .cart-item {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
        }

        .cart-item-top {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            min-width: 80px;
            border-radius: 10px;
        }

        .cart-item-details {
            flex: 1;
            min-width: 0;
        }

        .cart-item-title {
            font-size: 0.9rem;
            line-height: 1.3;
            margin-bottom: 0.35rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cart-item-price {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .quantity-controls {
            gap: 0.5rem;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }

        .quantity-display {
            min-width: 30px;
            font-size: 1rem;
        }

        .cart-item-actions {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            min-width: auto;
        }

        .cart-item-total {
            font-size: 1.2rem;
            min-width: auto;
            text-align: {{ is_rtl() ? 'right' : 'left' }};
        }

        .remove-btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            min-width: auto;
        }

        /* Cart Summary Mobile */
        .cart-summary {
            padding: 1.25rem;
            border-radius: 12px;
            position: static;
        }

        .summary-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
        }

        .summary-row {
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .summary-row.total {
            font-size: 1.25rem;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        .checkout-btn {
            padding: 0.875rem;
            font-size: 0.95rem;
            margin-top: 1.25rem;
        }

        .continue-shopping-link {
            font-size: 0.9rem;
            margin-top: 0.875rem;
        }

        /* Empty Cart Mobile */
        .empty-cart {
            padding: 2.5rem 1rem;
        }

        .empty-cart i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .empty-cart h2 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .empty-cart p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .continue-shopping-btn {
            padding: 0.875rem 2rem;
            font-size: 0.95rem;
        }
    }

    /* Mobile Responsive - Small Phone */
    @media (max-width: 480px) {
        .cart-container {
            padding: 1rem 0.75rem;
        }

        .cart-header {
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .cart-header h1 {
            font-size: 1.25rem;
        }

        .cart-header i {
            font-size: 1.25rem;
        }

        .cart-items-section {
            padding: 0.75rem;
        }

        .cart-item {
            padding: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .cart-item-image {
            width: 70px;
            height: 70px;
            min-width: 70px;
        }

        .cart-item-title {
            font-size: 0.85rem;
        }

        .cart-item-price {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .quantity-display {
            min-width: 25px;
            font-size: 0.9rem;
        }

        .cart-item-actions {
            padding-top: 0.75rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .cart-item-total {
            font-size: 1.1rem;
        }

        .remove-btn {
            padding: 0.45rem 0.875rem;
            font-size: 0.8rem;
            gap: 0.35rem;
        }

        .remove-btn i {
            font-size: 0.75rem;
        }

        /* Cart Summary Small Phone */
        .cart-summary {
            padding: 1rem;
        }

        .summary-title {
            font-size: 1.1rem;
        }

        .summary-row {
            font-size: 0.9rem;
        }

        .summary-row.total {
            font-size: 1.1rem;
        }

        .checkout-btn {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        /* Empty Cart Small Phone */
        .empty-cart {
            padding: 2rem 0.75rem;
        }

        .empty-cart i {
            font-size: 3rem;
        }

        .empty-cart h2 {
            font-size: 1.1rem;
        }

        .empty-cart p {
            font-size: 0.85rem;
        }

        .continue-shopping-btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
    }

    /* RTL Mobile Adjustments */
    @media (max-width: 768px) {
        [dir="rtl"] .cart-item-actions {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .quantity-controls {
            flex-direction: row-reverse;
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
                    {{ __('messages.continue_shopping') }}
                </a>
            </div>
        </div>
    @else
        <div class="cart-content">
            <div class="cart-items-section">
                @foreach($cartItems as $item)
                    @if($item->product)
                    <div class="cart-item" data-product-id="{{ $item->product_id }}">
                        <div class="cart-item-top">
                            <a href="{{ route('product.detail', $item->product) }}" class="cart-item-image">
                                @if($item->product->images && $item->product->images->isNotEmpty())
                                    @php
                                        $imagePath = $item->product->images->first()->image_path;
                                        $imageUrl = (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://'))
                                            ? $imagePath
                                            : asset('storage/' . $imagePath);
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $item->product->name }}" 
                                         loading="lazy"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image\'><i class=\'fas fa-image\'></i></div>';">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="cart-item-details">
                                <a href="{{ route('product.detail', $item->product) }}" class="cart-item-title">
                                    {{ $item->product->name }}
                                </a>
                                <div class="cart-item-price">${{ number_format($item->price, 2) }}</div>
                                
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
                    @endif
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
                
                <a href="{{ route('checkout.index') }}" class="checkout-btn" style="text-decoration: none;">
                    <i class="fas fa-lock"></i>
                    {{ __('messages.proceed_to_checkout') }}
                </a>
                
                <a href="{{ route('products') }}" class="continue-shopping-link">
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
            
            removeFromCart(productId, cartItem);
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
                
                // Update cart count in header using global refresh function
                if (typeof refreshHeaderCounters === 'function') {
                    refreshHeaderCounters();
                }
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
                        
                        // Update cart count in header using global refresh function
                        if (typeof refreshHeaderCounters === 'function') {
                            refreshHeaderCounters();
                        }
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

    // This function is no longer needed - using global refreshHeaderCounters() instead
    // Keeping it here for backwards compatibility in case it's called elsewhere
    function updateCartCount() {
        if (typeof refreshHeaderCounters === 'function') {
            refreshHeaderCounters();
        }
    }
});
</script>
@endsection
