<?php
require_once 'config/config.php';

// Get user data if logged in
$user = null;
if (isLoggedIn()) {
    $userObj = new User();      
    $userResult = $userObj->getUserProfile($_SESSION['user_id']);
    if ($userResult['success']) {
        $user = $userResult['user'];
    }
}

// Get challenges
$challengesObj = new Challenges();
$userId = isLoggedIn() ? $_SESSION['user_id'] : null;

// Get daily challenge
$dailyChallengeResult = $challengesObj->getDailyChallenge(null, $userId);
$dailyChallenge = $dailyChallengeResult['success'] ? $dailyChallengeResult['data'] : null;

// Get weekly challenges
$weeklyChallengesResult = $challengesObj->getWeeklyChallenges($userId);
$weeklyChallenges = $weeklyChallengesResult['success'] ? $weeklyChallengesResult['data'] : [];

// Get user challenge history if logged in
$challengeHistory = [];
$challengeStats = [];
if ($userId) {
    $historyResult = $challengesObj->getUserChallengeHistory($userId, 10);
    if ($historyResult['success']) {
        $challengeHistory = $historyResult['data']['history'];
        $challengeStats = $historyResult['data']['stats'];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحديات اليومية - <?php echo SITE_NAME; ?></title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="تحديات يومية وأسبوعية لتطوير المهارات الحياتية - منصة مهاراتي">
    <meta name="keywords" content="تحديات، مهارات، تطوير ذاتي، تعليم، مصر">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="التحديات اليومية - منصة مهاراتي">
    <meta property="og:description" content="اختبر نفسك مع تحديات يومية ممتعة لتطوير مهاراتك الحياتية">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/images/challenges-og.jpg">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/challenges.php">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي - التحديات">
    
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
    <link href="css/challenges-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><i class="fas fa-trophy"></i></div>
        <div class="floating-element element-2"><i class="fas fa-medal"></i></div>
        <div class="floating-element element-3"><i class="fas fa-star"></i></div>
        <div class="floating-element element-4"><i class="fas fa-crown"></i></div>
        <div class="floating-element element-5"><i class="fas fa-rocket"></i></div>
        <div class="floating-element element-6"><i class="fas fa-gem"></i></div>
        <div class="floating-element element-7"><i class="fas fa-brain"></i></div>
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
                        <a class="nav-link active" href="/maharati_platform/challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
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

    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="growth-particles"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-trophy me-2"></i>
                            <span>التحديات اليومية والأسبوعية</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4">
                            اختبر مهاراتك
                            <span class="gradient-text">واكسب النقاط</span>
                            <div class="growth-animation">
                                <i class="fas fa-trophy"></i>
                                <div class="growth-leaves">
                                    <span class="leaf leaf-1"></span>
                                    <span class="leaf leaf-2"></span>
                                    <span class="leaf leaf-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5">
                            تحديات يومية وأسبوعية ممتعة لتطبيق ما تعلمته من مهارات حياتية. 
                            اكسب النقاط وشارك في التحديات مع آلاف الشباب المصري
                        </p>
                        
                        <?php if ($userId && !empty($challengeStats)): ?>
                        <div class="achievement-showcase mb-4">
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="achievement-data">
                                    <span class="achievement-number"><?php echo $challengeStats['total_completed']; ?></span>
                                    <span class="achievement-label">تحديات مكتملة</span>
                                </div>
                            </div>
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="achievement-data">
                                    <span class="achievement-number"><?php echo $challengeStats['total_points_earned']; ?></span>
                                    <span class="achievement-label">نقاط مكتسبة</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="hero-buttons">
                            <?php if ($dailyChallenge): ?>
                                <a href="#daily-challenge" class="btn btn-hero-primary btn-lg me-3 growth-pulse">
                                    <i class="fas fa-rocket me-2"></i>تحدي اليوم
                                    <div class="btn-sparkle"></div>
                                </a>
                            <?php endif; ?>
                            <a href="#weekly-challenges" class="btn btn-hero-outline btn-lg">
                                <i class="fas fa-calendar-week me-2"></i>التحديات الأسبوعية
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="challenge-ecosystem">
                            <!-- Central Challenge Hub -->
                            <div class="challenge-hub">
                                <div class="hub-core">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="growth-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                            
                            <!-- Floating Achievement Nodes -->
                            <div class="achievement-node node-1">
                                <i class="fas fa-medal"></i>
                                <span>إنجازات</span>
                            </div>
                            <div class="achievement-node node-2">
                                <i class="fas fa-star"></i>
                                <span>نقاط</span>
                            </div>
                            <div class="achievement-node node-3">
                                <i class="fas fa-crown"></i>
                                <span>ألقاب</span>
                            </div>
                            <div class="achievement-node node-4">
                                <i class="fas fa-gem"></i>
                                <span>جوائز</span>
                            </div>
                            <div class="achievement-node node-5">
                                <i class="fas fa-fire"></i>
                                <span>تحديات</span>
                            </div>
                            <div class="achievement-node node-6">
                                <i class="fas fa-rocket"></i>
                                <span>تقدم</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-text">اسحب للأسفل</div>
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- User Stats Section (for logged in users) -->
    <?php if ($userId && !empty($challengeStats)): ?>
    <section class="user-stats-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $challengeStats['total_completed']; ?>">0</div>
                        <div class="stats-label">تحديات مكتملة</div>
                        <div class="achievement-progress">
                            <div class="progress-ring">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $challengeStats['total_points_earned']; ?>">0</div>
                        <div class="stats-label">نقاط مكتسبة</div>
                        <div class="achievement-progress">
                            <div class="progress-ring">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $challengeStats['completed_this_week']; ?>">0</div>
                        <div class="stats-label">هذا الأسبوع</div>
                        <div class="achievement-progress">
                            <div class="progress-ring">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $challengeStats['completed_today']; ?>">0</div>
                        <div class="stats-label">اليوم</div>
                        <div class="achievement-progress">
                            <div class="progress-ring">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Daily Challenge Section -->
    <?php if ($dailyChallenge): ?>
    <section class="daily-challenge-section py-5" id="daily-challenge">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="challenge-spotlight">
                        <div class="challenge-header">
                            <div class="challenge-icon">
                                <i class="fas fa-target"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <h3 class="challenge-title">تحدي اليوم</h3>
                            <div class="challenge-date"><?php echo date('d F Y', strtotime($dailyChallenge['challenge_date'])); ?></div>
                        </div>
                        
                        <div class="challenge-content">
                            <h4 class="challenge-name"><?php echo htmlspecialchars($dailyChallenge['title_ar']); ?></h4>
                            <p class="challenge-description">
                                <?php echo htmlspecialchars($dailyChallenge['description_ar']); ?>
                            </p>
                            
                            <?php if ($dailyChallenge['egyptian_example']): ?>
                            <div class="egyptian-example">
                                <div class="example-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="example-content">
                                    <h5>مثال من الواقع المصري</h5>
                                    <p><?php echo htmlspecialchars($dailyChallenge['egyptian_example']); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="challenge-stats">
                                <div class="stat-item">
                                    <i class="fas fa-users"></i>
                                    <span>342 مشارك</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-gem"></i>
                                    <span>+<?php echo $dailyChallenge['reward_points']; ?> نقطة</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    <span>
                                        <?php 
                                        $difficulties = ['easy' => '15 دقيقة', 'medium' => '30 دقيقة', 'hard' => '45 دقيقة'];
                                        echo $difficulties[$dailyChallenge['difficulty']] ?? '20 دقيقة';
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if (isset($dailyChallenge['user_completion']) && $dailyChallenge['user_completion']): ?>
                                <div class="completion-celebration">
                                    <div class="celebration-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="celebration-content">
                                        <h5>مبروك! تم إكمال التحدي</h5>
                                        <p>حصلت على <?php echo $dailyChallenge['user_completion']['earned_points']; ?> نقطة في <?php echo date('d/m/Y', strtotime($dailyChallenge['user_completion']['completed_at'])); ?></p>
                                    </div>
                                    <div class="celebration-confetti">
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                        <div class="confetti"></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php if ($userId): ?>
                                    <button class="btn btn-challenge-start" onclick="event.stopPropagation(); completeChallenge(<?php echo $dailyChallenge['id']; ?>, event)">
                                        <i class="fas fa-rocket me-2"></i>ابدأ التحدي الآن
                                        <div class="btn-energy-wave"></div>
                                    </button>
                                <?php else: ?>
                                    <a href="/maharati_platform/login.php" class="btn btn-challenge-start">
                                        <i class="fas fa-sign-in-alt me-2"></i>سجل دخولك لبدء التحدي
                                        <div class="btn-energy-wave"></div>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="challenge-decoration">
                            <div class="energy-particle particle-1"></div>
                            <div class="energy-particle particle-2"></div>
                            <div class="energy-particle particle-3"></div>
                            <div class="energy-particle particle-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Weekly Challenges Section -->
    <section class="weekly-challenges-section py-5" id="weekly-challenges">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">تحديات الأسبوع</h2>
                <p class="section-subtitle">تحديات هذا الأسبوع من <?php echo date('d/m', strtotime('monday this week')); ?> إلى <?php echo date('d/m', strtotime('sunday this week')); ?></p>
                <div class="section-divider"></div>
            </div>
            
            <?php if (empty($weeklyChallenges)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="empty-title">لا توجد تحديات هذا الأسبوع</h3>
                    <p class="empty-description">تابعنا لمعرفة التحديات الجديدة</p>
                    <a href="/maharati_platform/skills.php" class="btn btn-empty-action">
                        <i class="fas fa-seedling me-2"></i>استكشف المهارات
                        <div class="btn-bloom-effect"></div>
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($weeklyChallenges as $index => $challenge): ?>
                    <div class="col-lg-6">
                        <div class="weekly-challenge-card">
                            <div class="card-header">
                                <div class="challenge-icon-wrapper">
                                    <i class="fas fa-medal challenge-icon"></i>
                                </div>
                                <div class="challenge-meta">
                                    <h5 class="challenge-name"><?php echo htmlspecialchars($challenge['title_ar']); ?></h5>
                                    <div class="challenge-badges">
                                        <span class="difficulty-badge difficulty-<?php echo $challenge['difficulty']; ?>">
                                            <?php 
                                            $difficulties = ['easy' => 'سهل', 'medium' => 'متوسط', 'hard' => 'صعب'];
                                            echo $difficulties[$challenge['difficulty']] ?? 'متوسط';
                                            ?>
                                        </span>
                                        <span class="challenge-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($challenge['challenge_date'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="challenge-status">
                                    <?php if (isset($challenge['user_completion']) && $challenge['user_completion']): ?>
                                        <div class="status-completed">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="points-display">
                                            <i class="fas fa-coins"></i>
                                            <span><?php echo $challenge['reward_points']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <p class="challenge-description"><?php echo htmlspecialchars($challenge['description_ar']); ?></p>
                                
                                <div class="difficulty-indicator">
                                    <span class="difficulty-label">مستوى الصعوبة:</span>
                                    <div class="difficulty-dots">
                                        <?php 
                                        $difficulty_levels = ['easy' => 1, 'medium' => 2, 'hard' => 3];
                                        $current_level = $difficulty_levels[$challenge['difficulty']] ?? 1;
                                        for ($i = 1; $i <= 3; $i++): 
                                        ?>
                                            <div class="difficulty-dot <?php echo $i <= $current_level ? 'active' : ''; ?>"></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <?php if (isset($challenge['user_completion']) && $challenge['user_completion']): ?>
                                    <div class="completed-status">
                                        <i class="fas fa-trophy me-2"></i>
                                        <span>تم الإكمال - حصلت على <?php echo $challenge['user_completion']['earned_points']; ?> نقطة</span>
                                    </div>
                                <?php else: ?>
                                    <?php if (strtotime($challenge['challenge_date']) <= time()): ?>
                                        <?php if ($userId): ?>
                                            <button class="btn btn-weekly-challenge" onclick="event.stopPropagation(); completeChallenge(<?php echo $challenge['id']; ?>, event)">
                                                <i class="fas fa-seedling me-2"></i>ابدأ النمو
                                                <div class="btn-growth-effect"></div>
                                            </button>
                                        <?php else: ?>
                                            <a href="/maharati_platform/login.php" class="btn btn-weekly-challenge">
                                                <i class="fas fa-sign-in-alt me-2"></i>سجل دخولك أولاً
                                                <div class="btn-growth-effect"></div>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="upcoming-challenge">
                                            <i class="fas fa-clock me-2"></i>
                                            <span>متاح في <?php echo date('d/m', strtotime($challenge['challenge_date'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-glow"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Challenge History (for logged in users) -->
    <?php if ($userId && !empty($challengeHistory)): ?>
    <section class="challenge-history-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">رحلة إنجازاتك</h2>
                <p class="section-subtitle">التحديات التي أكملتها مؤخراً وحققت فيها النمو</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4">
                <?php foreach ($challengeHistory as $index => $historyItem): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="history-card">
                        <div class="history-header">
                            <div class="history-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="completion-info">
                                <div class="completion-date">
                                    <?php echo date('d/m/Y', strtotime($historyItem['completed_at'])); ?>
                                </div>
                                <div class="points-earned">
                                    <i class="fas fa-coins me-1"></i>
                                    +<?php echo $historyItem['earned_points']; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="history-content">
                            <h6 class="history-title"><?php echo htmlspecialchars($historyItem['title_ar']); ?></h6>
                            <div class="history-meta">
                                <span class="difficulty-badge difficulty-<?php echo $historyItem['difficulty']; ?>">
                                    <?php 
                                    $difficulties = ['easy' => 'سهل', 'medium' => 'متوسط', 'hard' => 'صعب'];
                                    echo $difficulties[$historyItem['difficulty']] ?? 'متوسط';
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-glow"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="progress.php" class="btn btn-view-all">
                    <i class="fas fa-chart-line me-2"></i>عرض جميع الإنجازات
                    <div class="btn-bloom-effect"></div>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Chat FAB -->
    <div class="chat-fab">
        <a href="chat.php" class="fab-button" title="تحدث مع المساعد الذكي">
            <i class="fas fa-comments"></i>
            <div class="fab-pulse-ring"></div>
        </a>
        <div class="fab-tooltip">مساعدك الذكي في رحلة التطوير</div>
    </div>

    <!-- Challenge Completion Modal -->
    <div class="modal fade" id="challengeModal" tabindex="-1" aria-labelledby="challengeModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modern-modal">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="challengeModalLabel">
                        <i class="fas fa-trophy me-2" aria-hidden="true"></i>إكمال التحدي
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" role="document">
                    <div class="modal-icon" aria-hidden="true">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <p id="challengeQuestion" class="modal-question">هل أكملت هذا التحدي بنجاح؟</p>
                    <div class="mb-3">
                        <label for="challengeResponse" class="form-label">شارك تجربتك (اختياري):</label>
                        <textarea class="form-control modern-textarea" id="challengeResponse" rows="3" 
                                  aria-describedby="challengeQuestion"
                                  placeholder="اكتب كيف أكملت التحدي وما تعلمته..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2" aria-hidden="true"></i>إلغاء
                        <span class="visually-hidden">إغلاق النافذة</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmComplete" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2" aria-hidden="true"></i>تأكيد الإكمال
                        <div class="btn-growth-effect"></div>
                        <span class="visually-hidden">تأكيد إكمال التحدي</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
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
                                    <li><a href="about.php">عن المنصة</a></li>
                                    <li><a href="success-stories.php">قصص النجاح</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>الدعم</h6>
                                <ul class="footer-links">
                                    <li><a href="help.php">المساعدة</a></li>
                                    <li><a href="contact.php">اتصل بنا</a></li>
                                    <li><a href="faq.php">الأسئلة الشائعة</a></li>
                                    <li><a href="community.php">المجتمع</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6>الشراكات</h6>
                                <ul class="footer-links">
                                    <li><a href="partners.php">شركاؤنا</a></li>
                                    <li><a href="instructors.php">كن مدرباً</a></li>
                                    <li><a href="api.php">API للمطورين</a></li>
                                    <li><a href="affiliate.php">البرنامج التابع</a></li>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS after page load
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    offset: 100
                });
            }
        });
    </script>
    <script src="js/challenges-script.js"></script>
</body>
</html>