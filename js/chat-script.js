// ===== Enhanced Educational Chat System with Growth Animations =====

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS with educational theme
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
            debounceDelay: 50,
            throttleDelay: 99,
        });
    }

    // Initialize Enhanced Chat System
    window.maharatiChat = new EnhancedMharatiChat();

    // Initialize Navigation Effects (matching index page)
    initializeEducationalNavigation();

    // Initialize Responsive Sidebar
    initializeEducationalSidebar();

    // Initialize Growth Particle System
    initializeGrowthParticles();

    console.log('🎯 Enhanced educational chat system initialized successfully!');
});

class EnhancedMharatiChat {
    constructor() {
        this.sessionId = this.generateSessionId();
        this.isTyping = false;
        this.messageCount = 0;
        this.suggestions = [];
        this.isConnected = true;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.showEnhancedWelcomeMessage();
        this.initializeScrollHandler();
        this.initializeEducationalSuggestions();
        this.initializeConnectionMonitoring();
        this.initializeKeyboardShortcuts();
        this.initializeAccessibilityFeatures();
    }
    
    generateSessionId() {
        return 'educational_chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    bindEvents() {
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const chatForm = document.getElementById('chatForm');
        const charCount = document.getElementById('charCount');
        const clearChat = document.getElementById('clearChat');
        const scrollToBottom = document.getElementById('scrollToBottom');
        const attachmentBtn = document.getElementById('attachmentBtn');
        
        // Enhanced form submission with growth animation
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessageWithGrowthEffect();
        });

        // Enhanced character count with growth feedback
        messageInput.addEventListener('input', (e) => {
            const count = e.target.value.length;
            charCount.textContent = count;
            
            // Growth-themed color changes
            if (count > 800) {
                charCount.style.color = '#ef4444';
                charCount.style.background = 'rgba(239, 68, 68, 0.1)';
            } else if (count > 600) {
                charCount.style.color = '#f59e0b';
                charCount.style.background = 'rgba(245, 158, 11, 0.1)';
            } else {
                charCount.style.color = '#6b7280';
                charCount.style.background = 'rgba(16, 185, 129, 0.05)';
            }
            
            // Show educational suggestions
            this.showEducationalSuggestions(e.target.value);
            
            // Add typing animation to input
            this.addInputGrowthEffect(e.target);
        });

        // Enhanced quick action buttons with growth effects
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.currentTarget.dataset.action;
                this.insertEducationalQuickAction(action);
                this.addGrowthClickEffect(e.currentTarget);
            });
        });

        // Enhanced sample questions with growth animations
        document.querySelectorAll('.sample-question-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const question = e.currentTarget.dataset.question;
                messageInput.value = question;
                this.sendMessageWithGrowthEffect();
                this.addQuestionGrowthEffect(e.currentTarget);
            });
        });

        // Enhanced topic cards with educational animations
        document.querySelectorAll('.topic-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const topic = e.currentTarget.dataset.topic;
                this.insertEducationalTopicPrompt(topic);
                this.addTopicGrowthEffect(e.currentTarget);
            });
        });

        // Enhanced clear chat with growth confirmation
        if (clearChat) {
            clearChat.addEventListener('click', () => {
                this.clearChatWithGrowthAnimation();
            });
        }

        // Enhanced scroll to bottom with growth animation
        if (scrollToBottom) {
            scrollToBottom.addEventListener('click', () => {
                this.scrollToBottomWithGrowth(true);
            });
        }

        // Attachment button (for future file upload features)
        if (attachmentBtn) {
            attachmentBtn.addEventListener('click', () => {
                this.showAttachmentOptions();
            });
        }

        // Auto-focus with growth effect
        messageInput.focus();
        this.addInputFocusGrowthEffect(messageInput);
    }
    
    async sendMessageWithGrowthEffect() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message || this.isTyping) return;
        
        // Enhanced send button animation with growth theme
        this.animateGrowthSendButton();
        
        this.displayMessage(message, 'user');
        messageInput.value = '';
        document.getElementById('charCount').textContent = '0';
        this.isTyping = true;
        
        this.showEducationalTypingIndicator();
        this.hideScrollButton();
        
        try {
            const response = await fetch('api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    session_id: this.sessionId,
                    timestamp: Date.now(),
                    message_count: this.messageCount,
                    context: 'educational_growth'
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            this.hideEducationalTypingIndicator();

            if (data.success) {
                // Natural delay with growth animation
                const delay = 800 + Math.random() * 1200;
                setTimeout(() => {
                    this.displayMessage(data.response, 'bot');
                    this.messageCount++;
                    
                    // Show educational suggestions if provided
                    if (data.suggestions) {
                        this.updateEducationalSuggestions(data.suggestions);
                    }
                    
                    // Add celebration effect for meaningful responses
                    if (data.response.length > 100) {
                        this.createLearningCelebration();
                    }
                }, delay);
                
            } else {
                this.displayMessage(
                    data.error || 'عذراً، حدث خطأ في فهم رسالتك. دعنا نحاول مرة أخرى! ⚠️', 
                    'bot', 
                    true
                );
                console.error('API Error:', data);
            }
        } catch (error) {
            console.error('Chat error:', error);
            this.hideEducationalTypingIndicator();

            let errorMessage = 'عذراً، حدث خطأ في إرسال الرسالة. ⚠️';
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'تعذر الاتصال بالخادم. تحقق من اتصال الإنترنت وسنحاول مرة أخرى. 📶';
            } else if (error.message) {
                errorMessage = `خطأ: ${error.message}`;
            }

            this.displayMessage(errorMessage, 'bot', true);
        } finally {
            this.isTyping = false;
            messageInput.focus();
            this.addInputFocusGrowthEffect(messageInput);
        }
    }
    
    displayMessage(message, type, isError = false) {
        const messagesArea = document.querySelector('.messages-scroll-area');
        const messageDiv = document.createElement('div');
        
        const timestamp = new Date().toLocaleTimeString('ar-EG', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        messageDiv.className = `message-bubble ${type}-message`;
        
        if (isError) {
            messageDiv.classList.add('error-message');
        }
        
        // Enhanced message formatting with educational context
        const formattedMessage = this.formatEducationalMessage(message);
        
        messageDiv.innerHTML = `
            <div class="message-content">${formattedMessage}</div>
            <div class="message-time">${timestamp}</div>
        `;
        
        // Enhanced entrance animation with growth theme
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateY(30px) scale(0.8)';
        
        messagesArea.appendChild(messageDiv);
        
        // Trigger growth animation
        requestAnimationFrame(() => {
            messageDiv.style.transition = 'all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            messageDiv.style.opacity = '1';
            messageDiv.style.transform = 'translateY(0) scale(1)';
        });
        
        this.scrollToBottomWithGrowth();
        this.updateScrollButton();
        
        // Enhanced message sound with educational theme
        this.playEducationalMessageSound(type);
        
        // Add message delivered animation
        setTimeout(() => {
            messageDiv.style.animation = 'messageDelivered 0.3s ease-out';
        }, 500);
    }
    
    formatEducationalMessage(message) {
        // Convert URLs to educational-themed links
        message = message.replace(/(https?:\/\/[^\s]+)/g, 
            '<a href="$1" target="_blank" rel="noopener noreferrer" class="message-link">$1 🔗</a>');
        
        // Convert line breaks
        message = message.replace(/\n/g, '<br>');
        
        // Highlight educational terms with growth theme
        const educationalTerms = [
            'تطوير', 'نمو', 'مهارة', 'تعلم', 'تدريب', 'تحسين', 'تقدم',
            'جنيه', 'مصر', 'القاهرة', 'الإسكندرية', 'الجيزة', 'راتب', 'وظيفة',
            'مقابلة', 'بنك', 'حساب', 'ادخار', 'استثمار', 'مشروع', 'ميزانية',
            'تواصل', 'ثقة', 'قيادة', 'إدارة', 'تخطيط', 'هدف', 'حلم', 'طموح'
        ];
        
        educationalTerms.forEach(term => {
            const regex = new RegExp(`\\b${term}\\b`, 'gi');
            message = message.replace(regex, `<mark class="educational-highlight">$&</mark>`);
        });
        
        // Format numbers as currency with Egyptian context
        message = message.replace(/(\d+)\s*(جنيه|ج\.م|pound|جم)/gi, 
            '<span class="currency-amount">💰 $1 $2</span>');
        
        // Add growth-themed emoji support
        const growthEmojiMap = {
            'مبروك': '🎉✨',
            'شكرا': '🙏✨',
            'أهلا': '👋🌟',
            'تمام': '✅🎯',
            'ممتاز': '⭐🚀',
            'رائع': '🌟💫',
            'نجح': '🏆✨',
            'تطوير': '📈🎯',
            'نمو': '🎯📊',
            'هدف': '🎯✨'
        };
        
        Object.keys(growthEmojiMap).forEach(word => {
            const regex = new RegExp(`\\b${word}\\b`, 'gi');
            message = message.replace(regex, `$& ${growthEmojiMap[word]}`);
        });
        
        // Highlight steps and tips
        message = message.replace(/(\d+[\.\)]?\s*[^\d][^\.!?]*[\.!?])/g, 
            '<div class="message-step">$1</div>');
        
        return message;
    }
    
    showEducationalTypingIndicator() {
        const messagesArea = document.querySelector('.messages-scroll-area');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'message-bubble bot-message typing-indicator';
        typingDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="typing-dots me-3">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
                <span class="typing-text">✨ مساعد مهاراتي يفكر في إجابة مفيدة...</span>
                <div class="thinking-animation ms-2">
                    <i class="fas fa-brain" style="animation: thinkingPulse 1s ease-in-out infinite;"></i>
                </div>
            </div>
        `;
        
        messagesArea.appendChild(typingDiv);
        this.scrollToBottomWithGrowth();
        
        // Show typing in header with growth effect
        const headerTyping = document.querySelector('.typing-indicator-header');
        if (headerTyping) {
            headerTyping.classList.remove('d-none');
        }
        
        // Enhanced typing sound with educational theme
        this.playEducationalTypingSound();
    }
    
    hideEducationalTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.style.transition = 'all 0.3s ease-out';
            typingIndicator.style.opacity = '0';
            typingIndicator.style.transform = 'translateY(-20px) scale(0.8)';
            setTimeout(() => typingIndicator.remove(), 300);
        }
        
        // Hide typing in header
        const headerTyping = document.querySelector('.typing-indicator-header');
        if (headerTyping) {
            headerTyping.classList.add('d-none');
        }
        
        this.stopEducationalTypingSound();
    }
    
    showEnhancedWelcomeMessage() {
        setTimeout(() => {
            const welcomeMessage = `
                <div class="welcome-message">
                    <div class="welcome-icon">🎯</div>
                    <h4>أهلاً وسهلاً في رحلة النمو معنا!</h4>
                    <p>
                        أنا مساعد مهاراتي الذكي 🤖 مصمم خصيصاً لمساعدة الشباب المصري في تطوير مهاراتهم الحياتية.
                        سأكون معك في كل خطوة من رحلة نموك الشخصي والمهني.
                    </p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="welcome-feature" data-topic="money">
                                💰 إدارة الأموال والادخار الذكي
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="welcome-feature" data-topic="work">
                                💼 مهارات العمل والتطوير المهني
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="welcome-feature" data-topic="communication">
                                🗣️ التواصل والثقة بالنفس
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="welcome-feature" data-topic="health">
                                🧠 الصحة النفسية والتوازن
                            </div>
                        </div>
                    </div>
                    <p class="mt-4" style="color: var(--forest-green); font-weight: 600;">
                        🌟 ابدأ بسؤال عن أي مهارة تريد تطويرها، وسأساعدك بأمثلة عملية من واقع الحياة المصرية!
                    </p>
                </div>
            `;
            
            const messagesArea = document.querySelector('.messages-scroll-area');
            messagesArea.innerHTML = welcomeMessage;
            
            // Enhanced welcome animation with growth theme
            const welcomeEl = document.querySelector('.welcome-message');
            if (welcomeEl) {
                welcomeEl.style.opacity = '0';
                welcomeEl.style.transform = 'translateY(50px) scale(0.9)';
                
                requestAnimationFrame(() => {
                    welcomeEl.style.transition = 'all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                    welcomeEl.style.opacity = '1';
                    welcomeEl.style.transform = 'translateY(0) scale(1)';
                });
                
                // Bind welcome feature clicks
                welcomeEl.querySelectorAll('.welcome-feature').forEach(feature => {
                    feature.addEventListener('click', (e) => {
                        const topic = e.currentTarget.dataset.topic;
                        if (topic) {
                            this.insertEducationalTopicPrompt(topic);
                            this.addWelcomeFeatureGrowthEffect(e.currentTarget);
                        }
                    });
                });
            }
        }, 1200);
    }
    
    initializeScrollHandler() {
        const messagesArea = document.querySelector('.messages-scroll-area');
        if (!messagesArea) return;
        
        let scrollTimeout;
        messagesArea.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.updateScrollButton();
            }, 100);
        }, { passive: true });
    }
    
    updateScrollButton() {
        const messagesArea = document.querySelector('.messages-scroll-area');
        const scrollButton = document.getElementById('scrollToBottom');
        
        if (!messagesArea || !scrollButton) return;
        
        const isNearBottom = messagesArea.scrollTop >= 
            (messagesArea.scrollHeight - messagesArea.clientHeight - 150);
        
        if (isNearBottom) {
            scrollButton.classList.remove('visible');
        } else {
            scrollButton.classList.add('visible');
        }
    }
    
    hideScrollButton() {
        const scrollButton = document.getElementById('scrollToBottom');
        if (scrollButton) {
            scrollButton.classList.remove('visible');
        }
    }
    
    scrollToBottomWithGrowth(smooth = true) {
        const messagesArea = document.querySelector('.messages-scroll-area');
        if (!messagesArea) return;
        
        const scrollOptions = {
            top: messagesArea.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        };
        
        messagesArea.scrollTo(scrollOptions);
        
        // Growth animation for scroll
        if (smooth) {
            messagesArea.style.animation = 'scrollGrowthFlow 0.5s ease-out';
            setTimeout(() => {
                messagesArea.style.animation = '';
            }, 500);
        }
        
        // Update scroll button after scrolling
        setTimeout(() => {
            this.updateScrollButton();
        }, 400);
    }
    
    clearChatWithGrowthAnimation() {
        const clearBtn = document.getElementById('clearChat');
        
        // Show confirmation with growth theme
        if (confirm('✨ هل تريد بداية جديدة؟ سيتم مسح المحادثة وبدء رحلة نمو جديدة.')) {
            // Add clear animation
            const messagesArea = document.querySelector('.messages-scroll-area');
            messagesArea.style.animation = 'messagesGrowthFadeOut 0.5s ease-out';
            
            setTimeout(() => {
                messagesArea.innerHTML = '';
                this.messageCount = 0;
                this.showEnhancedWelcomeMessage();
                
                // Show success with growth theme
                this.showEducationalToast('🎯 بداية جديدة! مرحباً برحلة نمو جديدة', 'success');
                
                // Add clear button success animation
                this.addGrowthSuccessEffect(clearBtn);
            }, 500);
        }
    }
    
    insertEducationalQuickAction(action) {
        const messageInput = document.getElementById('messageInput');
        const prompts = {
            money: '🎯 أريد تطوير مهاراتي في إدارة الأموال والادخار بطريقة ذكية ومناسبة للشباب المصري',
            work: '💼 أحتاج مساعدة في تطوير مهاراتي المهنية والحصول على فرص عمل أفضل في السوق المصري',
            communication: '🗣️ كيف يمكنني تحسين مهارات التواصل والثقة بالنفس في التعامل مع الآخرين؟',
            health: '🧠 أريد نصائح للتعامل مع الضغوط النفسية وتحسين صحتي النفسية'
        };
        
        messageInput.value = prompts[action] || '';
        messageInput.focus();
        this.addInputGrowthEffect(messageInput);
    }
    
    insertEducationalTopicPrompt(topic) {
        const messageInput = document.getElementById('messageInput');
        const prompts = {
            money: '💰 أريد تعلم كيفية إدارة أموالي بشكل أفضل، ادخار جزء من راتبي، وبناء خطة مالية ذكية للمستقبل بما يناسب الحياة في مصر',
            work: '🚀 أحتاج مساعدة في تطوير مهاراتي المهنية، كتابة سيرة ذاتية قوية، والاستعداد للمقابلات في الشركات المصرية',
            communication: '💬 كيف يمكنني تحسين مهارات التواصل، بناء الثقة بالنفس، وتكوين علاقات إيجابية في العمل والحياة؟',
            health: '🌸 أريد نصائح للتعامل مع الضغوط النفسية، تحسين الصحة النفسية، وإيجاد التوازن في الحياة'
        };
        
        messageInput.value = prompts[topic] || '';
        messageInput.focus();
        this.addInputGrowthEffect(messageInput);
    }
    
    initializeEducationalSuggestions() {
        this.suggestions = [
            'كيف أوفر من راتبي الشهري وأبني صندوق طوارئ؟',
            'ما أفضل طريقة لكتابة سيرة ذاتية تلفت انتباه أصحاب العمل؟',
            'كيف أتعامل مع ضغط العمل والتوتر اليومي؟',
            'أريد بدء مشروع صغير، ما الخطوات الأولى؟',
            'كيف أطور مهارات التواصل والثقة بالنفس؟',
            'ما أفضل طرق الاستثمار للمبتدئين في مصر؟',
            'كيف أنظم وقتي وأزيد إنتاجيتي؟',
            'أحتاج نصائح للتخلص من القلق وبناء الثقة',
            'كيف أتفاوض على راتب أفضل في العمل؟',
            'ما هي مهارات القرن الحادي والعشرين المطلوبة؟',
            'كيف أبني شبكة علاقات مهنية قوية؟',
            'أريد تعلم مهارات جديدة، من أين أبدأ؟'
        ];
    }
    
    showEducationalSuggestions(input) {
        const suggestionsContainer = document.getElementById('inputSuggestions');
        if (!suggestionsContainer || input.length < 3) {
            suggestionsContainer.classList.add('d-none');
            return;
        }
        
        const filteredSuggestions = this.suggestions.filter(s => 
            s.includes(input) || input.split(' ').some(word => 
                word.length > 2 && s.includes(word)
            )
        ).slice(0, 4);
        
        if (filteredSuggestions.length > 0) {
            suggestionsContainer.innerHTML = filteredSuggestions.map(suggestion => 
                `<div class="suggestion-item" data-suggestion="${suggestion}">
                    <i class="fas fa-seedling"></i>
                    <span>${suggestion}</span>
                 </div>`
            ).join('');
            
            suggestionsContainer.classList.remove('d-none');
            
            // Bind click events with growth animations
            suggestionsContainer.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    const suggestion = e.currentTarget.dataset.suggestion;
                    document.getElementById('messageInput').value = suggestion;
                    suggestionsContainer.classList.add('d-none');
                    this.addSuggestionGrowthEffect(e.currentTarget);
                });
            });
        } else {
            suggestionsContainer.classList.add('d-none');
        }
    }
    
    updateEducationalSuggestions(newSuggestions) {
        this.suggestions = [...new Set([...this.suggestions, ...newSuggestions])];
    }
    
    initializeConnectionMonitoring() {
        // Monitor connection status with educational feedback
        window.addEventListener('online', () => {
            this.isConnected = true;
            this.showConnectionStatus('متصل - جاهز للنمو معك! ✨', 'connected');
        });
        
        window.addEventListener('offline', () => {
            this.isConnected = false;
            this.showConnectionStatus('غير متصل - تحقق من الإنترنت 📶', 'disconnected');
        });
    }
    
    showConnectionStatus(message, status) {
        let statusEl = document.querySelector('.connection-status');
        
        if (!statusEl) {
            statusEl = document.createElement('div');
            statusEl.className = 'connection-status';
            document.body.appendChild(statusEl);
        }
        
        statusEl.textContent = message;
        statusEl.className = `connection-status ${status} show`;
        
        setTimeout(() => {
            statusEl.classList.remove('show');
        }, 3000);
    }
    
    initializeKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+Enter to send message
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                this.sendMessageWithGrowthEffect();
            }
            
            // Ctrl+L to clear chat with growth animation
            if (e.ctrlKey && e.key === 'l') {
                e.preventDefault();
                this.clearChatWithGrowthAnimation();
            }
            
            // Ctrl+/ to focus input with growth effect
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                const input = document.getElementById('messageInput');
                if (input) {
                    input.focus();
                    this.addInputFocusGrowthEffect(input);
                }
            }
            
            // Escape to close suggestions and sidebar
            if (e.key === 'Escape') {
                const suggestions = document.getElementById('inputSuggestions');
                const sidebar = document.getElementById('chatSidebar');
                
                if (suggestions && !suggestions.classList.contains('d-none')) {
                    suggestions.classList.add('d-none');
                } else if (sidebar && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }
    
    initializeAccessibilityFeatures() {
        // Enhanced screen reader support
        const messagesArea = document.querySelector('.messages-scroll-area');
        if (messagesArea) {
            messagesArea.setAttribute('aria-live', 'polite');
            messagesArea.setAttribute('aria-label', 'منطقة المحادثة مع المساعد الذكي');
        }
        
        // Keyboard navigation for quick actions
        document.querySelectorAll('.quick-action-btn, .sample-question-btn, .topic-card').forEach(element => {
            element.setAttribute('tabindex', '0');
            element.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    element.click();
                }
            });
        });
        
        // Enhanced focus management
        this.setupEnhancedFocusManagement();
    }
    
    setupEnhancedFocusManagement() {
        const focusableElements = document.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        focusableElements.forEach(element => {
            element.addEventListener('focus', () => {
                element.style.animation = 'focusGrowthGlow 0.3s ease-out';
            });
            
            element.addEventListener('blur', () => {
                element.style.animation = '';
            });
        });
    }
    
    showAttachmentOptions() {
        // Future implementation for file attachments
        this.showEducationalToast('🔗 ميزة المرفقات قريباً - ترقب التحديثات!', 'info');
    }
    
    // ===== Enhanced Animation Methods =====
    
    addGrowthClickEffect(element) {
        element.style.transform = 'scale(0.95)';
        element.style.transition = 'transform 0.15s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 150);
        
        // Add growth ripple effect
        this.createGrowthRipple(element);
    }
    
    addTopicGrowthEffect(card) {
        card.style.transform = 'translateY(-8px) scale(0.98)';
        card.style.transition = 'transform 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        
        const growthIndicator = card.querySelector('.topic-growth-indicator::after');
        if (growthIndicator) {
            growthIndicator.style.width = '100%';
        }
        
        setTimeout(() => {
            card.style.transform = 'translateY(-5px) scale(1)';
        }, 200);
        
        this.createEducationalSparkle(card);
    }
    
    addQuestionGrowthEffect(button) {
        button.style.transform = 'translateX(-8px) scale(0.98)';
        button.style.transition = 'transform 0.2s ease-out';
        
        const growthTrail = button.querySelector('.question-growth-trail');
        if (growthTrail) {
            growthTrail.style.width = '100%';
        }
        
        setTimeout(() => {
            button.style.transform = 'translateX(-5px) scale(1)';
        }, 200);
    }
    
    addWelcomeFeatureGrowthEffect(feature) {
        feature.style.transform = 'scale(0.95)';
        feature.style.background = 'rgba(16, 185, 129, 0.15)';
        feature.style.transition = 'all 0.2s ease-out';
        
        setTimeout(() => {
            feature.style.transform = 'scale(1)';
            feature.style.background = 'rgba(16, 185, 129, 0.08)';
        }, 200);
    }
    
    addInputGrowthEffect(input) {
        input.style.background = 'rgba(16, 185, 129, 0.03)';
        input.style.transition = 'background 0.3s ease-out';
        
        setTimeout(() => {
            input.style.background = 'transparent';
        }, 300);
    }
    
    addInputFocusGrowthEffect(input) {
        const inputGroup = input.closest('.chat-input-group');
        if (inputGroup) {
            inputGroup.style.animation = 'inputFocusGrowth 0.3s ease-out';
            setTimeout(() => {
                inputGroup.style.animation = '';
            }, 300);
        }
    }
    
    addGrowthSuccessEffect(element) {
        element.style.animation = 'successGrowthPulse 0.5s ease-out';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }
    
    addSuggestionGrowthEffect(suggestion) {
        suggestion.style.background = 'rgba(16, 185, 129, 0.15)';
        suggestion.style.transform = 'translateX(8px)';
        suggestion.style.transition = 'all 0.2s ease-out';
        
        setTimeout(() => {
            suggestion.style.background = '';
            suggestion.style.transform = '';
        }, 200);
    }
    
    animateGrowthSendButton() {
        const sendButton = document.getElementById('sendButton');
        const growthEffect = sendButton.querySelector('.send-growth-effect');
        
        if (growthEffect) {
            growthEffect.style.animation = 'none';
            growthEffect.offsetHeight; // Trigger reflow
            growthEffect.style.animation = 'sendGrowthRipple 0.6s ease-out';
        }
        
        sendButton.style.transform = 'scale(0.95)';
        sendButton.style.transition = 'transform 0.1s ease-out';
        
        setTimeout(() => {
            sendButton.style.transform = 'scale(1)';
        }, 100);
    }
    
    createGrowthRipple(element) {
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            transform: scale(0);
            animation: growthRippleExpand 0.8s ease-out;
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
    
    createEducationalSparkle(element) {
        for (let i = 0; i < 5; i++) {
            setTimeout(() => {
                const sparkle = document.createElement('div');
                sparkle.style.cssText = `
                    position: absolute;
                    width: 6px;
                    height: 6px;
                    background: var(--secondary-gradient);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: educationalSparkle 1s ease-out forwards;
                `;
                
                const rect = element.getBoundingClientRect();
                sparkle.style.left = (rect.left + Math.random() * rect.width) + 'px';
                sparkle.style.top = (rect.top + Math.random() * rect.height) + 'px';
                
                document.body.appendChild(sparkle);
                setTimeout(() => sparkle.remove(), 1000);
            }, i * 100);
        }
    }
    
    createLearningCelebration() {
        // Create celebration particles for meaningful learning moments
        for (let i = 0; i < 12; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    width: 8px;
                    height: 8px;
                    background: var(--secondary-gradient);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 10000;
                    animation: learningCelebrationParticle 2s ease-out forwards;
                `;
                
                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 2000);
            }, i * 80);
        }
    }
    
    // ===== Enhanced Sound System =====
    
    playEducationalMessageSound(type) {
        if (typeof Audio === 'undefined') return;
        
        try {
            // Educational-themed frequencies
            const frequency = type === 'user' ? 880 : 660; // Musical notes
            this.playEducationalBeep(frequency, 120);
        } catch (e) {
            // Ignore audio errors
        }
    }
    
    playEducationalTypingSound() {
        // Could implement subtle typing sound for educational ambiance
    }
    
    stopEducationalTypingSound() {
        // Could stop typing sound
    }
    
    playEducationalBeep(frequency, duration) {
        if (typeof AudioContext === 'undefined' && typeof webkitAudioContext === 'undefined') {
            return;
        }
        
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.05, audioContext.currentTime); // Softer volume
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + duration / 1000);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + duration / 1000);
    }
    
    // Enhanced toast notification system with growth theme
    showEducationalToast(message, type = 'info', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `educational-toast toast-${type}`;
        
        const typeStyles = {
            success: 'var(--primary-gradient)',
            error: 'var(--danger-gradient)', 
            warning: 'var(--warning-gradient)',
            info: 'var(--accent-gradient)'
        };
        
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${typeStyles[type]};
            color: white;
            padding: 1.2rem 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-strong);
            z-index: 10000;
            transform: translateX(400px) scale(0.8);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            font-size: 0.95rem;
            max-width: 350px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Enhanced animate in with growth effect
        setTimeout(() => {
            toast.style.transform = 'translateX(0) scale(1)';
        }, 100);

        // Auto remove with shrink effect
        setTimeout(() => {
            toast.style.transform = 'translateX(400px) scale(0.8)';
            setTimeout(() => toast.remove(), 400);
        }, duration);

        return toast;
    }
}

