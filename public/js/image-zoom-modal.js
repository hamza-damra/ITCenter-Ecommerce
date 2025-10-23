/**
 * ============================================
 * IMAGE ZOOM MODAL SYSTEM - JAVASCRIPT
 * نظام تكبير الصور - جافاسكريبت
 * ============================================
 * 
 * @version 1.0.0
 * @date 2025-10-23
 * @author ITCenter Development Team
 */

// ============================================
// GLOBAL VARIABLES
// المتغيرات العامة
// ============================================

let currentModalImageIndex = 0;  // Current image index
let modalImages = [];            // Array of image URLs
let isZoomed = false;            // Zoom state flag
let zoomLevel = 2.5;             // Zoom level multiplier
let isRTL = document.dir === 'rtl' || document.documentElement.dir === 'rtl';

// ============================================
// INITIALIZATION
// التهيئة
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initializeImageZoomSystem();
});

/**
 * Initialize the entire image zoom system
 * تهيئة نظام تكبير الصور بالكامل
 */
function initializeImageZoomSystem() {
    // Collect all image URLs from thumbnails
    collectModalImages();
    
    // Setup click events
    setupImageClickEvents();
    
    // Setup zoom functionality
    setupModalZoom();
    
    // Setup keyboard shortcuts
    setupKeyboardShortcuts();
    
    // Setup outside click to close
    setupOutsideClickClose();
}

/**
 * Collect all image URLs from modal thumbnails
 * جمع جميع روابط الصور من المصغرات
 */
function collectModalImages() {
    const thumbnails = document.querySelectorAll('.modal-thumbnail img');
    modalImages = Array.from(thumbnails).map(img => img.src);
    console.log('Collected images:', modalImages.length);
}

/**
 * Setup click events for main image and thumbnails
 * إعداد أحداث النقر للصورة الرئيسية والمصغرات
 */
function setupImageClickEvents() {
    // Main image click to open modal
    const mainImage = document.querySelector('.main-image');
    if (mainImage) {
        mainImage.addEventListener('click', function() {
            openZoomModal(0);
        });
    }

    // Thumbnail clicks to open modal with specific image
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.addEventListener('click', function(e) {
            e.stopPropagation();
            openZoomModal(index);
        });
    });
}

// ============================================
// MODAL CONTROL FUNCTIONS
// دوال التحكم بالمودال
// ============================================

/**
 * Open zoom modal with specific image
 * فتح مودال التكبير مع صورة محددة
 * 
 * @param {number} imageIndex - Index of the image to display
 */
function openZoomModal(imageIndex = 0) {
    currentModalImageIndex = imageIndex;
    const modal = document.getElementById('imageZoomModal');
    const modalImage = document.getElementById('modalImage');
    
    if (modalImages.length > 0) {
        // Set the image source
        modalImage.src = modalImages[currentModalImageIndex];
        
        // Show the modal
        modal.classList.add('active');
        
        // Update thumbnails
        updateModalThumbnails();
        
        // Show zoom indicator
        showZoomIndicator();
        
        // Disable body scroll
        document.body.style.overflow = 'hidden';
        
        console.log('Modal opened with image:', currentModalImageIndex);
    }
}

/**
 * Close zoom modal and reset state
 * إغلاق مودال التكبير وإعادة تعيين الحالة
 */
function closeZoomModal() {
    const modal = document.getElementById('imageZoomModal');
    
    // Hide the modal
    modal.classList.remove('active');
    
    // Reset zoom state
    resetZoom();
    
    // Re-enable body scroll
    document.body.style.overflow = '';
    
    console.log('Modal closed');
}

// ============================================
// NAVIGATION FUNCTIONS
// دوال التنقل
// ============================================

/**
 * Navigate between images in modal
 * التنقل بين الصور في المودال
 * 
 * @param {number} direction - Direction to navigate (+1 for next, -1 for previous)
 */
function navigateModalImage(direction) {
    currentModalImageIndex += direction;
    
    // Loop around at boundaries
    if (currentModalImageIndex < 0) {
        currentModalImageIndex = modalImages.length - 1;
    } else if (currentModalImageIndex >= modalImages.length) {
        currentModalImageIndex = 0;
    }
    
    // Update the image
    const modalImage = document.getElementById('modalImage');
    modalImage.src = modalImages[currentModalImageIndex];
    
    // Update thumbnails
    updateModalThumbnails();
    
    // Reset zoom
    resetZoom();
    
    // Show indicator
    showZoomIndicator();
    
    console.log('Navigated to image:', currentModalImageIndex);
}

/**
 * Select specific image from modal thumbnails
 * اختيار صورة محددة من مصغرات المودال
 * 
 * @param {number} index - Index of the image to select
 */
function selectModalImage(index) {
    currentModalImageIndex = index;
    
    // Update the image
    const modalImage = document.getElementById('modalImage');
    modalImage.src = modalImages[currentModalImageIndex];
    
    // Update thumbnails
    updateModalThumbnails();
    
    // Reset zoom
    resetZoom();
    
    // Show indicator
    showZoomIndicator();
    
    console.log('Selected image:', currentModalImageIndex);
}

/**
 * Update active thumbnail in modal
 * تحديث المصغرة النشطة في المودال
 */
function updateModalThumbnails() {
    document.querySelectorAll('.modal-thumbnail').forEach((thumb, index) => {
        if (index === currentModalImageIndex) {
            thumb.classList.add('active');
            // Scroll into view smoothly
            thumb.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest',
                inline: 'nearest'
            });
        } else {
            thumb.classList.remove('active');
        }
    });
}

