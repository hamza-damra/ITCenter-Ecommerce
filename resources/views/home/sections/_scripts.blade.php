{{-- Category Carousel JavaScript --}}
<script>
    (function() {
        let currentPosition = 0;
        const track = document.getElementById('categoryCarouselTrack');
        if (!track) return;
        const dotsContainer = document.getElementById('categoryCarouselDots');
        const originalCards = track.querySelectorAll('.category-carousel-card');
        const totalCards = originalCards.length;
        let isTransitioning = false;

        function getSlidesPerView() {
            const width = window.innerWidth;
            if (width >= 1024) return 5;
            if (width >= 768) return 4;
            if (width >= 480) return 3;
            return 2;
        }

        let slidesPerView = getSlidesPerView();

        function createInfiniteLoop() {
            const originalHTML = track.innerHTML;
            track.innerHTML = originalHTML + originalHTML + originalHTML;
            currentPosition = totalCards;
            updateCarouselPosition(false);
        }

        function initDots() {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalCards; i++) {
                const dot = document.createElement('div');
                dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                dot.onclick = () => goToCategorySlide(i);
                dotsContainer.appendChild(dot);
            }
        }

        function updateCarouselPosition(animate = true) {
            const allCards = track.querySelectorAll('.category-carousel-card');
            if (allCards.length === 0) return;
            const cardWidth = allCards[0].offsetWidth;
            const offset = -(currentPosition * cardWidth);
            if (animate) {
                track.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            } else {
                track.style.transition = 'none';
            }
            track.style.transform = `translateX(${offset}px)`;
            if (dotsContainer) {
                const dots = dotsContainer.querySelectorAll('.carousel-dot');
                const activeIndex = (currentPosition - totalCards + totalCards) % totalCards;
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === activeIndex);
                });
            }
        }

        window.slideCategoryCarousel = function (direction) {
            if (isTransitioning) return;
            isTransitioning = true;
            currentPosition += direction;
            updateCarouselPosition(true);
            setTimeout(() => {
                if (currentPosition <= 0) {
                    currentPosition = totalCards * 2;
                    updateCarouselPosition(false);
                } else if (currentPosition >= totalCards * 2) {
                    currentPosition = totalCards;
                    updateCarouselPosition(false);
                }
                isTransitioning = false;
            }, 300);
        };

        window.goToCategorySlide = function (index) {
            if (isTransitioning) return;
            currentPosition = totalCards + index;
            updateCarouselPosition(true);
        };

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const newSlidesPerView = getSlidesPerView();
                if (newSlidesPerView !== slidesPerView) {
                    slidesPerView = newSlidesPerView;
                    createInfiniteLoop();
                    initDots();
                }
            }, 250);
        });

        let touchStartX = 0;
        let touchEndX = 0;
        track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                slideCategoryCarousel(diff > 0 ? 1 : -1);
            }
        }, { passive: true });

        if (totalCards > 0) {
            createInfiniteLoop();
            initDots();
        }
    })();
</script>

{{-- Special Offer Swapper Logic --}}
<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const swapper = document.getElementById('specialOfferSwapper');
            if (!swapper) return;
            const slides = swapper.querySelectorAll('.special-offer-slide');
            const dots = swapper.querySelectorAll('.special-offer-dot');
            let currentIndex = 0;
            let intervalId;

            function showSlide(index) {
                slides.forEach(slide => {
                    slide.classList.remove('active');
                    slide.style.position = 'absolute';
                });
                dots.forEach(dot => dot.classList.remove('active'));
                slides[index].classList.add('active');
                slides[index].style.position = 'relative';
                if (dots[index]) dots[index].classList.add('active');
                currentIndex = index;
            }

            function nextSlide() {
                showSlide((currentIndex + 1) % slides.length);
            }

            function startRotation() {
                if (slides.length > 1) {
                    intervalId = setInterval(nextSlide, 5000);
                }
            }

            function stopRotation() {
                if (intervalId) clearInterval(intervalId);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', (e) => {
                    e.stopPropagation();
                    stopRotation();
                    showSlide(index);
                    startRotation();
                });
            });

            swapper.addEventListener('mouseenter', stopRotation);
            swapper.addEventListener('mouseleave', startRotation);
            startRotation();
        });
    })();
</script>

