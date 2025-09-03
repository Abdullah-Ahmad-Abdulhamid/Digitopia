// ===== Enhanced Challenges Page Animations =====

// Global variables
let currentChallengeId = null;

// Global function to handle challenge button clicks
window.completeChallenge = function(challengeId, event) {
    console.log('Starting challenge:', challengeId, 'from event:', event);
    
    // Prevent default if event is provided (for anchor tags)
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    currentChallengeId = challengeId;
    
    const modalElement = document.getElementById('challengeModal');
    if (!modalElement) {
        console.error('Challenge modal not found');
        return;
    }
    
    // Clean up any existing modal instances
    const existingModal = bootstrap.Modal.getInstance(modalElement);
    if (existingModal) {
        existingModal.dispose();
    }
    
    // Remove any existing backdrops and reset body
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.classList.remove('modal-open');
    
    // Reset modal element
    modalElement.style.display = 'none';
    modalElement.classList.remove('show');
    modalElement.removeAttribute('aria-hidden');
    modalElement.setAttribute('aria-modal', 'true');
    modalElement.setAttribute('role', 'dialog');
    modalElement.removeAttribute('inert');
    
    // Set the challenge ID as a data attribute
    modalElement.setAttribute('data-challenge-id', challengeId);
    
    // Clear any previous response
    const responseInput = document.getElementById('challengeResponse');
    if (responseInput) {
        responseInput.value = '';
    }
    
    // Initialize the modal
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: false,
        focus: true
    });
    
    // Add event listeners for show/hide
    const handleShown = () => {
        console.log('Modal shown event triggered');
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
        modalElement.removeAttribute('aria-hidden');
        modalElement.setAttribute('aria-modal', 'true');
        
        // Set focus trap
        const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        const focusableContent = modalElement.querySelectorAll(focusableElements);
        const firstFocusableElement = focusableContent[0];
        const lastFocusableElement = focusableContent[focusableContent.length - 1];
        
        if (firstFocusableElement) {
            firstFocusableElement.focus();
        }
        
        // Handle keyboard trap
        const handleKeyDown = (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey && document.activeElement === firstFocusableElement) {
                    e.preventDefault();
                    lastFocusableElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastFocusableElement) {
                    e.preventDefault();
                    firstFocusableElement.focus();
                }
            } else if (e.key === 'Escape') {
                modal.hide();
            }
        };
        
        modalElement.addEventListener('keydown', handleKeyDown);
        
        // Clean up event listener on hide
        const cleanup = () => {
            modalElement.removeEventListener('keydown', handleKeyDown);
            modalElement.removeEventListener('hidden.bs.modal', cleanup);
        };
        
        modalElement.addEventListener('hidden.bs.modal', cleanup, { once: true });
    };
    
    const handleHidden = () => {
        console.log('Modal hidden event triggered');
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
        document.body.classList.remove('modal-open');
        modalElement.setAttribute('aria-hidden', 'true');
        modalElement.removeAttribute('aria-modal');
        
        // Remove all modal backdrops
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        
        // Restore body scroll
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    };
    
    // Add event listeners
    modalElement.addEventListener('shown.bs.modal', handleShown);
    modalElement.addEventListener('hidden.bs.modal', handleHidden);
    
    // Show the modal
    modal.show();
    
    // Clean up event listeners when modal is hidden
    const oneTimeHidden = () => {
        modalElement.removeEventListener('shown.bs.modal', handleShown);
        modalElement.removeEventListener('hidden.bs.modal', handleHidden);
        modalElement.removeEventListener('hidden.bs.modal', oneTimeHidden);
    };
    modalElement.addEventListener('hidden.bs.modal', oneTimeHidden, { once: true });
    
    console.log('Modal shown for challenge:', challengeId);
};

