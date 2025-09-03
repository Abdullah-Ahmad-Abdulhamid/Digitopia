<?php
require_once 'config/config.php';

// Require login
requireLogin();

// Get user progress
$skillsObj = new Skills();
$progressResult = $skillsObj->getUserProgress($_SESSION['user_id']);

if (!$progressResult['success']) {
    $userProgress = [];
    $userStats = [];
} else {
    $userProgress = $progressResult['data']['progress'];
    $userStats = $progressResult['data']['stats'];
}

// Get user profile
$userObj = new User();
$userResult = $userObj->getUserProfile($_SESSION['user_id']);
$user = $userResult['success'] ? $userResult['user'] : [];

// Get challenge stats
$challengesObj = new Challenges();
$challengeHistoryResult = $challengesObj->getUserChallengeHistory($_SESSION['user_id'], 5);
$challengeStats = [];
$recentChallenges = [];
if ($challengeHistoryResult['success']) {
    $challengeStats = $challengeHistoryResult['data']['stats'];
    $recentChallenges = $challengeHistoryResult['data']['history'];
}

// Calculate level progress
$currentPoints = $userStats['total_points'] ?? 0;
$nextLevelPoints = 50; // مبتدئ إلى متوسط
$levelName = $userStats['level_name'] ?? 'مبتدئ';
$nextLevel = 'متوسط';

if ($currentPoints >= 200) {
    $nextLevelPoints = 500; // متقدم إلى خبير
    $nextLevel = 'خبير';
} elseif ($currentPoints >= 50) {
    $nextLevelPoints = 200; // متوسط إلى متقدم
    $nextLevel = 'متقدم';
}

