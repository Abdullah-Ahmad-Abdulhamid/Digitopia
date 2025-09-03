// ===== Enhanced Skills Page JavaScript with Growth Theme =====

class SkillsPageManager {
    constructor() {
        this.currentSkills = [];
        this.searchTimeout = null;
        this.currentPage = 1;
        this.skillsPerPage = 9;
        this.isLoading = false;
        this.init();
    }

    init() {
        this.setupAOS();
        this.setupTiltEffect();
        this.setupSearch();
        this.setupFilters();
        this.setupLoadMore();
        this.setupSkillCards();
        this.setupKeyboardNavigation();
        this.setupPerformanceOptimizations();
        this.storeOriginalSkills();
        console.log('🌱 Skills Page Manager initialized successfully!');
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
                },
                startEvent: 'DOMContentLoaded',
                animatedClassName: 'aos-animate',
                useClassNames: false,
                disableMutationObserver: false,
                debounceDelay: 50,
                throttleDelay: 99,
            });
        }
    }

    // Setup Vanilla Tilt for skill cards
    setupTiltEffect() {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"), {
                max: 8,
                speed: 400,
                scale: 1.02,
                perspective: 1000,
                transition: true,
                "max-glare": 0.1,
                "glare-prerender": false,
                gyroscope: true
            });
        }
    }

    // Enhanced Search Functionality
    setupSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const searchSuggestions = document.getElementById('searchSuggestions');

        if (!searchInput) return;

        // Real-time search with debouncing
        searchInput.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            const query = e.target.value.trim().toLowerCase();
            
            if (query.length > 0) {
                this.searchTimeout = setTimeout(() => {
                    this.showSearchSuggestions(query);
                    this.performInstantSearch(query);
                }, 300);
            } else {
                this.hideSearchSuggestions();
                this.showAllSkills();
            }
        });

        // Handle search input interactions
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.style.animation = 'searchFocus 0.5s ease-out';
        });

        searchInput.addEventListener('blur', () => {
            setTimeout(() => this.hideSearchSuggestions(), 200);
        });

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.performFullSearch(searchInput.value.trim());
                this.hideSearchSuggestions();
            } else if (e.key === 'Escape') {
                searchInput.value = '';
                this.hideSearchSuggestions();
                this.showAllSkills();
            }
        });

        // Search button
        if (searchButton) {
            searchButton.addEventListener('click', () => {
                const query = searchInput.value.trim();
                if (query) {
                    this.performFullSearch(query);
                    this.hideSearchSuggestions();
                }
            });
        }
    }

    // Store original skills for filtering
    storeOriginalSkills() {
        const skillCards = document.querySelectorAll('.skill-card-container');
        this.currentSkills = Array.from(skillCards).map(card => ({
            element: card,
            title: card.querySelector('.skill-title')?.textContent.toLowerCase() || '',
            category: card.querySelector('.skill-category')?.textContent.toLowerCase() || '',
            description: card.querySelector('.skill-description')?.textContent.toLowerCase() || ''
        }));
    }

    // Show search suggestions with animation
    showSearchSuggestions(query) {
        const searchSuggestions = document.getElementById('searchSuggestions');
        if (!searchSuggestions || query.length < 2) {
            this.hideSearchSuggestions();
            return;
        }

        const suggestions = this.generateSuggestions(query);
        if (suggestions.length === 0) {
            this.hideSearchSuggestions();
            return;
        }

        let html = '';
        suggestions.slice(0, 5).forEach(suggestion => {
            html += `
                <div class="suggestion-item" onclick="skillsManager.applySuggestion('${suggestion}')">
                    <i class="fas fa-search me-2 text-muted"></i>
                    ${this.highlightText(suggestion, query)}
                </div>
            `;
        });

        searchSuggestions.innerHTML = html;
        searchSuggestions.style.display = 'block';
        
        // Animate suggestions
        searchSuggestions.style.opacity = '0';
        searchSuggestions.style.transform = 'translateY(-10px)';
        
        requestAnimationFrame(() => {
            searchSuggestions.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            searchSuggestions.style.opacity = '1';
            searchSuggestions.style.transform = 'translateY(0)';
        });
    }

    hideSearchSuggestions() {
        const searchSuggestions = document.getElementById('searchSuggestions');
        if (searchSuggestions) {
            searchSuggestions.style.display = 'none';
        }
    }

    generateSuggestions(query) {
        const suggestions = new Set();
        
        this.currentSkills.forEach(skill => {
            if (skill.title.includes(query)) {
                suggestions.add(skill.title);
            }
            if (skill.category.includes(query)) {
                suggestions.add(skill.category);
            }
        });
        
        return Array.from(suggestions).slice(0, 5);
    }

    highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong style="color: var(--leaf-green);">$1</strong>');
    }

    applySuggestion(suggestion) {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = suggestion;
        this.hideSearchSuggestions();
        this.performFullSearch(suggestion);
    }

    // Instant search functionality
    performInstantSearch(query) {
        if (query.length < 2) {
            this.showAllSkills();
            return;
        }

        let visibleCount = 0;
        this.currentSkills.forEach(skill => {
            const matches = skill.title.includes(query) || 
                          skill.category.includes(query) || 
                          skill.description.includes(query);
            
            if (matches) {
                skill.element.style.display = 'block';
                skill.element.style.animation = 'skillCardAppear 0.6s ease-out';
                visibleCount++;
            } else {
                skill.element.style.display = 'none';
            }
        });

        this.updateResultsCount(visibleCount);
    }

    // Full search with loading state
    performFullSearch(query) {
        if (!query || query.length < 2) {
            this.showToast('يرجى إدخال كلمة بحث أطول من حرفين', 'warning');
            return;
        }

        this.showSearchLoading();

        // Simulate API call with realistic delay
        setTimeout(() => {
            const results = this.currentSkills.filter(skill => 
                skill.title.includes(query.toLowerCase()) || 
                skill.category.includes(query.toLowerCase()) || 
                skill.description.includes(query.toLowerCase())
            );

            this.displaySearchResults(results, query);
        }, 1000);
    }

    showAllSkills() {
        this.currentSkills.forEach(skill => {
            skill.element.style.display = 'block';
        });
        this.updateResultsCount();
    }

    showSearchLoading() {
        const skillsGrid = document.getElementById('skillsGrid');
        if (skillsGrid) {
            skillsGrid.innerHTML = `
                <div class="search-loading">
                    <div class="loading-animation">
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                    </div>
                    <p class="loading-text">جاري البحث عن المهارات...</p>
                </div>
            `;
        }
    }

    displaySearchResults(results, query) {
        const skillsGrid = document.getElementById('skillsGrid');
        
        if (results.length === 0) {
            skillsGrid.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                        <div class="icon-growth-ring"></div>
                    </div>
                    <h3 class="empty-title">لا توجد نتائج للبحث</h3>
                    <p class="empty-description">
                        لم نجد مهارات تطابق "<strong>${query}</strong>".<br>
                        جرب كلمات أخرى أو تصفح التصنيفات المختلفة.
                    </p>
                    <div class="empty-actions">
                        <button class="btn btn-hero-primary btn-lg" onclick="skillsManager.clearSearch()">
                            <i class="fas fa-times me-2"></i>مسح البحث
                            <div class="btn-sparkle"></div>
                        </button>
                    </div>
                </div>
            `;
        } else {
            // Re-populate with filtered results
            skillsGrid.innerHTML = '';
            results.forEach((result, index) => {
                const clonedElement = result.element.cloneNode(true);
                clonedElement.style.animationDelay = `${index * 100}ms`;
                clonedElement.style.opacity = '0';
                clonedElement.style.transform = 'translateY(30px)';
                skillsGrid.appendChild(clonedElement);
                
                // Animate in
                setTimeout(() => {
                    clonedElement.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                    clonedElement.style.opacity = '1';
                    clonedElement.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Re-initialize tilt effect
            this.setupTiltEffect();
            this.setupSkillCards();
        }
        
        this.updateResultsCount(results.length);
    }

    clearSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = '';
        this.hideSearchSuggestions();
        location.reload();
    }

    updateResultsCount(count = null) {
        const actualCount = count !== null ? count : this.currentSkills.filter(skill => 
            skill.element.style.display !== 'none'
        ).length;
        
        document.title = `المهارات (${actualCount}) - مهاراتي`;
    }

    // Filter tabs functionality
    setupFilters() {
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active from all tabs
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                
                // Add active to clicked tab with animation
                tab.classList.add('active');
                tab.style.animation = 'filterActivate 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                
                // Show loading state
                this.showFilterLoading();
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = tab.href;
                }, 800);
            });

            // Add hover sound feedback (visual)
            tab.addEventListener('mouseenter', () => {
                tab.style.transform = 'translateY(-3px) scale(1.02)';
            });

            tab.addEventListener('mouseleave', () => {
                if (!tab.classList.contains('active')) {
                    tab.style.transform = '';
                }
            });
        });
    }

    showFilterLoading() {
        const skillsGrid = document.getElementById('skillsGrid');
        if (skillsGrid) {
            skillsGrid.innerHTML = `
                <div class="filter-loading">
                    <div class="loading-animation">
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                        <div class="loading-cube"></div>
                    </div>
                    <p class="loading-text">جاري تحديث المهارات...</p>
                </div>
            `;
        }
    }

    // Load more functionality
    setupLoadMore() {
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                if (this.isLoading) return;
                
                this.isLoading = true;
                const originalContent = loadMoreBtn.innerHTML;
                
                loadMoreBtn.innerHTML = '<i class="fas fa-seedling fa-spin me-2"></i>جاري النمو...';
                loadMoreBtn.disabled = true;
                loadMoreBtn.style.opacity = '0.7';
                
                // Simulate API call
                setTimeout(() => {
                    this.loadMoreSkills(loadMoreBtn, originalContent);
                }, 1500);
            });
        }
    }

    loadMoreSkills(button, originalContent) {
        // In a real implementation, this would fetch from the server
        // For demo purposes, we'll simulate loading more skills
        
        const skillsGrid = document.getElementById('skillsGrid');
        const existingSkills = skillsGrid.querySelectorAll('.skill-card-container');
        const totalSkills = existingSkills.length;
        
        // Create mock additional skills
        if (totalSkills < 20) { // Simulate having more skills to load
            for (let i = 0; i < 3; i++) {
                const mockSkill = this.createMockSkillCard(totalSkills + i + 1);
                skillsGrid.appendChild(mockSkill);
                
                // Animate in
                setTimeout(() => {
                    mockSkill.style.opacity = '1';
                    mockSkill.style.transform = 'translateY(0)';
                }, i * 200);
            }
            
            // Re-initialize tilt effect for new cards
            this.setupTiltEffect();
            
            button.innerHTML = originalContent;
            button.disabled = false;
            button.style.opacity = '1';
            this.isLoading = false;
            this.currentPage++;
            
            this.showToast('تم تحميل مهارات جديدة بنجاح! 🌱', 'success');
        } else {
            button.innerHTML = '<i class="fas fa-check me-2"></i>تم تحميل جميع المهارات';
            button.disabled = true;
            
            setTimeout(() => {
                button.style.animation = 'fadeOutDown 0.5s ease-in';
                setTimeout(() => button.style.display = 'none', 500);
            }, 2000);
            
            this.isLoading = false;
        }
    }

    createMockSkillCard(index) {
        const skillCard = document.createElement('div');
        skillCard.className = 'skill-card-container';
        skillCard.style.opacity = '0';
        skillCard.style.transform = 'translateY(30px)';
        skillCard.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        
        const mockTitles = [
            'مهارة إدارة الوقت الفعال',
            'تطوير الثقة بالنفس',
            'فن التواصل الإيجابي',
            'استراتيجيات الادخار الذكي',
            'مهارات القيادة الشخصية'
        ];
        
        const mockCategories = ['التطوير الشخصي', 'إدارة المال', 'التواصل', 'القيادة'];
        const mockIcons = ['fas fa-lightbulb', 'fas fa-coins', 'fas fa-comments', 'fas fa-crown'];
        
        const title = mockTitles[index % mockTitles.length];
        const category = mockCategories[index % mockCategories.length];
        const icon = mockIcons[index % mockIcons.length];
        
        skillCard.innerHTML = `
            <div class="skill-card-growth" data-tilt>
                <div class="skill-header">
                    <div class="skill-icon-container">
                        <div class="skill-icon-wrapper">
                            <i class="${icon} skill-icon"></i>
                        </div>
                        <div class="skill-glow"></div>
                    </div>
                    <div class="skill-meta">
                        <h5 class="skill-title">${title}</h5>
                        <span class="skill-category">${category}</span>
                    </div>
                </div>
                
                <div class="skill-body">
                    <p class="skill-description">مهارة مهمة لتطوير قدراتك الشخصية والمهنية في الحياة العملية والاجتماعية...</p>
                    
                    <div class="skill-features">
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>30 دقيقة</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-eye"></i>
                            <span>${Math.floor(Math.random() * 1000) + 100} مشاهدة</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>${Math.floor(Math.random() * 500) + 50} متعلم</span>
                        </div>
                    </div>
                    
                    <div class="skill-badges">
                        <span class="difficulty-badge difficulty-beginner">مبتدئ</span>
                        <span class="rating-badge">
                            <i class="fas fa-star"></i>
                            4.${Math.floor(Math.random() * 9) + 1}
                        </span>
                    </div>
                </div>
                
                <div class="skill-footer">
                    <a href="#" class="btn btn-skill-growth w-100">
                        <i class="fas fa-play me-2"></i>ابدأ رحلة التعلم
                        <div class="btn-growth-effect"></div>
                    </a>
                </div>
                
                <div class="card-shine"></div>
                <div class="growth-indicator">
                    <div class="growth-line"></div>
                </div>
            </div>
        `;
        
        return skillCard;
    }

    // Enhanced skill card interactions
    setupSkillCards() {
        document.querySelectorAll('.skill-card-growth').forEach(card => {
            // Enhanced hover effects
            card.addEventListener('mouseenter', () => {
                this.createHoverGlowEffect(card);
            });

            card.addEventListener('mouseleave', () => {
                this.removeHoverGlowEffect(card);
            });

            // Click interaction with ripple effect
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.btn')) {
                    const skillTitle = card.querySelector('.skill-title')?.textContent;
                    console.log('Skill card clicked:', skillTitle);
                    
                    this.createGrowthRipple(e, card);
                }
            });

            // Button interactions
            const buttons = card.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.createButtonGrowthEffect(e, btn);
                });
            });
        });
    }

    createHoverGlowEffect(card) {
        const glow = card.querySelector('.skill-glow');
        if (glow) {
            glow.style.opacity = '0.3';
        }
        
        const growthLine = card.querySelector('.growth-line');
        if (growthLine) {
            growthLine.style.width = '100%';
        }
    }

    removeHoverGlowEffect(card) {
        const glow = card.querySelector('.skill-glow');
        if (glow) {
            glow.style.opacity = '0';
        }
        
        const growthLine = card.querySelector('.growth-line');
        if (growthLine) {
            growthLine.style.width = '0';
        }
    }

    createGrowthRipple(event, element) {
        const ripple = document.createElement('div');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
            transform: scale(0);
            animation: growthRipple 0.8s ease-out;
            left: ${event.clientX - rect.left - size/2}px;
            top: ${event.clientY - rect.top - size/2}px;
            width: ${size}px;
            height: ${size}px;
            pointer-events: none;
            z-index: 1000;
        `;
        
        element.style.position = 'relative';
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 800);
    }

    createButtonGrowthEffect(event, button) {
        const effect = button.querySelector('.btn-growth-effect');
        if (effect) {
            effect.style.width = '0';
            effect.style.height = '0';
            
            requestAnimationFrame(() => {
                effect.style.width = '300px';
                effect.style.height = '300px';
                
                setTimeout(() => {
                    effect.style.width = '0';
                    effect.style.height = '0';
                }, 400);
            });
        }
    }

    // Enhanced keyboard navigation
    setupKeyboardNavigation() {
        let focusIndex = -1;
        const focusableCards = document.querySelectorAll('.skill-card-growth');

        document.addEventListener('keydown', (e) => {
            // Quick search shortcut
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                    this.showToast('استخدم Ctrl+/ للبحث السريع', 'info', 2000);
                }
            }
            
            // Arrow key navigation
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                if (document.activeElement.id === 'searchInput') return;
                
                e.preventDefault();
                
                if (e.key === 'ArrowRight') {
                    focusIndex = (focusIndex + 1) % focusableCards.length;
                } else {
                    focusIndex = (focusIndex - 1 + focusableCards.length) % focusableCards.length;
                }
                
                this.focusSkillCard(focusableCards[focusIndex]);
            }
            
            // Enter key to open skill
            if (e.key === 'Enter' && document.activeElement.classList.contains('skill-card-growth')) {
                const startButton = document.activeElement.querySelector('.btn-skill-growth, .btn-premium-upgrade');
                if (startButton) {
                    startButton.click();
                }
            }
            
            // Escape to clear search
            if (e.key === 'Escape') {
                const searchInput = document.getElementById('searchInput');
                if (searchInput && searchInput.value) {
                    this.clearSearch();
                }
            }
        });

        // Make skill cards focusable
        focusableCards.forEach((card, index) => {
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');
            const title = card.querySelector('.skill-title')?.textContent || '';
            card.setAttribute('aria-label', `مهارة: ${title}`);
        });
    }

    focusSkillCard(card) {
        card.focus();
        card.style.outline = '3px solid rgba(16, 185, 129, 0.6)';
        card.style.outlineOffset = '4px';
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove outline after a delay
        setTimeout(() => {
            card.addEventListener('blur', () => {
                card.style.outline = 'none';
            }, { once: true });
        }, 100);
    }

    // Performance optimizations
    setupPerformanceOptimizations() {
        // Intersection Observer for lazy animations
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const element = entry.target;
                if (entry.isIntersecting) {
                    element.classList.add('animate-active');
                    element.style.willChange = 'transform, opacity';
                    
                    // Animate progress bars
                    const progressBar = element.querySelector('.progress-fill');
                    if (progressBar) {
                        const width = progressBar.style.width;
                        progressBar.style.width = '0%';
                        setTimeout(() => {
                            progressBar.style.width = width;
                        }, 300);
                    }
                } else {
                    element.classList.remove('animate-active');
                    element.style.willChange = 'auto';
                }
            });
        }, { threshold: 0.1 });

        // Observe skill cards for performance
        document.querySelectorAll('.skill-card-growth').forEach(card => {
            animationObserver.observe(card);
        });

        // Debounce scroll events
        let isScrolling = false;
        
        window.addEventListener('scroll', this.debounce(() => {
            const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
            document.documentElement.style.setProperty('--scroll-progress', `${scrollPercent}%`);
        }, 10), { passive: true });

        // Pause animations when page is not visible
        document.addEventListener('visibilitychange', () => {
            const isHidden = document.hidden;
            const expensiveElements = document.querySelectorAll('.skills-ecosystem, .floating-skill, .knowledge-hub');
            
            expensiveElements.forEach(element => {
                element.style.animationPlayState = isHidden ? 'paused' : 'running';
            });
        });
    }

    // Utility function for debouncing
    debounce(func, wait) {
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

    // Enhanced toast notification system
    showToast(message, type = 'info', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `skills-toast toast-${type}`;
        
        const typeIcons = {
            success: 'check-circle',
            warning: 'exclamation-triangle',
            error: 'times-circle',
            info: 'info-circle'
        };
        
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${typeIcons[type]} toast-icon"></i>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in with growth effect
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0) scale(1)';
        });
        
        // Auto remove with shrink effect
        setTimeout(() => {
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 400);
        }, duration);
        
        return toast;
    }

    // Counter animation for stats
    animateCounters() {
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

                    setTimeout(() => updateCounter(), 300);
                    counterObserver.unobserve(counter);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.counter').forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    createCelebrationEffect(element) {
        for (let i = 0; i < 6; i++) {
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
            }, i * 100);
        }
    }
}