// Enhanced navigation effects (matching index page)
function initializeEducationalNavigation() {
    const navbar = document.querySelector('.glass-nav');
    if (!navbar) return;
    
    let lastScrollTop = 0;
    let ticking = false;
    
    const updateNavbar = () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add/remove scrolled class with growth animation
        if (scrollTop > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Auto-hide navbar on scroll down (disabled for chat page)
        // if (scrollTop > lastScrollTop && scrollTop > 300) {
        //     navbar.style.transform = 'translateY(-100%)';
        // } else {
        //     navbar.style.transform = 'translateY(0)';
        // }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        ticking = false;
    };
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateNavbar);
            ticking = true;
        }
    }, { passive: true });
    
    // Enhanced dropdown animations with growth theme
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            const dropdown = e.target.closest('.dropdown').querySelector('.dropdown-menu');
            if (dropdown) {
                dropdown.style.animation = 'dropdownGrowthExpand 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            }
        });
    });
}

// Enhanced responsive sidebar with educational theme
function initializeEducationalSidebar() {
    const sidebar = document.getElementById('chatSidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    
    if (!sidebar || !toggleBtn) return;
    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        
        // Add growth animation to toggle button
        toggleBtn.style.animation = 'toggleGrowthPulse 0.3s ease-out';
        setTimeout(() => {
            toggleBtn.style.animation = '';
        }, 300);
    });
    
    // Close sidebar when clicking outside on mobile with growth animation
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                if (sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        }
    });
    
    // Handle window resize with smooth transitions
    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('show');
        }
    });
}

