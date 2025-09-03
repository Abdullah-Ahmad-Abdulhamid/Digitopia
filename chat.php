<?php
require_once 'config/config.php';

// Get user data if logged in
$user = null;
$userStats = null;
if (isLoggedIn()) {
    $userObj = new User();      
    $userResult = $userObj->getUserProfile($_SESSION['user_id']);
    if ($userResult['success']) {
        $user = $userResult['user'];
    }
    
    // Get user stats
    $skillsObj = new Skills();
    $statsResult = $skillsObj->getUserProgress($_SESSION['user_id']);
    if ($statsResult['success']) {
        $userStats = $statsResult['data']['stats'];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المساعد الذكي - <?php echo SITE_NAME; ?></title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="تحدث مع المساعد الذكي لمنصة مهاراتي واحصل على نصائح مخصصة للنمو والتطوير">
    <meta name="keywords" content="مساعد ذكي، دردشة، نصائح، مهارات حياتية، ذكاء اصطناعي، تعليم">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="المساعد الذكي - منصة مهاراتي">
    <meta property="og:description" content="تحدث مع المساعد الذكي واحصل على نصائح مخصصة لتطوير مهاراتك الحياتية">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/images/og-chat.jpg">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/chat.php">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" href="images/logo.png">

    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/maharati_platform/manifest.json">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="css/chat-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><i class="fas fa-seedling"></i></div>
        <div class="floating-element element-2"><i class="fas fa-lightbulb"></i></div>
        <div class="floating-element element-3"><i class="fas fa-brain"></i></div>
        <div class="floating-element element-4"><i class="fas fa-robot"></i></div>
        <div class="floating-element element-5"><i class="fas fa-comments"></i></div>
        <div class="floating-element element-6"><i class="fas fa-graduation-cap"></i></div>
        <div class="floating-element element-7"><i class="fas fa-book-open"></i></div>
        <div class="floating-element element-8"><i class="fas fa-heart"></i></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top glass-nav py-2" id="mainNavbar" style="min-height: 100px;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/maharati_platform/index.php">
                <div class="brand-logo" style="margin-right: 20px; display: flex; align-items: center;">
                    <img src="images/logo.png" alt="Maharati Logo" class="img-fluid" style="height: 80px; transition: all 0.3s ease;">
                </div>
                <span class="fw-bold brand-text">مهاراتي</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/index.php"><i class="fas fa-home me-2"></i>الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/skills.php"><i class="fas fa-seedling me-2"></i>المهارات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/maharati_platform/chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/progress.php"><i class="fas fa-chart-line me-2"></i>تقدمي</a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="user-name"><?php echo htmlspecialchars($user['name'] ?? 'المستخدم'); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end animated-dropdown">
                                <li><a class="dropdown-item" href="/maharati_platform/profile.php"><i class="fas fa-user-edit me-2"></i>الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="/maharati_platform/subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/maharati_platform/logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل خروج</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link login-link" href="/maharati_platform/login.php"><i class="fas fa-sign-in-alt me-2"></i>دخول</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Chat Section -->
    <section class="chat-section">
        <div class="chat-background-pattern"></div>
        <div class="growth-particles-chat"></div>
        
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Main Chat Area -->
                <div class="col-lg-8 col-md-12 d-flex flex-column chat-main-area" data-aos="fade-right">
                    <!-- Chat Header -->
                    <div class="chat-header glass-header">
                        <div class="d-flex align-items-center">
                            <div class="ai-avatar-wrapper me-3">
                                <div class="ai-avatar">
                                    <i class="fas fa-seedling"></i>
                                    <div class="avatar-growth-rings">
                                        <div class="growth-ring ring-1"></div>
                                        <div class="growth-ring ring-2"></div>
                                    </div>
                                </div>
                                <div class="status-indicator">
                                    <div class="status-dot"></div>
                                </div>
                            </div>
                            <div class="ai-info">
                                <h4 class="ai-name mb-1">🎯 مساعد مهاراتي الذكي</h4>
                                <div class="ai-status">
                                    <i class="fas fa-circle text-success me-1"></i>
                                    <span>جاهز لمساعدتك في النمو والتطوير</span>
                                    <div class="typing-indicator-header d-none">
                                        <div class="typing-animation">
                                            <span class="typing-dot"></span>
                                            <span class="typing-dot"></span>
                                            <span class="typing-dot"></span>
                                        </div>
                                        <span>يفكر في إجابتك...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <button class="btn btn-glass-action" id="clearChat" title="بداية جديدة">
                                <i class="fas fa-seedling"></i>
                                <div class="action-glow"></div>
                            </button>
                            <button class="btn btn-glass-action d-lg-none" id="toggleSidebar" title="إظهار/إخفاء المساعدة">
                                <i class="fas fa-compass"></i>
                                <div class="action-glow"></div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Messages Container -->
                    <div class="messages-container flex-grow-1" id="chatMessages">
                        <div class="messages-scroll-area">
                            <!-- Messages will be loaded here -->
                        </div>
                        <div class="scroll-to-bottom-chat" id="scrollToBottom">
                            <i class="fas fa-arrow-down"></i>
                            <div class="scroll-ripple"></div>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div class="chat-input-container glass-input">
                        <div class="input-suggestions d-none" id="inputSuggestions">
                            <!-- Suggestions will appear here -->
                        </div>
                        <form id="chatForm" class="chat-form">
                            <div class="input-group chat-input-group">
                                <button class="btn btn-attachment" type="button" id="attachmentBtn" title="إضافات ذكية">
                                    <i class="fas fa-plus"></i>
                                    <div class="attachment-pulse"></div>
                                </button>
                                <input type="text" class="form-control chat-input" id="messageInput" 
                                       placeholder="شاركني ما تريد تطويره أو تعلمه..." 
                                       maxlength="1000" autocomplete="off">
                                <button class="btn btn-send" type="submit" id="sendButton">
                                    <i class="fas fa-paper-plane send-icon"></i>
                                    <div class="send-growth-effect"></div>
                                </button>
                            </div>
                            <div class="input-meta">
                                <div class="quick-actions">
                                    <button type="button" class="quick-action-btn" data-action="money">
                                        <i class="fas fa-coins"></i>
                                        <span>إدارة المال</span>
                                        <div class="action-bloom"></div>
                                    </button>
                                    <button type="button" class="quick-action-btn" data-action="work">
                                        <i class="fas fa-briefcase"></i>
                                        <span>مهارات العمل</span>
                                        <div class="action-bloom"></div>
                                    </button>
                                    <button type="button" class="quick-action-btn" data-action="communication">
                                        <i class="fas fa-comments"></i>
                                        <span>التواصل</span>
                                        <div class="action-bloom"></div>
                                    </button>
                                    <button type="button" class="quick-action-btn" data-action="health">
                                        <i class="fas fa-heart"></i>
                                        <span>الصحة النفسية</span>
                                        <div class="action-bloom"></div>
                                    </button>
                                </div>
                                <div class="char-count">
                                    <span id="charCount">0</span>/1000
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 chat-sidebar" id="chatSidebar" data-aos="fade-left" data-aos-delay="200">
                    <div class="sidebar-content glass-sidebar">
                        <!-- AI Assistant Info -->
                        <div class="sidebar-section ai-info-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-seedling"></i>
                                    <div class="icon-growth-effect"></div>
                                </div>
                                <h5 class="section-title">مساعدك في النمو</h5>
                            </div>
                            <div class="ai-description">
                                <p>مرحباً! أنا مساعد مهاراتي الذكي 🎯 مصمم خصيصاً لمساعدة الشباب المصري في رحلة النمو وتطوير المهارات الحياتية.</p>
                                <div class="ai-capabilities">
                                    <div class="capability-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>نصائح مخصصة للثقافة المصرية</span>
                                    </div>
                                    <div class="capability-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>متاح 24/7 لمرافقتك في رحلة النمو</span>
                                    </div>
                                    <div class="capability-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>يتطور مع تفاعلك لخدمة أفضل</span>
                                    </div>
                                    <div class="capability-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>أمثلة عملية من الحياة المصرية</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Growth Topics -->
                        <div class="sidebar-section topics-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-compass"></i>
                                    <div class="icon-growth-effect"></div>
                                </div>
                                <h5 class="section-title">مجالات النمو</h5>
                            </div>
                            <div class="topics-grid">
                                <div class="topic-card" data-topic="money">
                                    <div class="topic-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="topic-info">
                                        <h6>إدارة المال</h6>
                                        <p>الادخار والاستثمار والميزانية الذكية</p>
                                    </div>
                                    <div class="topic-growth-indicator"></div>
                                </div>
                                <div class="topic-card" data-topic="work">
                                    <div class="topic-icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="topic-info">
                                        <h6>مهارات العمل</h6>
                                        <p>السيرة الذاتية والمقابلات والتطوير المهني</p>
                                    </div>
                                    <div class="topic-growth-indicator"></div>
                                </div>
                                <div class="topic-card" data-topic="communication">
                                    <div class="topic-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <div class="topic-info">
                                        <h6>التواصل</h6>
                                        <p>العلاقات والثقة بالنفس والتأثير</p>
                                    </div>
                                    <div class="topic-growth-indicator"></div>
                                </div>
                                <div class="topic-card" data-topic="health">
                                    <div class="topic-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="topic-info">
                                        <h6>الصحة النفسية</h6>
                                        <p>التعامل مع الضغوط والتوازن النفسي</p>
                                    </div>
                                    <div class="topic-growth-indicator"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Growth Questions -->
                        <div class="sidebar-section questions-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-lightbulb"></i>
                                    <div class="icon-growth-effect"></div>
                                </div>
                                <h5 class="section-title">أسئلة للنمو</h5>
                            </div>
                            <div class="sample-questions">
                                <button class="sample-question-btn" data-question="كيف أوفر من راتبي 3000 جنيه شهرياً؟">
                                    <i class="fas fa-piggy-bank me-2"></i>
                                    كيف أوفر من راتبي الشهري؟
                                    <div class="question-growth-trail"></div>
                                </button>
                                <button class="sample-question-btn" data-question="ازاي أكتب CV قوي للتقديم في الشركات المصرية؟">
                                    <i class="fas fa-file-alt me-2"></i>
                                    كيف أكتب سيرة ذاتية قوية؟
                                    <div class="question-growth-trail"></div>
                                </button>
                                <button class="sample-question-btn" data-question="إيه أفضل طريقة للتعامل مع ضغط العمل والتوتر؟">
                                    <i class="fas fa-brain me-2"></i>
                                    كيف أتعامل مع ضغط العمل؟
                                    <div class="question-growth-trail"></div>
                                </button>
                                <button class="sample-question-btn" data-question="ازاي أحسن مهارات التواصل والثقة بالنفس؟">
                                    <i class="fas fa-users me-2"></i>
                                    كيف أطور مهارات التواصل؟
                                    <div class="question-growth-trail"></div>
                                </button>
                                <button class="sample-question-btn" data-question="إيه أفضل طريقة لبدء مشروع صغير بميزانية محدودة؟">
                                    <i class="fas fa-rocket me-2"></i>
                                    كيف أبدأ مشروع صغير؟
                                    <div class="question-growth-trail"></div>
                                </button>
                                <button class="sample-question-btn" data-question="ازاي أنظم وقتي بشكل أفضل وأزود إنتاجيتي؟">
                                    <i class="fas fa-clock me-2"></i>
                                    كيف أنظم وقتي بذكاء؟
                                    <div class="question-growth-trail"></div>
                                </button>
                            </div>
                        </div>

                        <?php if (isLoggedIn()): ?>
                        <!-- User Growth Profile -->
                        <div class="sidebar-section user-stats-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-user-graduate"></i>
                                    <div class="icon-growth-effect"></div>
                                </div>
                                <h5 class="section-title">رحلة نموك</h5>
                            </div>
                            <div class="user-growth-card">
                                <div class="user-avatar-large">
                                    <i class="fas fa-user"></i>
                                    <div class="avatar-growth-glow"></div>
                                </div>
                                <div class="user-details">
                                    <h6><?php echo htmlspecialchars($user['name'] ?? 'المستخدم'); ?></h6>
                                    <p class="text-muted"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                                    <?php if ($userStats): ?>
                                    <div class="growth-stats">
                                        <div class="growth-stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-gem"></i>
                                            </div>
                                            <div class="stat-info">
                                                <span class="stat-number"><?php echo $userStats['total_points']; ?></span>
                                                <span class="stat-label">نقطة نمو</span>
                                            </div>
                                        </div>
                                        <div class="growth-stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                            <div class="stat-info">
                                                <span class="stat-number"><?php echo $userStats['completed_skills']; ?></span>
                                                <span class="stat-label">مهارة مكتملة</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Growth Tip -->
                        <div class="sidebar-section tip-section">
                            <div class="growth-tip-card">
                                <div class="tip-icon">
                                    <i class="fas fa-lightbulb"></i>
                                    <div class="tip-glow-pulse"></div>
                                </div>
                                <div class="tip-content">
                                    <h6>💡 نصيحة للنمو</h6>
                                    <p>كلما كانت أسئلتك أكثر تفصيلاً وتحديداً، كانت النصائح أكثر فائدة لرحلة نموك الشخصي والمهني.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="footer-brand">
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <img src="images/logo.png" alt="Logo" style="width: 60px; height: 60px; margin-left: 15px; margin-right: 15px;">
                                <h3 style="margin: 0; font-size: 1.8rem;">مهاراتي</h3>
                            </div>
                            <p>منصة التطوير والنمو الشخصي للشباب العربي</p>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                                <a href="/maharati_platform/register.php" class="btn btn-register"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="/maharati_platform/login.php" class="btn btn-login">kedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <h6>روابط سريعة</h6>
                                <ul class="footer-links">
                                    <li><a href="skills.php">المهارات</a></li>
                                    <li><a href="challenges.php">التحديات</a></li>
                                    <li><a href="#">عن المنصة</a></li>
                                    <li><a href="#">قصص النجاح</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>الدعم</h6>
                                <ul class="footer-links">
                                    <li><a href="#">المساعدة</a></li>
                                    <li><a href="#">اتصل بنا</a></li>
                                    <li><a href="#">الأسئلة الشائعة</a></li>
                                    <li><a href="#">المجتمع</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>الشراكات</h6>
                                <ul class="footer-links">
                                    <li><a href="#">شركاؤنا</a></li>
                                    <li><a href="#">كن مدرباً</a></li>
                                    <li><a href="#">API للمطورين</a></li>
                                    <li><a href="#">البرنامج التابع</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="text-center">
                    <p>&copy; 2025 مهاراتي. جميع الحقوق محفوظة. صنع بـ <i class="fas fa-heart text-danger"></i> للشباب المصري</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/chat-script.js"></script>
</body>
</html>