    // ===== Modern Register Page Manager =====

class RegisterPageManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 3;
        this.formData = {};
        this.init();
    }

    init() {
        this.setupAOS();
        this.setupFormValidation();
        this.setupPasswordToggle();
        this.setupPasswordStrength();
        this.setupPasswordMatch();
        this.setupStepNavigation();
        this.setupFormAnimations();
        this.setupGrowthEffects();
        this.setupLoadingStates();
        this.setupAccessibility();
        this.setupPerformanceOptimizations();
        console.log('🌱 Register page initialized successfully!');
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

        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.validateField(e.target);
                this.updateFormState();
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
            
            let isValid = this.validateAllFields();

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
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        // Remove existing errors
        this.clearFieldError(field);

        // Validation logic
        switch (fieldName) {
            case 'name':
                if (!value) {
                    isValid = false;
                    errorMessage = 'الاسم الكامل مطلوب';
                } else if (value.length < 3) {
                    isValid = false;
                    errorMessage = 'الاسم يجب أن يكون 3 أحرف على الأقل';
                }
                break;
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
            case 'password_confirm':
                const password = document.getElementById('password').value;
                if (!value) {
                    isValid = false;
                    errorMessage = 'تأكيد كلمة المرور مطلوب';
                } else if (value !== password) {
                    isValid = false;
                    errorMessage = 'كلمة المرور غير متطابقة';
                }
                break;
            case 'phone':
                if (value && !this.isValidPhone(value)) {
                    isValid = false;
                    errorMessage = 'يرجى إدخال رقم هاتف صحيح';
                }
                break;
            case 'age':
                if (value && (value < 13 || value > 100)) {
                    isValid = false;
                    errorMessage = 'العمر يجب أن يكون بين 13 و 100 سنة';
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

    // Validate all fields
    validateAllFields() {
        const requiredFields = ['name', 'email', 'password', 'password_confirm'];
        let allValid = true;

        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && !this.validateField(field, true)) {
                allValid = false;
            }
        });

        // Check terms checkbox
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            this.showToast('يرجى الموافقة على الشروط والأحكام', 'error');
            allValid = false;
        }

        return allValid;
    }

    // Email validation
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Phone validation
    isValidPhone(phone) {
        const phoneRegex = /^01[0-2,5]{1}[0-9]{8}$/;
        return phoneRegex.test(phone);
    }

    // Show field error with growth animation
    showFieldError(field, message) {
        const wrapper = field.closest('.input-wrapper');
        field.classList.add('error');
        
        let errorElement = wrapper.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            wrapper.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        
        // Animate error appearance
        setTimeout(() => {
            errorElement.classList.add('show');
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
            successIcon.style.cssText = `
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--leaf-green);
                font-size: 1.1rem;
                z-index: 10;
                animation: successAppear 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            `;
            wrapper.appendChild(successIcon);
        }
    }

    // Clear field error
    clearFieldError(field) {
        const wrapper = field.closest('.input-wrapper');
        const errorElement = wrapper.querySelector('.field-error');
        const successIcon = wrapper.querySelector('.success-icon');
        
        field.classList.remove('error', 'success');
        
        if (errorElement) {
            errorElement.classList.remove('show');
            setTimeout(() => errorElement.remove(), 300);
        }
        
        if (successIcon) {
            successIcon.remove();
        }
    }

    // Password toggle functionality
    setupPasswordToggle() {
        const passwordToggles = ['togglePassword', 'togglePasswordConfirm'];
        
        passwordToggles.forEach(toggleId => {
            const toggleBtn = document.getElementById(toggleId);
            const passwordInput = toggleId === 'togglePassword' ? 
                document.getElementById('password') : 
                document.getElementById('password_confirm');

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
        });
    }

    // Password strength indicator
    setupPasswordStrength() {
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text');

        if (passwordInput && strengthMeter) {
            passwordInput.addEventListener('input', () => {
                const password = passwordInput.value;
                const strength = this.calculatePasswordStrength(password);
                
                strengthMeter.style.width = `${strength.percentage}%`;
                strengthMeter.style.background = strength.gradient;
                strengthText.textContent = strength.text;
                strengthText.style.color = strength.color;
            });
        }
    }

    // Calculate password strength
    calculatePasswordStrength(password) {
        let score = 0;
        const length = password.length;

        if (length >= 6) score += 20;
        if (length >= 8) score += 20;
        if (/[a-z]/.test(password)) score += 20;
        if (/[A-Z]/.test(password)) score += 20;
        if (/[0-9]/.test(password)) score += 10;
        if (/[^A-Za-z0-9]/.test(password)) score += 10;

        let strengthConfig;
        if (score < 40) {
            strengthConfig = {
                percentage: score,
                gradient: 'var(--danger-gradient)',
                text: 'ضعيفة',
                color: '#ef4444'
            };
        } else if (score < 70) {
            strengthConfig = {
                percentage: score,
                gradient: 'var(--warning-gradient)',
                text: 'متوسطة',
                color: '#f59e0b'
            };
        } else {
            strengthConfig = {
                percentage: score,
                gradient: 'var(--success-gradient)',
                text: 'قوية',
                color: '#22c55e'
            };
        }

        return strengthConfig;
    }

    // Password match indicator
    setupPasswordMatch() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirm');
        const matchIndicator = document.querySelector('.password-match-indicator');

        if (passwordInput && confirmInput && matchIndicator) {
            const checkMatch = () => {
                if (confirmInput.value && passwordInput.value === confirmInput.value) {
                    matchIndicator.classList.add('show');
                } else {
                    matchIndicator.classList.remove('show');
                }
            };

            passwordInput.addEventListener('input', checkMatch);
            confirmInput.addEventListener('input', checkMatch);
        }
    }

    // Step navigation setup
    setupStepNavigation() {
        const nextBtn1 = document.getElementById('nextStep1');
        const nextBtn2 = document.getElementById('nextStep2');
        const backBtn = document.getElementById('backStep');

        if (nextBtn1) {
            nextBtn1.addEventListener('click', () => {
                if (this.validateStep(1)) {
                    this.goToStep(2);
                }
            });
        }

        if (nextBtn2) {
            nextBtn2.addEventListener('click', () => {
                if (this.validateStep(2)) {
                    this.goToStep(3);
                }
            });
        }

        if (backBtn) {
            backBtn.addEventListener('click', () => {
                this.goToStep(2);
            });
        }
    }

    // Validate current step
    validateStep(step) {
        let isValid = true;
        let fieldsToValidate = [];

        switch (step) {
            case 1:
                fieldsToValidate = ['name', 'email', 'password', 'password_confirm'];
                break;
            case 2:
                // Optional fields, no validation needed
                isValid = true;
                break;
        }

        fieldsToValidate.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && !this.validateField(field, true)) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Navigate to specific step
    goToStep(stepNumber) {
        const currentStepElement = document.getElementById(`step${this.currentStep}`);
        const targetStepElement = document.getElementById(`step${stepNumber}`);
        const currentProgressStep = document.querySelector(`.progress-step[data-step="${this.currentStep}"]`);
        const targetProgressStep = document.querySelector(`.progress-step[data-step="${stepNumber}"]`);

        // Hide current step
        currentStepElement.classList.remove('active');
        currentProgressStep.classList.remove('active');

        // Mark previous steps as completed
        if (stepNumber > this.currentStep) {
            currentProgressStep.classList.add('completed');
        } else {
            currentProgressStep.classList.remove('completed');
        }

        // Show target step
        setTimeout(() => {
            targetStepElement.classList.add('active');
            targetProgressStep.classList.add('active');
            
            // Update progress bar
            this.updateProgressBar(stepNumber);
            
            // Scroll to top of form
            targetStepElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 200);

        this.currentStep = stepNumber;
        this.announceToScreenReader(`الانتقال إلى الخطوة ${stepNumber} من ${this.totalSteps}`);
    }

    // Update progress bar
    updateProgressBar(step) {
        const progressFill = document.querySelector('.progress-fill');
        const percentage = ((step - 1) / (this.totalSteps - 1)) * 100;
        
        if (progressFill) {
            progressFill.style.width = `${percentage}%`;
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
        const checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    this.createGrowthBurst(checkbox.closest('.custom-checkbox'));
                }
            });
        });
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
        // Brand icon interaction
        const brandIcon = document.querySelector('.brand-icon-wrapper');
        if (brandIcon) {
            brandIcon.addEventListener('click', () => {
                this.createCelebrationEffect(brandIcon);
            });
        }

        // Benefit items interaction
        document.querySelectorAll('.benefit-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateY(-8px) scale(1.05)';
                this.animateBenefitIcon(item);
            });

            item.addEventListener('mouseleave', () => {
                item.style.transform = '';
            });
        });

        // Journey points interaction
        document.querySelectorAll('.journey-point').forEach(point => {
            point.addEventListener('mouseenter', () => {
                point.style.transform = 'scale(1.1)';
                this.showJourneyTooltip(point);
            });

            point.addEventListener('mouseleave', () => {
                point.style.transform = '';
                this.hideJourneyTooltip();
            });
        });
    }

    // Animate benefit icon
    animateBenefitIcon(benefitItem) {
        const icon = benefitItem.querySelector('.benefit-icon');
        if (icon && !icon.classList.contains('animated')) {
            icon.classList.add('animated');
            icon.style.animation = 'benefitIconBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            
            setTimeout(() => {
                icon.classList.remove('animated');
                icon.style.animation = '';
            }, 600);
        }
    }

    // Show journey tooltip
    showJourneyTooltip(point) {
        const label = point.querySelector('.point-label').textContent;
        const descriptions = {
            'البداية': 'انضم لمجتمع التعلم وابدأ رحلتك',
            'تطوير المهارات': 'تعلم مهارات حياتية عملية',
            'الإنجازات': 'احصل على شهادات وإنجازات',
            'النجاح': 'حقق أهدافك وطموحاتك'
        };

        const tooltip = document.createElement('div');
        tooltip.className = 'journey-tooltip';
        tooltip.innerHTML = `
            <div class="tooltip-title">${label}</div>
            <div class="tooltip-text">${descriptions[label] || ''}</div>
        `;
        tooltip.style.cssText = `
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            white-space: nowrap;
            z-index: 1000;
            animation: tooltipAppear 0.3s ease-out;
            min-width: 150px;
            text-align: center;
        `;
        
        point.appendChild(tooltip);
    }

    // Hide journey tooltip
    hideJourneyTooltip() {
        const tooltip = document.querySelector('.journey-tooltip');
        if (tooltip) {
            tooltip.style.animation = 'tooltipDisappear 0.2s ease-in';
            setTimeout(() => tooltip.remove(), 200);
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

    // Update form state
    updateFormState() {
        // Update next button state
        const nextBtn1 = document.getElementById('nextStep1');
        const nextBtn2 = document.getElementById('nextStep2');
        
        if (nextBtn1) {
            const step1Valid = this.validateStep(1);
            nextBtn1.disabled = !step1Valid;
            nextBtn1.classList.toggle('ready', step1Valid);
        }
        
        if (nextBtn2) {
            nextBtn2.disabled = false; // Step 2 is optional
            nextBtn2.classList.add('ready');
        }
    }

    // Loading state management
    setupLoadingStates() {
        const form = document.querySelector('.modern-form');

        form.addEventListener('submit', (e) => {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (name && email && password) {
                setTimeout(() => {
                    this.showLoadingState();
                }, 300);
            }
        });
    }

    // Show loading state
    showLoadingState() {
        const overlay = document.getElementById('loadingOverlay');
        const submitBtn = document.getElementById('registerBtn');
        
        // Show overlay
        overlay.classList.add('show');
        
        // Update button
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        const btnContent = submitBtn.querySelector('.btn-content');
        if (btnContent) {
            btnContent.innerHTML = `
                <i class="fas fa-seedling fa-spin me-2"></i>
                جاري إنشاء حسابك...
            `;
        }
    }

    // Hide loading state
    hideLoadingState() {
        const overlay = document.getElementById('loadingOverlay');
        const submitBtn = document.getElementById('registerBtn');
        
        overlay.classList.remove('show');
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        const btnContent = submitBtn.querySelector('.btn-content');
        if (btnContent) {
            btnContent.innerHTML = `
                <i class="fas fa-rocket me-2"></i>
                ابدأ رحلة التطوير
            `;
        }
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
                    const step = focused.closest('.form-step');
                    if (step.id === 'step1') {
                        const nextBtn = document.getElementById('nextStep1');
                        if (nextBtn && !nextBtn.disabled) {
                            nextBtn.click();
                        }
                    } else if (step.id === 'step2') {
                        const nextBtn = document.getElementById('nextStep2');
                        if (nextBtn && !nextBtn.disabled) {
                            nextBtn.click();
                        }
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
        ariaLive.style.cssText = `
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        `;
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
        document.querySelectorAll('.growth-journey, .floating-element, .brand-icon-wrapper').forEach(el => {
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
            const animatedElements = document.querySelectorAll('.floating-element, .growth-journey, .brand-icon-wrapper');
            
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
        document.querySelectorAll('.journey-tooltip, .skill-tooltip').forEach(tooltip => {
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

    @keyframes benefitIconBounce {
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

    /* Button ready state */
    .btn-next.ready,
    .btn-register-primary.ready {
        animation: buttonReady 2s ease-in-out infinite;
    }

    @keyframes buttonReady {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    /* Keyboard navigation enhancement */
    .keyboard-navigation *:focus {
        outline: 3px solid var(--leaf-green) !important;
        outline-offset: 3px !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
    }

    /* Tooltip styles */
    .tooltip-title {
        font-weight: 700;
        margin-bottom: 0.3rem;
        color: #fbbf24;
    }

    .tooltip-text {
        font-size: 0.8rem;
        opacity: 0.9;
    }
`;

document.head.appendChild(customAnimations);

// Auto-dismiss alerts
const setupAlertDismissal = () => {
    const alerts = document.querySelectorAll('.modern-alert');
    alerts.forEach(alert => {
        // Auto dismiss after 6 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'alertSlideOut 0.5s ease-in-out';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 6000);
    });
};

// Form field enhancement
const enhanceFormFields = () => {
    const inputs = document.querySelectorAll('.modern-input');
    
    inputs.forEach(input => {
        // Add floating label effect
        input.addEventListener('input', () => {
            const wrapper = input.closest('.input-wrapper') || input.closest('.floating-input') || input.closest('.floating-select');
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
    new RegisterPageManager();
    setupAlertDismissal();
    enhanceFormFields();

    // Add entrance animation to main content
    const formContainer = document.querySelector('.register-form-container');
    const inspirationContent = document.querySelector('.inspiration-content');
    
    if (formContainer) {
        formContainer.style.animation = 'slideInFromRight 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
    }
    
    if (inspirationContent) {
        inspirationContent.style.animation = 'slideInFromLeft 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.2s both';
    }

    // Initialize form progress
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        progressFill.style.width = '0%';
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

    /* Button disabled state */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn:disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }
`;

document.head.appendChild(entranceAnimations);

// Export for module usage
export { RegisterPageManager };

// Global utility functions
window.RegisterUtils = {
    showToast: (message, type, duration) => {
        const manager = new RegisterPageManager();
        return manager.showToast(message, type, duration);
    },
    createGrowthBurst: (element) => {
        const manager = new RegisterPageManager();
        return manager.createGrowthBurst(element);
    },
    goToStep: (step) => {
        const manager = new RegisterPageManager();
        return manager.goToStep(step);
    }
};