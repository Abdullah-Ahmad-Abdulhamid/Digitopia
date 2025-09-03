// ===== Enhanced Skill Page JavaScript =====

class SkillPageAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.setupSkillAnimations();
        this.setupVideoPlayer();
        this.setupProgressSystem();
        this.setupInteractiveElements();
        this.setupParticleEffects();
        this.setupPerformanceOptimizations();
        console.log('🎓 Skill page animations initialized successfully!');
    }

    // Skill-specific animations
    setupSkillAnimations() {
        // Enhanced skill icon interaction
        const skillIcon = document.querySelector('.skill-icon-large');
        if (skillIcon) {
            skillIcon.addEventListener('click', () => {
                this.createSkillBurst(skillIcon);
            });

            skillIcon.addEventListener('mouseenter', () => {
                skillIcon.style.animation = 'skillIconExcitement 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            });
        }

        // Animated skill badges
        document.querySelectorAll('.skill-badge').forEach((badge, index) => {
            badge.style.animationDelay = `${index * 0.1}s`;
            
            badge.addEventListener('mouseenter', () => {
                badge.style.transform = 'translateY(-8px) scale(1.08)';
                this.createBadgeGlow(badge);
            });
            
            badge.addEventListener('mouseleave', () => {
                badge.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Learning elements interaction
        document.querySelectorAll('.learning-element').forEach(element => {
            element.addEventListener('click', () => {
                this.createLearningBurst(element);
            });
        });
    }

    // Enhanced video player
    setupVideoPlayer() {
        const playButton = document.querySelector('.play-button');
        const videoPlaceholder = document.getElementById('videoPlaceholder');
        const skillVideo = document.getElementById('skillVideo');
        const progressBar = document.getElementById('videoProgress');
        
        if (!playButton || !videoPlaceholder || !skillVideo) return;

        playButton.addEventListener('click', () => {
            this.playVideo();
        });

        // Video progress tracking
        if (skillVideo) {
            skillVideo.addEventListener('timeupdate', () => {
                if (skillVideo.duration && progressBar) {
                    const progress = (skillVideo.currentTime / skillVideo.duration) * 100;
                    progressBar.style.width = `${progress}%`;
                }
            });

            skillVideo.addEventListener('ended', () => {
                this.onVideoComplete();
            });

            skillVideo.addEventListener('play', () => {
                this.onVideoStart();
            });
        }

        // Custom video controls
        this.setupCustomVideoControls();
    }

    playVideo() {
        const placeholder = document.getElementById('videoPlaceholder');
        const video = document.getElementById('skillVideo');
        
        if (placeholder && video) {
            // Add loading state
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'video-loading';
            loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
            placeholder.appendChild(loadingIndicator);

            setTimeout(() => {
                placeholder.style.display = 'none';
                video.style.display = 'block';
                video.play();
                
                // Show success toast
                this.showToast('بدأ تشغيل الفيديو التعليمي! 🎬', 'success');
                
                // Track video start
                this.trackVideoStart();
            }, 1000);
        }
    }

    setupCustomVideoControls() {
        const progressTrack = document.querySelector('.progress-track');
        const progressThumb = document.getElementById('progressThumb');
        
        if (progressTrack && progressThumb) {
            progressTrack.addEventListener('click', (e) => {
                const rect = progressTrack.getBoundingClientRect();
                const percent = (e.clientX - rect.left) / rect.width;
                
                const video = document.getElementById('skillVideo');
                if (video && video.duration) {
                    video.currentTime = percent * video.duration;
                }
            });
        }
    }

    onVideoStart() {
        console.log('Video started');
        // Track video engagement
        if (typeof gtag !== 'undefined') {
            gtag('event', 'video_start', {
                'skill_id': window.skillId || 'unknown'
            });
        }
    }

    onVideoComplete() {
        this.showToast('تم الانتهاء من الفيديو! ممتاز! 🎉', 'success');
        
        // Auto-update progress if user is logged in
        if (window.isLoggedIn) {
            setTimeout(() => {
                if (typeof updateProgress === 'function') {
                    updateProgress(25); // Video completion = 25% progress
                }
            }, 1000);
        }
    }

    trackVideoStart() {
        // API call to track video start
        fetch('api/skills.php?action=track-video', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                skill_id: window.skillId || 1,
                action: 'video_start'
            })
        }).catch(error => {
            console.error('Video tracking error:', error);
        });
    }

    // Enhanced progress system
    setupProgressSystem() {
        this.initProgressCircle();
        this.setupMilestones();
        this.setupRatingSystem();
    }

    initProgressCircle() {
        const progressCircle = document.querySelector('.progress-circle');
        if (!progressCircle) return;

        const progress = parseInt(progressCircle.dataset.progress || '0');
        const radius = 86;
        const circumference = 2 * Math.PI * radius;

        // Create enhanced SVG with gradient
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('class', 'progress-svg');
        svg.setAttribute('width', '180');
        svg.setAttribute('height', '180');
        svg.setAttribute('viewBox', '0 0 180 180');

        // Create gradient definition
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
        gradient.setAttribute('id', 'progressGradient');
        gradient.setAttribute('x1', '0%');
        gradient.setAttribute('y1', '0%');
        gradient.setAttribute('x2', '100%');
        gradient.setAttribute('y2', '100%');

        const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop1.setAttribute('offset', '0%');
        stop1.setAttribute('style', 'stop-color:#10b981;stop-opacity:1');

        const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop2.setAttribute('offset', '100%');
        stop2.setAttribute('style', 'stop-color:#34d399;stop-opacity:1');

        gradient.appendChild(stop1);
        gradient.appendChild(stop2);
        defs.appendChild(gradient);
        svg.appendChild(defs);

        // Background circle
        const backgroundCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        backgroundCircle.setAttribute('class', 'progress-background');
        backgroundCircle.setAttribute('cx', '90');
        backgroundCircle.setAttribute('cy', '90');
        backgroundCircle.setAttribute('r', radius);

        // Foreground circle
        const foregroundCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        foregroundCircle.setAttribute('class', 'progress-foreground');
        foregroundCircle.setAttribute('cx', '90');
        foregroundCircle.setAttribute('cy', '90');
        foregroundCircle.setAttribute('r', radius);
        foregroundCircle.setAttribute('stroke', 'url(#progressGradient)');
        foregroundCircle.setAttribute('stroke-dasharray', circumference);
        foregroundCircle.setAttribute('stroke-dashoffset', circumference);

        svg.appendChild(backgroundCircle);
        svg.appendChild(foregroundCircle);
        progressCircle.insertBefore(svg, progressCircle.firstChild);

        // Animate when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        const offset = circumference - (progress / 100) * circumference;
                        foregroundCircle.style.strokeDashoffset = offset;
                        
                        // Animate counter
                        const counter = progressCircle.querySelector('.progress-text');
                        if (counter) {
                            this.animateCounter(counter, 0, progress, 2000);
                        }
                    }, 500);
                    observer.unobserve(entry.target);
                }
            });
        });

        observer.observe(progressCircle);
    }

    setupMilestones() {
        document.querySelectorAll('.milestone').forEach((milestone, index) => {
            milestone.addEventListener('mouseenter', () => {
                milestone.style.transform = 'translateY(-8px) scale(1.05)';
                this.createMilestoneGlow(milestone);
            });
            
            milestone.addEventListener('mouseleave', () => {
                milestone.style.transform = 'translateY(0) scale(1)';
            });

            milestone.addEventListener('click', () => {
                const progress = parseInt(milestone.dataset.progress);
                this.animateMilestoneClick(milestone);
                
                if (typeof updateProgress === 'function') {
                    updateProgress(progress);
                }
            });

            // Staggered entrance animation
            milestone.style.opacity = '0';
            milestone.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                milestone.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                milestone.style.opacity = '1';
                milestone.style.transform = 'translateY(0)';
            }, 200 + (index * 100));
        });
    }

    setupRatingSystem() {
        const ratingStars = document.querySelectorAll('.rating-star');
        if (ratingStars.length === 0) return;

        ratingStars.forEach((star, index) => {
            star.addEventListener('mouseenter', () => {
                this.highlightStars(index);
                this.createStarSparkles(star);
            });
            
            star.addEventListener('mouseleave', () => {
                this.resetStars();
            });
            
            star.addEventListener('click', () => {
                this.setRating(index + 1);
                this.createRatingSuccess(star);
            });
        });
    }

    // Interactive elements
    setupInteractiveElements() {
        // Enhanced sidebar cards
        document.querySelectorAll('.related-skill-item, .action-btn, .tip-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                this.createHoverEffect(item);
            });
            
            item.addEventListener('click', () => {
                this.createClickEffect(item);
            });
        });

        // Enhanced button interactions
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.createButtonRipple(btn, e);
                
                // Loading state for async operations
                if (btn.dataset.async === 'true') {
                    this.showButtonLoading(btn);
                }
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            this.handleKeyboardNavigation(e);
        });
    }

    // Particle effects
    setupParticleEffects() {
        this.createFloatingParticles();
        this.setupAmbientAnimations();
    }

    createFloatingParticles() {
        const hero = document.querySelector('.growth-particles');
        if (!hero) return;

        // Create learning-themed particles
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'learning-particle';
            
            const icons = ['🌱', '💡', '📚', '🎯', '⭐', '🚀'];
            const randomIcon = icons[Math.floor(Math.random() * icons.length)];
            
            particle.style.cssText = `
                position: absolute;
                font-size: 1.2rem;
                left: ${Math.random() * 100}%;
                top: ${100 + Math.random() * 20}%;
                animation: learningParticleFloat ${15 + Math.random() * 10}s infinite linear;
                animation-delay: ${Math.random() * 10}s;
                opacity: 0.7;
                pointer-events: none;
                z-index: 1;
            `;
            
            particle.textContent = randomIcon;
            hero.appendChild(particle);
        }

        // Add particle animation
        if (!document.querySelector('#learning-particle-styles')) {
            const particleStyles = document.createElement('style');
            particleStyles.id = 'learning-particle-styles';
            particleStyles.textContent = `
                @keyframes learningParticleFloat {
                    0% {
                        transform: translateY(0px) rotate(0deg);
                        opacity: 0;
                    }
                    10% {
                        opacity: 0.7;
                    }
                    90% {
                        opacity: 0.3;
                    }
                    100% {
                        transform: translateY(-100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(particleStyles);
        }
    }

    setupAmbientAnimations() {
        // Breathing animation for important elements
        const breathingElements = document.querySelectorAll('.skill-icon-large, .premium-icon, .play-button');
        breathingElements.forEach(element => {
            element.style.animation += ', elementBreathe 4s ease-in-out infinite';
        });

        // Add breathing animation
        if (!document.querySelector('#breathing-styles')) {
            const breathingStyles = document.createElement('style');
            breathingStyles.id = 'breathing-styles';
            breathingStyles.textContent = `
                @keyframes elementBreathe {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.02); }
                }
                @keyframes skillIconExcitement {
                    0% { transform: scale(1) rotate(0deg); }
                    25% { transform: scale(1.1) rotate(5deg); }
                    50% { transform: scale(1.2) rotate(10deg); }
                    75% { transform: scale(1.1) rotate(5deg); }
                    100% { transform: scale(1) rotate(0deg); }
                }
            `;
            document.head.appendChild(breathingStyles);
        }
    }

    // Performance optimizations
    setupPerformanceOptimizations() {
        // Intersection observer for expensive animations
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

        // Observe heavy animation elements
        document.querySelectorAll('.learning-element, .skill-icon-large, .premium-icon').forEach(el => {
            expensiveAnimationObserver.observe(el);
        });

        // Reduce animations on low-end devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-fast', '0.1s');
            document.documentElement.style.setProperty('--animation-normal', '0.2s');
            document.documentElement.style.setProperty('--animation-slow', '0.3s');
        }

        // Pause animations when page is hidden
        document.addEventListener('visibilitychange', () => {
            const isHidden = document.hidden;
            const animatedElements = document.querySelectorAll('[class*="animate"], [style*="animation"]');
            
            animatedElements.forEach(element => {
                if (isHidden) {
                    element.style.animationPlayState = 'paused';
                } else {
                    element.style.animationPlayState = 'running';
                }
            });
        });
    }

    // Utility functions
    createSkillBurst(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        for (let i = 0; i < 12; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 8px;
                height: 8px;
                background: #fbbf24;
                border-radius: 50%;
                left: ${centerX}px;
                top: ${centerY}px;
                pointer-events: none;
                z-index: 1000;
                animation: skillBurstParticle 1s ease-out forwards;
            `;
            
            const angle = (Math.PI * 2 * i) / 12;
            const distance = 60 + Math.random() * 40;
            
            particle.style.setProperty('--end-x', Math.cos(angle) * distance + 'px');
            particle.style.setProperty('--end-y', Math.sin(angle) * distance + 'px');
            
            document.body.appendChild(particle);
            
            setTimeout(() => particle.remove(), 1000);
        }

        // Add burst animation
        this.addDynamicStyle('skillBurstParticle', `
            @keyframes skillBurstParticle {
                0% { 
                    opacity: 1; 
                    transform: translate(-50%, -50%) scale(1); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(calc(-50% + var(--end-x)), calc(-50% + var(--end-y))) scale(0); 
                }
            }
        `);
    }

    createBadgeGlow(badge) {
        const glow = document.createElement('div');
        glow.style.cssText = `
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #10b981, #f97316);
            border-radius: 30px;
            opacity: 0.3;
            z-index: -1;
            filter: blur(8px);
            animation: badgeGlowPulse 2s ease-in-out infinite;
        `;
        
        badge.style.position = 'relative';
        badge.appendChild(glow);
        
        setTimeout(() => {
            if (glow.parentNode) {
                glow.parentNode.removeChild(glow);
            }
        }, 2000);
    }

    createLearningBurst(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Create knowledge particles
        const symbols = ['💡', '🎯', '⭐', '🚀', '🌟', '✨'];
        
        for (let i = 0; i < 6; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                font-size: 1.5rem;
                left: ${centerX}px;
                top: ${centerY}px;
                pointer-events: none;
                z-index: 1000;
                animation: knowledgeParticle 1.5s ease-out forwards;
            `;
            
            particle.textContent = symbols[i];
            
            const angle = (Math.PI * 2 * i) / 6;
            const distance = 80 + Math.random() * 60;
            
            particle.style.setProperty('--end-x', Math.cos(angle) * distance + 'px');
            particle.style.setProperty('--end-y', Math.sin(angle) * distance + 'px');
            
            document.body.appendChild(particle);
            
            setTimeout(() => particle.remove(), 1500);
        }

        this.addDynamicStyle('knowledgeParticle', `
            @keyframes knowledgeParticle {
                0% { 
                    opacity: 1; 
                    transform: translate(-50%, -50%) scale(1) rotate(0deg); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(calc(-50% + var(--end-x)), calc(-50% + var(--end-y))) scale(0.3) rotate(360deg); 
                }
            }
        `);
    }

    createMilestoneGlow(milestone) {
        const glow = document.createElement('div');
        glow.style.cssText = `
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 23px;
            opacity: 0.3;
            z-index: -1;
            filter: blur(6px);
            animation: milestoneGlowPulse 1.5s ease-in-out infinite;
        `;
        
        milestone.style.position = 'relative';
        milestone.appendChild(glow);
        
        setTimeout(() => {
            if (glow.parentNode) {
                glow.parentNode.removeChild(glow);
            }
        }, 1500);
    }

    animateMilestoneClick(milestone) {
        milestone.style.transform = 'scale(0.95)';
        setTimeout(() => {
            milestone.style.transform = 'translateY(-8px) scale(1.05)';
        }, 150);
        
        // Create completion effect if it's a completed milestone
        if (milestone.classList.contains('completed')) {
            this.createCompletionEffect(milestone);
        }
    }

    createCompletionEffect(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        for (let i = 0; i < 8; i++) {
            const star = document.createElement('div');
            star.style.cssText = `
                position: fixed;
                font-size: 1.2rem;
                left: ${centerX}px;
                top: ${centerY}px;
                pointer-events: none;
                z-index: 1000;
                animation: completionStar 1.2s ease-out forwards;
            `;
            
            star.textContent = '⭐';
            
            const angle = (Math.PI * 2 * i) / 8;
            const distance = 50 + Math.random() * 30;
            
            star.style.setProperty('--end-x', Math.cos(angle) * distance + 'px');
            star.style.setProperty('--end-y', Math.sin(angle) * distance + 'px');
            
            document.body.appendChild(star);
            
            setTimeout(() => star.remove(), 1200);
        }

        this.addDynamicStyle('completionStar', `
            @keyframes completionStar {
                0% { 
                    opacity: 1; 
                    transform: translate(-50%, -50%) scale(0) rotate(0deg); 
                }
                50% { 
                    opacity: 1; 
                    transform: translate(calc(-50% + var(--end-x)), calc(-50% + var(--end-y))) scale(1.2) rotate(180deg); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(calc(-50% + var(--end-x)), calc(-50% + var(--end-y))) scale(0) rotate(360deg); 
                }
            }
        `);
    }

    highlightStars(index) {
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, i) => {
            if (i <= index) {
                star.style.color = '#fbbf24';
                star.style.transform = 'scale(1.3)';
                star.style.filter = 'drop-shadow(0 0 10px rgba(251, 191, 36, 0.8))';
            } else {
                star.style.color = '#e2e8f0';
                star.style.transform = 'scale(1)';
                star.style.filter = 'none';
            }
        });
    }

    resetStars() {
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            if (!star.classList.contains('active')) {
                star.style.color = '#e2e8f0';
                star.style.transform = 'scale(1)';
                star.style.filter = 'none';
            }
        });
    }

    setRating(rating) {
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
                star.style.color = '#fbbf24';
            } else {
                star.classList.remove('active');
                star.style.color = '#e2e8f0';
            }
        });
    }

    createStarSparkles(star) {
        const rect = star.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        for (let i = 0; i < 5; i++) {
            const sparkle = document.createElement('div');
            sparkle.style.cssText = `
                position: fixed;
                width: 4px;
                height: 4px;
                background: #fbbf24;
                border-radius: 50%;
                left: ${centerX + (Math.random() - 0.5) * 40}px;
                top: ${centerY + (Math.random() - 0.5) * 40}px;
                pointer-events: none;
                z-index: 1000;
                animation: starSparkle 0.8s ease-out forwards;
            `;
            
            document.body.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
        }

        this.addDynamicStyle('starSparkle', `
            @keyframes starSparkle {
                0% { opacity: 1; transform: scale(0); }
                50% { opacity: 1; transform: scale(1.2); }
                100% { opacity: 0; transform: scale(0); }
            }
        `);
    }

    createRatingSuccess(star) {
        const rect = star.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Create success wave
        const wave = document.createElement('div');
        wave.style.cssText = `
            position: fixed;
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.6) 0%, transparent 70%);
            border-radius: 50%;
            left: ${centerX}px;
            top: ${centerY}px;
            pointer-events: none;
            z-index: 999;
            animation: ratingSuccessWave 0.8s ease-out forwards;
        `;
        
        document.body.appendChild(wave);
        setTimeout(() => wave.remove(), 800);

        this.addDynamicStyle('ratingSuccessWave', `
            @keyframes ratingSuccessWave {
                0% { 
                    opacity: 0.8; 
                    transform: translate(-50%, -50%) scale(0); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(-50%, -50%) scale(15); 
                }
            }
        `);
    }

    createHoverEffect(element) {
        const rect = element.getBoundingClientRect();
        
        // Create hover particles
        for (let i = 0; i < 3; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 6px;
                height: 6px;
                background: linear-gradient(45deg, #10b981, #f97316);
                border-radius: 50%;
                left: ${rect.right - 20 + Math.random() * 20}px;
                top: ${rect.top + rect.height / 2 + (Math.random() - 0.5) * 20}px;
                pointer-events: none;
                z-index: 999;
                animation: hoverParticle 1s ease-out forwards;
            `;
            
            document.body.appendChild(particle);
            setTimeout(() => particle.remove(), 1000);
        }

        this.addDynamicStyle('hoverParticle', `
            @keyframes hoverParticle {
                0% { opacity: 0.8; transform: scale(1) translateX(0); }
                100% { opacity: 0; transform: scale(0.3) translateX(30px); }
            }
        `);
    }

    createClickEffect(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Create click ripple
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: fixed;
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            left: ${centerX}px;
            top: ${centerY}px;
            pointer-events: none;
            z-index: 999;
            animation: clickRipple 0.6s ease-out forwards;
        `;
        
        document.body.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);

        this.addDynamicStyle('clickRipple', `
            @keyframes clickRipple {
                0% { 
                    opacity: 0.6; 
                    transform: translate(-50%, -50%) scale(0); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(-50%, -50%) scale(10); 
                }
            }
        `);
    }

    createButtonRipple(button, event) {
        const rect = button.getBoundingClientRect();
        const x = (event.clientX || rect.left + rect.width / 2) - rect.left;
        const y = (event.clientY || rect.top + rect.height / 2) - rect.top;

        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            left: ${x - 10}px;
            top: ${y - 10}px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.6) 0%, transparent 70%);
            transform: scale(0);
            animation: buttonRipple 0.6s ease-out forwards;
            pointer-events: none;
            z-index: 100;
        `;
        
        button.style.position = 'relative';
        button.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);

        this.addDynamicStyle('buttonRipple', `
            @keyframes buttonRipple {
                0% { transform: scale(0); opacity: 0.8; }
                100% { transform: scale(4); opacity: 0; }
            }
        `);
    }

    showButtonLoading(button) {
        const originalContent = button.innerHTML;
        const originalClass = button.className;
        
        button.classList.add('loading');
        button.innerHTML = '<i class="fas fa-seedling fa-spin me-2"></i>جاري المعالجة...';
        
        // Reset after timeout
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.className = originalClass;
        }, 3000);
    }

    handleKeyboardNavigation(e) {
        // Enhanced keyboard navigation
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
            setTimeout(() => {
                document.body.classList.remove('keyboard-navigation');
            }, 3000);
        }

        // Arrow key navigation for milestones
        if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
            const milestones = Array.from(document.querySelectorAll('.milestone'));
            const currentIndex = milestones.indexOf(document.activeElement);
            
            if (currentIndex !== -1) {
                e.preventDefault();
                let nextIndex;
                
                if (e.key === 'ArrowRight') {
                    nextIndex = (currentIndex + 1) % milestones.length;
                } else {
                    nextIndex = currentIndex > 0 ? currentIndex - 1 : milestones.length - 1;
                }
                
                milestones[nextIndex].focus();
                this.createFocusGlow(milestones[nextIndex]);
            }
        }

        // Enter/Space to activate focused elements
        if (e.key === 'Enter' || e.key === ' ') {
            const focused = document.activeElement;
            if (focused.classList.contains('milestone')) {
                e.preventDefault();
                focused.click();
            }
        }
    }

    createFocusGlow(element) {
        const glow = document.createElement('div');
        glow.style.cssText = `
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid #10b981;
            border-radius: 22px;
            opacity: 0.6;
            z-index: -1;
            animation: focusGlow 0.3s ease-out;
        `;
        
        element.style.position = 'relative';
        element.appendChild(glow);
        
        setTimeout(() => {
            if (glow.parentNode) {
                glow.parentNode.removeChild(glow);
            }
        }, 300);
    }

    // Enhanced counter animation
    animateCounter(element, start, end, duration) {
        if (!element) return;

        const startTime = Date.now();
        const range = end - start;

        const updateCounter = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (range * easeOutCubic));
            
            element.textContent = current + '%';
            
            // Add number change effect
            if (progress < 1) {
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 50);
                
                requestAnimationFrame(updateCounter);
            } else {
                // Final animation
                element.classList.add('counter-complete');
                this.createCounterCompleteEffect(element);
            }
        };

        updateCounter();
    }

    createCounterCompleteEffect(element) {
        const rect = element.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Create number completion effect
        const effect = document.createElement('div');
        effect.style.cssText = `
            position: fixed;
            left: ${centerX}px;
            top: ${centerY}px;
            font-size: 2rem;
            color: #10b981;
            pointer-events: none;
            z-index: 1000;
            animation: numberComplete 1s ease-out forwards;
            font-weight: 900;
        `;
        
        effect.textContent = '✓';
        document.body.appendChild(effect);
        
        setTimeout(() => effect.remove(), 1000);

        this.addDynamicStyle('numberComplete', `
            @keyframes numberComplete {
                0% { 
                    opacity: 0; 
                    transform: translate(-50%, -50%) scale(0) rotate(0deg); 
                }
                50% { 
                    opacity: 1; 
                    transform: translate(-50%, -50%) scale(1.3) rotate(180deg); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(-50%, -50%) scale(0) rotate(360deg); 
                }
            }
        `);
    }

    // Enhanced toast notification
    showToast(message, type = 'info', duration = 4000) {
        // Remove existing toasts
        document.querySelectorAll('.skill-toast').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `skill-toast toast-${type}`;

        const colors = {
            success: 'linear-gradient(135deg, #10b981, #34d399)',
            error: 'linear-gradient(135deg, #ef4444, #f87171)',
            warning: 'linear-gradient(135deg, #f59e0b, #fbbf24)',
            info: 'linear-gradient(135deg, #3b82f6, #60a5fa)'
        };

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        toast.innerHTML = `
            <div class="toast-content">
                <i class="${icons[type]} toast-icon"></i>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-progress"></div>
        `;

        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${colors[type]};
            color: white;
            padding: 1.2rem 1.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(400px) scale(0.8);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 400px;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
        `;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0) scale(1)';
        }, 100);

        // Auto remove
        const autoRemove = setTimeout(() => {
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 500);
        }, duration);

        // Click to dismiss
        toast.addEventListener('click', () => {
            clearTimeout(autoRemove);
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 500);
        });

        return toast;
    }

    // Utility function to add dynamic styles
    addDynamicStyle(name, css) {
        if (document.querySelector(`#dynamic-style-${name}`)) return;
        
        const style = document.createElement('style');
        style.id = `dynamic-style-${name}`;
        style.textContent = css;
        document.head.appendChild(style);
    }

    // Public methods
    updateMilestones(percentage) {
        document.querySelectorAll('.milestone').forEach(milestone => {
            const milestoneProgress = parseInt(milestone.dataset.progress);
            if (percentage >= milestoneProgress) {
                milestone.classList.add('completed');
                this.createCompletionEffect(milestone);
            } else {
                milestone.classList.remove('completed');
            }
        });
    }
}

