/**
 * Horizontal Product Scroller
 * Supports smooth scrolling, auto-scroll, RTL, and drag-to-scroll
 */

class HorizontalScroller {
    constructor(wrapperId) {
        this.wrapper = document.getElementById(wrapperId);
        if (!this.wrapper) return;

        this.container = this.wrapper.querySelector('.scroller-container');
        this.track = this.wrapper.querySelector('.scroller-track');
        this.leftArrow = this.wrapper.querySelector('.scroller-arrow-left');
        this.rightArrow = this.wrapper.querySelector('.scroller-arrow-right');
        this.dotsContainer = this.wrapper.parentElement.querySelector('.scroller-dots');
        
        // Get configuration
        this.isRTL = document.documentElement.dir === 'rtl';
        this.autoScroll = this.container.dataset.autoScroll === 'true';
        this.autoScrollInterval = parseInt(this.container.dataset.autoScrollInterval) || 3000;
        this.cardsToScroll = parseInt(this.container.dataset.cardsToScroll) || 1;
        
        // State
        this.currentIndex = 0;
        this.autoScrollTimer = null;
        this.isDragging = false;
        this.startX = 0;
        this.scrollLeft = 0;
        
        // Calculate dimensions
        this.updateDimensions();
        
        // Initialize
        this.init();
    }

    init() {
        // Event listeners
        this.leftArrow?.addEventListener('click', () => this.scrollPrev());
        this.rightArrow?.addEventListener('click', () => this.scrollNext());
        
        // Drag to scroll
        this.container.addEventListener('mousedown', (e) => this.startDragging(e));
        this.container.addEventListener('mousemove', (e) => this.drag(e));
        this.container.addEventListener('mouseup', () => this.stopDragging());
        this.container.addEventListener('mouseleave', () => this.stopDragging());
        
        // Touch support
        this.container.addEventListener('touchstart', (e) => this.startDragging(e));
        this.container.addEventListener('touchmove', (e) => this.drag(e));
        this.container.addEventListener('touchend', () => this.stopDragging());
        
        // Auto-scroll pause on hover
        if (this.autoScroll) {
            this.container.addEventListener('mouseenter', () => this.pauseAutoScroll());
            this.container.addEventListener('mouseleave', () => this.resumeAutoScroll());
            this.startAutoScroll();
        }
        
        // Window resize
        window.addEventListener('resize', () => this.updateDimensions());
        
        // Keyboard navigation
        this.container.setAttribute('tabindex', '0');
        this.container.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Create progress dots
        this.createDots();
        
        // Update arrow states
        this.updateArrows();
    }

    updateDimensions() {
        const cards = this.track.querySelectorAll('.scroller-card-wrapper');
        this.totalCards = cards.length;
        
        if (this.totalCards === 0) return;
        
        // Get card width including gap
        const firstCard = cards[0];
        const cardStyle = window.getComputedStyle(firstCard);
        const cardWidth = firstCard.offsetWidth;
        const gap = parseFloat(window.getComputedStyle(this.track).gap) || 0;
        
        this.cardWidth = cardWidth;
        this.cardWithGap = cardWidth + gap;
        
        // Calculate visible cards
        const containerWidth = this.container.offsetWidth;
        this.visibleCards = Math.floor(containerWidth / this.cardWithGap);
        this.maxIndex = Math.max(0, this.totalCards - this.visibleCards);
        
        // Update position
        this.updatePosition(false);
    }

    scrollNext() {
        if (this.currentIndex >= this.maxIndex) {
            // Loop back to start
            this.currentIndex = 0;
        } else {
            this.currentIndex = Math.min(this.currentIndex + this.cardsToScroll, this.maxIndex);
        }
        this.updatePosition(true);
        this.resetAutoScroll();
    }

    scrollPrev() {
        if (this.currentIndex <= 0) {
            // Loop to end
            this.currentIndex = this.maxIndex;
        } else {
            this.currentIndex = Math.max(this.currentIndex - this.cardsToScroll, 0);
        }
        this.updatePosition(true);
        this.resetAutoScroll();
    }

    scrollToIndex(index) {
        this.currentIndex = Math.max(0, Math.min(index, this.maxIndex));
        this.updatePosition(true);
        this.resetAutoScroll();
    }