// Initialize growth particle system for chat
function initializeGrowthParticles() {
    const chatParticles = document.querySelector('.growth-particles-chat');
    if (!chatParticles) return;

    // Create educational particles
    for (let i = 0; i < 15; i++) {
        const particle = document.createElement('div');
        particle.className = 'chat-growth-particle';
        particle.style.cssText = `
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--leaf-green);
            border-radius: 50%;
            pointer-events: none;
            animation: chatParticleFloat ${15 + Math.random() * 10}s infinite linear;
            left: ${Math.random() * 100}%;
            top: ${100 + Math.random() * 20}%;
            animation-delay: ${Math.random() * 10}s;
            opacity: 0.4;
        `;
        chatParticles.appendChild(particle);
    }

    // Create knowledge particles
    for (let i = 0; i < 8; i++) {
        const colors = ['var(--sunshine-orange)', 'var(--sky-blue)', 'var(--flower-pink)', 'var(--leaf-green)'];
        const particle = document.createElement('div');
        particle.className = 'knowledge-particle';
        particle.style.cssText = `
            position: absolute;
            width: 3px;
            height: 3px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            border-radius: 50%;
            pointer-events: none;
            animation: knowledgeParticleOrbit ${12 + Math.random() * 8}s infinite linear;
            left: ${20 + Math.random() * 60}%;
            top: ${20 + Math.random() * 60}%;
            animation-delay: ${Math.random() * 8}s;
            opacity: 0.3;
        `;
        chatParticles.appendChild(particle);
    }
}