// Initialize skill page animations
let skillPageAnimations;

document.addEventListener('DOMContentLoaded', () => {
    skillPageAnimations = new SkillPageAnimations();
});

// Global functions for PHP integration
function initProgressCircle() {
    if (skillPageAnimations) {
        skillPageAnimations.initProgressCircle();
    }
}

function initProgressTracking() {
    if (skillPageAnimations) {
        skillPageAnimations.setupProgressSystem();
    }
}

function initRatingSystem() {
    if (skillPageAnimations) {
        skillPageAnimations.setupRatingSystem();
    }
}

function updateMilestones(percentage) {
    if (skillPageAnimations) {
        skillPageAnimations.updateMilestones(percentage);
    }
}

function showToast(message, type, duration) {
    if (skillPageAnimations) {
        return skillPageAnimations.showToast(message, type, duration);
    } else {
        // Fallback for early calls
        console.log(`Toast: ${message} (${type})`);
    }
}

// Enhanced video functionality
function initVideoPlayer() {
    const video = document.getElementById('skillVideo');
    if (video) {
        // Add custom video event listeners
        video.addEventListener('loadstart', () => {
            console.log('Video loading started');
        });

        video.addEventListener('canplay', () => {
            console.log('Video can start playing');
        });

        video.addEventListener('progress', () => {
            // Update buffer progress if needed
            const buffered = video.buffered;
            if (buffered.length > 0) {
                const bufferPercent = (buffered.end(0) / video.duration) * 100;
                console.log(`Video buffered: ${bufferPercent}%`);
            }
        });
    }
}