// ============================================
// ZOOM FUNCTIONALITY
// وظائف التكبير
// ============================================

/**
 * Setup zoom functionality with mouse tracking
 * إعداد وظيفة التكبير مع تتبع المؤشر
 */
function setupModalZoom() {
    const modalImageWrapper = document.getElementById('modalImageWrapper');
    const modalMainImage = document.getElementById('modalMainImage');
    const modalImage = document.getElementById('modalImage');

    if (!modalImageWrapper || !modalMainImage || !modalImage) return;

    // Toggle zoom on click
    modalImageWrapper.addEventListener('click', function(e) {
        if (e.target === modalImage || e.target === modalMainImage) {
            isZoomed = !isZoomed;
            
            if (isZoomed) {
                modalMainImage.classList.add('zoomed');
                modalImageWrapper.classList.add('zoomed');
                hideZoomIndicator();
                console.log('Zoom enabled');
            } else {
                resetZoom();
                console.log('Zoom disabled');
            }
        }
    });

    // Track mouse movement for zoom positioning
    modalImageWrapper.addEventListener('mousemove', function(e) {
        if (!isZoomed) return;

        const rect = modalImageWrapper.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        modalImage.style.transformOrigin = `${x}% ${y}%`;
    });

    // Reset zoom when mouse leaves
    modalImageWrapper.addEventListener('mouseleave', function() {
        if (isZoomed) {
            resetZoom();
        }
    });
}

/**
 * Reset zoom state
 * إعادة تعيين حالة التكبير
 */
function resetZoom() {
    isZoomed = false;
    
    const modalMainImage = document.getElementById('modalMainImage');
    const modalImageWrapper = document.getElementById('modalImageWrapper');
    const modalImage = document.getElementById('modalImage');
    
    if (modalMainImage) modalMainImage.classList.remove('zoomed');
    if (modalImageWrapper) modalImageWrapper.classList.remove('zoomed');
    if (modalImage) modalImage.style.transformOrigin = 'center center';
}

// ============================================
// ZOOM INDICATOR
// مؤشر التكبير
// ============================================

/**
 * Show zoom indicator for 2 seconds
 * إظهار مؤشر التكبير لمدة ثانيتين
 */
function showZoomIndicator() {
    const indicator = document.getElementById('zoomIndicator');
    if (!indicator) return;
    
    indicator.classList.add('show');
    
    setTimeout(() => {
        indicator.classList.remove('show');
    }, 2000);
}

/**
 * Hide zoom indicator immediately
 * إخفاء مؤشر التكبير فوراً
 */
function hideZoomIndicator() {
    const indicator = document.getElementById('zoomIndicator');
    if (indicator) {
        indicator.classList.remove('show');
    }
}

// ============================================
// KEYBOARD SHORTCUTS
// اختصارات لوحة المفاتيح
// ============================================

/**
 * Setup keyboard shortcuts for modal control
 * إعداد اختصارات لوحة المفاتيح للتحكم بالمودال
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('imageZoomModal');
        
        if (!modal || !modal.classList.contains('active')) return;
        
        switch(e.key) {
            case 'Escape':
                closeZoomModal();
                break;
                
            case 'ArrowLeft':
                // RTL: left arrow goes to next, LTR: left arrow goes to previous
                navigateModalImage(isRTL ? 1 : -1);
                break;
                
            case 'ArrowRight':
                // RTL: right arrow goes to previous, LTR: right arrow goes to next
                navigateModalImage(isRTL ? -1 : 1);
                break;
                
            default:
                break;
        }
    });
}

// ============================================
// OUTSIDE CLICK TO CLOSE
// النقر خارج النافذة للإغلاق
// ============================================

/**
 * Setup outside click to close modal
 * إعداد النقر خارج النافذة لإغلاق المودال
 */
function setupOutsideClickClose() {
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('imageZoomModal');
        
        if (e.target === modal) {
            closeZoomModal();
        }
    });
}

// ============================================
// UTILITY FUNCTIONS
// دوال مساعدة
// ============================================

/**
 * Check if device is touch-enabled
 * التحقق من دعم اللمس
 * 
 * @returns {boolean}
 */
function isTouchDevice() {
    return (('ontouchstart' in window) ||
            (navigator.maxTouchPoints > 0) ||
            (navigator.msMaxTouchPoints > 0));
}

/**
 * Get current modal state
 * الحصول على حالة المودال الحالية
 * 
 * @returns {Object}
 */
function getModalState() {
    return {
        isOpen: document.getElementById('imageZoomModal')?.classList.contains('active') || false,
        currentIndex: currentModalImageIndex,
        totalImages: modalImages.length,
        isZoomed: isZoomed,
        zoomLevel: zoomLevel
    };
}

/**
 * Preload all images for better performance
 * تحميل جميع الصور مسبقاً لأداء أفضل
 */
function preloadImages() {
    modalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
    console.log('Images preloaded');
}

// ============================================
// PUBLIC API
// واجهة برمجية عامة
// ============================================

window.ImageZoomModal = {
    open: openZoomModal,
    close: closeZoomModal,
    navigate: navigateModalImage,
    select: selectModalImage,
    getState: getModalState,
    preload: preloadImages
};

// ============================================
// CONSOLE HELPERS (Development Only)
// مساعدات الكونسول (للتطوير فقط)
// ============================================

if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    console.log('🔍 Image Zoom Modal System Loaded');
    console.log('📦 Available methods:', Object.keys(window.ImageZoomModal));
    console.log('⌨️ Keyboard shortcuts: ESC (close), ← → (navigate)');
}
