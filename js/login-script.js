// ===== Modern Login Page Animations and Interactions =====

class LoginPageManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupAOS();
        this.setupFormValidation();
        this.setupPasswordToggle();
        this.setupFormAnimations();
        this.setupGrowthEffects();
        this.setupLoadingStates();
        this.setupAccessibility();
        this.setupPerformanceOptimizations();
        console.log('🌱 Login page initialized successfully!');
    }

    // Initialize AOS animations
    setupAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                once: true,
                offset: 120,
                disable: function() {
                    return window.innerWidth < 768;
                }
            });
        }
    }

    // Enhanced form validation with growth feedback
    setupFormValidation() {
        const form = document.querySelector('.modern-form');
        const inputs = form.querySelectorAll('.modern-input');
        const submitBtn = document.getElementById('loginBtn');

        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.validateField(e.target);
                this.updateSubmitButton();
            });

            input.addEventListener('blur', (e) => {
                this.validateField(e.target, true);
            });

            input.addEventListener('focus', (e) => {
                this.clearFieldError(e.target);
                this.addFocusEffect(e.target);
            });
        });

        // Form submission with enhanced UX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let isValid = true;
            inputs.forEach(input => {
                if (!this.validateField(input, true)) {
                    isValid = false;
                }
            });

            if (isValid) {
                this.showLoadingState();
                // Submit the form after a short delay for UX
                setTimeout(() => {
                    e.target.submit();
                }, 300);
            } else {
                this.showValidationError();
            }
        });
    }

    // Field validation with growth-themed feedback
    validateField(field, showError = false) {
        const value = field.value.trim();
        const fieldType = field.type;
        const wrapper = field.closest('.input-wrapper');
        let isValid = true;
        let errorMessage = '';

        // Remove existing errors
        this.clearFieldError(field);

        // Validation logic
        switch (fieldType) {
            case 'email':
                if (!value) {
                    isValid = false;
                    errorMessage = 'البريد الإلكتروني مطلوب';
                } else if (!this.isValidEmail(value)) {
                    isValid = false;
                    errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
                }
                break;
            case 'password':
                if (!value) {
                    isValid = false;
                    errorMessage = 'كلمة المرور مطلوبة';
                } else if (value.length < 6) {
                    isValid = false;
                    errorMessage = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
                }
                break;
        }

        // Show error if validation failed and showError is true
        if (!isValid && showError) {
            this.showFieldError(field, errorMessage);
        }

        // Add success state if valid
        if (isValid && value) {
            this.showFieldSuccess(field);
        }

        return isValid;
    }

    // Email validation
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show field error with growth animation
    showFieldError(field, message) {
        const wrapper = field.closest('.input-wrapper');
        field.classList.add('error');
        
        // Create error message element
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
        
        wrapper.appendChild(errorElement);
        
        // Animate error appearance
        setTimeout(() => {
            errorElement.style.opacity = '1';
            errorElement.style.transform = 'translateY(0)';
        }, 10);

        // Shake animation
        field.style.animation = 'fieldShake 0.5s ease-in-out';
        setTimeout(() => {
            field.style.animation = '';
        }, 500);
    }

    // Show field success
    showFieldSuccess(field) {
        field.classList.add('success');
        field.classList.remove('error');
        
        // Add success icon
        const wrapper = field.closest('.input-wrapper');
        let successIcon = wrapper.querySelector('.success-icon');
        
        if (!successIcon) {
            successIcon = document.createElement('div');
            successIcon.className = 'success-icon';
            successIcon.innerHTML = '<i class="fas fa-check"></i>';
            wrapper.appendChild(successIcon);
        }
        
        successIcon.style.animation = 'successAppear 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
    }

    // Clear field error
    clearFieldError(field) {
        const wrapper = field.closest('.input-wrapper');
        const errorElement = wrapper.querySelector('.field-error');
        const successIcon = wrapper.querySelector('.success-icon');
        
        field.classList.remove('error', 'success');
        
        if (errorElement) {
            errorElement.remove();
        }
        
        if (successIcon) {
            successIcon.remove();
        }
    }

    // Update submit button state
    updateSubmitButton() {
        const inputs = document.querySelectorAll('.modern-input');
        const submitBtn = document.getElementById('loginBtn');
        let allValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                allValid = false;
            }
        });

        if (allValid) {
            submitBtn.classList.add('ready');
            submitBtn.disabled = false;
        } else {
            submitBtn.classList.remove('ready');
            submitBtn.disabled = true;
        }
    }

    // Password toggle functionality
    setupPasswordToggle() {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                const icon = toggleBtn.querySelector('i');
                
                // Toggle password visibility
                passwordInput.type = isPassword ? 'text' : 'password';
                
                // Update icon with animation
                icon.style.animation = 'iconFlip 0.3s ease-in-out';
                
                setTimeout(() => {
                    icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
                    icon.style.animation = '';
                }, 150);

                // Add ripple effect
                this.createRippleEffect(toggleBtn);
            });
        }
    }

    // Enhanced form animations
    setupFormAnimations() {
        const inputs = document.querySelectorAll('.modern-input');
        
        inputs.forEach(input => {
            // Focus effects
            input.addEventListener('focus', () => {
                this.addFocusEffect(input);
            });

            input.addEventListener('blur', () => {
                this.removeFocusEffect(input);
            });

            // Typing animation
            input.addEventListener('input', () => {
                this.addTypingEffect(input);
            });
        });

        // Checkbox animations
        const checkbox = document.querySelector('.modern-checkbox');
        if (checkbox) {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    this.createGrowthBurst(checkbox);
                }
            });
        }
    }

    // Add focus effect to input
    addFocusEffect(input) {
        const wrapper = input.closest('.input-wrapper');
        const focusLine = wrapper.querySelector('.input-focus-line');
        
        input.style.transform = 'translateY(-2px)';
        input.style.boxShadow = '0 8px 25px rgba(16, 185, 129, 0.15)';
        
        if (focusLine) {
            focusLine.style.width = '100%';
        }
    }

    // Remove focus effect
    removeFocusEffect(input) {
        if (!input.classList.contains('error')) {
            input.style.transform = '';
            input.style.boxShadow = '';
        }
        
        const wrapper = input.closest('.input-wrapper');
        const focusLine = wrapper.querySelector('.input-focus-line');
        
        if (focusLine && !input.value.trim()) {
            focusLine.style.width = '0';
        }
    }

    // Typing effect
    addTypingEffect(input) {
        input.style.backgroundColor = 'rgba(16, 185, 129, 0.02)';
        
        clearTimeout(input.typingTimer);
        input.typingTimer = setTimeout(() => {
            input.style.backgroundColor = '';
        }, 1000);
    }

    // Growth effects for interactions
    setupGrowthEffects() {
        // Skill leaf hover effects
        document.querySelectorAll('.skill-leaf').forEach(leaf => {
            leaf.addEventListener('mouseenter', () => {
                leaf.style.transform = 'scale(1.15) rotate(10deg)';
                this.showSkillTooltip(leaf);
            });

            leaf.addEventListener('mouseleave', () => {
                leaf.style.transform = '';
                this.hideSkillTooltip();
            });
        });

        // Stat items interaction
        document.querySelectorAll('.stat-item').forEach(stat => {
            stat.addEventListener('mouseenter', () => {
                stat.style.transform = 'translateY(-8px) scale(1.05)';
                this.animateStatNumber(stat);
            });

            stat.addEventListener('mouseleave', () => {
                stat.style.transform = '';
            });
        });

        // Brand icon interaction
        const brandIcon = document.querySelector('.brand-icon-wrapper');
        if (brandIcon) {
            brandIcon.addEventListener('click', () => {
                this.createCelebrationEffect(brandIcon);
            });
        }
    }

    // Show skill tooltip
    showSkillTooltip(leaf) {
        const skill = leaf.getAttribute('data-skill');
        const tooltip = document.createElement('div');
        tooltip.className = 'skill-tooltip';
        tooltip.textContent = skill;
        tooltip.style.cssText = `
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            z-index: 1000;
            animation: tooltipAppear 0.3s ease-out;
        `;
        
        leaf.appendChild(tooltip);
    }

    // Hide skill tooltip
    hideSkillTooltip() {
        const tooltip = document.querySelector('.skill-tooltip');
        if (tooltip) {
            tooltip.style.animation = 'tooltipDisappear 0.2s ease-in';
            setTimeout(() => tooltip.remove(), 200);
        }
    }

    // Animate stat numbers
    animateStatNumber(statElement) {
        const numberElement = statElement.querySelector('.stat-number');
        if (numberElement && !numberElement.classList.contains('animated')) {
            numberElement.classList.add('animated');
            numberElement.style.animation = 'statNumberGrow 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        }
    }

    // Create growth burst effect
    createGrowthBurst(element) {
        for (let i = 0; i < 6; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: var(--primary-gradient);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: growthParticle 0.8s ease-out forwards;
                `;

                const rect = element.getBoundingClientRect();
                particle.style.left = (rect.left + rect.width / 2) + 'px';
                particle.style.top = (rect.top + rect.height / 2) + 'px';

                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 800);
            }, i * 50);
        }
    }

    // Create celebration effect
    createCelebrationEffect(element) {
        for (let i = 0; i < 12; i++) {
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
                    animation: celebrationParticle 1.2s ease-out forwards;
                `;

                const rect = element.getBoundingClientRect();
                particle.style.left = (rect.left + rect.width / 2) + 'px';
                particle.style.top = (rect.top + rect.height / 2) + 'px';

                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 1200);
            }, i * 80);
        }
    }

    // Create ripple effect
    createRippleEffect(element) {
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            background: rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            animation: rippleGrow 0.6s ease-out;
            pointer-events: none;
            z-index: 100;
        `;

        element.style.position = 'relative';
        element.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }

    // Show validation error with shake effect
    showValidationError() {
        const form = document.querySelector('.modern-form');
        form.style.animation = 'formShake 0.6s ease-in-out';
        
        setTimeout(() => {
            form.style.animation = '';
        }, 600);

        // Show error toast
        this.showToast('يرجى التحقق من البيانات المدخلة', 'error');
    }

    // Loading state management
    setupLoadingStates() {
        const form = document.querySelector('.modern-form');
        const loadingOverlay = document.getElementById('loadingOverlay');

        // Show loading on form submission
        form.addEventListener('submit', (e) => {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                setTimeout(() => {
                    this.showLoadingState();
                }, 300);
            }
        });
    }

    // Show loading state
    showLoadingState() {
        const overlay = document.getElementById('loadingOverlay');
        const submitBtn = document.getElementById('loginBtn');
        
        // Show overlay
        overlay.classList.add('show');
        
        // Update button
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        const btnContent = submitBtn.querySelector('.btn-content');
        btnContent.innerHTML = `
            <i class="fas fa-seedling fa-spin me-2"></i>
            جاري تسجيل الدخول...
        `;
    }

    // Hide loading state
    hideLoadingState() {
        const overlay = document.getElementById('loadingOverlay');
        const submitBtn = document.getElementById('loginBtn');
        
        overlay.classList.remove('show');
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        const btnContent = submitBtn.querySelector('.btn-content');
        btnContent.innerHTML = `
            <i class="fas fa-sign-in-alt me-2"></i>
            ابدأ رحلة التطوير
        `;
    }

    // Enhanced accessibility
    setupAccessibility() {
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
                setTimeout(() => {
                    document.body.classList.remove('keyboard-navigation');
                }, 3000);
            }

            // Enter key on form elements
            if (e.key === 'Enter') {
                const focused = document.activeElement;
                if (focused.classList.contains('modern-input')) {
                    const form = focused.closest('form');
                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.click();
                    }
                }
            }

            // Escape key handling
            if (e.key === 'Escape') {
                this.hideLoadingState();
                this.hideAllTooltips();
            }
        });

        // Screen reader announcements
        this.setupAriaLive();
    }

    // Setup ARIA live region for announcements
    setupAriaLive() {
        const ariaLive = document.createElement('div');
        ariaLive.setAttribute('aria-live', 'polite');
        ariaLive.setAttribute('aria-atomic', 'true');
        ariaLive.className = 'sr-only';
        ariaLive.id = 'aria-announcements';
        document.body.appendChild(ariaLive);
    }

    // Announce to screen readers
    announceToScreenReader(message) {
        const ariaLive = document.getElementById('aria-announcements');
        if (ariaLive) {
            ariaLive.textContent = message;
            setTimeout(() => {
                ariaLive.textContent = '';
            }, 1000);
        }
    }

    // Performance optimizations
    setupPerformanceOptimizations() {
        // Intersection Observer for expensive animations
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-active');
                    entry.target.style.willChange = 'transform, opacity';
                } else {
                    entry.target.classList.remove('animate-active');
                    entry.target.style.willChange = 'auto';
                }
            });
        }, { threshold: 0.1 });

        // Observe expensive animation elements
        document.querySelectorAll('.growth-tree, .floating-element, .brand-icon-wrapper').forEach(el => {
            animationObserver.observe(el);
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
            const animatedElements = document.querySelectorAll('.floating-element, .growth-tree, .brand-icon-wrapper');
            
            animatedElements.forEach(element => {
                element.style.animationPlayState = isHidden ? 'paused' : 'running';
            });
        });
    }

    // Toast notification system
    showToast(message, type = 'info', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        
        const typeConfig = {
            success: { icon: 'fa-check-circle', gradient: 'var(--success-gradient)' },
            error: { icon: 'fa-exclamation-circle', gradient: 'var(--danger-gradient)' },
            warning: { icon: 'fa-exclamation-triangle', gradient: 'var(--warning-gradient)' },
            info: { icon: 'fa-info-circle', gradient: 'var(--accent-gradient)' }
        };

        const config = typeConfig[type] || typeConfig.info;

        toast.style.cssText = `
            position: fixed;
            top: 30px;
            right: 30px;
            background: ${config.gradient};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            z-index: 10000;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            max-width: 350px;
            font-weight: 600;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        `;

        toast.innerHTML = `
            <i class="fas ${config.icon}"></i>
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
        }, duration);

        return toast;
    }

    // Hide all tooltips
    hideAllTooltips() {
        document.querySelectorAll('.skill-tooltip').forEach(tooltip => {
            tooltip.remove();
        });
    }
}

