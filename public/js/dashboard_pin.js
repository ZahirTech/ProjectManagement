/**
 * Enhanced Pinned Items JavaScript
 * Provides smooth animations and interactions for the pinned items section
 */

// Toggle Pinned Items with smooth animation
function togglePinnedItems() {
    const container = document.getElementById('expandablePinnedItems');
    const expandText = document.getElementById('expandText');
    const expandIcon = document.getElementById('expandIcon');
    const toggleBtn = document.getElementById('togglePinnedBtn');

    if (!container) return;

    const isExpanded = container.classList.contains('expanded');

    if (isExpanded) {
        // Collapse
        container.classList.remove('expanded');
        if (expandText) expandText.textContent = 'Show All';
        if (toggleBtn) toggleBtn.classList.remove('expanded');

        // Smooth scroll to pinned section header
        setTimeout(() => {
            const pinnedSection = document.querySelector('.pinned-section');
            if (pinnedSection) {
                pinnedSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100);
    } else {
        // Expand
        container.classList.add('expanded');
        if (expandText) expandText.textContent = 'Show Less';
        if (toggleBtn) toggleBtn.classList.add('expanded');

        // Animate cards on expand
        setTimeout(() => {
            animateExpandedCards();
        }, 100);
    }
}

// Animate cards when expanding
function animateExpandedCards() {
    const expandedContainer = document.getElementById('expandablePinnedItems');
    if (!expandedContainer) return;

    const cards = expandedContainer.querySelectorAll('.pinned-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize smooth scroll behavior
function initSmoothScroll() {
    // Add smooth scrolling to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Add ripple effect to pinned cards
function addRippleEffect(event, card) {
    const ripple = document.createElement('span');
    const rect = card.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.className = 'ripple-effect';

    card.appendChild(ripple);

    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Initialize ripple effects on cards
function initRippleEffects() {
    const style = document.createElement('style');
    style.textContent = `
        .pinned-card {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.3);
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    document.querySelectorAll('.pinned-card').forEach(card => {
        card.addEventListener('mousedown', function (e) {
            // Don't add ripple if clicking on a link or button
            if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON') {
                addRippleEffect(e, this);
            }
        });
    });
}

// Lazy load images if any (future feature)
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Add touch gestures for mobile
function initTouchGestures() {
    let touchStartY = 0;
    let touchEndY = 0;

    const pinnedSection = document.querySelector('.pinned-section');
    if (!pinnedSection) return;

    pinnedSection.addEventListener('touchstart', (e) => {
        touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    pinnedSection.addEventListener('touchend', (e) => {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeDistance = touchStartY - touchEndY;
        const expandableContainer = document.getElementById('expandablePinnedItems');

        if (!expandableContainer) return;

        // Swipe up to expand (threshold: 50px)
        if (swipeDistance > 50 && !expandableContainer.classList.contains('expanded')) {
            togglePinnedItems();
        }
        // Swipe down to collapse (threshold: 50px)
        else if (swipeDistance < -50 && expandableContainer.classList.contains('expanded')) {
            togglePinnedItems();
        }
    }
}

// Observe card visibility for animations
function initCardObserver() {
    if ('IntersectionObserver' in window) {
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        document.querySelectorAll('.pinned-card').forEach(card => {
            cardObserver.observe(card);
        });
    }
}

// Add keyboard navigation support
function initKeyboardNavigation() {
    const cards = document.querySelectorAll('.pinned-card');
    cards.forEach((card, index) => {
        card.setAttribute('tabindex', '0');

        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }

            // Arrow key navigation
            if (e.key === 'ArrowRight' && cards[index + 1]) {
                e.preventDefault();
                cards[index + 1].focus();
            }
            if (e.key === 'ArrowLeft' && cards[index - 1]) {
                e.preventDefault();
                cards[index - 1].focus();
            }
        });
    });
}

// Performance optimization: Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimize scroll performance
function initScrollOptimization() {
    let ticking = false;

    const handleScroll = () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                // Add any scroll-based animations here
                ticking = false;
            });
            ticking = true;
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
}

// Add loading state management
function showLoadingState() {
    const pinnedSection = document.querySelector('.pinned-section');
    if (!pinnedSection) return;

    pinnedSection.classList.add('loading');
}

function hideLoadingState() {
    const pinnedSection = document.querySelector('.pinned-section');
    if (!pinnedSection) return;

    pinnedSection.classList.remove('loading');
}

// Initialize all features
function initPinnedItemsEnhancements() {
    // Run after DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initSmoothScroll();
        initRippleEffects();
        initLazyLoading();
        initTouchGestures();
        initCardObserver();
        initKeyboardNavigation();
        initScrollOptimization();

        // Add entrance animation delay
        setTimeout(() => {
            document.querySelectorAll('.pinned-card').forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        }, 100);
    }
}

// Auto-initialize
if (typeof window !== 'undefined') {
    initPinnedItemsEnhancements();
}

// Export for use in other modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        togglePinnedItems,
        initPinnedItemsEnhancements
    };
}
