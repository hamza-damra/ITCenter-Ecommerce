<?php $__env->startSection('title', __('messages.checkout') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
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

    /* Checkout Container */
    .checkout-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem;
        min-height: calc(100vh - 200px);
        background: #f5f5f5;
    }

    /* Progress Steps */
    .checkout-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        position: relative;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .checkout-progress::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, #1f2937 50%, #e2e8f0 50%);
        z-index: 0;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #999;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }

    .progress-step.active .step-circle {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-color: #1f2937;
        color: #fff;
        box-shadow: 0 4px 15px rgba(31, 41, 55, 0.4);
    }

    .progress-step.completed .step-circle {
        background: #4CAF50;
        border-color: #4CAF50;
        color: #fff;
    }

    .step-label {
        font-size: 0.9rem;
        color: #999;
        font-weight: 500;
        text-align: center;
    }

    .progress-step.active .step-label {
        color: #1f2937;
        font-weight: 600;
    }

    /* Main Content Grid */
    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        align-items: start;
    }

    /* Checkout Form */
    .checkout-form-section {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: #4169E1;
        font-size: 1.3rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-grid.full {
        grid-template-columns: 1fr;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group label .required {
        color: #ff4757;
        margin-left: 3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.9rem 1.2rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #fafafa;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4169E1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(65, 105, 225, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    /* Order Summary Sidebar */
    .order-summary-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: #fff;
        position: sticky;
        top: 100px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }

    .summary-header {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .summary-items {
        margin-bottom: 2rem;
    }

    .summary-item-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease;
    }

    .summary-item-link:hover {
        transform: translateX(-3px);
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .summary-item-link:hover .summary-item {
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .summary-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        flex-shrink: 0;
    }

    .summary-item-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
    }

    .summary-item-details {
        flex: 1;
    }

    .summary-item-name {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .summary-item-qty {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .summary-item-price {
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* Summary Totals */
    .summary-totals {
        padding: 1.5rem 0;
        border-top: 2px solid rgba(255, 255, 255, 0.2);
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row .label {
        opacity: 0.9;
    }

    .summary-row .value {
        font-weight: 600;
    }

    .summary-row.total {
        font-size: 1.4rem;
        font-weight: 700;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* Place Order Button */
    .place-order-btn {
        width: 100%;
        background: #fff;
        color: #667eea;
        padding: 1.2rem;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .place-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: #f8f9fa;
    }

    .place-order-btn i {
        font-size: 1rem;
    }

    /* Secure Badge */
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .secure-badge i {
        font-size: 1.2rem;
    }

    /* Payment Method Section */
    .payment-methods {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }

    .payment-option {
        padding: 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fafafa;
    }

    .payment-option:hover {
        border-color: #4169E1;
        background: #fff;
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #4169E1;
    }

    .payment-option-label {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-icon {
        font-size: 1.8rem;
        color: #4169E1;
    }

    .payment-info h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .payment-info p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    /* RTL Support */
    [dir="rtl"] .checkout-content {
        direction: rtl;
    }

    [dir="rtl"] .summary-row {
        direction: rtl;
    }

    [dir="rtl"] .form-group label .required {
        margin-right: 3px;
        margin-left: 0;
    }

    /* Mobile Responsive */
    @media (max-width: 968px) {
        .checkout-container {
            padding: 2rem 1rem;
        }

        .checkout-content {
            grid-template-columns: 1fr;
        }

        .checkout-progress {
            margin-bottom: 2rem;
        }

        .progress-step {
            font-size: 0.85rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 0.95rem;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .checkout-form-section {
            padding: 1.5rem;
        }

        .order-summary-sidebar {
            position: static;
            margin-top: 2rem;
        }
    }
</style>

<div class="checkout-container">
    <!-- Progress Steps -->
    <div class="checkout-progress">
        <div class="progress-step completed">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label"><?php echo e(__('messages.cart')); ?></div>
        </div>
        <div class="progress-step active">
            <div class="step-circle">2</div>
            <div class="step-label"><?php echo e(__('messages.checkout')); ?></div>
        </div>
        <div class="progress-step">
            <div class="step-circle">3</div>
            <div class="step-label"><?php echo e(__('messages.confirmation')); ?></div>
        </div>
    </div>

    <div class="checkout-content">
        <!-- Checkout Form -->
        <div class="checkout-form-section">
            <form id="checkout-form" method="POST" action="<?php echo e(route('checkout.process')); ?>">
                <?php echo csrf_field(); ?>
                
                <!-- Contact Information -->
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    <?php echo e(__('messages.contact_information')); ?>

                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">
                            <?php echo e(__('messages.first_name')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="<?php echo e(old('first_name', $user->first_name ?? '')); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">
                            <?php echo e(__('messages.last_name')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="<?php echo e(old('last_name', $user->last_name ?? '')); ?>"
                               required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="email">
                            <?php echo e(__('messages.email')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email', $user->email ?? '')); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <?php echo e(__('messages.phone')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo e(old('phone', $user->phone ?? '')); ?>"
                               required>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="section-title" style="margin-top: 2rem;">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo e(__('messages.shipping_address')); ?>

                </div>

                <div class="form-grid full">
                    <div class="form-group">
                        <label for="address">
                            <?php echo e(__('messages.street_address')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="<?php echo e(old('address')); ?>"
                               placeholder="<?php echo e(__('messages.address_placeholder')); ?>"
                               required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="city">
                            <?php echo e(__('messages.city')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="<?php echo e(old('city')); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="state"><?php echo e(__('messages.state')); ?></label>
                        <input type="text" 
                               id="state" 
                               name="state" 
                               value="<?php echo e(old('state')); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="postal_code">
                            <?php echo e(__('messages.postal_code')); ?>

                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="postal_code" 
                               name="postal_code" 
                               value="<?php echo e(old('postal_code')); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="country">
                            <?php echo e(__('messages.country')); ?>

                            <span class="required">*</span>
                        </label>
                        <select id="country" name="country" required>
                            <option value=""><?php echo e(__('messages.select_country')); ?></option>
                            <option value="Israel" <?php echo e(old('country') == 'Israel' ? 'selected' : ''); ?>><?php echo e(__('messages.israel')); ?></option>
                            <option value="Palestine" <?php echo e(old('country') == 'Palestine' ? 'selected' : ''); ?>><?php echo e(__('messages.palestine')); ?></option>
                            <option value="Jordan" <?php echo e(old('country') == 'Jordan' ? 'selected' : ''); ?>><?php echo e(__('messages.jordan')); ?></option>
                            <option value="Egypt" <?php echo e(old('country') == 'Egypt' ? 'selected' : ''); ?>><?php echo e(__('messages.egypt')); ?></option>
                            <option value="Lebanon" <?php echo e(old('country') == 'Lebanon' ? 'selected' : ''); ?>><?php echo e(__('messages.lebanon')); ?></option>
                            <option value="Syria" <?php echo e(old('country') == 'Syria' ? 'selected' : ''); ?>><?php echo e(__('messages.syria')); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="payment-methods">
                    <div class="section-title">
                        <i class="fas fa-credit-card"></i>
                        <?php echo e(__('messages.payment_method')); ?>

                    </div>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                        <div class="payment-option-label">
                            <i class="fas fa-money-bill-wave payment-icon"></i>
                            <div class="payment-info">
                                <h4><?php echo e(__('messages.cash_on_delivery')); ?></h4>
                                <p><?php echo e(__('messages.cod_description')); ?></p>
                            </div>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank_transfer">
                        <div class="payment-option-label">
                            <i class="fas fa-university payment-icon"></i>
                            <div class="payment-info">
                                <h4><?php echo e(__('messages.bank_transfer')); ?></h4>
                                <p><?php echo e(__('messages.bank_transfer_description')); ?></p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Order Notes -->
                <div class="form-grid full" style="margin-top: 2rem;">
                    <div class="form-group">
                        <label for="notes"><?php echo e(__('messages.order_notes')); ?></label>
                        <textarea id="notes" 
                                  name="notes" 
                                  placeholder="<?php echo e(__('messages.order_notes_placeholder')); ?>"><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-sidebar">
            <h2 class="summary-header"><?php echo e(__('messages.order_summary')); ?></h2>

            <div class="summary-items">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->product): ?>
                        <a href="<?php echo e(route('product.detail', $item->product->slug)); ?>" class="summary-item-link">
                            <div class="summary-item">
                                <div class="summary-item-image">
                                    <?php
                                        // Get raw main_image value from database
                                        $mainImage = $item->product->getAttributes()['main_image'] ?? null;
                                        $imageSrc = asset('images/products/default.png'); // default
                                        
                                        if ($mainImage) {
                                            if (str_starts_with($mainImage, 'http')) {
                                                $imageSrc = $mainImage;
                                            } elseif (str_starts_with($mainImage, 'images/')) {
                                                $imageSrc = asset($mainImage);
                                            } else {
                                                $imageSrc = asset('storage/' . $mainImage);
                                            }
                                        }
                                    ?>
                                    <img src="<?php echo e($imageSrc); ?>" 
                                         alt="<?php echo e($item->product->name); ?>"
                                         onerror="this.src='<?php echo e(asset('images/products/default.png')); ?>'">
                                </div>
                                <div class="summary-item-details">
                                    <div class="summary-item-name">
                                        <?php echo e($item->product->{'name_' . current_locale()}); ?>

                                    </div>
                                    <div class="summary-item-qty">
                                        <?php echo e(__('messages.quantity')); ?>: <?php echo e($item->quantity); ?>

                                    </div>
                                </div>
                                <div class="summary-item-price">
                                    ₪<?php echo e(number_format($item->price * $item->quantity, 2)); ?>

                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="summary-totals">
                <div class="summary-row">
                    <span class="label"><?php echo e(__('messages.subtotal')); ?></span>
                    <span class="value">₪<?php echo e(number_format($subtotal, 2)); ?></span>
                </div>
                <div class="summary-row total">
                    <span class="label"><?php echo e(__('messages.total')); ?></span>
                    <span class="value">₪<?php echo e(number_format($total, 2)); ?></span>
                </div>
            </div>

            <button type="submit" form="checkout-form" class="place-order-btn">
                <i class="fas fa-check-circle"></i>
                <?php echo e(__('messages.place_order')); ?>

            </button>

            <div class="secure-badge">
                <i class="fas fa-lock"></i>
                <?php echo e(__('messages.secure_checkout')); ?>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(checkoutForm);
        
        // Validate form
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.place-order-btn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.processing_order")); ?>...';
        
        // Submit form
        fetch(checkoutForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            alert('<?php echo e(__("messages.order_error")); ?>');
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\checkout.blade.php ENDPATH**/ ?>