// Enhanced utility functions with educational context
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

// Enhanced error handling with educational feedback
window.addEventListener('error', function(e) {
    console.error('Educational Chat Error:', e.error);
    if (window.maharatiChat) {
        window.maharatiChat.showEducationalToast('✨ حدث خطأ، لكن التعلم مستمر!', 'error');
    }
});

window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled Promise Rejection:', e.reason);
    if (window.maharatiChat) {
        window.maharatiChat.showEducationalToast('🔄 مشكلة في الاتصال، نحاول إعادة التواصل', 'warning');
    }
});

// Add enhanced CSS animations
const enhancedAnimationStyles = document.createElement('style');
enhancedAnimationStyles.textContent = `
@keyframes chatParticleFloat {
    0% {
        transform: translateY(0px) scale(0.8);
        opacity: 0;
    }
    10% {
        opacity: 0.4;
    }
    90% {
        opacity: 0.3;
    }
    100% {
        transform: translateY(-100vh) translateX(${Math.random() * 100 - 50}px) scale(1.2);
        opacity: 0;
    }
}

@keyframes knowledgeParticleOrbit {
    0% {
        transform: rotate(0deg) translateX(40px) rotate(0deg);
        opacity: 0.3;
    }
    50% {
        opacity: 0.6;
    }
    100% {
        transform: rotate(360deg) translateX(40px) rotate(-360deg);
        opacity: 0.3;
    }
}

@keyframes growthRippleExpand {
    0% {
        transform: scale(0);
        opacity: 0.6;
    }
    100% {
        transform: scale(15);
        opacity: 0;
    }
}

@keyframes educationalSparkle {
    0% {
        opacity: 1;
        transform: scale(1) translate(0, 0);
    }
    100% {
        opacity: 0;
        transform: scale(1.5) translate(${Math.random() * 60 - 30}px, ${-30 - Math.random() * 30}px);
    }
}

@keyframes sendGrowthRipple {
    0% {
        width: 0;
        height: 0;
        opacity: 0.8;
    }
    100% {
        width: 120px;
        height: 120px;
        opacity: 0;
    }
}

@keyframes messagesGrowthFadeOut {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.95);
    }
}

@keyframes inputFocusGrowth {
    0% {
        transform: translateY(0) scale(1);
    }
    50% {
        transform: translateY(-2px) scale(1.01);
    }
    100% {
        transform: translateY(0) scale(1);
    }
}

@keyframes successGrowthPulse {
    0% {
        transform: scale(1);
        box-shadow: var(--shadow-soft);
    }
    50% {
        transform: scale(1.05);
        box-shadow: var(--shadow-growth);
    }
    100% {
        transform: scale(1);
        box-shadow: var(--shadow-soft);
    }
}

@keyframes focusGrowthGlow {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
    50% {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
}

@keyframes dropdownGrowthExpand {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(-20px);
    }
    60% {
        transform: scale(1.02) translateY(-5px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes toggleGrowthPulse {
    0% {
        transform: scale(1) rotate(0deg);
    }
    50% {
        transform: scale(1.1) rotate(5deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
    }
}

@keyframes scrollGrowthFlow {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
    100% {
        transform: translateY(0);
    }
}

@keyframes thinkingPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.8;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
}

@keyframes learningCelebrationParticle {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    100% {
        opacity: 0;
        transform: translate(
            calc(-50% + ${Math.random() * 200 - 100}px), 
            calc(-50% + ${-100 - Math.random() * 100}px)
        ) scale(1.5);
    }
}

/* Enhanced message content styling */
.educational-highlight {
    background: rgba(16, 185, 129, 0.15);
    color: var(--forest-green);
    padding: 0.1rem 0.4rem;
    border-radius: 6px;
    font-weight: 600;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.message-step {
    background: rgba(59, 130, 246, 0.08);
    border-left: 3px solid var(--sky-blue);
    padding: 0.8rem 1rem;
    margin: 0.5rem 0;
    border-radius: 0 8px 8px 0;
    font-weight: 500;
}

.currency-amount {
    background: rgba(249, 115, 22, 0.1);
    color: var(--sunshine-orange);
    padding: 0.2rem 0.6rem;
    border-radius: 8px;
    font-weight: 700;
    border: 1px solid rgba(249, 115, 22, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.message-link {
    color: var(--sky-blue);
    text-decoration: none;
    transition: all var(--animation-fast);
    border-bottom: 1px solid rgba(59, 130, 246, 0.3);
}

.message-link:hover {
    color: var(--sunshine-orange);
    border-bottom-color: rgba(249, 115, 22, 0.5);
}

/* Keyboard navigation enhancement */
.keyboard-navigation *:focus {
    outline: 3px solid var(--leaf-green) !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2) !important;
    border-radius: 8px !important;
}

/* Loading spinner for messages */
.educational-loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(16, 185, 129, 0.3);
    border-top: 2px solid var(--leaf-green);
    border-radius: 50%;
    animation: educationalSpin 1s linear infinite;
}

@keyframes educationalSpin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
`;

document.head.appendChild(enhancedAnimationStyles);

// Export enhanced utilities for external use
window.EducationalChatUtils = {
    debounce,
    throttle,
    initializeEducationalSidebar,
    initializeEducationalNavigation,
    initializeGrowthParticles,
    showGrowthToast: (message, type) => {
        if (window.maharatiChat) {
            return window.maharatiChat.showEducationalToast(message, type);
        }
    }
};

// Performance monitoring for educational experience
let performanceMetrics = {
    messagesLoaded: 0,
    averageResponseTime: 0,
    totalInteractions: 0
};

window.EducationalPerformance = performanceMetrics;

// Enhanced page visibility handling for better performance
document.addEventListener('visibilitychange', () => {
    const isHidden = document.hidden;
    const animatedElements = document.querySelectorAll(
        '.ai-avatar, .floating-element, .growth-ring, .typing-dot'
    );
    
    animatedElements.forEach(element => {
        if (isHidden) {
            element.style.animationPlayState = 'paused';
        } else {
            element.style.animationPlayState = 'running';
        }
    });
});

console.log('🎯 Enhanced educational chat system with growth animations loaded successfully! 🚀💬');