// Add custom CSS animations
const customAnimations = document.createElement('style');
customAnimations.textContent = `
    @keyframes fieldShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    @keyframes successAppear {
        0% {
            opacity: 0;
            transform: scale(0) rotate(-90deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    @keyframes growthParticle {
        0% {
            opacity: 1;
            transform: scale(1) translate(0, 0);
        }
        100% {
            opacity: 0;
            transform: scale(1.5) translate(${Math.random() * 100 - 50}px, ${-30 - Math.random() * 30}px);
        }
    }

    @keyframes celebrationParticle {
        0% {
            opacity: 1;
            transform: scale(1) translate(0, 0);
        }
        100% {
            opacity: 0;
            transform: scale(1.8) translate(${Math.random() * 120 - 60}px, ${-40 - Math.random() * 40}px);
        }
    }

    @keyframes rippleGrow {
        0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 0.8;
        }
        100% {
            transform: translate(-50%, -50%) scale(4);
            opacity: 0;
        }
    }

    @keyframes formShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    @keyframes iconFlip {
        0% { transform: rotateY(0deg); }
        50% { transform: rotateY(90deg); }
        100% { transform: rotateY(0deg); }
    }

    @keyframes statNumberGrow {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes tooltipAppear {
        0% {
            opacity: 0;
            transform: translateX(-50%) translateY(10px) scale(0.8);
        }
        100% {
            opacity: 1;
            transform: translateX(-50%) translateY(0) scale(1);
        }
    }

    @keyframes tooltipDisappear {
        0% {
            opacity: 1;
            transform: translateX(-50%) translateY(0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateX(-50%) translateY(-10px) scale(0.8);
        }
    }

    /* Field error styles */
    .field-error {
        color: #dc2626;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease-out;
        display: flex;
        align-items: center;
    }

    .modern-input.error {
        border-color: #dc2626;
        background: rgba(239, 68, 68, 0.02);
    }

    .modern-input.success {
        border-color: var(--leaf-green);
        background: rgba(16, 185, 129, 0.02);
    }

    .success-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--leaf-green);
        font-size: 1.1rem;
        z-index: 10;
    }

    .btn-login-primary.ready {
        background: var(--primary-gradient);
        animation: buttonReady 2s ease-in-out infinite;
    }

    @keyframes buttonReady {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }

    /* Keyboard navigation enhancement */
    .keyboard-navigation *:focus {
        outline: 3px solid var(--leaf-green) !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
    }

    /* Dark mode adjustments */
    @media (prefers-color-scheme: dark) {
        .modern-form {
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .demo-info {
            background: rgba(0, 0, 0, 0.3);
        }
        
        .testimonial-card {
            background: rgba(0, 0, 0, 0.3);
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .modern-input {
            border-width: 3px;
        }
        
        .btn-login-primary {
            border: 2px solid var(--leaf-green);
        }
        
        .skill-leaf, .stat-item {
            border: 2px solid white;
        }
    }
`;

