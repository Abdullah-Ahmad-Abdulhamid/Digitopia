// ===== Profile Page Enhanced Interactions =====

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out-cubic',
            once: true,
            offset: 100,
            disable: function() {
                return window.innerWidth < 768;
            }
        });
    }

    // Initialize Vanilla Tilt for cards
    if (typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
            max: 8,
            speed: 400,
            scale: 1.02,
            perspective: 1000,
            transition: true,
            "max-glare": 0.1,
            "glare-prerender": false
        });
    }

    // Enhanced Form Interactions
    initializeFormEnhancements();
    
    // Password validation
    setupPasswordValidation();
    
    // Form submission enhancements
    enhanceFormSubmissions();
    
    // Auto-save functionality
    setupAutoSave();
    
    // Profile image interactions
    setupProfileImageInteractions();
    
    // Notification system
    setupNotifications();
    
    // Progress indicators
    setupProgressIndicators();

    console.log('Profile page enhancements initialized! ✨');
});

// Enhanced form interactions
function initializeFormEnhancements() {
    const inputs = document.querySelectorAll('.modern-input, .modern-select');
    
    inputs.forEach(input => {
        // Floating label effect
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
            addRippleEffect(this);
        });

        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.parentElement.classList.remove('focused');
            }
            this.parentElement.classList.remove('focused');
        });

        // Real-time validation
        input.addEventListener('input', function() {
            validateField(this);
        });

        // Check initial values
        if (input.value.trim()) {
            input.parentElement.classList.add('focused');
        }
    });
}

// Add ripple effect to inputs
function addRippleEffect(element) {
    const ripple = document.createElement('div');
    ripple.className = 'input-ripple';
    ripple.style.cssText = `
        position: absolute;
        top: 50%;
        left: 10px;
        width: 10px;
        height: 10px;
        background: rgba(102, 126, 234, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: inputRipple 0.6s ease-out;
        pointer-events: none;
    `;
    
    element.parentElement.style.position = 'relative';
    element.parentElement.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
}

// Add input ripple animation
const inputRippleStyles = document.createElement('style');
inputRippleStyles.textContent = `
    @keyframes inputRipple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .input-wrapper.focused .modern-input {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }
    
    .form-group.shake {
        animation: fieldShake 0.5s ease-in-out;
    }
    
    @keyframes fieldShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .success-checkmark {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #10b981;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .input-wrapper.valid .success-checkmark {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }
`;
document.head.appendChild(inputRippleStyles);

// Field validation
function validateField(field) {
    const wrapper = field.parentElement;
    const formGroup = field.closest('.form-group');
    
    // Remove existing validation classes
    wrapper.classList.remove('valid', 'invalid');
    formGroup.classList.remove('shake');
    
    // Remove existing checkmark
    const existingCheckmark = wrapper.querySelector('.success-checkmark');
    if (existingCheckmark) existingCheckmark.remove();
    
    let isValid = true;
    let errorMessage = '';
    
    // Basic validation
    if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        errorMessage = 'هذا الحقل مطلوب';
    }
    
    // Email validation
    if (field.type === 'email' && field.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            isValid = false;
            errorMessage = 'عنوان بريد إلكتروني غير صحيح';
        }
    }
    
    // Phone validation
    if (field.name === 'phone' && field.value.trim()) {
        const phoneRegex = /^01[0-2,5]{1}[0-9]{8}$/;
        if (!phoneRegex.test(field.value)) {
            isValid = false;
            errorMessage = 'رقم هاتف غير صحيح';
        }
    }
    
    // Age validation
    if (field.name === 'age' && field.value) {
        const age = parseInt(field.value);
        if (age < 13 || age > 100) {
            isValid = false;
            errorMessage = 'العمر يجب أن يكون بين 13 و 100';
        }
    }
    
    if (isValid && field.value.trim()) {
        wrapper.classList.add('valid');
        // Add success checkmark
        const checkmark = document.createElement('i');
        checkmark.className = 'fas fa-check success-checkmark';
        wrapper.appendChild(checkmark);
    } else if (!isValid) {
        wrapper.classList.add('invalid');
        formGroup.classList.add('shake');
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    const formGroup = field.closest('.form-group');
    let errorElement = formGroup.querySelector('.field-error');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.style.cssText = `
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            animation: errorSlideIn 0.3s ease;
        `;
        formGroup.appendChild(errorElement);
    }
    
    errorElement.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        if (errorElement) {
            errorElement.style.animation = 'errorSlideOut 0.3s ease';
            setTimeout(() => errorElement.remove(), 300);
        }
    }, 3000);
}