class ChallengesAnimations {
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
        this.setupChallengeSystem();
    }

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
                startEvent: 'DOMContentLoaded'
            });
        }
    }

    setupNavigation() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

    setupCounters() {
        // Counter animation for stats
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };

            updateCount();
        });
    }

    setupProgressAnimations() {
        // Animate progress bars on scroll into view
        const progressBars = document.querySelectorAll('.progress-bar');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = entry.target;
                    const width = progressBar.getAttribute('aria-valuenow');
                    progressBar.style.width = width + '%';
                    progressBar.style.opacity = 1;
                }
            });
        }, { threshold: 0.5 });

        progressBars.forEach(bar => observer.observe(bar));
    }

    setupParticleSystem() {
        // Add floating particles to hero section
        const hero = document.querySelector('.hero-section');
        if (!hero) return;

        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'floating-particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: #10b981;
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
    }

    setupGrowthAnimations() {
        // Add hover effects to cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });
    }

    setupInteractiveElements() {
        // Add click effects to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.createRippleEffect(e, btn);
            });
        });
    }

    createRippleEffect(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${event.clientX - rect.left - size/2}px`;
        ripple.style.top = `${event.clientY - rect.top - size/2}px`;
        
        ripple.classList.add('ripple');
        element.appendChild(ripple);
        
        // Remove ripple after animation
        setTimeout(() => ripple.remove(), 600);
    }

    setupPerformanceOptimizations() {
        // Add will-change to elements that will be animated
        const animatedElements = document.querySelectorAll('.card, .btn, .challenge-card');
        animatedElements.forEach(el => {
            el.style.willChange = 'transform, box-shadow';
        });
    }

    setupChallengeSystem() {
        const self = this;
        const challengeModal = document.getElementById('challengeModal');
        if (!challengeModal) return;
        
        // Clean up any existing modal instances
        const existingModal = bootstrap.Modal.getInstance(challengeModal);
        if (existingModal) {
            existingModal.dispose();
        }
        
        // Initialize new modal instance
        const modal = new bootstrap.Modal(challengeModal, {
            backdrop: 'static',
            keyboard: false,
            focus: true
        });
        
        // Store modal instance for later use
        challengeModal._modal = modal;

        // Handle modal show event
        const handleShow = function() {
            // Ensure modal is visible
            challengeModal.style.display = 'block';
            challengeModal.classList.add('show');
            challengeModal.setAttribute('aria-hidden', 'false');
            
            // Add animation
            const dialog = challengeModal.querySelector('.modal-dialog');
            if (dialog) {
                dialog.style.animation = 'fadeIn 0.3s ease-out';
            }
            
            // Add sparkle effect
            if (typeof self.createSparkleEffect === 'function') {
                self.createSparkleEffect(challengeModal);
            }
            
            // Focus on first input
            const firstInput = challengeModal.querySelector('input, textarea, button:not([disabled])');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
            
            console.log('Modal shown');
        };

        // Handle modal hide event
        const handleHide = function() {
            const dialog = challengeModal.querySelector('.modal-dialog');
            if (dialog) {
                dialog.style.animation = 'fadeOut 0.3s ease-in';
            }
            
            console.log('Hiding modal');
        };

        // Handle modal hidden event
        const handleHidden = function() {
            // Reset form
            const form = challengeModal.querySelector('form');
            if (form) form.reset();
            
            // Clean up sparkle effects
            challengeModal.querySelectorAll('.sparkle').forEach(s => s.remove());
            
            // Reset modal state
            challengeModal.classList.remove('show');
            challengeModal.style.display = 'none';
            challengeModal.setAttribute('aria-hidden', 'true');
            
            // Remove any remaining backdrop
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            
            console.log('Modal hidden');
        };
        
        // Remove existing event listeners
        challengeModal.removeEventListener('show.bs.modal', handleShow);
        challengeModal.removeEventListener('hide.bs.modal', handleHide);
        challengeModal.removeEventListener('hidden.bs.modal', handleHidden);
        
        // Add new event listeners
        challengeModal.addEventListener('show.bs.modal', handleShow);
        challengeModal.addEventListener('hide.bs.modal', handleHide);
        challengeModal.addEventListener('hidden.bs.modal', handleHidden);

        // Handle confirm button click
        const confirmBtn = document.getElementById('confirmComplete');
        if (confirmBtn) {
            // Remove existing click handlers
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            newConfirmBtn.addEventListener('click', function() {
                if (!currentChallengeId) {
                    console.warn('No current challenge ID');
                    return;
                }
                
                const response = document.getElementById('challengeResponse')?.value || '';
                console.log('Completing challenge:', currentChallengeId, 'Response:', response);
                
                // Show loading state
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
                
                // Reset any previous timeouts
                if (this._timeout) {
                    clearTimeout(this._timeout);
                }
                
                // Simulate API call
                this._timeout = setTimeout(() => {
                    try {
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = originalText;
                        
                        // Hide modal
                        if (modal) {
                            modal.hide();
                        }
                        
                        // Show success message
                        if (typeof self.showSuccessMessage === 'function') {
                            self.showSuccessMessage('تم إكمال التحدي بنجاح!', 3000);
                        } else {
                            alert('تم إكمال التحدي بنجاح!');
                        }
                        
                        // Reload page after a delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } catch (error) {
                        console.error('Error completing challenge:', error);
                        this.disabled = false;
                        this.innerHTML = originalText;
                        alert('حدث خطأ أثناء محاولة إكمال التحدي. يرجى المحاولة مرة أخرى.');
                    }
                }, 1000);
            });
        }
    }
    
    createSparkleEffect(container) {
        const sparkles = ['✨', '⭐', '🌟', '💫', '⚡'];
        
        for (let i = 0; i < 8; i++) {
            const sparkle = document.createElement('span');
            sparkle.className = 'sparkle';
            sparkle.textContent = sparkles[Math.floor(Math.random() * sparkles.length)];
            
            sparkle.style.cssText = `
                position: absolute;
                font-size: ${Math.random() * 20 + 10}px;
                opacity: 0;
                animation: sparkle ${Math.random() * 2 + 1}s ease-in-out infinite;
                animation-delay: ${Math.random() * 0.5}s;
                pointer-events: none;
                z-index: 1000;
            `;
            
            // Position randomly around the container
            const rect = container.getBoundingClientRect();
            sparkle.style.left = `${Math.random() * rect.width}px`;
            sparkle.style.top = `${Math.random() * rect.height}px`;
            
            container.appendChild(sparkle);
            
            // Remove after animation
            setTimeout(() => sparkle.remove(), 2000);
        }
    }
    
    showSuccessMessage(message, points) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.role = 'alert';
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <strong>${message}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="mt-2">لقد ربحت ${points} نقطة!</div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, 5000);
    }
}


// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded, initializing challenges...');
    
    // Add debug click handlers to all weekly challenge buttons
    document.querySelectorAll('.btn-weekly-challenge').forEach(btn => {
        if (!btn.onclick) {  // Only add if not already handled
            btn.addEventListener('click', function(e) {
                const challengeId = this.closest('[data-challenge-id]')?.dataset.challengeId || 
                                  this.closest('.weekly-challenge-card')?.querySelector('[onclick*="completeChallenge"]')?.onclick
                                      .toString().match(/completeChallenge\((\d+)/)?.[1];
                if (challengeId) {
                    completeChallenge(challengeId, e);
                } else {
                    console.warn('Could not find challenge ID for button:', this);
                }
            }, false);
        }
    });
    
    // Initialize animations
    new ChallengesAnimations();
    
    console.log('Challenges initialized');
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(20px); }
    }
    
    @keyframes sparkle {
        0% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1.2); }
        100% { opacity: 0; transform: scale(0.5); }
    }
    
    @keyframes growthParticleFloat {
        0% { transform: translateY(0) rotate(0deg); opacity: 0; }
        10% { opacity: 0.6; }
        90% { opacity: 0.6; }
        100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.7);
        transform: scale(0);
        animation: ripple 0.6s linear;
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