{{-- Promo Styles --}}
<style>
    .promo-badge { position: absolute; top: 20px; right: 20px; background: #ff6b6b; color: white; padding: 0.5rem 1rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(255, 107, 107, 0.4); z-index: 2; }
    [dir="rtl"] .promo-badge { right: auto; left: 20px; }
    .promo-image { background: white; border-radius: 15px; padding: 1rem; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08); height: 100%; display: flex; align-items: center; justify-content: center; }
    .promo-image img { width: 100%; max-height: 220px; object-fit: contain; display: block; }
    .promo-content { color: white; }
    .promo-header { margin-bottom: .5rem; }
    .promo-product-name { font-size: .95rem; opacity: .9; }
    .promo-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 1rem; color: white; }
    .promo-features { list-style: none; padding: 0; margin: 0 0 1.5rem 0; }
    .promo-features li { padding: 0.4rem 0; font-size: 0.95rem; opacity: 0.95; }
    .promo-features i { color: #4ade80; margin-inline-end: 0.5rem; }
    .promo-price { background: rgba(255, 255, 255, 0.2); padding: 1rem; border-radius: 10px; margin-bottom: 1rem; backdrop-filter: blur(10px); }
    .price-row { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; }
    .promo-btn { display: block; width: 100%; background: #ffffff; color: #3b82f6; text-align: center; padding: 1rem; border-radius: 10px; font-weight: 700; text-decoration: none; transition: all 0.3s ease; margin-bottom: 1rem; }
    .special-offer-swapper { display: flex; flex-direction: column; height: 100%; overflow: hidden; }
    .special-offer-slides { position: relative; flex: 1; width: 100%; }
    .special-offer-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; visibility: hidden; transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out; display: flex; flex-direction: column; }
    .special-offer-slide.active { opacity: 1; visibility: visible; position: relative; }
    .special-offer-dots { display: flex; justify-content: center; gap: 8px; padding: 10px 0; margin-top: auto; width: 100%; z-index: 5; position: relative; }
    .special-offer-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(0, 0, 0, 0.2); border: none; cursor: pointer; padding: 0; transition: all 0.3s ease; }
    .special-offer-dot.active { background: #3b82f6; transform: scale(1.2); }
</style>

{{-- Hero Slider + Animations + Countdown --}}
<script>
    // Store cart product IDs from server
    window.cartProductIds = @json($cartProductIds);

    document.addEventListener('DOMContentLoaded', function () {
        // Hide page loader when everything is ready
        const pageLoader = document.getElementById('page-loader');
        if (pageLoader) {
            setTimeout(() => {
                pageLoader.style.opacity = '0';
                pageLoader.style.transition = 'opacity 0.3s ease';
                setTimeout(() => { pageLoader.style.display = 'none'; }, 300);
            }, 100);
        }

        // Hero Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const progressBar = document.getElementById('sliderProgressBar');
        const totalSlides = slides.length;
        let slideInterval;
        let progressInterval;
        const slideDuration = 5000;

        window.changeSlide = function (direction) {
            if (totalSlides <= 1) return;
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            updateSlider();
            startAutoSlide();
        }

        window.goToSlide = function (slideIndex) {
            if (totalSlides <= 1) return;
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            currentSlide = slideIndex;
            updateSlider();
            startAutoSlide();
        }

        function updateSlider() {
            if (totalSlides === 0) return;
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            if (progressBar) progressBar.style.transform = 'scaleX(0)';
            if (slides[currentSlide]) slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        }

        function animateProgressBar() {
            if (!progressBar) return;
            clearInterval(progressInterval);
            let progress = 0;
            const increment = 100 / (slideDuration / 50);
            progressInterval = setInterval(() => {
                progress += increment;
                if (progress >= 100) { progress = 100; clearInterval(progressInterval); }
                progressBar.style.transform = 'scaleX(' + (progress / 100) + ')';
            }, 50);
        }

        function startAutoSlide() {
            if (totalSlides <= 1) return;
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            animateProgressBar();
            slideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
                animateProgressBar();
            }, slideDuration);
        }

        if (totalSlides > 1) startAutoSlide();

        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => { clearInterval(slideInterval); clearInterval(progressInterval); });
            heroSection.addEventListener('mouseleave', () => { startAutoSlide(); });
        }

        // Scroll Animation
        const observerOptions = { threshold: 0.15, rootMargin: '0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('animate-in'); });
        }, observerOptions);
        document.querySelectorAll('.product-card, .special-card').forEach(el => {
            el.classList.add('scroll-animate');
            observer.observe(el);
        });

        // Wishlist buttons observer
        const observeWishlistButtons = () => {
            document.querySelectorAll('.wishlist-btn').forEach(button => {
                const mo = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            const icon = button.querySelector('i');
                            if (button.classList.contains('active')) {
                                if (icon) icon.style.color = '#ff0000';
                            } else {
                                if (icon) icon.style.color = '#666';
                            }
                        }
                    });
                });
                mo.observe(button, { attributes: true });
            });
        };
        setTimeout(observeWishlistButtons, 500);

        // Countdown Timer
        function startCountdown() {
            const hoursElement = document.getElementById('hours');
            const minutesElement = document.getElementById('minutes');
            const secondsElement = document.getElementById('seconds');
            if (!hoursElement || !minutesElement || !secondsElement) return;
            let totalSeconds = 8 * 3600 + 19 * 60 + 36;
            function updateCountdown() {
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                hoursElement.textContent = hours.toString().padStart(2, '0');
                minutesElement.textContent = minutes.toString().padStart(2, '0');
                secondsElement.textContent = seconds.toString().padStart(2, '0');
                if (totalSeconds <= 0) { totalSeconds = 8 * 3600 + 19 * 60 + 36; } else { totalSeconds--; }
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
        startCountdown();

        // Promo Featured countdown
        (function initPromoFeaturedCountdown() {
            const blocks = document.querySelectorAll('.promo-featured-card .promo-countdown[data-end]');
            if (!blocks.length) return;
            const update = () => {
                blocks.forEach(block => {
                    const end = new Date(block.getAttribute('data-end')).getTime();
                    if (!end) return;
                    const now = Date.now();
                    let diff = Math.max(0, end - now);
                    const hours = Math.floor(diff / 3_600_000);
                    diff %= 3_600_000;
                    const mins = Math.floor(diff / 60_000);
                    const secs = Math.floor((diff % 60_000) / 1000);
                    const hEl = block.querySelector('.cd-hours');
                    const mEl = block.querySelector('.cd-mins');
                    const sEl = block.querySelector('.cd-secs');
                    if (hEl) hEl.textContent = String(hours).padStart(2, '0');
                    if (mEl) mEl.textContent = String(mins).padStart(2, '0');
                    if (sEl) sEl.textContent = String(secs).padStart(2, '0');
                });
            };
            update();
            setInterval(update, 1000);
        })();
    });
</script>