$progressToNext = min(100, ($currentPoints / $nextLevelPoints) * 100);
$pointsNeeded = max(0, $nextLevelPoints - $currentPoints);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقدمي - مهاراتي</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="تابع تقدمك في تعلم المهارات الحياتية على منصة مهاراتي">
    <meta name="keywords" content="تقدم التعلم، مهارات حياتية، إنجازات، تطوير ذاتي">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="تقدمي - منصة مهاراتي">
    <meta property="og:description" content="تابع تقدمك وإنجازاتك في تعلم المهارات الحياتية">
    <meta property="og:image" content="/images/og-image.jpg">
    <meta property="og:url" content="https://maharati.app/progress">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" href="images/logo.png">

    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="css/index-styles.css" rel="stylesheet">
    <link href="css/progress-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><i class="fas fa-chart-line"></i></div>
        <div class="floating-element element-2"><i class="fas fa-trophy"></i></div>
        <div class="floating-element element-3"><i class="fas fa-star"></i></div>
        <div class="floating-element element-4"><i class="fas fa-medal"></i></div>
        <div class="floating-element element-5"><i class="fas fa-crown"></i></div>
        <div class="floating-element element-6"><i class="fas fa-graduation-cap"></i></div>
        <div class="floating-element element-7"><i class="fas fa-rocket"></i></div>
        <div class="floating-element element-8"><i class="fas fa-seedling"></i></div>
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
                        <a class="nav-link" href="index.php"><i class="fas fa-home me-2"></i>الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="skills.php"><i class="fas fa-seedling me-2"></i>المهارات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="progress.php"><i class="fas fa-chart-line me-2"></i>تقدمي</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name"><?php echo htmlspecialchars($user['name'] ?? $_SESSION['user_name'] ?? 'المستخدم'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animated-dropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل خروج</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="progress-hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="growth-particles"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-chart-line me-2"></i>
                            <span>رحلة نموك الشخصي</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4">
                            تقدمك في
                            <span class="gradient-text">رحلة النمو</span>
                            <div class="growth-animation">
                                <i class="fas fa-trophy"></i>
                                <div class="growth-sparkles">
                                    <span class="sparkle sparkle-1"></span>
                                    <span class="sparkle sparkle-2"></span>
                                    <span class="sparkle sparkle-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5">
                            تابع إنجازاتك واكتشف مدى تطورك في المهارات الحياتية التي تعلمتها. كل خطوة تقربك من أهدافك!
                        </p>
                        
                        <div class="achievement-highlights">
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="achievement-text">
                                    <span class="number"><?php echo $userStats['completed_skills'] ?? 0; ?></span>
                                    <span class="label">مهارة مكتملة</span>
                                </div>
                            </div>
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <div class="achievement-text">
                                    <span class="number"><?php echo $userStats['total_points'] ?? 0; ?></span>
                                    <span class="label">نقطة تطوير</span>
                                </div>
                            </div>
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="achievement-text">
                                    <span class="number"><?php echo $levelName; ?></span>
                                    <span class="label">مستواك الحالي</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="level-progress-visual">
                        <div class="progress-ecosystem">
                            <!-- Central Level Hub -->
                            <div class="level-hub">
                                <div class="hub-core">
                                    <i class="fas fa-crown"></i>
                                    <div class="level-text">
                                        <span class="level-name"><?php echo $levelName; ?></span>
                                        <span class="level-percentage"><?php echo round($progressToNext); ?>%</span>
                                    </div>
                                </div>
                                <div class="progress-rings">
                                    <svg class="progress-ring" width="200" height="200">
                                        <defs>
                                            <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                        <circle class="progress-ring-bg" cx="100" cy="100" r="85" 
                                                fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="8"></circle>
                                        <circle class="progress-ring-fill" cx="100" cy="100" r="85" 
                                                fill="none" stroke="url(#progressGradient)" stroke-width="8"
                                                style="--progress: <?php echo $progressToNext; ?>"></circle>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Floating Achievement Nodes -->
                            <div class="achievement-node node-1">
                                <i class="fas fa-book-open"></i>
                                <span><?php echo $userStats['total_skills'] ?? 0; ?> مهارات</span>
                            </div>
                            <div class="achievement-node node-2">
                                <i class="fas fa-trophy"></i>
                                <span><?php echo $challengeStats['total_completed'] ?? 0; ?> تحديات</span>
                            </div>
                            <div class="achievement-node node-3">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $userStats['total_time'] ?? 0; ?> دقيقة</span>
                            </div>
                        </div>
                        
                        <div class="level-details">
                            <p class="next-level-text">المستوى التالي: <strong><?php echo $nextLevel; ?></strong></p>
                            <p class="points-needed-text"><?php echo $pointsNeeded; ?> نقطة متبقية للترقي</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-text">اكتشف إنجازاتك</div>
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- User Stats Section -->
    <section class="user-stats-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $userStats['total_skills'] ?? 0; ?>">0</div>
                        <div class="stats-label">مهارات بدأتها</div>
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
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $userStats['completed_skills'] ?? 0; ?>">0</div>
                        <div class="stats-label">مهارات أكملتها</div>
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
                            <i class="fas fa-gem"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $userStats['total_points'] ?? 0; ?>">0</div>
                        <div class="stats-label">نقاط التطوير</div>
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
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stats-number counter" data-count="<?php echo $challengeStats['total_completed'] ?? 0; ?>">0</div>
                        <div class="stats-label">تحديات أكملتها</div>
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

    <!-- Skills Progress Section -->
    <section class="skills-progress-section py-5">
        <div class="container">
            <div class="row">
                <!-- Skills Progress -->
                <div class="col-lg-8">
                    <div class="section-header mb-4">
                        <h2 class="section-title">مهاراتك قيد النمو</h2>
                        <p class="section-subtitle">تابع تطورك في كل مهارة بدأت رحلة تعلمها</p>
                        <div class="section-divider"></div>
                    </div>

                    <?php if (empty($userProgress)): ?>
                        <div class="empty-state-spotlight">
                            <div class="empty-state-content">
                                <div class="empty-state-icon">
                                    <i class="fas fa-seedling"></i>
                                    <div class="icon-glow"></div>
                                </div>
                                <h4 class="empty-state-title">ابدأ رحلة نموك الآن</h4>
                                <p class="empty-state-text">
                                    لم تبدأ أي مهارة بعد! اكتشف عالم المهارات الحياتية وابدأ رحلة التطوير
                                </p>
                                <a href="skills.php" class="btn btn-hero-primary btn-lg">
                                    <i class="fas fa-rocket me-2"></i>استكشف المهارات
                                    <div class="btn-sparkle"></div>
                                </a>
                            </div>
                            <div class="empty-state-decoration">
                                <div class="floating-particle particle-1"></div>
                                <div class="floating-particle particle-2"></div>
                                <div class="floating-particle particle-3"></div>
                                <div class="floating-particle particle-4"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="skills-progress-grid">
                            <?php foreach ($userProgress as $index => $progress): ?>
                            <div class="skill-progress-card-modern" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="skill-card-header">
                                    <div class="skill-icon-wrapper">
                                        <i class="<?php echo htmlspecialchars($progress['icon_name'] ?? 'fas fa-lightbulb'); ?> skill-icon"></i>
                                        <div class="skill-icon-glow"></div>
                                    </div>
                                    <div class="skill-meta">
                                        <h5 class="skill-title">
                                            <a href="skill.php?id=<?php echo $progress['id']; ?>" class="skill-link">
                                                <?php echo htmlspecialchars($progress['title_ar']); ?>
                                            </a>
                                        </h5>
                                        <span class="skill-category"><?php echo htmlspecialchars($progress['category_name']); ?></span>
                                    </div>
                                    <?php if ($progress['completed_at']): ?>
                                        <div class="completion-badge">
                                            <i class="fas fa-crown"></i>
                                            <div class="completion-sparkle"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="skill-card-body">
                                    <div class="progress-visualization">
                                        <div class="circular-progress-modern" data-progress="<?php echo $progress['progress_percentage']; ?>">
                                            <svg class="progress-circle" width="100" height="100">
                                                <defs>
                                                    <linearGradient id="skillGradient<?php echo $index; ?>" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
                                                    </linearGradient>
                                                </defs>
                                                <circle class="progress-circle-bg" cx="50" cy="50" r="40"></circle>
                                                <circle class="progress-circle-fill" cx="50" cy="50" r="40" 
                                                        style="--progress: <?php echo $progress['progress_percentage']; ?>"
                                                        stroke="url(#skillGradient<?php echo $index; ?>)"></circle>
                                            </svg>
                                            <div class="progress-text">
                                                <span class="progress-percentage"><?php echo round($progress['progress_percentage']); ?>%</span>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-details">
                                            <div class="progress-item">
                                                <div class="progress-icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="progress-info">
                                                    <span class="info-value"><?php echo $progress['time_spent']; ?> دقيقة</span>
                                                    <span class="info-label">وقت التعلم</span>
                                                </div>
                                            </div>
                                            <div class="progress-item">
                                                <div class="progress-icon">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                                <div class="progress-info">
                                                    <span class="info-value">
                                                        <?php if ($progress['completed_at']): ?>
                                                            <?php echo date('d/m/Y', strtotime($progress['completed_at'])); ?>
                                                        <?php else: ?>
                                                            قيد التقدم
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="info-label">حالة المهارة</span>
                                                </div>
                                            </div>
                                            <?php if ($progress['rating']): ?>
                                            <div class="progress-item">
                                                <div class="progress-icon">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <div class="progress-info">
                                                    <div class="rating-stars">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?php echo $i <= $progress['rating'] ? 'star-filled' : 'star-empty'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="info-label">تقييمك</span>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="skill-card-footer">
                                    <?php if ($progress['completed_at']): ?>
                                        <a href="skill.php?id=<?php echo $progress['id']; ?>" class="btn btn-skill-review">
                                            <i class="fas fa-eye me-2"></i>مراجعة المهارة
                                            <div class="btn-growth-effect"></div>
                                        </a>
                                    <?php else: ?>
                                        <a href="skill.php?id=<?php echo $progress['id']; ?>" class="btn btn-skill-continue">
                                            <i class="fas fa-play me-2"></i>متابعة النمو
                                            <div class="btn-growth-effect"></div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-glow"></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Enhanced Sidebar -->
                <div class="col-lg-4">
                    <!-- Level Progress Card -->
                    <div class="modern-sidebar-card level-progression-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <h5 class="card-title-modern">مستوى تطورك</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="level-display-modern">
                                <div class="current-level">
                                    <span class="level-badge"><?php echo $levelName; ?></span>
                                    <p class="level-points"><?php echo $currentPoints; ?> نقطة</p>
                                </div>
                                
                                <div class="level-progress-modern">
                                    <div class="progress-info">
                                        <span>التقدم للمستوى التالي</span>
                                        <span><?php echo round($progressToNext); ?>%</span>
                                    </div>
                                    <div class="modern-progress-bar">
                                        <div class="progress-fill-modern" style="width: <?php echo $progressToNext; ?>%"></div>
                                        <div class="progress-shimmer"></div>
                                    </div>
                                    <p class="next-level-info">
                                        <?php echo $pointsNeeded; ?> نقطة للوصول لمستوى <strong><?php echo $nextLevel; ?></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-glow"></div>
                    </div>

                    <!-- Recent Challenges -->
                    <?php if (!empty($recentChallenges)): ?>
                    <div class="modern-sidebar-card challenges-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h6 class="card-title-modern">إنجازاتك الأخيرة</h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="challenges-list">
                                <?php foreach (array_slice($recentChallenges, 0, 3) as $challenge): ?>
                                <div class="challenge-item-modern">
                                    <div class="challenge-icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="challenge-content">
                                        <h6 class="challenge-title"><?php echo htmlspecialchars($challenge['title_ar']); ?></h6>
                                        <div class="challenge-meta">
                                            <span class="challenge-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('d/m', strtotime($challenge['completed_at'])); ?>
                                            </span>
                                            <span class="challenge-points">
                                                <i class="fas fa-gem me-1"></i>
                                                +<?php echo $challenge['earned_points']; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <a href="challenges.php" class="btn btn-view-all-small">
                                <i class="fas fa-compass me-2"></i>استكشف المزيد
                                <div class="btn-bloom-effect"></div>
                            </a>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="modern-sidebar-card actions-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h6 class="card-title-modern">إجراءات سريعة</h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="quick-actions-modern">
                                <a href="skills.php" class="quick-action-btn-modern">
                                    <div class="action-icon">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <span>مهارة جديدة</span>
                                    <div class="action-glow"></div>
                                </a>
                                <a href="challenges.php" class="quick-action-btn-modern">
                                    <div class="action-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <span>تحدي اليوم</span>
                                    <div class="action-glow"></div>
                                </a>
                                <a href="chat.php" class="quick-action-btn-modern">
                                    <div class="action-icon">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <span>المساعد الذكي</span>
                                    <div class="action-glow"></div>
                                </a>
                                <a href="profile.php" class="quick-action-btn-modern">
                                    <div class="action-icon">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <span>الملف الشخصي</span>
                                    <div class="action-glow"></div>
                                </a>
                            </div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievement Showcase Section -->
    <?php if (!empty($userStats) && ($userStats['completed_skills'] > 0 || $userStats['total_points'] > 0)): ?>
    <section class="achievements-showcase-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">صالة إنجازاتك</h2>
                <p class="section-subtitle">الشارات والأوسمة التي حققتها في رحلة نموك</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="achievements-gallery">
                <?php if ($userStats['completed_skills'] >= 1): ?>
                <div class="achievement-card-modern earned" data-aos="zoom-in" data-aos-delay="100">
                    <div class="achievement-glow"></div>
                    <div class="achievement-content">
                        <div class="achievement-badge">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h6 class="achievement-name">بذرة النمو</h6>
                        <p class="achievement-desc">أكملت أول مهارة في رحلتك</p>
                        <div class="achievement-date">حققتها مؤخراً</div>
                    </div>
                    <div class="achievement-sparkles">
                        <span class="sparkle sparkle-1"></span>
                        <span class="sparkle sparkle-2"></span>
                        <span class="sparkle sparkle-3"></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($userStats['total_points'] >= 50): ?>
                <div class="achievement-card-modern earned" data-aos="zoom-in" data-aos-delay="200">
                    <div class="achievement-glow"></div>
                    <div class="achievement-content">
                        <div class="achievement-badge">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h6 class="achievement-name">جامع الجواهر</h6>
                        <p class="achievement-desc">حصلت على 50+ نقطة تطوير</p>
                        <div class="achievement-date">مستمر في النمو</div>
                    </div>
                    <div class="achievement-sparkles">
                        <span class="sparkle sparkle-1"></span>
                        <span class="sparkle sparkle-2"></span>
                        <span class="sparkle sparkle-3"></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($challengeStats['total_completed'] >= 5): ?>
                <div class="achievement-card-modern earned" data-aos="zoom-in" data-aos-delay="300">
                    <div class="achievement-glow"></div>
                    <div class="achievement-content">
                        <div class="achievement-badge">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h6 class="achievement-name">محارب التحديات</h6>
                        <p class="achievement-desc">أكملت 5+ تحديات بنجاح</p>
                        <div class="achievement-date">محقق أهداف</div>
                    </div>
                    <div class="achievement-sparkles">
                        <span class="sparkle sparkle-1"></span>
                        <span class="sparkle sparkle-2"></span>
                        <span class="sparkle sparkle-3"></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($userStats['completed_skills'] >= 5): ?>
                <div class="achievement-card-modern earned" data-aos="zoom-in" data-aos-delay="400">
                    <div class="achievement-glow"></div>
                    <div class="achievement-content">
                        <div class="achievement-badge">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h6 class="achievement-name">عقل نشط</h6>
                        <p class="achievement-desc">أكملت 5+ مهارات متنوعة</p>
                        <div class="achievement-date">متعلم مثابر</div>
                    </div>
                    <div class="achievement-sparkles">
                        <span class="sparkle sparkle-1"></span>
                        <span class="sparkle sparkle-2"></span>
                        <span class="sparkle sparkle-3"></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($userStats['total_points'] >= 200): ?>
                <div class="achievement-card-modern earned legendary" data-aos="zoom-in" data-aos-delay="500">
                    <div class="achievement-glow legendary-glow"></div>
                    <div class="achievement-content">
                        <div class="achievement-badge legendary-badge">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h6 class="achievement-name">أسطورة المهارات</h6>
                        <p class="achievement-desc">حصلت على 200+ نقطة - إنجاز مذهل!</p>
                        <div class="achievement-date">مستوى أسطوري</div>
                    </div>
                    <div class="achievement-sparkles legendary-sparkles">
                        <span class="sparkle sparkle-1"></span>
                        <span class="sparkle sparkle-2"></span>
                        <span class="sparkle sparkle-3"></span>
                        <span class="sparkle sparkle-4"></span>
                        <span class="sparkle sparkle-5"></span>
                    </div>
                </div>
                <?php endif; ?>
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

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="footer-brand">
                            <h5><i class="fas fa-seedling me-2"></i>مهاراتي</h5>
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
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="js/index-script.js"></script>
    <script src="js/progress-script.js"></script>
</body>
</html>