// Export for external use
window.SkillPageAnimations = SkillPageAnimations;
window.skillPageInstance = skillPageAnimations;

// Additional utility functions
window.SkillPageUtils = {
    showToast: (message, type, duration) => {
        return showToast(message, type, duration);
    },
    
    createCelebration: (element) => {
        if (skillPageAnimations) {
            skillPageAnimations.createCompletionEffect(element);
        }
    },
    
    animateProgress: (percentage) => {
        const progressFill = document.getElementById('progressFill');
        const progressPercentage = document.getElementById('progressPercentage');
        
        if (progressFill) {
            progressFill.style.width = percentage + '%';
            progressFill.classList.add('progress-updating');
            setTimeout(() => {
                progressFill.classList.remove('progress-updating');
            }, 1000);
        }
        
        if (progressPercentage && skillPageAnimations) {
            skillPageAnimations.animateCounter(progressPercentage, 
                parseInt(progressPercentage.textContent) || 0, 
                percentage, 
                1000);
        }
        
        updateMilestones(percentage);
    }
};

// Handle page visibility for performance
document.addEventListener('visibilitychange', () => {
    const video = document.getElementById('skillVideo');
    if (video && document.hidden) {
        video.pause();
    }
});

// Add toast styles
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    .toast-content {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    
    .toast-icon {
        margin-left: 0.8rem;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .toast-message {
        flex: 1;
        line-height: 1.4;
        margin: 0 0.8rem;
    }
    
    .toast-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        cursor: pointer;
        padding: 0.2rem;
        border-radius: 4px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    
    .toast-close:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        width: 100%;
        transform-origin: left;
        animation: toastProgress var(--duration, 4000ms) linear forwards;
    }
    
    @keyframes toastProgress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
    
    .keyboard-navigation *:focus {
        outline: 3px solid #10b981 !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
    }
    
    .counter-complete {
        animation: counterSuccess 0.5s ease-out;
    }
    
    @keyframes counterSuccess {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    
    @keyframes badgeGlowPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }
    
    @keyframes milestoneGlowPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }
`;

document.head.appendChild(toastStyles);