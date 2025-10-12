// Carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.products-carousel');

    if (carousel) {
        const wrapper = carousel.querySelector('.products-wrapper');
        const prevBtn = carousel.querySelector('.prev');
        const nextBtn = carousel.querySelector('.next');
        const products = wrapper.querySelectorAll('.product-card');

        let currentIndex = 0;
        const productsPerView = getProductsPerView();
        const maxIndex = Math.max(0, products.length - productsPerView);

        function getProductsPerView() {
            if (window.innerWidth <= 480) return 1;
            if (window.innerWidth <= 768) return 2;
            if (window.innerWidth <= 1024) return 3;
            return 4;
        }

        function updateCarousel() {
            const productWidth = products[0].offsetWidth;
            const gap = 20;
            const offset = currentIndex * (productWidth + gap);

            // For RTL, we need to scroll in the opposite direction
            wrapper.style.transform = `translateX(${offset}px)`;
        }

        function slideNext() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }

        function slidePrev() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }

        // Button click handlers
        if (prevBtn && nextBtn) {
            nextBtn.addEventListener('click', slideNext);
            prevBtn.addEventListener('click', slidePrev);
        }

        // Auto-slide (optional)
        let autoSlideInterval = setInterval(() => {
            if (currentIndex >= maxIndex) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            updateCarousel();
        }, 5000);

        // Pause auto-slide on hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });

        carousel.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(() => {
                if (currentIndex >= maxIndex) {
                    currentIndex = 0;
                } else {
                    currentIndex++;
                }
                updateCarousel();
            }, 5000);
        });

        // Update on window resize
        window.addEventListener('resize', () => {
            const newProductsPerView = getProductsPerView();
            if (newProductsPerView !== productsPerView) {
                currentIndex = 0;
                updateCarousel();
            }
        });
    }
});

// Wishlist functionality
const wishlistButtons = document.querySelectorAll('.wishlist-btn');
wishlistButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const icon = this.querySelector('i');

        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.style.background = '#ef4444';
            this.style.color = 'white';

            // Show notification
            showNotification('تمت الإضافة إلى المفضلة');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.style.background = '';
            this.style.color = '';

            showNotification('تم الإزالة من المفضلة');
        }
    });
});

// Add to cart functionality
const cartButtons = document.querySelectorAll('.btn-cart');
cartButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        const productCard = this.closest('.product-card') || this.closest('.product-card-small');
        const productName = productCard.querySelector('h3, h4').textContent;

        // Animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 200);

        // Update cart badge
        updateCartBadge();

        // Show notification
        showNotification(`تمت إضافة "${productName}" إلى السلة`);
    });
});

// Update cart badge
function updateCartBadge() {
    const cartBadge = document.querySelector('.action-icon .badge');
    if (cartBadge) {
        let currentCount = parseInt(cartBadge.textContent);
        cartBadge.textContent = currentCount + 1;

        // Animation
        cartBadge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartBadge.style.transform = '';
        }, 300);
    }
}

// Notification system
function showNotification(message) {
    // Check if notification already exists
    let notification = document.querySelector('.notification');

    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'notification';
        document.body.appendChild(notification);

        // Add notification styles dynamically
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                .notification {
                    position: fixed;
                    top: 100px;
                    right: 20px;
                    background: #10b981;
                    color: white;
                    padding: 16px 24px;
                    border-radius: 12px;
                    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
                    z-index: 10000;
                    font-weight: 600;
                    opacity: 0;
                    transform: translateX(100px);
                    transition: all 0.3s ease;
                }

                .notification.show {
                    opacity: 1;
                    transform: translateX(0);
                }
            `;
            document.head.appendChild(style);
        }
    }

    notification.textContent = message;
    notification.classList.add('show');

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Search functionality
const searchInput = document.querySelector('.search-bar input');
const searchButton = document.querySelector('.search-bar button');

if (searchInput && searchButton) {
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        const searchTerm = searchInput.value.trim();

        if (searchTerm) {
            showNotification(`البحث عن: ${searchTerm}`);
            // Here you would typically send the search query to a server
            console.log('Searching for:', searchTerm);
        }
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchButton.click();
        }
    });
}

// Mobile menu toggle (for future enhancement)
function createMobileMenu() {
    if (window.innerWidth <= 768) {
        const nav = document.querySelector('.nav');
        const navUl = nav.querySelector('ul');

        // Add hamburger button if not exists
        if (!document.querySelector('.hamburger-btn')) {
            const hamburger = document.createElement('button');
            hamburger.className = 'hamburger-btn';
            hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            nav.insertBefore(hamburger, navUl);

            // Add styles
            const style = document.createElement('style');
            style.textContent = `
                .hamburger-btn {
                    display: none;
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    padding: 10px 15px;
                    border-radius: 8px;
                    font-size: 20px;
                    cursor: pointer;
                    margin: 10px 0;
                }

                @media (max-width: 768px) {
                    .hamburger-btn {
                        display: block;
                    }

                    .nav ul {
                        display: none;
                        flex-direction: column;
                        background: white;
                        position: absolute;
                        top: 100%;
                        right: 0;
                        left: 0;
                        padding: 20px;
                        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                    }

                    .nav ul.active {
                        display: flex;
                    }
                }
            `;
            document.head.appendChild(style);

            // Toggle menu
            hamburger.addEventListener('click', function() {
                navUl.classList.toggle('active');
            });
        }
    }
}

// Initialize mobile menu
createMobileMenu();
window.addEventListener('resize', createMobileMenu);

// Product card hover effects enhancement
const productCards = document.querySelectorAll('.product-card, .product-card-small');
productCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
    });
});

// Lazy loading images (simple implementation)
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Console welcome message
console.log('%cIT Center E-commerce', 'color: #0ea5e9; font-size: 24px; font-weight: bold;');
console.log('%cBuilt with HTML, CSS, and JavaScript', 'color: #64748b; font-size: 14px;');