// Add additional animation styles
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
@keyframes filterActivate {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes fadeOutDown {
    0% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(20px); }
}

@keyframes celebrationParticle {
    0% {
        opacity: 1;
        transform: scale(1) translate(0, 0) rotate(0deg);
    }
    100% {
        opacity: 0;
        transform: scale(1.5) translate(${Math.random() * 100 - 50}px, ${-50 - Math.random() * 50}px) rotate(${Math.random() * 360}deg);
    }
}

@keyframes growthRipple {
    0% {
        transform: scale(0);
        opacity: 0.6;
    }
    100% {
        transform: scale(4);
        opacity: 0;
    }
}

/* Enhanced button states */
.btn-skill-growth:focus,
.btn-premium-upgrade:focus {
    outline: 3px solid rgba(16, 185, 129, 0.5);
    outline-offset: 3px;
}

.btn-skill-growth:active,
.btn-premium-upgrade:active {
    transform: translateY(-1px) scale(0.98);
}

/* Enhanced card focus states */
.skill-card-growth:focus {
    outline: 3px solid rgba(16, 185, 129, 0.6);
    outline-offset: 4px;
    border-radius: 25px;
}

/* Loading spinner enhancement */
.fa-spin {
    animation: growthSpin 2s linear infinite;
}

@keyframes growthSpin {
    0% { transform: rotate(0deg) scale(1); }
    50% { transform: rotate(180deg) scale(1.1); }
    100% { transform: rotate(360deg) scale(1); }
}

/* Enhanced responsive animations */
@media (max-width: 768px) {
    .skill-card-growth:hover {
        transform: translateY(-8px);
    }
    
    .skills-toast {
        right: 10px;
        left: 10px;
        max-width: none;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .skills-ecosystem,
    .knowledge-hub,
    .floating-skill,
    .skill-icon-wrapper,
    .premium-badge {
        animation: none !important;
    }
    
    .skill-card-growth:hover {
        transform: translateY(-5px) !important;
    }
    
    .loading-cube {
        animation: none !important;
    }
}
`;

document.head.appendChild(additionalStyles);

// Initialize the Skills Page Manager
let skillsManager;

document.addEventListener('DOMContentLoaded', () => {
    skillsManager = new SkillsPageManager();
    
    // Initialize counter animations
    skillsManager.animateCounters();
    
    // Make manager globally available
    window.skillsManager = skillsManager;
});

// Handle page visibility for performance
document.addEventListener('visibilitychange', () => {
    const isHidden = document.hidden;
    const animations = document.querySelectorAll('.skills-ecosystem, .floating-skill, .knowledge-hub');
    
    animations.forEach(element => {
        element.style.animationPlayState = isHidden ? 'paused' : 'running';
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { SkillsPageManager };
}