document.head.appendChild(customAnimations);

// Auto-dismiss alerts
const setupAlertDismissal = () => {
    const alerts = document.querySelectorAll('.modern-alert');
    alerts.forEach(alert => {
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'alertSlideOut 0.5s ease-in-out';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
};

// Form field enhancement
const enhanceFormFields = () => {
    const inputs = document.querySelectorAll('.modern-input');
    
    inputs.forEach(input => {
        // Add floating label effect
        input.addEventListener('input', () => {
            const wrapper = input.closest('.input-wrapper');
            if (input.value.trim()) {
                wrapper.classList.add('has-value');
            } else {
                wrapper.classList.remove('has-value');
            }
        });

        // Auto-complete enhancements
        input.addEventListener('change', () => {
            if (input.value.trim()) {
                input.style.animation = 'inputSuccess 0.5s ease-out';
                setTimeout(() => {
                    input.style.animation = '';
                }, 500);
            }
        });
    });
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new LoginPageManager();
    setupAlertDismissal();
    enhanceFormFields();

    // Add entrance animation to main content
    const formContainer = document.querySelector('.login-form-container');
    const inspirationContent = document.querySelector('.inspiration-content');
    
    if (formContainer) {
        formContainer.style.animation = 'slideInFromRight 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
    }
    
    if (inspirationContent) {
        inspirationContent.style.animation = 'slideInFromLeft 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.2s both';
    }
});

// Add entrance animations
const entranceAnimations = document.createElement('style');
entranceAnimations.textContent = `
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes inputSuccess {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    @keyframes alertSlideOut {
        0% {
            opacity: 1;
            transform: translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
`;

document.head.appendChild(entranceAnimations);

// Export for module usage
export { LoginPageManager };

// Global utility functions
window.LoginUtils = {
    showToast: (message, type, duration) => {
        const manager = new LoginPageManager();
        return manager.showToast(message, type, duration);
    },
    createGrowthBurst: (element) => {
        const manager = new LoginPageManager();
        return manager.createGrowthBurst(element);
    }
};