// Add error animation styles
const errorStyles = document.createElement('style');
errorStyles.textContent = `
    @keyframes errorSlideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes errorSlideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(errorStyles);

// Password validation and toggle
function setupPasswordValidation() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    
    // Password strength indicator
    if (newPasswordInput) {
        const strengthIndicator = createPasswordStrengthIndicator();
        newPasswordInput.parentElement.appendChild(strengthIndicator);
        
        newPasswordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value, strengthIndicator);
        });
    }
    
    // Confirm password validation
    if (confirmPasswordInput && newPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const isMatch = this.value === newPasswordInput.value;
            const wrapper = this.parentElement;
            
            wrapper.classList.remove('valid', 'invalid');
            
            if (this.value.trim()) {
                if (isMatch) {
                    wrapper.classList.add('valid');
                    // Add success checkmark
                    if (!wrapper.querySelector('.success-checkmark')) {
                        const checkmark = document.createElement('i');
                        checkmark.className = 'fas fa-check success-checkmark';
                        wrapper.appendChild(checkmark);
                    }
                } else {
                    wrapper.classList.add('invalid');
                    showFieldError(this, 'كلمة المرور غير متطابقة');
                }
            }
        });
    }
}

// Create password strength indicator
function createPasswordStrengthIndicator() {
    const indicator = document.createElement('div');
    indicator.className = 'password-strength';
    indicator.style.cssText = `
        margin-top: 0.5rem;
        padding: 0.5rem;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    `;
    
    indicator.innerHTML = `
        <div class="strength-bars" style="display: flex; gap: 4px; margin-bottom: 0.5rem;">
            <div class="strength-bar" style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
            <div class="strength-bar" style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
            <div class="strength-bar" style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
            <div class="strength-bar" style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
        </div>
        <div class="strength-text" style="font-size: 0.8rem; font-weight: 600;">أدخل كلمة مرور</div>
    `;
    
    return indicator;
}

// Update password strength
function updatePasswordStrength(password, indicator) {
    const bars = indicator.querySelectorAll('.strength-bar');
    const text = indicator.querySelector('.strength-text');
    
    // Reset bars
    bars.forEach(bar => {
        bar.style.background = '#e2e8f0';
    });
    
    if (!password) {
        text.textContent = 'أدخل كلمة مرور';
        text.style.color = '#6b7280';
        return;
    }
    
    let strength = 0;
    let strengthText = '';
    let strengthColor = '';
    
    // Check password criteria
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // Update bars and text based on strength
    switch(strength) {
        case 1:
        case 2:
            strengthText = 'ضعيفة';
            strengthColor = '#ef4444';
            bars[0].style.background = strengthColor;
            break;
        case 3:
            strengthText = 'متوسطة';
            strengthColor = '#f59e0b';
            bars[0].style.background = strengthColor;
            bars[1].style.background = strengthColor;
            break;
        case 4:
            strengthText = 'جيدة';
            strengthColor = '#10b981';
            bars[0].style.background = strengthColor;
            bars[1].style.background = strengthColor;
            bars[2].style.background = strengthColor;
            break;
        case 5:
            strengthText = 'قوية جداً';
            strengthColor = '#059669';
            bars.forEach(bar => {
                bar.style.background = strengthColor;
            });
            break;
        default:
            strengthText = 'ضعيفة جداً';
            strengthColor = '#dc2626';
    }
    
    text.textContent = strengthText;
    text.style.color = strengthColor;
}

// Password toggle functionality
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
        button.setAttribute('title', 'إخفاء كلمة المرور');
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
        button.setAttribute('title', 'إظهار كلمة المرور');
    }
    
    // Add toggle animation
    button.style.animation = 'toggleBounce 0.3s ease';
    setTimeout(() => {
        button.style.animation = '';
    }, 300);
}

// Add toggle bounce animation
const toggleStyles = document.createElement('style');
toggleStyles.textContent = `
    @keyframes toggleBounce {
        0%, 100% { transform: translateY(-50%) scale(1); }
        50% { transform: translateY(-50%) scale(1.2); }
    }
