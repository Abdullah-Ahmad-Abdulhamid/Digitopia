// ===== Enhanced Subscription Page Animations =====

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out-cubic',
        once: true,
        offset: 100,
        disable: function() {
            return window.innerWidth < 768;
        }
    });

    // Initialize Vanilla Tilt for 3D card effects
    if (typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
            max: 15,
            speed: 400,
            scale: 1.05,
            perspective: 1000,
            transition: true,
            "max-glare": 0.2,
            "glare-prerender": false
        });
    }

    // Enhanced Navigation Scroll Effect
    const navbar = document.querySelector('.glass-nav');
    let lastScrollTop = 0;

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Hide/show navbar on scroll
        if (scrollTop > lastScrollTop && scrollTop > 300) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, { passive: true });

    // Animated Counter Effect
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const finalValue = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const increment = finalValue / (duration / 16);
                let currentValue = 0;

                const updateCounter = () => {
                    currentValue += increment;
                    if (currentValue < finalValue) {
                        counter.textContent = Math.floor(currentValue);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = finalValue;
                    }
                };

                updateCounter();
                counterObserver.unobserve(counter);
            }
        });
    }, observerOptions);

    // Observe all counter elements
    document.querySelectorAll('.counter').forEach(counter => {
        counterObserver.observe(counter);
    });

    // Progress Bar Animation
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressLine = entry.target.querySelector('.progress-line');
                if (progressLine) {
                    progressLine.style.animation = 'progressFill 2s ease-out forwards';
                }
                progressObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stats-progress').forEach(progress => {
        progressObserver.observe(progress);
    });

    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - navbar.offsetHeight;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Enhanced Button Loading Effect
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.href && !this.href.includes('#') && !this.href.includes('javascript:')) {
                const ripple = this.querySelector('.btn-ripple') || this.querySelector('.fab-ripple');
                if (ripple) {
                    ripple.style.animation = 'none';
                    ripple.offsetHeight; // Trigger reflow
                    ripple.style.animation = 'rippleEffect 0.6s linear';
                }

                // Show loading state
                const originalText = this.innerHTML;
                const icon = this.querySelector('i');
                if (icon && !this.classList.contains('loading')) {
                    this.classList.add('loading');
                    icon.className = 'fas fa-spinner fa-spin me-2';
                    
                    // Reset after navigation (fallback)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('loading');
                    }, 3000);
                }
            }
        });
    });

    // Subscription Plan Selection Animation
    document.querySelectorAll('.pricing-card-3d').forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Add glow effect
            this.style.boxShadow = '0 25px 50px rgba(102, 126, 234, 0.2)';
            
            // Animate particles if they exist
            const particles = this.querySelectorAll('.particle');
            particles.forEach(particle => {
                particle.style.animation = 'particleFloat 2s ease-in-out infinite';
            });
        });

        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });

    // Enhanced Pricing Card Interactions
    document.querySelectorAll('.btn-plan-select').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);

            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: rippleEffect 0.6s linear;
                left: ${e.offsetX - 10}px;
                top: ${e.offsetY - 10}px;
                width: 20px;
                height: 20px;
                pointer-events: none;
            `;
            
            this.parentElement.style.position = 'relative';
            this.parentElement.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Particle System for Hero Section
    function createSubscriptionParticles() {
        const hero = document.querySelector('.hero-section');
        if (!hero) return;

        for (let i = 0; i < 15; i++) {
            const particle = document.createElement('div');
            particle.className = 'subscription-particle';
            particle.style.cssText = `
                position: absolute;
                width: 3px;
                height: 3px;
                background: rgba(255, 255, 255, 0.6);
                border-radius: 50%;
                pointer-events: none;
                animation: subscriptionParticleFloat ${10 + Math.random() * 15}s infinite linear;
                left: ${Math.random() * 100}%;
                top: ${100 + Math.random() * 20}%;
                animation-delay: ${Math.random() * 8}s;
                z-index: 1;
            `;
            hero.appendChild(particle);
        }
    }

    // Add subscription-specific animation keyframes
    const subscriptionStyles = document.createElement('style');
    subscriptionStyles.textContent = `
        @keyframes subscriptionParticleFloat {
            0% {
                transform: translateY(0px) translateX(0px);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) translateX(${Math.random() * 300 - 150}px);
                opacity: 0;
            }
        }
        
        @keyframes rippleEffect {
            0% { transform: scale(0); opacity: 0.6; }
            100% { transform: scale(1); opacity: 0; }
        }
        
        @keyframes progressFill {
            to { width: 100%; }
        }
        
        @keyframes planCardHover {
            0% { transform: translateY(0) rotateX(0); }
            100% { transform: translateY(-15px) rotateX(5deg); }
        }
        
        @keyframes paymentSuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .payment-success {
            animation: paymentSuccess 0.6s ease-out;
        }
        
        .btn-processing {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .feature-highlight {
            animation: featureHighlight 2s ease-in-out;
        }
        
        @keyframes featureHighlight {
            0%, 100% { background: transparent; }
            50% { background: rgba(102, 126, 234, 0.1); }
        }
    `;
    document.head.appendChild(subscriptionStyles);

    // Initialize subscription particles
    createSubscriptionParticles();

    // Enhanced Accordion Interactions
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function() {
            // Add smooth animation to icon rotation
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transition = 'transform 0.3s ease';
                if (this.classList.contains('collapsed')) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                }
            }
        });
    });

    // Comparison Table Highlight Effect
    document.querySelectorAll('.feature-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            // Highlight the entire row
            const cells = this.querySelectorAll('.feature-name, .feature-value');
            cells.forEach(cell => {
                cell.classList.add('feature-highlight');
            });
        });

        row.addEventListener('mouseleave', function() {
            const cells = this.querySelectorAll('.feature-name, .feature-value');
            cells.forEach(cell => {
                cell.classList.remove('feature-highlight');
            });
        });
    });



    // Form Enhancement for Payment Modal
    document.querySelectorAll('input, textarea, select').forEach(input => {
        // Add floating labels effect
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Card Number Formatting
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Card Expiry Formatting
    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Enhanced Loading States
    function showLoading(element, message = 'جارٍ التحميل...') {
        element.classList.add('btn-processing');
        element.style.pointerEvents = 'none';
        
        const originalContent = element.innerHTML;
        element.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${message}`;
        
        // Store original content for restoration
        element.dataset.originalContent = originalContent;
    }

    function hideLoading(element) {
        element.classList.remove('btn-processing');
        element.style.pointerEvents = 'auto';
        
        if (element.dataset.originalContent) {
            element.innerHTML = element.dataset.originalContent;
            delete element.dataset.originalContent;
        }
    }

    // Toast Notification System
    function showToast(message, type = 'info', callback = null) {
        const toast = document.createElement('div');
        toast.className = `subscription-toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#667eea'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(400px);
            transition: all 0.3s ease;
            cursor: pointer;
            max-width: 300px;
            font-weight: 600;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        // Click to dismiss or execute callback
        toast.addEventListener('click', () => {
            if (callback) callback();
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 300);
        });
    }

    // Global functions for subscription functionality
    window.SubscriptionApp = {
        showToast: (message, type, callback) => showToast(message, type, callback),
        showLoading: (element, message) => showLoading(element, message),
        hideLoading: (element) => hideLoading(element)
    };

    console.log('Enhanced subscription page animations initialized successfully! 💎✨');
});

// ===== Subscription-Specific Functions =====

// Plan Selection Function
function selectPlan(planType) {
    const plans = {
        free: { name: 'مجاني', price: 0, period: 'دائماً' },
        basic: { name: 'أساسي', price: 99, period: 'شهرياً' },
        premium: { name: 'مميز', price: 199, period: 'شهرياً' }
    };

    const selectedPlan = plans[planType];
    
    if (!selectedPlan) {
        console.error('Invalid plan type:', planType);
        return;
    }

    // If it's the free plan and user is not logged in, redirect to registration
    if (planType === 'free' && !document.querySelector('.user-dropdown')) {
        window.location.href = 'register.php';
        return;
    }

    // For paid plans, show payment modal
    if (planType !== 'free') {
        showPaymentModal(selectedPlan);
    } else {
        // Handle free plan activation
        window.SubscriptionApp.showToast('تم تفعيل الخطة المجانية بنجاح!', 'success');
    }
}

// Show Payment Modal
function showPaymentModal(plan) {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const paymentSummary = document.getElementById('paymentSummary');
    
    // Populate payment summary
    paymentSummary.innerHTML = `
        <div class="payment-plan-details">
            <div class="plan-summary">
                <div class="plan-icon">
                    <i class="fas fa-${plan.name === 'أساسي' ? 'star' : 'crown'}"></i>
                </div>
                <div class="plan-info">
                    <h5 class="plan-name">خطة ${plan.name}</h5>
                    <p class="plan-price">${plan.price === 0 ? 'مجاني' : plan.price + ' جنيه'} ${plan.period}</p>
                </div>
            </div>
            <div class="payment-breakdown">
                <div class="breakdown-item">
                    <span>السعر الأساسي:</span>
                    <span>${plan.price} جنيه</span>
                </div>
                <div class="breakdown-item">
                    <span>الضرائب:</span>
                    <span>0 جنيه</span>
                </div>
                <div class="breakdown-item total">
                    <span>الإجمالي:</span>
                    <span>${plan.price} جنيه</span>
                </div>
            </div>
        </div>
    `;
    
    // Store selected plan data
    document.getElementById('paymentModal').dataset.selectedPlan = JSON.stringify(plan);
    
    modal.show();
}

// Confirm Payment Function
document.addEventListener('DOMContentLoaded', function() {
    const confirmPaymentBtn = document.getElementById('confirmPayment');
    if (confirmPaymentBtn) {
        confirmPaymentBtn.addEventListener('click', function() {
            processPayment();
        });
    }
});

// Process Payment Function
function processPayment() {
    const paymentForm = document.getElementById('paymentForm');
    const confirmBtn = document.getElementById('confirmPayment');
    const modal = document.getElementById('paymentModal');
    const selectedPlan = JSON.parse(modal.dataset.selectedPlan || '{}');
    
    // Validate form
    if (!paymentForm.checkValidity()) {
        paymentForm.reportValidity();
        return;
    }
    
    // Show loading state
    window.SubscriptionApp.showLoading(confirmBtn, 'جارٍ المعالجة...');
    
    // Get form data
    const formData = new FormData(paymentForm);
    const paymentData = {
        plan: selectedPlan,
        cardName: document.getElementById('cardName').value,
        cardNumber: document.getElementById('cardNumber').value,
        cardExpiry: document.getElementById('cardExpiry').value,
        cardCvc: document.getElementById('cardCvc').value
    };
    
    // Simulate payment processing
    setTimeout(() => {
        // In a real application, you would send this data to your payment processor
        console.log('Processing payment:', paymentData);
        
        // Simulate successful payment
        window.SubscriptionApp.hideLoading(confirmBtn);
        
        // Show success message
        window.SubscriptionApp.showToast(
            `تم الاشتراك في خطة ${selectedPlan.name} بنجاح!`, 
            'success'
        );
        
        // Close modal
        bootstrap.Modal.getInstance(modal).hide();
        
        // Add success animation to the selected plan card
        const planCards = document.querySelectorAll('.pricing-card-3d');
        planCards.forEach(card => {
            const planName = card.querySelector('.plan-name').textContent;
            if (planName.includes(selectedPlan.name)) {
                card.classList.add('payment-success');
                
                // Update button text
                const button = card.querySelector('.btn-plan-select');
                button.innerHTML = '<i class="fas fa-check me-2"></i>مشترك حالياً';
                button.disabled = true;
                button.classList.add('btn-secondary');
            }
        });
        
        // Redirect to dashboard or refresh page after a delay
        setTimeout(() => {
            window.location.reload();
        }, 2000);
        
    }, 3000); // Simulate 3 second processing time
}

// Form Validation Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Real-time validation for card number
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            const value = this.value.replace(/\s+/g, '');
            const isValid = /^\d{16}$/.test(value);
            
            if (isValid) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else if (value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }
    
    // Real-time validation for expiry date
    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function() {
            const value = this.value;
            const isValid = /^\d{2}\/\d{2}$/.test(value);
            
            if (isValid) {
                // Check if date is in the future
                const [month, year] = value.split('/');
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100;
                const currentMonth = currentDate.getMonth() + 1;
                
                const inputYear = parseInt(year, 10);
                const inputMonth = parseInt(month, 10);
                
                const isValidDate = (inputYear > currentYear) || 
                                  (inputYear === currentYear && inputMonth >= currentMonth);
                
                if (isValidDate && inputMonth >= 1 && inputMonth <= 12) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            } else if (value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }
    
    // Real-time validation for CVC
    const cardCvcInput = document.getElementById('cardCvc');
    if (cardCvcInput) {
        cardCvcInput.addEventListener('input', function() {
            const value = this.value;
            const isValid = /^\d{3,4}$/.test(value);
            
            if (isValid) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else if (value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }
});

// Keyboard Navigation Enhancement
document.addEventListener('keydown', function(e) {
    // Enhanced tab navigation with visual feedback
    if (e.key === 'Tab') {
        document.body.classList.add('keyboard-navigation');
        
        setTimeout(() => {
            document.body.classList.remove('keyboard-navigation');
        }, 3000);
    }

    // Escape key to close modals
    if (e.key === 'Escape') {
        // Close payment modal if open
        const paymentModal = document.getElementById('paymentModal');
        if (paymentModal) {
            const modalInstance = bootstrap.Modal.getInstance(paymentModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    }
});

// PWA Installation Enhancement for Subscription Page
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    // Show custom install button specifically for subscription page
    const installBtn = document.createElement('button');
    installBtn.className = 'btn btn-warning position-fixed subscription-install-btn';
    installBtn.style.cssText = `
        bottom: 100px; 
        left: 30px; 
        z-index: 1000; 
        border-radius: 50px;
        animation: subscriptionInstallPulse 2s infinite;
        box-shadow: 0 10px 30px rgba(247, 183, 51, 0.3);
        padding: 0.8rem 1.5rem;
        font-weight: 600;
    `;
    installBtn.innerHTML = '<i class="fas fa-mobile-alt me-2"></i>ثبت التطبيق للاشتراك السريع';
    installBtn.onclick = installSubscriptionApp;
    
    // Add install animation
    const installStyles = document.createElement('style');
    installStyles.textContent = `
        @keyframes subscriptionInstallPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    `;
    document.head.appendChild(installStyles);
    
    document.body.appendChild(installBtn);
});

function installSubscriptionApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
                window.SubscriptionApp.showToast('تم تثبيت التطبيق بنجاح!', 'success');
            }
            deferredPrompt = null;
            const installBtn = document.querySelector('.subscription-install-btn');
            if (installBtn) installBtn.remove();
        });
    }
}

// Enhanced Analytics Tracking (if you use Google Analytics or similar)
function trackSubscriptionEvent(eventName, planType, value = null) {
    // Google Analytics 4 example
    if (typeof gtag !== 'undefined') {
        gtag('event', eventName, {
            'event_category': 'subscription',
            'event_label': planType,
            'value': value
        });
    }
    
    // Console log for development
    console.log('Subscription Event:', { eventName, planType, value });
}

// Usage examples:
// trackSubscriptionEvent('plan_selected', 'basic');
// trackSubscriptionEvent('payment_initiated', 'premium', 199);
// trackSubscriptionEvent('subscription_completed', 'basic', 99);

// Utility Functions
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

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Export functions for potential use in other scripts
window.SubscriptionUtils = {
    selectPlan: (planType) => selectPlan(planType),
    showPaymentModal: (plan) => showPaymentModal(plan),
    processPayment: () => processPayment(),
    trackSubscriptionEvent: (eventName, planType, value) => trackSubscriptionEvent(eventName, planType, value),
    debounce,
    throttle
};