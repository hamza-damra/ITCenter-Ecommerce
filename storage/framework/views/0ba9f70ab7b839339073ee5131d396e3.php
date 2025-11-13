<?php $__env->startSection('title', $product->name . ' - IT Center'); ?>

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

    .product-detail-container {
        padding: 3rem 0;
        background: #fff;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
    }

    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 968px) {
        .product-main {
            grid-template-columns: 1fr;
        }
    }

    /* Product Images Section */
    .product-images {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .main-image {
        width: 100%;
        height: 500px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        position: relative;
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        cursor: pointer;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        transition: transform 0.3s, opacity 0.3s;
        padding: 10px;
    }

    .main-image:hover img {
        transform: scale(1.05);
        opacity: 0.9;
    }

    /* Image Zoom Modal Styles */
    .image-zoom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
    }

    .image-zoom-modal.active {
        display: flex;
        opacity: 1;
    }

    .modal-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 2rem;
    }

    .modal-main-content {
        display: flex;
        gap: 1.5rem;
        max-width: 1600px;
        width: 100%;
        height: 95vh;
        align-items: center;
    }

    .modal-image-wrapper {
        flex: 1;
        height: 100%;
        position: relative;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        cursor: default;
    }

    .modal-main-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .modal-main-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: opacity 0.3s ease, transform 0.3s ease;
        animation: fadeInImage 0.3s ease;
    }

    @keyframes fadeInImage {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Modal Thumbnails Sidebar */
    .modal-thumbnails {
        width: 140px;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        overflow-y: auto;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }

    .modal-thumbnails::-webkit-scrollbar {
        width: 4px;
    }

    .modal-thumbnails::-webkit-scrollbar-track {
        background: transparent;
    }

    .modal-thumbnails::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .modal-thumbnail {
        width: 100%;
        aspect-ratio: 1;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .modal-thumbnail:hover {
        border-color: rgba(39, 98, 243, 0.6);
        transform: scale(1.05);
    }

    .modal-thumbnail.active {
        border-color: #2762f3;
        box-shadow: 0 0 0 2px rgba(39, 98, 243, 0.3);
    }

    .modal-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 4px;
    }

    /* Navigation Arrows */
    .modal-nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #333;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .modal-nav-arrow:hover {
        background: #2762f3;
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
    }

    .modal-nav-arrow:active {
        transform: translateY(-50%) scale(0.95);
    }

    .modal-nav-arrow.prev {
        <?php echo e(is_rtl() ? 'right: 1rem;' : 'left: 1rem;'); ?>

    }

    .modal-nav-arrow.next {
        <?php echo e(is_rtl() ? 'left: 1rem;' : 'right: 1rem;'); ?>

    }

    /* Close Button */
    .modal-close-btn {
        position: absolute;
        top: 1.5rem;
        <?php echo e(is_rtl() ? 'left: 1.5rem;' : 'right: 1.5rem;'); ?>

        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #333;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .modal-close-btn:hover {
        background: #e74c3c;
        color: #ffffff;
        transform: rotate(90deg) scale(1.1);
    }

    .modal-close-btn:active {
        transform: rotate(90deg) scale(0.95);
    }

    /* Image Counter */
    .image-counter {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.75);
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        pointer-events: none;
        backdrop-filter: blur(10px);
    }

    /* Mobile Responsive */
    @media (max-width: 968px) {
        .modal-main-content {
            flex-direction: column-reverse;
            height: 95vh;
            padding: 1rem 0;
        }

        .modal-thumbnails {
            width: 100%;
            height: 100px;
            flex-direction: row;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .modal-thumbnail {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .modal-image-wrapper {
            height: calc(100% - 120px);
        }

        .modal-main-image {
            padding: 1rem;
        }

        .modal-nav-arrow {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .modal-nav-arrow.prev {
            <?php echo e(is_rtl() ? 'right: 0.5rem;' : 'left: 0.5rem;'); ?>

        }

        .modal-nav-arrow.next {
            <?php echo e(is_rtl() ? 'left: 0.5rem;' : 'right: 0.5rem;'); ?>

        }

        .modal-close-btn {
            top: 1rem;
            <?php echo e(is_rtl() ? 'left: 1rem;' : 'right: 1rem;'); ?>

            width: 38px;
            height: 38px;
            font-size: 1.2rem;
        }

        .image-counter {
            bottom: 1rem;
            font-size: 0.75rem;
            padding: 0.4rem 1rem;
        }

        .modal-container {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .modal-thumbnails {
            height: 80px;
        }

        .modal-thumbnail {
            width: 60px;
            height: 60px;
        }

        .modal-image-wrapper {
            height: calc(100% - 100px);
        }

        .modal-main-image {
            padding: 0.5rem;
        }
    }

    .thumbnail-images {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .thumbnail {
        height: 100px;
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #4169E1;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        padding: 5px;
    }

    /* Product Info Section */
    .product-info {
        padding: 1rem 0;
    }

    .product-category {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .stars {
        color: #ffa500;
        font-size: 1.1rem;
    }

    .rating-text {
        color: #666;
        font-size: 0.9rem;
    }

    .product-price {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .current-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2762f3;
    }

    .original-price {
        font-size: 1.5rem;
        color: #999;
        text-decoration: line-through;
    }

    .discount-badge {
        background: #ff4444;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #d4edda;
        color: #155724;
        border-radius: 6px;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .stock-status.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    .product-description {
        color: #555;
        line-height: 1.8;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    /* Quantity Selector */
    .quantity-section {
        margin-bottom: 1.5rem;
    }

    .quantity-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        display: block;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .quantity-btn {
        background: #f5f5f5;
        border: none;
        padding: 0.8rem 1.2rem;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.3s;
    }

    .quantity-btn:hover {
        background: #e0e0e0;
    }

    .quantity-input {
        border: none;
        width: 60px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 0.8rem 0;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn-add-cart {
        flex: 1;
        background: #4169E1;
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add-cart:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(65, 105, 225, 0.3);
    }

    .btn-add-cart:disabled,
    .btn-buy-now:disabled,
    .quantity-btn:disabled {
        background: #ccc;
        color: #666;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-add-cart:disabled:hover,
    .btn-buy-now:disabled:hover,
    .quantity-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .btn-buy-now {
        flex: 1;
        background: #000;
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-buy-now:hover {
        background: #333;
        transform: translateY(-2px);
    }

    .btn-wishlist {
        background: #fff;
        border: 2px solid #4169E1;
        color: #4169E1;
        padding: 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.3rem;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: #fff;
        color: #ff0000;
        border-color: #ff0000;
    }

    .btn-wishlist.active {
        background: #fff;
        color: #ff0000;
        border-color: #ff0000;
    }

    .btn-wishlist.active i {
        color: #ff0000;
    }

    .btn-wishlist i {
        transition: color 0.3s;
    }

    /* Product Features */
    .product-features {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .feature-item:last-child {
        border-bottom: none;
    }

    .feature-icon {
        color: #4169E1;
        font-size: 1.3rem;
        width: 30px;
    }

    .feature-text {
        color: #555;
        font-size: 0.95rem;
    }

    /* Specifications Section */
    .specifications-section {
        margin-top: 3rem;
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4169E1;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .spec-item {
        display: flex;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .spec-label {
        font-weight: 600;
        color: #333;
        min-width: 150px;
    }

    .spec-value {
        color: #666;
    }

    /* Related Products */
    .related-products {
        margin-top: 4rem;
        padding: 3rem 0;
        background: #f8f9fa;
    }

    .related-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 2rem;
        text-align: center;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .product-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(230, 146, 112, 0.08);
        position: relative;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2762f3 0%, #1a4dbf 50%, #333333 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(39, 98, 243, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: rgba(39, 98, 243, 0.2);
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .product-card-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        padding: 10px;
        transition: transform 0.4s ease-in-out, filter 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
        filter: brightness(1);
        will-change: transform;
    }

    .product-card:hover .product-card-image img {
        transform: scale(1.08);
        filter: brightness(1.05);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .product-card-content {
        padding: 1rem;
    }

    .product-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-card-title {
        color: #2762f3;
    }

    .product-card-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2762f3;
    }

    @media (max-width: 968px) {
        .product-main {
            grid-template-columns: 1fr;
        }

        .product-images {
            position: relative;
            top: 0;
        }

        .specs-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 568px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }

    /* RTL Support */
    [dir="rtl"] .product-detail-container {
        direction: rtl;
        text-align: right;
    }

    [dir="rtl"] .product-rating {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    [dir="rtl"] .product-price {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    [dir="rtl"] .action-buttons {
        direction: rtl;
    }

    [dir="rtl"] .feature-item {
        direction: rtl;
    }

    [dir="rtl"] .spec-item {
        direction: rtl;
    }

    [dir="rtl"] .quantity-selector {
        direction: ltr;
        justify-content: flex-end;
    }

    /* أخفي الأسهم في كروم/إيدج/سفاري */
.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* أخفيها في فايرفوكس */
.quantity-input {
  -moz-appearance: textfield;
  appearance: textfield; /* دعم عام */
}

</style>

<div class="product-detail-container">
    <div class="container">
        <div class="product-main">
            <!-- Product Images -->
            <div class="product-images">
                <div class="main-image">
                    <?php
                        $mainImageUrl = $product->main_image 
                            ? (filter_var($product->main_image, FILTER_VALIDATE_URL) 
                                ? $product->main_image 
                                : asset('storage/' . $product->main_image))
                            : 'https://via.placeholder.com/800x800/f5f5f5/666666?text=' . urlencode($product->name);
                    ?>
                    <img src="<?php echo e($mainImageUrl); ?>" alt="<?php echo e($product->name); ?>" id="mainImage" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22800%22%3E%3Crect width=%22800%22 height=%22800%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2224%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                </div>
                <div class="thumbnail-images">
                    <?php if($product->images->count() > 0): ?>
                        <?php $__currentLoopData = $product->images->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="thumbnail <?php echo e($index === 0 ? 'active' : ''); ?>">
                                <?php
                                    $thumbnailUrl = $image->image_path 
                                        ? (filter_var($image->image_path, FILTER_VALIDATE_URL) 
                                            ? $image->image_path 
                                            : asset('storage/' . $image->image_path))
                                        : 'https://via.placeholder.com/200x200/f5f5f5/666666?text=Image+' . ($index + 1);
                                ?>
                                <img src="<?php echo e($thumbnailUrl); ?>" alt="<?php echo e($product->name); ?>" onclick="changeImage(this)" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2216%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="thumbnail active">
                            <img src="<?php echo e($mainImageUrl); ?>" alt="<?php echo e($product->name); ?>" onclick="changeImage(this)" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2216%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-category"><?php echo e($product->category->{'name_' . current_locale()} ?? $product->category->name ?? __('messages.Uncategorized')); ?> <?php if($product->brand): ?> / <?php echo e($product->brand->{'name_' . current_locale()} ?? $product->brand->name); ?><?php endif; ?></div>
                <h1 class="product-title"><?php echo e($product->{'name_' . current_locale()} ?? $product->name); ?></h1>

                <div class="product-rating" style="cursor: pointer;" onclick="document.getElementById('reviews-section').scrollIntoView({ behavior: 'smooth' })">
                    <div class="stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= floor($product->avg_rating)): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif($i - $product->avg_rating < 1): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text"><?php echo e(number_format($product->avg_rating, 1)); ?> (<?php echo e($product->reviews_count); ?> <?php echo e(__('messages.reviews')); ?>)</span>
                </div>

                <div class="product-price">
                    <span class="current-price">$<?php echo e(number_format($product->final_price, 2)); ?></span>
                    <?php if($product->is_on_sale): ?>
                        <span class="original-price">$<?php echo e(number_format($product->price, 2)); ?></span>
                        <span class="discount-badge">-<?php echo e($product->discount_percentage); ?>%</span>
                    <?php endif; ?>
                </div>

                <div class="stock-status <?php echo e($product->stock_status === 'out_of_stock' ? 'out-of-stock' : ''); ?>">
                    <?php if($product->stock_status === 'in_stock'): ?>
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo e(__('messages.in_stock')); ?></span>
                    <?php else: ?>
                        <i class="fas fa-times-circle"></i>
                        <span><?php echo e(__('messages.out_of_stock')); ?></span>
                    <?php endif; ?>
                </div>

                <p class="product-description">
                    <?php echo e($product->{'short_description_' . current_locale()} ?? $product->short_description ?? $product->{'description_' . current_locale()} ?? $product->description); ?>

                </p>

                <div class="product-features">
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast feature-icon"></i>
                        <span class="feature-text"><?php echo e(__('messages.free_shipping')); ?></span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo feature-icon"></i>
                        <span class="feature-text"><?php echo e(__('messages.return_policy')); ?></span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <span class="feature-text"><?php echo e(__('messages.warranty')); ?></span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset feature-icon"></i>
                        <span class="feature-text"><?php echo e(__('messages.customer_support')); ?></span>
                    </div>
                </div>

                <div class="quantity-section">
                    <label class="quantity-label"><?php echo e(__('messages.quantity')); ?>:</label>
                    <div class="quantity-selector">
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="decreaseQuantity()" <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>>-</button>
                            <input type="number" class="quantity-input" value="1" min="1" 
                                   max="<?php echo e($product->track_stock ? $product->stock_quantity : 999); ?>" 
                                   id="quantity" 
                                   <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>>
                            <button class="quantity-btn" onclick="increaseQuantity()" <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>>+</button>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-add-cart"
                            type="button"
                            onclick="addToCartWithQuantity(<?php echo e($product->id); ?>, this)"
                            <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>>
                        <i class="fas fa-shopping-cart"></i>
                        <?php echo e($product->stock_status === 'out_of_stock' ? __('messages.out_of_stock') : __('messages.add_to_cart')); ?>

                    </button>
                    <button class="btn-buy-now"
                            type="button"
                            onclick="buyNow(<?php echo e($product->id); ?>, this)"
                            <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>>
                        <?php echo e($product->stock_status === 'out_of_stock' ? __('messages.unavailable') : __('messages.buy_now')); ?>

                    </button>
                    <button class="btn-wishlist wishlist-btn"
                            type="button"
                            data-product-id="<?php echo e($product->id); ?>">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="specifications-section">
            <h2 class="section-title"><?php echo e(__('messages.technical_specifications')); ?></h2>
            <div class="specs-grid">
                <?php if($product->specifications && is_array($product->specifications)): ?>
                    <?php $__currentLoopData = $product->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="spec-item">
                            <span class="spec-label"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</span>
                            <span class="spec-value"><?php echo e($value); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="spec-item">
                        <span class="spec-label">SKU:</span>
                        <span class="spec-value"><?php echo e($product->sku); ?></span>
                    </div>
                    <?php if($product->weight): ?>
                        <div class="spec-item">
                            <span class="spec-label">Weight:</span>
                            <span class="spec-value"><?php echo e($product->weight); ?> kg</span>
                        </div>
                    <?php endif; ?>
                    <?php if($product->warranty): ?>
                        <div class="spec-item">
                            <span class="spec-label">Warranty:</span>
                            <span class="spec-value"><?php echo e($product->warranty); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($product->length && $product->width && $product->height): ?>
                        <div class="spec-item">
                            <span class="spec-label">Dimensions:</span>
                            <span class="spec-value"><?php echo e($product->length); ?> x <?php echo e($product->width); ?> x <?php echo e($product->height); ?> cm</span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Full Description -->
        <?php if($product->{'description_' . current_locale()} ?? $product->description && ($product->{'description_' . current_locale()} ?? $product->description) != ($product->{'short_description_' . current_locale()} ?? $product->short_description)): ?>
        <div class="specifications-section" style="margin-top: 2rem;">
            <h2 class="section-title"><?php echo e(__('messages.product_description')); ?></h2>
            <div style="color: #555; line-height: 1.8; font-size: 1rem;">
                <?php echo nl2br(e($product->{'description_' . current_locale()} ?? $product->description)); ?>

            </div>
        </div>
        <?php endif; ?>

        <!-- Reviews Section -->
        <?php echo $__env->make('partials.reviews-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<!-- Related Products -->
<div class="related-products">
    <div class="container">
        <h2 class="related-title"><?php echo e(__('messages.related_products')); ?></h2>
        <div class="products-grid">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.detail', $relatedProduct->slug)); ?>" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        <div class="product-card-image">
                            <?php
                                $relatedImageUrl = $relatedProduct->main_image 
                                    ? (filter_var($relatedProduct->main_image, FILTER_VALIDATE_URL) 
                                        ? $relatedProduct->main_image 
                                        : asset('storage/' . $relatedProduct->main_image))
                                    : 'https://via.placeholder.com/300x200/f5f5f5/666666?text=' . urlencode($relatedProduct->{'name_' . current_locale()} ?? $relatedProduct->name);
                            ?>
                            <img src="<?php echo e($relatedImageUrl); ?>" alt="<?php echo e($relatedProduct->{'name_' . current_locale()} ?? $relatedProduct->name); ?>" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2216%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                        </div>
                        <div class="product-card-content">
                            <h3 class="product-card-title"><?php echo e($relatedProduct->{'name_' . current_locale()} ?? $relatedProduct->name); ?></h3>
                            <div class="product-card-price">$<?php echo e(number_format($relatedProduct->final_price, 2)); ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<!-- Image Zoom Modal -->
<?php
    $allImages = collect([$product->main_image]);
    if($product->images->count() > 0) {
        $allImages = $allImages->merge($product->images->pluck('image_path'));
    }

    // Resolve first image URL for immediate render inside modal (avoids broken/empty src before JS runs)
    $firstImagePath = $allImages->first();
    $firstImageUrl = $firstImagePath
        ? (filter_var($firstImagePath, FILTER_VALIDATE_URL)
            ? $firstImagePath
            : asset('storage/' . $firstImagePath))
        : 'https://via.placeholder.com/1000x800/f5f5f5/666666?text=' . urlencode($product->name);
?>
<div class="image-zoom-modal" id="imageZoomModal">
    <div class="modal-container">
        <!-- Close Button -->
        <button class="modal-close-btn" onclick="closeZoomModal()">
            <i class="fas fa-times"></i>
        </button>

        <!-- Navigation Arrows -->
        <button class="modal-nav-arrow prev" onclick="navigateModalImage(-1)">
            <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
        </button>
        <button class="modal-nav-arrow next" onclick="navigateModalImage(1)">
            <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i>
        </button>

        <!-- Image Counter -->
        <div class="image-counter" id="imageCounter">
            <i class="fas fa-images"></i>
            <span id="currentImageNumber">1</span> / <span id="totalImages"><?php echo e($allImages->count()); ?></span>
        </div>

        <div class="modal-main-content">
            <!-- Main Image -->
            <div class="modal-image-wrapper" id="modalImageWrapper">
                <div class="modal-main-image" id="modalMainImage">
                    <img src="<?php echo e($firstImageUrl); ?>" alt="<?php echo e($product->name); ?>" id="modalImage" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%221000%22 height=%22800%22%3E%3Crect width=%221000%22 height=%22800%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2224%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                </div>
            </div>

            <!-- Thumbnails Sidebar -->
            <div class="modal-thumbnails" id="modalThumbnails">
                <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagePath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $imageUrl = $imagePath 
                            ? (filter_var($imagePath, FILTER_VALIDATE_URL) 
                                ? $imagePath 
                                : asset('storage/' . $imagePath))
                            : 'https://via.placeholder.com/200x200/f5f5f5/666666?text=Image+' . ($index + 1);
                    ?>
                    <div class="modal-thumbnail <?php echo e($index === 0 ? 'active' : ''); ?>" onclick="selectModalImage(<?php echo e($index); ?>)">
                        <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product->name); ?>" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23f5f5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23666%22 font-family=%22Arial%22 font-size=%2216%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Image Gallery Modal System
    let currentModalImageIndex = 0;
    let modalImages = [];
    let totalImagesCount = 0;
    let modal = null;
    let modalImage = null;

    // Initialize modal images array
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements and store in global scope
        modal = document.getElementById('imageZoomModal');
        modalImage = document.getElementById('modalImage');
        
        // Collect all image URLs from modal thumbnails (they have the correct full URLs)
        const modalThumbnails = document.querySelectorAll('.modal-thumbnail img');
        modalImages = Array.from(modalThumbnails).map(img => img.src);
        totalImagesCount = modalImages.length;

        // Add click event to main image to open modal
        const mainImage = document.querySelector('.main-image');
        if (mainImage) {
            mainImage.addEventListener('click', function() {
                openImageGallery(0);
            });
        }

        // Add click events to thumbnails to open modal with specific image
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            thumb.addEventListener('click', function(e) {
                // Prevent changeImage from firing
                e.stopPropagation();
                // Add 1 to index since main image is at index 0
                openImageGallery(index);
            });
        });
    });

    // Open image gallery modal
    function openImageGallery(imageIndex = 0) {
        currentModalImageIndex = imageIndex;

        // Safety: if for any reason the array is empty, rebuild from modal thumbnails
        if (!modalImages || modalImages.length === 0) {
            const modalThumbImgs = document.querySelectorAll('.modal-thumbnail img');
            modalImages = Array.from(modalThumbImgs).map(img => img.src);
            totalImagesCount = modalImages.length;
            const totalSpan = document.getElementById('totalImages');
            if (totalSpan) totalSpan.textContent = totalImagesCount;
        }

        if (modalImages.length > 0) {
            const nextSrc = modalImages[currentModalImageIndex];
            try { console.debug('Modal opening. Setting src ->', nextSrc); } catch (e) {}
            modalImage.src = nextSrc;
            modal.classList.add('active');
            updateModalThumbnails();
            updateImageCounter();
            document.body.style.overflow = 'hidden';
        }
    }

    // Close image gallery modal
    function closeZoomModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Navigate between images in modal
    function navigateModalImage(direction) {
        currentModalImageIndex += direction;
        
        // Loop around
        if (currentModalImageIndex < 0) {
            currentModalImageIndex = modalImages.length - 1;
        } else if (currentModalImageIndex >= modalImages.length) {
            currentModalImageIndex = 0;
        }
        
        changeModalImage();
    }

    // Select image from modal thumbnails
    function selectModalImage(index) {
        currentModalImageIndex = index;
        changeModalImage();
    }

    // Change modal image with fade effect
    function changeModalImage() {
        // Fade out
        modalImage.style.opacity = '0';
        
        setTimeout(() => {
            // Change image
            const nextSrc = modalImages[currentModalImageIndex];
            try { console.debug('Modal change. Setting src ->', nextSrc); } catch (e) {}
            modalImage.src = nextSrc;
            
            // Trigger animation
            modalImage.style.animation = 'none';
            setTimeout(() => {
                modalImage.style.animation = 'fadeInImage 0.3s ease';
                modalImage.style.opacity = '1';
            }, 10);
            
            updateModalThumbnails();
            updateImageCounter();
        }, 150);
    }

    // Update active thumbnail in modal
    function updateModalThumbnails() {
        document.querySelectorAll('.modal-thumbnail').forEach((thumb, index) => {
            if (index === currentModalImageIndex) {
                thumb.classList.add('active');
                thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                thumb.classList.remove('active');
            }
        });
    }

    // Update image counter
    function updateImageCounter() {
        const currentNumber = document.getElementById('currentImageNumber');
        if (currentNumber) {
            currentNumber.textContent = currentModalImageIndex + 1;
        }
    }

    // Close modal on clicking outside
    document.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeZoomModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (modal && modal.classList.contains('active')) {
            if (e.key === 'Escape') {
                closeZoomModal();
            } else if (e.key === 'ArrowLeft') {
                navigateModalImage(<?php echo e(is_rtl() ? '1' : '-1'); ?>);
            } else if (e.key === 'ArrowRight') {
                navigateModalImage(<?php echo e(is_rtl() ? '-1' : '1'); ?>);
            }
        }
    });

    // Change main image
    function changeImage(element) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = element.src.replace('150x150', '600x600');

        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.parentElement.classList.add('active');
    }

    // Quantity controls
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    // Add to cart functionality with quantity support
    function addToCartWithQuantity(productId, button) {
        console.log('Add to cart clicked for product:', productId);
        
        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value) || 1;
        const originalText = button.innerHTML;

        console.log('Quantity:', quantity);

        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('Security token not found. Please refresh the page.');
            return;
        }

        console.log('Sending request to:', `/cart/add/${productId}`);

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return window.handleAccountStatus ? handleAccountStatus(response) : response;
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Show success feedback
                button.innerHTML = '<i class="fas fa-check"></i> Added!';
                button.style.background = '#28a745';
                
                // Update cart count in header
                updateCartCount();

                // Reset button after 2 seconds
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    button.style.background = '';
                }, 2000);

                // Show notification
                showNotification(data.message || 'Product added to cart successfully!');
            } else {
                // Show error
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('An error occurred. Please try again.');
        });
    }

    // Buy Now functionality
    function buyNow(productId, button) {
        console.log('Buy Now clicked for product:', productId);

        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value) || 1;
        const originalText = button.innerHTML;

        console.log('Quantity:', quantity);

        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('Security token not found. Please refresh the page.');
            return;
        }

        console.log('Sending request to:', `/cart/add/${productId}`);

        // Add product to cart
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return window.handleAccountStatus ? handleAccountStatus(response) : response;
        })
        .then(response => response.json())
        .then(async data => {
            console.log('Response data:', data);

            if (data.success) {
                // Update cart count in header
                await updateCartCount();

                // Show notification
                showNotification(data.message || 'Product added to cart! Redirecting to checkout...');

                // Wait a bit longer to ensure the database transaction is complete
                // Then redirect to checkout
                setTimeout(() => {
                    // Force a full page reload to checkout to ensure fresh cart data
                    window.location.href = '<?php echo e(route("checkout.index")); ?>?fresh=1';
                }, 800);
            } else {
                // Show error
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('An error occurred. Please try again.');
        });
    }

    // Wishlist functionality is handled by the global script in layout
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/product-detail.blade.php ENDPATH**/ ?>