`;
document.head.appendChild(toggleStyles);

// Make togglePassword globally available
window.togglePassword = togglePassword;

// Enhanced form submissions
function enhanceFormSubmissions() {
    const forms = document.querySelectorAll('.modern-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Validate all fields before submission
            const fields = this.querySelectorAll('.modern-input[required], .modern-select[required]');
            let isFormValid = true;
            
            fields.forEach(field => {
                if (!validateField(field)) {
                    isFormValid = false;
                }
            });
            
            if (!isFormValid) {
                e.preventDefault();
                showToast('يرجى تصحيح الأخطاء قبل المتابعة', 'error');
                return;
            }
            
            // Show loading state
            if (submitButton) {
                showButtonLoading(submitButton);
            }
        });
    });
}

// Show button loading state
function showButtonLoading(button) {
    const originalContent = button.innerHTML;
    const loadingContent = `
        <i class="fas fa-spinner fa-spin me-2"></i>
        جاري الحفظ...
    `;
    
    button.innerHTML = loadingContent;
    button.disabled = true;
    
    // Reset after 3 seconds (fallback)
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 3000);
}

// Auto-save functionality
function setupAutoSave() {
    const autoSaveInputs = document.querySelectorAll('.modern-input:not([name="current_password"]):not([name="new_password"]):not([name="confirm_password"])');
    let autoSaveTimeout;
    
    autoSaveInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            
            // Show auto-save indicator
            showAutoSaveIndicator(this);
            
            autoSaveTimeout = setTimeout(() => {
                autoSaveField(this);
            }, 2000);
        });
    });
}

// Show auto-save indicator
function showAutoSaveIndicator(field) {
    const wrapper = field.parentElement;
    let indicator = wrapper.querySelector('.auto-save-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'auto-save-indicator';
        indicator.style.cssText = `
            position: absolute;
            right: 0.5rem;
            top: -25px;
            font-size: 0.7rem;
            color: #f59e0b;
            background: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
        `;
        wrapper.appendChild(indicator);
    }
    
    indicator.innerHTML = '<i class="fas fa-clock me-1"></i>سيتم الحفظ تلقائياً...';
    indicator.style.opacity = '1';
    
    setTimeout(() => {
        indicator.style.opacity = '0';
    }, 1500);
}

// Auto-save field (simulation)
function autoSaveField(field) {
    // In a real application, this would make an AJAX request
    const wrapper = field.parentElement;
    let indicator = wrapper.querySelector('.auto-save-indicator');
    
    if (indicator) {
        indicator.innerHTML = '<i class="fas fa-check me-1"></i>تم الحفظ';
        indicator.style.color = '#10b981';
        indicator.style.opacity = '1';
        
        setTimeout(() => {
            indicator.style.opacity = '0';
        }, 2000);
    }
}

// Profile image interactions
function setupProfileImageInteractions() {
    const avatars = document.querySelectorAll('.avatar-wrapper');
    
    avatars.forEach(avatar => {
        // Add hover effect
        avatar.addEventListener('mouseenter', function() {
            this.style.animation = 'avatarHover 0.3s ease forwards';
        });
        
        avatar.addEventListener('mouseleave', function() {
            this.style.animation = '';
        });
        
        // Add click to upload functionality (placeholder)
        avatar.addEventListener('click', function() {
            showImageUploadDialog();
        });
    });
}

// Add avatar hover animation
const avatarStyles = document.createElement('style');
avatarStyles.textContent = `
    @keyframes avatarHover {
        0% { transform: scale(1); }
        100% { transform: scale(1.05); }
    }
    
    .avatar-wrapper {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .avatar-wrapper:hover {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
`;
document.head.appendChild(avatarStyles);

// Show image upload dialog (placeholder)
function showImageUploadDialog() {
    showToast('ميزة تحميل الصورة الشخصية قريباً!', 'info');
}

// Enhanced notification system
function setupNotifications() {
    // Check for URL parameters for success/error messages
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('success')) {
        showToast('تم تحديث البيانات بنجاح!', 'success');
    }
    
    if (urlParams.get('error')) {
        showToast('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
    }
}

// Enhanced toast function
function showToast(message, type = 'info', duration = 5000) {
    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#667eea'
    };
    
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 30px;
        background: white;
        color: ${colors[type]};
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border-left: 4px solid ${colors[type]};
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transform: translateX(400px);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        cursor: pointer;
        max-width: 350px;
        font-weight: 500;
        display: flex;
        align-items: center;
    `;
    
    toast.innerHTML = `
        <i class="${icons[type]} me-2"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            margin-left: 1rem;
            color: ${colors[type]};
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
    
    // Click to dismiss
    toast.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 300);
        }
    });
}

// Progress indicators
function setupProgressIndicators() {
    // Profile completion indicator
    const profileFields = document.querySelectorAll('.modern-input:not([type="password"]), .modern-select');
    let filledFields = 0;
    
    profileFields.forEach(field => {
        if (field.value && field.value.trim()) {
            filledFields++;
        }
    });
    
    const completionPercentage = Math.round((filledFields / profileFields.length) * 100);
    
    // Show completion indicator if less than 100%
    if (completionPercentage < 100) {
        createCompletionIndicator(completionPercentage);
    }
}

// Create profile completion indicator
function createCompletionIndicator(percentage) {
    const indicator = document.createElement('div');
    indicator.className = 'completion-indicator';
    indicator.style.cssText = `
        position: fixed;
        bottom: 100px;
        left: 30px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        animation: indicatorSlideIn 0.5s ease;
        border: 2px solid #667eea;
        max-width: 280px;
    `;
    
    indicator.innerHTML = `
        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
            <i class="fas fa-user-check me-2" style="color: #667eea;"></i>
            <strong style="color: #667eea;">إكمال الملف الشخصي</strong>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="font-size: 0.9rem; color: #6b7280;">مكتمل ${percentage}%</span>
            <span style="font-size: 0.8rem; color: #9ca3af;">${Math.round((100 - percentage) / 100 * 8)} حقول متبقية</span>
        </div>
        <div style="width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
            <div style="width: ${percentage}%; height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 3px; transition: width 1s ease;"></div>
        </div>
    `;
    
    document.body.appendChild(indicator);
    
    // Auto-hide after 10 seconds
    setTimeout(() => {
        indicator.style.animation = 'indicatorSlideOut 0.5s ease';
        setTimeout(() => indicator.remove(), 500);
    }, 10000);
}

// Add completion indicator animations
const completionStyles = document.createElement('style');
completionStyles.textContent = `
    @keyframes indicatorSlideIn {
        from {
            opacity: 0;
            transform: translateX(-100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes indicatorSlideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-100px);
        }
    }
`;
document.head.appendChild(completionStyles);

// Utility functions for external access
window.ProfileEnhancements = {
    showToast,
    validateField,
    togglePassword
};