/**
 * Product Detail JavaScript Functions
 * Handles image gallery, quantity controls, and wishlist functionality
 */

// ====================================
// Image Gallery Functions
// ====================================

/**
 * Change the main product image when thumbnail is clicked
 * @param {HTMLElement} element - The clicked thumbnail image element
 */
function changeImage(element) {
    const mainImage = document.getElementById('mainImage');
    
    // Update main image source
    mainImage.src = element.src.replace('150x150', '600x600');

    // Update active thumbnail styling
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.parentElement.classList.add('active');
}

// ====================================
// Quantity Control Functions
// ====================================

/**
 * Increase product quantity
 */
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
    }
}

/**
 * Decrease product quantity
 */
function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    
    if (current > 1) {
        input.value = current - 1;
    }
}

// ====================================
// Wishlist Functions
// ====================================

/**
 * Toggle wishlist icon between filled and outlined state
 */
function toggleWishlist() {
    const wishlistBtn = document.querySelector('.btn-wishlist');
    const icon = wishlistBtn.querySelector('i');
    
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        // TODO: Add to wishlist API call
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        // TODO: Remove from wishlist API call
    }
}

// ====================================
// Initialize Event Listeners
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist button click handler
    const wishlistBtn = document.querySelector('.btn-wishlist');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', toggleWishlist);
    }
});
