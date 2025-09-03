// ===== Modern Educational Platform Animations =====

class EducationalAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.setupAOS();
        this.setupNavigation();
        this.setupCounters();
        this.setupProgressAnimations();
        this.setupParticleSystem();
        this.setupGrowthAnimations();
        this.setupInteractiveElements();
        this.setupPerformanceOptimizations();
        this.setupPWAFeatures();
        console.log('🌱 Educational platform animations initialized successfully!');
    }

    // Initialize AOS with custom settings
    setupAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                once: true,
                offset: 120,
                disable: function() {
                    return window.innerWidth < 768;
                },
                startEvent: 'DOMContentLoaded',
                initClassName: false,
                animatedClassName: 'aos-animate',
                useClassNames: false,
                disableMutationObserver: false,
                debounceDelay: 50,
                throttleDelay: 99,
            });
        }
    }

    // Enhanced navigation with growth animations
    setupNavigation() {
        const navbar = document.querySelector('.glass-nav');
        if (!navbar) return;

        let lastScrollTop = 0;
        let ticking = false;

        const updateNavbar = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Add/remove scrolled class
            if (scrollTop > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            // Auto-hide navbar on scroll down
            if (scrollTop > lastScrollTop && scrollTop > 300) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });

        // Enhanced dropdown animations
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                const dropdown = e.target.closest('.dropdown').querySelector('.dropdown-menu');
                if (dropdown) {
                    dropdown.style.animation = 'dropdownGrow 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                }
            });
        });
    }

    // Animated counters with growth effect
    setupCounters() {
        const observerOptions = {
            threshold: 0.6,
            rootMargin: '0px 0px -50px 0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const finalValue = parseInt(counter.getAttribute('data-count'));
                    const duration = 2000;
                    const startValue = 0;
                    const increment = finalValue / (duration / 16);
                    let currentValue = startValue;

                    // Add growth animation class
                    counter.classList.add('counting');

                    const updateCounter = () => {
                        currentValue += increment;
                        if (currentValue < finalValue) {
                            counter.textContent = Math.floor(currentValue);
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = finalValue;
                            counter.classList.add('count-completed');
                            this.createCelebrationEffect(counter);
                        }
                    };

                    // Delay for dramatic effect
                    setTimeout(() => {
                        updateCounter();
                    }, 300);
                    
                    counterObserver.unobserve(counter);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.counter').forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    // Create celebration effect for completed counters
    createCelebrationEffect(element) {
        for (let i = 0; i < 8; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 6px;
                    height: 6px;
                    background: var(--warning-gradient);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: celebrationParticle 1s ease-out forwards;
                `;
                
                const rect = element.getBoundingClientRect();
                particle.style.left = (rect.left + rect.width / 2) + 'px';
                particle.style.top = (rect.top + rect.height / 2) + 'px';
                
                document.body.appendChild(particle);
                
                setTimeout(() => particle.remove(), 1000);
            }, i * 50);
        }
    }

    // Progress animations with organic growth
    setupProgressAnimations() {
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressElements = entry.target.querySelectorAll('.progress-fill');
                    progressElements.forEach((progress, index) => {
                        setTimeout(() => {
                            progress.style.animation = 'organicGrowth 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                        }, index * 200);
                    });
                    progressObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.achievement-progress, .progress-section').forEach(progress => {
            progressObserver.observe(progress);
        });
    }

    // Dynamic particle system for growth visualization
    setupParticleSystem() {
        const hero = document.querySelector('.growth-particles');
        if (!hero) return;

        // Create growth particles
        for (let i = 0; i < 25; i++) {
            const particle = document.createElement('div');
            particle.className = 'growth-particle';
            particle.style.cssText = `
                position: absolute;
                width: 3px;
                height: 3px;
                background: radial-gradient(circle, #10b981 0%, transparent 70%);
                border-radius: 50%;
                pointer-events: none;
                animation: growthParticleFloat ${12 + Math.random() * 8}s infinite linear;
                left: ${Math.random() * 100}%;
                top: ${100 + Math.random() * 20}%;
                animation-delay: ${Math.random() * 8}s;
                opacity: 0.6;
            `;
            hero.appendChild(particle);
        }

        // Create learning particles with different colors
        for (let i = 0; i < 15; i++) {
            const colors = ['#f97316', '#3b82f6', '#ec4899', '#10b981'];
            const particle = document.createElement('div');
            particle.className = 'learning-particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                border-radius: 50%;
                pointer-events: none;
                animation: learningParticleOrbit ${15 + Math.random() * 10}s infinite linear;
                left: ${20 + Math.random() * 60}%;
                top: ${20 + Math.random() * 60}%;
                animation-delay: ${Math.random() * 10}s;
                opacity: 0.4;
            `;
            hero.appendChild(particle);
        }
    }

    // Growth-themed animations for interactions
    setupGrowthAnimations() {
        // Category cards growth animation on hover
        document.querySelectorAll('.category-card-growth').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const icon = card.querySelector('.category-icon');
                const line = card.querySelector('.growth-line');
                
                if (icon) {
                    icon.style.animation = 'categoryGrowthHover 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                }
                
                if (line) {
                    line.style.width = '100%';
                }
            });

            card.addEventListener('mouseleave', () => {
                const icon = card.querySelector('.category-icon');
                const line = card.querySelector('.growth-line');
                
                if (icon) {
                    icon.style.animation = '';
                }
                
                if (line) {
                    line.style.width = '0';
                }
            });

            card.addEventListener('click', () => {
                this.createGrowthRipple(card);
            });
        });

        // Skill cards with learning progression animation
        document.querySelectorAll('.skill-card-modern').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const iconWrapper = card.querySelector('.skill-icon-wrapper');
                if (iconWrapper) {
                    iconWrapper.style.animation = 'skillIconGlow 0.8s ease-in-out';
                }
            });
        });
    }

    // Create growth ripple effect
    createGrowthRipple(element) {
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            transform: scale(0);
            animation: growthRipple 0.8s ease-out;
            pointer-events: none;
            z-index: 100;
        `;
        
        const rect = element.getBoundingClientRect();
        ripple.style.left = (rect.width / 2 - 5) + 'px';
        ripple.style.top = (rect.height / 2 - 5) + 'px';
        
        element.style.position = 'relative';
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 800);
    }

    // Interactive elements with growth feedback
    setupInteractiveElements() {
        // Enhanced button interactions
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!btn.href || btn.href.includes('#')) {
                    e.preventDefault();
                }
                
                // Create growth burst effect
                this.createGrowthBurst(e.target, e);
                
                // Add loading state for navigation buttons
                if (btn.href && !btn.href.includes('#') && !btn.href.includes('javascript:')) {
                    this.showButtonLoading(btn);
                }
            });

            // Add hover sound feedback (visual)
            btn.addEventListener('mouseenter', () => {
                btn.style.transform = 'translateY(-2px) scale(1.02)';
            });

            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
            });
        });

        // Enhanced form interactions
        document.querySelectorAll('input, textarea, select').forEach(input => {
            this.enhanceFormElement(input);
        });

        // Card tilt effects
        this.setupCardTilt();
    }

    // Create growth burst animation
    createGrowthBurst(element, event) {
        const burst = document.createElement('div');
        const rect = element.getBoundingClientRect();
        const x = (event.clientX || rect.left + rect.width / 2) - rect.left;
        const y = (event.clientY || rect.top + rect.height / 2) - rect.top;

        burst.style.cssText = `
            position: absolute;
            left: ${x - 10}px;
            top: ${y - 10}px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.6) 0%, transparent 70%);
            transform: scale(0);
            animation: growthBurst 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: none;
            z-index: 100;
        `;

        element.style.position = 'relative';
        element.appendChild(burst);

        setTimeout(() => burst.remove(), 600);
    }

    // Enhanced card tilt with growth theme
    setupCardTilt() {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
                max: 12,
                speed: 400,
                scale: 1.05,
                perspective: 1000,
                transition: true,
                "max-glare": 0.15,
                "glare-prerender": false,
                gyroscope: true
            });
        }
    }

    // Show loading state with growth animation
    showButtonLoading(button) {
        const originalContent = button.innerHTML;
        const icon = button.querySelector('i');
        
        if (icon && !button.classList.contains('loading')) {
            button.classList.add('loading');
            
            // Create growing loader
            button.innerHTML = `
                <i class="fas fa-seedling fa-spin me-2"></i>
                جاري التحميل...
            `;
            
            // Reset after timeout (fallback)
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.classList.remove('loading');
            }, 3000);
        }
    }

    // Enhanced form elements with organic animations
    enhanceFormElement(element) {
        // Add focus ring animation
        element.addEventListener('focus', () => {
            element.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.2)';
            element.style.borderColor = 'var(--leaf-green)';
            element.style.transform = 'scale(1.02)';
        });

        element.addEventListener('blur', () => {
            element.style.boxShadow = '';
            element.style.borderColor = '';
            element.style.transform = '';
        });

        // Add typing animation feedback
        if (element.type === 'text' || element.type === 'email' || element.tagName === 'TEXTAREA') {
            let typingTimer;
            element.addEventListener('input', () => {
                clearTimeout(typingTimer);
                element.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
                
                typingTimer = setTimeout(() => {
                    element.style.backgroundColor = '';
                }, 1000);
            });
        }
    }

    // Performance optimizations for smooth animations
    setupPerformanceOptimizations() {
        // Intersection Observer for expensive animations
        const expensiveAnimationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const element = entry.target;
                if (entry.isIntersecting) {
                    element.classList.add('animate-active');
                    element.style.willChange = 'transform, opacity';
                } else {
                    element.classList.remove('animate-active');
                    element.style.willChange = 'auto';
                }
            });
        }, { threshold: 0.1 });

        // Observe elements with expensive animations
        document.querySelectorAll('.learning-ecosystem, .floating-element, .growth-hub').forEach(el => {
            expensiveAnimationObserver.observe(el);
        });

        // Reduce animations on low-performance devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-fast', '0.1s');
            document.documentElement.style.setProperty('--animation-normal', '0.2s');
            document.documentElement.style.setProperty('--animation-slow', '0.3s');
        }
    }

    // Progressive Web App features
    setupPWAFeatures() {
        // Service Worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', async () => {
                try {
                    const registration = await navigator.serviceWorker.register('/sw.js');
                    console.log('🌱 Service Worker registered successfully');
                    
                    // Handle updates
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                this.showUpdateNotification();
                            }
                        });
                    });
                } catch (error) {
                    console.log('Service Worker registration failed:', error);
                }
            });
        }

        // Install app prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            this.showInstallPrompt(deferredPrompt);
        });
    }

    // Show app installation prompt
    showInstallPrompt(deferredPrompt) {
        const installBtn = document.createElement('button');
        installBtn.className = 'install-prompt-btn';
        installBtn.style.cssText = `
            position: fixed;
            bottom: 100px;
            left: 30px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: var(--shadow-growth);
            z-index: 1000;
            animation: installPromptSlide 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            cursor: pointer;
            font-size: 0.9rem;
        `;
        installBtn.innerHTML = '<i class="fas fa-download me-2"></i>ثبت التطبيق';
        
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const result = await deferredPrompt.userChoice;
                if (result.outcome === 'accepted') {
                    this.showToast('تم تثبيت التطبيق بنجاح! 🌱', 'success');
                }
                deferredPrompt = null;
                installBtn.remove();
            }
        });
        
        document.body.appendChild(installBtn);
        
        // Auto-hide after 10 seconds
        setTimeout(() => {
            if (installBtn.parentNode) {
                installBtn.style.animation = 'installPromptSlideOut 0.3s ease-in';
                setTimeout(() => installBtn.remove(), 300);
            }
        }, 10000);
    }

    // Show update notification
    showUpdateNotification() {
        this.showToast('إصدار جديد متاح! انقر لإعادة التحميل 🚀', 'info', () => {
            window.location.reload();
        });
    }

    // Enhanced toast notification system
    showToast(message, type = 'info', callback = null) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        
        const typeColors = {
            success: 'var(--primary-gradient)',
            error: 'var(--danger-gradient)',
            warning: 'var(--warning-gradient)',
            info: 'var(--accent-gradient)'
        };
        
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${typeColors[type]};
            color: white;
            padding: 1.2rem 1.8rem;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            z-index: 10000;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            cursor: pointer;
            max-width: 350px;
            font-weight: 600;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Animate in with growth effect
        setTimeout(() => {
            toast.style.transform = 'translateX(0) scale(1)';
        }, 100);

        // Auto remove with shrink effect
        setTimeout(() => {
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 400);
        }, 5000);

        // Click handling
        toast.addEventListener('click', () => {
            if (callback) callback();
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 400);
        });

        return toast;
    }

    // Smooth scroll with growth momentum
    setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    const navbar = document.querySelector('.glass-nav');
                    const offsetTop = target.offsetTop - (navbar ? navbar.offsetHeight : 0);
                    
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Add arrival animation
                    target.style.animation = 'targetReached 1s ease-out';
                    setTimeout(() => {
                        target.style.animation = '';
                    }, 1000);
                }
            });
        });
    }

    // Enhanced keyboard navigation
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Tab navigation with visual feedback
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
                setTimeout(() => {
                    document.body.classList.remove('keyboard-navigation');
                }, 3000);
            }

            // Escape key handling
            if (e.key === 'Escape') {
                // Close modals and dropdowns
                document.querySelectorAll('.modal.show').forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });
                
                document.querySelectorAll('.dropdown-menu.show').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }

            // Arrow key navigation for cards
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                this.handleCardNavigation(e);
            }
        });
    }

    // Card navigation with arrow keys
    handleCardNavigation(e) {
        const focusedElement = document.activeElement;
        const cards = Array.from(document.querySelectorAll('.category-card-growth, .skill-card-modern'));
        const currentIndex = cards.indexOf(focusedElement);
        
        if (currentIndex !== -1) {
            e.preventDefault();
            let nextIndex;
            
            if (e.key === 'ArrowRight') {
                nextIndex = (currentIndex + 1) % cards.length;
            } else {
                nextIndex = currentIndex > 0 ? currentIndex - 1 : cards.length - 1;
            }
            
            cards[nextIndex].focus();
            cards[nextIndex].style.animation = 'cardFocusGlow 0.5s ease-out';
        }
    }

    // Lazy loading for images with growth transition
    setupLazyLoading() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.style.transition = 'all 0.5s ease-out';
                    img.src = img.dataset.src;
                    img.style.opacity = '0';
                    img.style.transform = 'scale(0.8)';
                    
                    img.onload = () => {
                        img.style.opacity = '1';
                        img.style.transform = 'scale(1)';
                    };
                    
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Theme toggling with smooth transitions
    setupThemeToggling() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-theme');
                const isDark = document.body.classList.contains('dark-theme');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                
                // Animate theme change
                document.body.style.transition = 'all 0.5s ease-out';
                setTimeout(() => {
                    document.body.style.transition = '';
                }, 500);
            });
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }
    }
}

// Add custom CSS animations
const animationStyles = document.createElement('style');
animationStyles.textContent = `
    @keyframes growthParticleFloat {
        0% {
            transform: translateY(0px) translateX(0px) scale(1);
            opacity: 0;
        }
        10% {
            opacity: 0.6;
        }
        90% {
            opacity: 0.4;
        }
        100% {
            transform: translateY(-100vh) translateX(${Math.random() * 100 - 50}px) scale(1.5);
            opacity: 0;
        }
    }
    
    @keyframes learningParticleOrbit {
        0% {
            transform: rotate(0deg) translateX(50px) rotate(0deg);
            opacity: 0.4;
        }
        50% {
            opacity: 0.8;
        }
        100% {
            transform: rotate(360deg) translateX(50px) rotate(-360deg);
            opacity: 0.4;
        }
    }
    
    @keyframes growthBurst {
        0% { 
            transform: scale(0); 
            opacity: 0.8; 
        }
        50% { 
            transform: scale(2); 
            opacity: 0.4; 
        }
        100% { 
            transform: scale(4); 
            opacity: 0; 
        }
    }
    
    @keyframes growthRipple {
        0% { 
            transform: scale(0); 
            opacity: 0.6; 
        }
        100% { 
            transform: scale(20); 
            opacity: 0; 
        }
    }
    
    @keyframes organicGrowth {
        0% { 
            width: 0; 
            transform: scaleY(0.8);
        }
        60% { 
            transform: scaleY(1.1);
        }
        100% { 
            transform: scaleY(1);
        }
    }
    
    @keyframes categoryGrowthHover {
        0% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(5deg); }
        100% { transform: scale(1.05) rotate(0deg); }
    }
    
    @keyframes targetReached {
        0% { background: rgba(16, 185, 129, 0.1); }
        50% { background: rgba(16, 185, 129, 0.2); }
        100% { background: transparent; }
    }
    
    @keyframes cardFocusGlow {
        0%, 100% { box-shadow: var(--shadow-soft); }
        50% { box-shadow: var(--shadow-growth); }
    }
    
    @keyframes dropdownGrow {
        0% { 
            opacity: 0; 
            transform: scale(0.8) translateY(-20px); 
        }
        100% { 
            opacity: 1; 
            transform: scale(1) translateY(0); 
        }
    }
    
    @keyframes installPromptSlide {
        0% { 
            opacity: 0; 
            transform: translateX(-100px) scale(0.8); 
        }
        100% { 
            opacity: 1; 
            transform: translateX(0) scale(1); 
        }
    }
    
    @keyframes installPromptSlideOut {
        0% { 
            opacity: 1; 
            transform: translateX(0) scale(1); 
        }
        100% { 
            opacity: 0; 
            transform: translateX(-100px) scale(0.8); 
        }
    }
    
    @keyframes celebrationParticle {
        0% { 
            opacity: 1; 
            transform: scale(1) translate(0, 0); 
        }
        100% { 
            opacity: 0; 
            transform: scale(1.5) translate(${Math.random() * 100 - 50}px, ${-50 - Math.random() * 50}px); 
        }
    }
    
    /* Dark theme support */
    .dark-theme {
        --bg-primary: #111827;
        --bg-secondary: #1f2937;
        --bg-tertiary: #374151;
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --glass-bg: rgba(0, 0, 0, 0.3);
        --glass-border: rgba(255, 255, 255, 0.1);
    }
    
    /* Keyboard navigation enhancement */
    .keyboard-navigation *:focus {
        outline: 3px solid var(--leaf-green) !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
        border-radius: 8px !important;
    }
    
    /* Accessibility improvements */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        
        .floating-elements,
        .learning-ecosystem,
        .growth-particles {
            display: none !important;
        }
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .glass-nav {
            background: rgba(255, 255, 255, 0.98) !important;
        }
        
        .btn {
            border: 2px solid currentColor !important;
        }
        
        .skill-card-modern,
        .category-card-growth,
        .modern-stats-card {
            border: 2px solid var(--text-primary) !important;
        }
    }
    
    /* Print optimizations */
    @media print {
        .floating-elements,
        .chat-fab,
        .navbar,
        .hero-background,
        .growth-particles,
        .challenge-decoration,
        .learning-ecosystem {
            display: none !important;
        }
        
        .hero-section {
            min-height: auto !important;
            padding: 2rem 0 !important;
        }
        
        .skill-card-modern,
        .category-card-growth,
        .challenge-spotlight {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
`;

document.head.appendChild(animationStyles);

// Utility functions for external use
window.EduAnimations = {
    showToast: (message, type, callback) => {
        const animations = new EducationalAnimations();
        return animations.showToast(message, type, callback);
    },
    createGrowthBurst: (element, event) => {
        const animations = new EducationalAnimations();
        return animations.createGrowthBurst(element, event);
    },
    createCelebrationEffect: (element) => {
        const animations = new EducationalAnimations();
        return animations.createCelebrationEffect(element);
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new EducationalAnimations();
});

// Handle page visibility changes for performance
document.addEventListener('visibilitychange', () => {
    const isHidden = document.hidden;
    const expensiveElements = document.querySelectorAll('.learning-ecosystem, .floating-element, .growth-hub');
    
    expensiveElements.forEach(element => {
        if (isHidden) {
            element.style.animationPlayState = 'paused';
        } else {
            element.style.animationPlayState = 'running';
        }
    });
});

// Export for module usage
export { EducationalAnimations };