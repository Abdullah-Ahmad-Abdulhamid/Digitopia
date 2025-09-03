// ===== Progress Page Enhanced Animations =====

class ProgressPageAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.setupProgressAnimations();
        this.setupCircularProgress();
        this.setupLevelAnimations();
        this.setupAchievementAnimations();
        this.setupSkillCardInteractions();
        this.setupSidebarAnimations();
        this.setupCounterAnimations();
        this.setupParticleSystem();
        this.setupPerformanceOptimizations();
        console.log('🚀 Progress page animations initialized successfully!');
    }

    // Initialize progress-specific animations
    setupProgressAnimations() {
        // Add SVG gradients for progress circles
        this.addSVGGradients();
        
        // Initialize progress ring animations with delay
        setTimeout(() => {
            const progressRings = document.querySelectorAll('.progress-ring-fill');
            progressRings.forEach((ring, index) => {
                const progress = parseFloat(ring.style.getPropertyValue('--progress')) || 0;
                ring.style.strokeDashoffset = '534'; // Reset to 0
                
                setTimeout(() => {
                    const offset = 534 - (534 * progress) / 100;
                    ring.style.strokeDashoffset = offset;
                }, index * 200 + 500);
            });
        }, 1000);

        // Animate level progress on scroll
        const levelProgressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = entry.target.querySelector('.progress-fill-modern');
                    if (progressBar) {
                        const targetWidth = progressBar.style.width;
                        progressBar.style.width = '0%';
                        
                        setTimeout(() => {
                            progressBar.style.width = targetWidth;
                        }, 300);
                    }
                    levelProgressObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.level-progression-card').forEach(card => {
            levelProgressObserver.observe(card);
        });
    }

    // Add SVG gradients for progress elements
    addSVGGradients() {
        const svgs = document.querySelectorAll('.progress-ring, .progress-circle');
        
        svgs.forEach((svg, index) => {
            if (svg.querySelector('defs')) return; // Already has gradients
            
            // Create unique gradient for each progress circle
            const gradientId = `progressGradient${index}`;
            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            
            gradient.setAttribute('id', gradientId);
            gradient.setAttribute('x1', '0%');
            gradient.setAttribute('y1', '0%');
            gradient.setAttribute('x2', '100%');
            gradient.setAttribute('y2', '100%');
            
            const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stop1.setAttribute('offset', '0%');
            stop1.setAttribute('stop-color', '#10b981');
            
            const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stop2.setAttribute('offset', '100%');
            stop2.setAttribute('stop-color', '#f97316');
            
            gradient.appendChild(stop1);
            gradient.appendChild(stop2);
            defs.appendChild(gradient);
            svg.appendChild(defs);
            
            // Update stroke reference
            const progressFill = svg.querySelector('.progress-ring-fill, .progress-circle-fill');
            if (progressFill) {
                progressFill.setAttribute('stroke', `url(#${gradientId})`);
            }
        });
    }

    // Initialize circular progress animations
    setupCircularProgress() {
        const circularProgressElements = document.querySelectorAll('.circular-progress-modern');
        
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressElement = entry.target;
                    const progressValue = progressElement.getAttribute('data-progress');
                    const progressCircle = progressElement.querySelector('.progress-circle-fill');
                    
                    if (progressCircle && progressValue) {
                        // Reset animation
                        progressCircle.style.strokeDashoffset = '251';
                        
                        setTimeout(() => {
                            const offset = 251 - (251 * progressValue) / 100;
                            progressCircle.style.strokeDashoffset = offset;
                        }, 400);
                        
                        // Animate percentage counter
                        this.animatePercentageCounter(progressElement, progressValue);
                    }
                    
                    progressObserver.unobserve(progressElement);
                }
            });
        }, { threshold: 0.3 });
        
        circularProgressElements.forEach(element => {
            progressObserver.observe(element);
        });
    }

    // Animate percentage counter
    animatePercentageCounter(element, targetValue) {
        const percentageElement = element.querySelector('.progress-percentage');
        if (!percentageElement) return;
        
        const startValue = 0;
        const duration = 2000;
        const startTime = performance.now();
        
        const updateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (ease-out cubic)
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.round(startValue + (targetValue - startValue) * easedProgress);
            
            percentageElement.textContent = currentValue + '%';
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        };
        
        requestAnimationFrame(updateCounter);
    }

    // Level animations with growth theme
    setupLevelAnimations() {
        const levelHub = document.querySelector('.level-hub');
        if (levelHub) {
            // Add interactive hover effects
            levelHub.addEventListener('mouseenter', () => {
                levelHub.style.animation = 'levelHubHover 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            });
            
            levelHub.addEventListener('mouseleave', () => {
                levelHub.style.animation = 'hubPulse 4s ease-in-out infinite';
            });
        }

        // Animate level badge on load
        const levelBadge = document.querySelector('.level-badge');
        if (levelBadge) {
            setTimeout(() => {
                levelBadge.style.animation = 'levelBadgeReveal 1s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            }, 1500);
        }
    }

    // Achievement animations with celebration effects
    setupAchievementAnimations() {
        const achievementCards = document.querySelectorAll('.achievement-card-modern');
        
        achievementCards.forEach((card, index) => {
            // Stagger animation delays
            card.style.animationDelay = `${index * 150}ms`;
            
            // Enhanced hover interactions
            card.addEventListener('mouseenter', () => {
                this.createAchievementSparkles(card);
                card.style.animation = 'achievementHover 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.animation = 'achievementEarned 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            });
            
            // Click celebration
            card.addEventListener('click', () => {
                this.createCelebrationBurst(card);
            });
        });
    }

    // Create achievement sparkles effect
    createAchievementSparkles(card) {
        for (let i = 0; i < 6; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'dynamic-achievement-sparkle';
            sparkle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: #fbbf24;
                border-radius: 50%;
                pointer-events: none;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                animation: achievementSparkleAnimation 1.5s ease-out forwards;
                z-index: 100;
            `;
            
            card.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 1500);
        }
    }

    // Create celebration burst effect
    createCelebrationBurst(element) {
        for (let i = 0; i < 12; i++) {
            const particle = document.createElement('div');
            const colors = ['#10b981', '#f97316', '#3b82f6', '#fbbf24', '#ec4899'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            
            particle.style.cssText = `
                position: absolute;
                width: 6px;
                height: 6px;
                background: ${randomColor};
                border-radius: 50%;
                pointer-events: none;
                z-index: 1000;
                animation: celebrationParticle 2s ease-out forwards;
            `;
            
            const rect = element.getBoundingClientRect();
            particle.style.left = (rect.left + rect.width / 2) + 'px';
            particle.style.top = (rect.top + rect.height / 2) + 'px';
            
            document.body.appendChild(particle);
            setTimeout(() => particle.remove(), 2000);
        }
    }

    // Enhanced skill card interactions
    setupSkillCardInteractions() {
        const skillCards = document.querySelectorAll('.skill-progress-card-modern');
        
        skillCards.forEach(card => {
            // 3D tilt effect
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 15;
                const rotateY = (centerX - x) / 15;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
            });

            // Progress animation on reveal
            const progressCircle = card.querySelector('.progress-circle-fill');
            if (progressCircle) {
                const cardObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                const progress = progressCircle.style.getPropertyValue('--progress');
                                progressCircle.style.strokeDashoffset = '251';
                                
                                setTimeout(() => {
                                    const offset = 251 - (251 * progress) / 100;
                                    progressCircle.style.strokeDashoffset = offset;
                                }, 200);
                            }, 300);
                            
                            cardObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2 });
                
                cardObserver.observe(card);
            }
        });
    }

    // Sidebar animations
    setupSidebarAnimations() {
        const sidebarCards = document.querySelectorAll('.modern-sidebar-card');
        
        sidebarCards.forEach((card, index) => {
            // Stagger entrance animations
            card.style.animationDelay = `${index * 200}ms`;
            
            // Enhanced hover effects
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
                card.style.boxShadow = 'var(--shadow-growth)';
                
                // Animate header icon
                const headerIcon = card.querySelector('.header-icon');
                if (headerIcon) {
                    headerIcon.style.animation = 'headerIconBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                }
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = 'var(--shadow-soft)';
                
                const headerIcon = card.querySelector('.header-icon');
                if (headerIcon) {
                    headerIcon.style.animation = 'headerIconPulse 3s ease-in-out infinite';
                }
            });
        });

        // Quick actions interactions
        const quickActionBtns = document.querySelectorAll('.quick-action-btn-modern');
        quickActionBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.createRippleEffect(btn, e);
            });
            
            btn.addEventListener('mouseenter', () => {
                const actionIcon = btn.querySelector('.action-icon');
                if (actionIcon) {
                    actionIcon.style.animation = 'actionIconJump 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                }
            });
        });

        // Challenge items hover effects
        const challengeItems = document.querySelectorAll('.challenge-item-modern');
        challengeItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.backgroundColor = 'rgba(16, 185, 129, 0.08)';
                item.style.transform = 'translateX(8px)';
                item.style.borderRadius = '12px';
                
                const challengeIcon = item.querySelector('.challenge-icon');
                if (challengeIcon) {
                    challengeIcon.style.animation = 'challengeIconBounce 0.5s ease-out';
                }
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.backgroundColor = 'transparent';
                item.style.transform = 'translateX(0)';
                item.style.borderRadius = '8px';
            });
        });
    }

    // Enhanced counter animations
    setupCounterAnimations() {
        const observerOptions = {
            threshold: 0.6,
            rootMargin: '0px 0px -50px 0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const finalValue = parseInt(counter.getAttribute('data-count'));
                    const duration = 2500;
                    const startTime = performance.now();

                    // Add counting animation class
                    counter.classList.add('counting');

                    const updateCounter = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        
                        // Easing function (ease-out cubic)
                        const easedProgress = 1 - Math.pow(1 - progress, 3);
                        const currentValue = Math.floor(finalValue * easedProgress);
                        
                        counter.textContent = currentValue;
                        
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = finalValue;
                            counter.classList.add('count-completed');
                            this.createCounterCelebration(counter);
                        }
                    };

                    // Delay for dramatic effect
                    setTimeout(() => {
                        requestAnimationFrame(updateCounter);
                    }, 400);

                    counterObserver.unobserve(counter);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.counter').forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    // Create celebration effect for completed counters
    createCounterCelebration(element) {
        for (let i = 0; i < 8; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                const colors = ['#10b981', '#f97316', '#3b82f6', '#fbbf24'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                
                particle.style.cssText = `
                    position: absolute;
                    width: 6px;
                    height: 6px;
                    background: ${randomColor};
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: counterCelebrationParticle 1.5s ease-out forwards;
                `;
                
                const rect = element.getBoundingClientRect();
                particle.style.left = (rect.left + rect.width / 2) + 'px';
                particle.style.top = (rect.top + rect.height / 2) + 'px';
                
                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 1500);
            }, i * 75);
        }
    }

    // Create ripple effect for button clicks
    createRippleEffect(button, event) {
        const ripple = document.createElement('div');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = (event.clientX || rect.left + rect.width / 2) - rect.left - size / 2;
        const y = (event.clientY || rect.top + rect.height / 2) - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            left: ${x}px;
            top: ${y}px;
            width: ${size}px;
            height: ${size}px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.3);
            transform: scale(0);
            animation: rippleAnimation 0.6s linear;
            pointer-events: none;
            z-index: 100;
        `;
        
        button.style.position = 'relative';
        button.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    }

    // Setup particle system for progress page
    setupParticleSystem() {
        const progressParticles = document.querySelector('.growth-particles');
        if (!progressParticles) return;

        // Create floating progress particles
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'progress-particle';
            particle.style.cssText = `
                position: absolute;
                width: 3px;
                height: 3px;
                background: rgba(16, 185, 129, 0.6);
                border-radius: 50%;
                pointer-events: none;
                animation: progressParticleFloat ${10 + Math.random() * 8}s infinite linear;
                left: ${Math.random() * 100}%;
                top: ${100 + Math.random() * 20}%;
                animation-delay: ${Math.random() * 8}s;
            `;
            progressParticles.appendChild(particle);
        }

        // Create achievement particles
        for (let i = 0; i < 15; i++) {
            const colors = ['#f97316', '#3b82f6', '#ec4899', '#fbbf24'];
            const particle = document.createElement('div');
            particle.className = 'achievement-particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                border-radius: 50%;
                pointer-events: none;
                animation: achievementParticleOrbit ${12 + Math.random() * 6}s infinite linear;
                left: ${20 + Math.random() * 60}%;
                top: ${20 + Math.random() * 60}%;
                animation-delay: ${Math.random() * 8}s;
                opacity: 0.5;
            `;
            progressParticles.appendChild(particle);
        }
    }

    // Performance optimizations
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

        // Observe expensive animation elements
        document.querySelectorAll('.progress-ecosystem, .floating-element, .level-hub').forEach(el => {
            expensiveAnimationObserver.observe(el);
        });

        // Reduce animations on low-performance devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-fast', '0.1s');
            document.documentElement.style.setProperty('--animation-normal', '0.2s');
            document.documentElement.style.setProperty('--animation-slow', '0.3s');
        }

        // Pause animations when page is hidden
        document.addEventListener('visibilitychange', () => {
            const isHidden = document.hidden;
            const expensiveElements = document.querySelectorAll('.progress-ecosystem, .floating-element, .level-hub');
            
            expensiveElements.forEach(element => {
                if (isHidden) {
                    element.style.animationPlayState = 'paused';
                } else {
                    element.style.animationPlayState = 'running';
                }
            });
        });
    }

    // Enhanced keyboard navigation
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Tab navigation enhancement
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
                setTimeout(() => {
                    document.body.classList.remove('keyboard-navigation');
                }, 3000);
            }

            // Arrow key navigation for cards
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                this.handleCardNavigation(e);
            }

            // Enter key for card activation
            if (e.key === 'Enter') {
                const focusedCard = document.activeElement.closest('.skill-progress-card-modern, .achievement-card-modern');
                if (focusedCard) {
                    const link = focusedCard.querySelector('a');
                    if (link) {
                        link.click();
                    }
                }
            }
        });
    }

    // Card navigation with arrow keys
    handleCardNavigation(e) {
        const focusedElement = document.activeElement;
        const cards = Array.from(document.querySelectorAll('.skill-progress-card-modern, .achievement-card-modern'));
        const currentIndex = cards.findIndex(card => card.contains(focusedElement));

        if (currentIndex !== -1) {
            e.preventDefault();
            let nextIndex;
            
            if (e.key === 'ArrowRight') {
                nextIndex = (currentIndex + 1) % cards.length;
            } else {
                nextIndex = currentIndex > 0 ? currentIndex - 1 : cards.length - 1;
            }

            const nextCard = cards[nextIndex];
            const focusableElement = nextCard.querySelector('a, button') || nextCard;
            focusableElement.focus();
            
            // Add visual feedback
            nextCard.style.animation = 'cardFocusGlow 0.5s ease-out';
        }
    }

    // Enhanced smooth scrolling
    setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                
                if (target) {
                    const navbar = document.querySelector('.glass-nav');
                    const offsetTop = target.offsetTop - (navbar ? navbar.offsetHeight + 20 : 20);
                    
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

    // Toast notification system
    showToast(message, type = 'success', callback = null) {
        const toast = document.createElement('div');
        toast.className = `progress-toast toast-${type}`;
        
        const typeConfig = {
            success: { color: '#10b981', icon: 'fas fa-check-circle' },
            error: { color: '#ef4444', icon: 'fas fa-exclamation-circle' },
            warning: { color: '#f59e0b', icon: 'fas fa-exclamation-triangle' },
            info: { color: '#3b82f6', icon: 'fas fa-info-circle' }
        };
        
        const config = typeConfig[type] || typeConfig.success;
        
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: white;
            color: var(--text-primary);
            padding: 1.2rem 1.8rem;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border-left: 4px solid ${config.color};
            z-index: 10000;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            cursor: pointer;
            max-width: 350px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        `;
        
        toast.innerHTML = `
            <i class="${config.icon}" style="color: ${config.color}; font-size: 1.2rem;"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
        
        // Click handling
        toast.addEventListener('click', () => {
            if (callback) callback();
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 400);
        });
        
        return toast;
    }
}

// Add custom CSS animations
const progressAnimationStyles = document.createElement('style');
progressAnimationStyles.textContent = `
    @keyframes progressParticleFloat {
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

    @keyframes achievementParticleOrbit {
        0% {
            transform: rotate(0deg) translateX(60px) rotate(0deg);
            opacity: 0.5;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: rotate(360deg) translateX(60px) rotate(-360deg);
            opacity: 0.5;
        }
    }

    @keyframes achievementSparkleAnimation {
        0% {
            opacity: 1;
            transform: scale(1) translate(0, 0) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: scale(2) translate(${Math.random() * 100 - 50}px, ${-30 - Math.random() * 40}px) rotate(360deg);
        }
    }

    @keyframes celebrationParticle {
        0% {
            opacity: 1;
            transform: scale(1) translate(0, 0);
        }
        100% {
            opacity: 0;
            transform: scale(1.5) translate(${Math.random() * 200 - 100}px, ${-100 - Math.random() * 100}px);
        }
    }

    @keyframes counterCelebrationParticle {
        0% {
            opacity: 1;
            transform: scale(1) translate(0, 0) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: scale(2) translate(${Math.random() * 150 - 75}px, ${-50 - Math.random() * 50}px) rotate(360deg);
        }
    }

    @keyframes rippleAnimation {
        0% {
            transform: scale(0);
            opacity: 0.6;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    @keyframes levelHubHover {
        0% {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
        }
        50% {
            transform: translate(-50%, -50%) scale(1.1) rotate(5deg);
        }
        100% {
            transform: translate(-50%, -50%) scale(1.05) rotate(0deg);
        }
    }

    @keyframes levelBadgeReveal {
        0% {
            opacity: 0;
            transform: scale(0.5) rotate(-45deg);
        }
        70% {
            transform: scale(1.1) rotate(5deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    @keyframes achievementHover {
        0% {
            transform: translateY(0) scale(1) rotateY(0deg);
        }
        50% {
            transform: translateY(-12px) scale(1.05) rotateY(5deg);
        }
        100% {
            transform: translateY(-8px) scale(1.02) rotateY(3deg);
        }
    }

    @keyframes headerIconBounce {
        0%, 100% {
            transform: scale(1) rotate(0deg);
        }
        50% {
            transform: scale(1.2) rotate(10deg);
        }
    }

    @keyframes actionIconJump {
        0%, 100% {
            transform: translateY(0px) rotate(0deg) scale(1);
        }
        50% {
            transform: translateY(-8px) rotate(10deg) scale(1.1);
        }
    }

    @keyframes challengeIconBounce {
        0%, 100% {
            transform: rotate(0deg) scale(1);
        }
        50% {
            transform: rotate(15deg) scale(1.1);
        }
    }

    @keyframes cardFocusGlow {
        0%, 100% {
            box-shadow: var(--shadow-soft);
        }
        50% {
            box-shadow: var(--shadow-growth);
        }
    }

    @keyframes targetReached {
        0% {
            background: rgba(16, 185, 129, 0.1);
        }
        50% {
            background: rgba(16, 185, 129, 0.2);
        }
        100% {
            background: transparent;
        }
    }

    /* Keyboard navigation enhancement */
    .keyboard-navigation *:focus {
        outline: 3px solid var(--leaf-green) !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
        border-radius: 8px !important;
    }

    /* Counting animation states */
    .counting {
        animation: countingPulse 0.5s ease-in-out;
    }

    @keyframes countingPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .count-completed {
        animation: countCompleted 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes countCompleted {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }
`;

document.head.appendChild(progressAnimationStyles);

// Utility functions for external use
window.ProgressAnimations = {
    showToast: (message, type, callback) => {
        const animations = new ProgressPageAnimations();
        return animations.showToast(message, type, callback);
    },
    createCelebrationBurst: (element) => {
        const animations = new ProgressPageAnimations();
        return animations.createCelebrationBurst(element);
    },
    createRippleEffect: (button, event) => {
        const animations = new ProgressPageAnimations();
        return animations.createRippleEffect(button, event);
    }
};

// Initialize progress page animations
document.addEventListener('DOMContentLoaded', () => {
    new ProgressPageAnimations();
    
    // Setup smooth scrolling
    const progressAnimations = new ProgressPageAnimations();
    progressAnimations.setupSmoothScrolling();
    progressAnimations.setupKeyboardNavigation();
});

// Handle page resize for responsive adjustments
window.addEventListener('resize', debounce(() => {
    // Recalculate progress ring sizes
    const progressRings = document.querySelectorAll('.progress-ring, .progress-circle');
    progressRings.forEach(ring => {
        const container = ring.parentElement;
        const containerWidth = container.offsetWidth;
        const newSize = Math.min(containerWidth * 0.6, 200);
        
        ring.setAttribute('width', newSize);
        ring.setAttribute('height', newSize);
        
        // Update circle positions
        const circles = ring.querySelectorAll('circle');
        circles.forEach(circle => {
            const radius = (newSize / 2) - 15;
            circle.setAttribute('cx', newSize / 2);
            circle.setAttribute('cy', newSize / 2);
            circle.setAttribute('r', radius);
        });
    });
}, 250));

// Debounce utility function
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

// Export for module usage
export { ProgressPageAnimations };

console.log('🌟 Progress page enhanced animations loaded successfully!');