    updatePosition(animate = true) {
        const translateX = this.currentIndex * this.cardWithGap;
        const direction = this.isRTL ? 1 : -1;
        
        if (animate) {
            this.track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        } else {
            this.track.style.transition = 'none';
        }
        
        this.track.style.transform = `translateX(${direction * translateX}px)`;
        
        // Update UI
        this.updateArrows();
        this.updateDots();
    }

    updateArrows() {
        if (!this.leftArrow || !this.rightArrow) return;
        
        // In RTL, swap the logic
        const canGoPrev = this.currentIndex > 0;
        const canGoNext = this.currentIndex < this.maxIndex;
        
        if (this.isRTL) {
            this.rightArrow.disabled = !canGoPrev;
            this.leftArrow.disabled = !canGoNext;
        } else {
            this.leftArrow.disabled = !canGoPrev;
            this.rightArrow.disabled = !canGoNext;
        }
    }

    createDots() {
        if (!this.dotsContainer) return;
        
        this.dotsContainer.innerHTML = '';
        
        // Calculate number of pages
        const pages = Math.ceil(this.totalCards / this.visibleCards);
        
        if (pages <= 1) return;
        
        for (let i = 0; i < pages; i++) {
            const dot = document.createElement('div');
            dot.className = 'scroller-dot';
            dot.addEventListener('click', () => {
                this.scrollToIndex(i * this.visibleCards);
            });
            this.dotsContainer.appendChild(dot);
        }
        
        this.updateDots();
    }

    updateDots() {
        if (!this.dotsContainer) return;
        
        const dots = this.dotsContainer.querySelectorAll('.scroller-dot');
        const currentPage = Math.floor(this.currentIndex / this.visibleCards);
        
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentPage);
        });
    }

    // Drag to scroll
    startDragging(e) {
        this.isDragging = true;
        this.container.classList.add('dragging');
        this.startX = this.getPositionX(e);
        this.scrollLeft = this.currentIndex * this.cardWithGap;
        
        // Pause auto-scroll while dragging
        this.pauseAutoScroll();
    }

    drag(e) {
        if (!this.isDragging) return;
        e.preventDefault();
        
        const x = this.getPositionX(e);
        const walk = (x - this.startX) * (this.isRTL ? -2 : 2);
        const newScrollLeft = this.scrollLeft - walk;
        
        // Calculate new index
        const newIndex = Math.round(newScrollLeft / this.cardWithGap);
        this.currentIndex = Math.max(0, Math.min(newIndex, this.maxIndex));
        
        this.updatePosition(false);
    }

    stopDragging() {
        if (!this.isDragging) return;
        
        this.isDragging = false;
        this.container.classList.remove('dragging');
        
        // Snap to nearest card
        this.updatePosition(true);
        
        // Resume auto-scroll after a delay
        if (this.autoScroll) {
            setTimeout(() => this.resumeAutoScroll(), 1000);
        }
    }

    getPositionX(e) {
        return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
    }

    // Auto-scroll
    startAutoScroll() {
        if (!this.autoScroll) return;
        
        this.autoScrollTimer = setInterval(() => {
            this.scrollNext();
        }, this.autoScrollInterval);
    }

    pauseAutoScroll() {
        if (this.autoScrollTimer) {
            clearInterval(this.autoScrollTimer);
            this.autoScrollTimer = null;
        }
        this.container.classList.add('paused');
    }

    resumeAutoScroll() {
        if (!this.autoScroll) return;
        
        this.container.classList.remove('paused');
        this.startAutoScroll();
    }

    resetAutoScroll() {
        if (!this.autoScroll) return;
        
        this.pauseAutoScroll();
        this.resumeAutoScroll();
    }

    // Keyboard navigation
    handleKeyboard(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            this.isRTL ? this.scrollNext() : this.scrollPrev();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            this.isRTL ? this.scrollPrev() : this.scrollNext();
        } else if (e.key === 'Home') {
            e.preventDefault();
            this.scrollToIndex(0);
        } else if (e.key === 'End') {
            e.preventDefault();
            this.scrollToIndex(this.maxIndex);
        }
    }

    // Public method to destroy instance
    destroy() {
        this.pauseAutoScroll();
        // Remove event listeners if needed
    }
}

// Auto-initialize all scrollers on page load
document.addEventListener('DOMContentLoaded', function() {
    const scrollers = document.querySelectorAll('.horizontal-scroller-wrapper');
    
    scrollers.forEach(wrapper => {
        new HorizontalScroller(wrapper.id);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = HorizontalScroller;
}
