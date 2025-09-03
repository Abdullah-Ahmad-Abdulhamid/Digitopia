<?php
require_once 'config/config.php';

$skillId = $_GET['id'] ?? null;
if (!$skillId) {
    redirectTo('skills.php');
}

// Get skill details
$skillsObj = new Skills();
$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$skillResult = $skillsObj->getSkill($skillId, $userId);

if (!$skillResult['success']) {
    redirectTo('skills.php');
}

$skill = $skillResult['data'];

// Check if premium skill and user has access
$hasAccess = true;
if ($skill['is_premium'] && (!isLoggedIn() || $_SESSION['subscription_type'] === 'free')) {
    $hasAccess = false;
}

// Get user data if logged in
$user = null;
if (isLoggedIn()) {
    $userObj = new User();
    $userResult = $userObj->getUserProfile($_SESSION['user_id']);
    if ($userResult['success']) {
        $user = $userResult['user'];
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($skill['title_ar']); ?> - <?php echo SITE_NAME ?? 'مهاراتي'; ?></title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="<?php echo htmlspecialchars(substr($skill['description_ar'], 0, 160)); ?>">
    <meta name="keywords" content="مهارات حياتية، تعليم، <?php echo htmlspecialchars($skill['title_ar']); ?>">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($skill['title_ar']); ?> - منصة مهاراتي">
    <meta property="og:description" content="<?php echo htmlspecialchars($skill['description_ar']); ?>">
    <meta property="og:image" content="<?php echo SITE_URL ?? ''; ?>/images/og-image.jpg">
    <meta property="og:url" content="<?php echo SITE_URL ?? ''; ?>/skill.php?id=<?php echo $skillId; ?>">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" href="images/logo.png">

    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="css/index-styles.css" rel="stylesheet">
    <link href="css/skill-styles.css" rel="stylesheet">
</head>
<body>

<!-- Animated Background Elements -->
<div class="floating-elements">
    <div class="floating-element element-1"><i class="fas fa-seedling"></i></div>
    <div class="floating-element element-2"><i class="fas fa-lightbulb"></i></div>
    <div class="floating-element element-3"><i class="fas fa-book-open"></i></div>
    <div class="floating-element element-4"><i class="fas fa-trophy"></i></div>
    <div class="floating-element element-5"><i class="fas fa-star"></i></div>
    <div class="floating-element element-6"><i class="fas fa-brain"></i></div>
    <div class="floating-element element-7"><i class="fas fa-rocket"></i></div>
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
                    <a class="nav-link" href="index.php"><i class="fas fa-home me-2"></i>الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="skills.php"><i class="fas fa-seedling me-2"></i>المهارات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
                </li>
                <?php if (isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="progress.php"><i class="fas fa-chart-line me-2"></i>تقدمي</a>
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
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل خروج</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link login-link" href="login.php"><i class="fas fa-sign-in-alt me-2"></i>دخول</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Skill Hero Section -->
<section class="skill-hero-section position-relative overflow-hidden">
    <div class="hero-background"></div>
    <div class="growth-particles"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8" data-aos="fade-right" data-aos-duration="1000">
                <div class="skill-hero-content">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb skill-breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="skills.php">المهارات</a></li>
                            <li class="breadcrumb-item"><a href="skills.php?category=<?php echo $skill['category_id']; ?>"><?php echo htmlspecialchars($skill['category_name']); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo htmlspecialchars($skill['title_ar']); ?></li>
                        </ol>
                    </nav>

                    <!-- Skill Header -->
                    <div class="skill-header" data-aos="fade-up" data-aos-delay="300">
                        <div class="skill-icon-container">
                            <div class="skill-icon-large">
                                <i class="<?php echo htmlspecialchars($skill['icon_name'] ?? 'fas fa-lightbulb'); ?>"></i>
                                <div class="icon-glow"></div>
                            </div>
                            <div class="skill-level-indicator">
                                <?php 
                                $levels = ['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'];
                                echo $levels[$skill['difficulty_level']] ?? 'مبتدئ';
                                ?>
                            </div>
                        </div>
                        
                        <div class="skill-meta">
                            <h1 class="skill-title"><?php echo htmlspecialchars($skill['title_ar']); ?></h1>
                            <p class="skill-description"><?php echo htmlspecialchars($skill['description_ar']); ?></p>
                            
                            <!-- Skill Badges -->
                            <div class="skill-badges">
                                <span class="skill-badge time-badge">
                                    <i class="fas fa-clock me-2"></i><?php echo $skill['estimated_time'] ?? 30; ?> دقيقة
                                </span>
                                <span class="skill-badge category-badge">
                                    <i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($skill['category_name']); ?>
                                </span>
                                <?php if ($skill['is_premium']): ?>
                                <span class="skill-badge premium-badge">
                                    <i class="fas fa-crown me-2"></i>مميز
                                </span>
                                <?php endif; ?>
                                <span class="skill-badge views-badge">
                                    <i class="fas fa-eye me-2"></i><?php echo $skill['views_count']; ?> متعلم
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="skill-actions" data-aos="fade-up" data-aos-delay="500">
                        <?php if ($hasAccess): ?>
                            <button class="btn btn-skill-primary btn-lg" id="startLearningBtn">
                                <i class="fas fa-play me-2"></i>ابدأ التعلم الآن
                                <div class="btn-sparkle"></div>
                            </button>
                        <?php else: ?>
                            <a href="subscription.php" class="btn btn-skill-premium btn-lg">
                                <i class="fas fa-crown me-2"></i>ترقية للاشتراك المميز
                                <div class="btn-sparkle"></div>
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-skill-outline btn-lg" onclick="shareSkill()">
                            <i class="fas fa-share-alt me-2"></i>مشاركة
                        </button>
                        <button class="btn btn-skill-outline btn-lg" onclick="addToFavorites()">
                            <i class="fas fa-heart me-2"></i>إضافة للمفضلة
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-left" data-aos-duration="1000">
                <div class="skill-stats-dashboard">
                    <?php if (isset($skill['user_progress']) && is_array($skill['user_progress'])): ?>
                        <!-- User Progress Dashboard -->
                        <div class="progress-dashboard-card">
                            <div class="dashboard-header">
                                <h4><i class="fas fa-chart-line me-2"></i>تقدمك في هذه المهارة</h4>
                            </div>
                            <div class="dashboard-content">
                                <div class="progress-circle-container">
                                    <div class="progress-circle" data-progress="<?php echo round($skill['user_progress']['progress_percentage']); ?>">
                                        <div class="progress-circle-inner">
                                            <span class="progress-text"><?php echo round($skill['user_progress']['progress_percentage']); ?>%</span>
                                            <span class="progress-label">مكتمل</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="progress-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span class="stat-label">الوقت المقضي</span>
                                        <span class="stat-value"><?php echo $skill['user_progress']['time_spent'] ?? 0; ?> دقيقة</span>
                                    </div>
                                    <?php if (isset($skill['user_progress']['rating'])): ?>
                                    <div class="stat-item">
                                        <i class="fas fa-star"></i>
                                        <span class="stat-label">تقييمك</span>
                                        <span class="stat-value"><?php echo $skill['user_progress']['rating']; ?>/5</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="stat-item">
                                        <i class="fas fa-calendar"></i>
                                        <span class="stat-label">آخر تحديث</span>
                                        <span class="stat-value">اليوم</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Skill Overview -->
                        <div class="skill-overview-card">
                            <div class="overview-header">
                                <h4><i class="fas fa-info-circle me-2"></i>نظرة عامة</h4>
                            </div>
                            <div class="overview-content">
                                <div class="overview-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-users"></i>
                                        <span class="stat-label">المتعلمين</span>
                                        <span class="stat-value"><?php echo $skill['views_count']; ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-star"></i>
                                        <span class="stat-label">التقييم</span>
                                        <span class="stat-value">4.8/5</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-certificate"></i>
                                        <span class="stat-label">شهادة</span>
                                        <span class="stat-value">متوفرة</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span class="stat-label">المدة</span>
                                        <span class="stat-value"><?php echo $skill['estimated_time'] ?? 30; ?> دقيقة</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Learning Elements -->
    <div class="learning-elements">
        <div class="learning-element element-1" data-aos="zoom-in" data-aos-delay="1000">
            <i class="fas fa-book"></i>
        </div>
        <div class="learning-element element-2" data-aos="zoom-in" data-aos-delay="1200">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div class="learning-element element-3" data-aos="zoom-in" data-aos-delay="1400">
            <i class="fas fa-brain"></i>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="skill-content-section py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <?php if (!$hasAccess): ?>
                    <!-- Premium Access Required -->
                    <div class="premium-gate-card" data-aos="fade-up">
                        <div class="premium-gate-content">
                            <div class="premium-icon">
                                <i class="fas fa-crown"></i>
                                <div class="crown-particles"></div>
                            </div>
                            <h3 class="premium-title">مهارة مميزة 👑</h3>
                            <p class="premium-description">هذه المهارة متاحة للمشتركين المميزين فقط. احصل على إمكانية الوصول الكامل لجميع المهارات المتقدمة والمحتوى الحصري.</p>
                            
                            <div class="premium-features">
                                <div class="feature-item">
                                    <i class="fas fa-check"></i>
                                    <span>وصول لجميع المهارات المتقدمة</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check"></i>
                                    <span>فيديوهات تعليمية عالية الجودة</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check"></i>
                                    <span>تتبع التقدم المفصل والتحليلات</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check"></i>
                                    <span>شهادات إتمام معتمدة</span>
                                </div>
                            </div>
                            
                            <a href="subscription.php" class="btn btn-premium-upgrade">
                                <i class="fas fa-rocket me-2"></i>ترقية الاشتراك الآن
                                <div class="btn-shine"></div>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Video Learning Section -->
                    <div class="video-learning-card" data-aos="fade-up">
                        <div class="video-header">
                            <h3><i class="fas fa-play-circle me-3"></i>شاهد وتعلم</h3>
                            <div class="video-divider"></div>
                        </div>
                        
                        <div class="video-container">
                            <div class="video-player-wrapper">
                                <div class="video-placeholder" id="videoPlaceholder">
                                    <div class="video-poster">
                                        <img src="https://images.pexels.com/photos/5428833/pexels-photo-5428833.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Video Thumbnail">
                                        <div class="play-button" onclick="playVideo()">
                                            <i class="fas fa-play"></i>
                                            <div class="play-ripple"></div>
                                        </div>
                                        <div class="video-duration">
                                            <i class="fas fa-clock me-2"></i><?php echo $skill['estimated_time'] ?? 30; ?> دقيقة
                                        </div>
                                    </div>
                                </div>
                                <video id="skillVideo" class="video-player" controls style="display: none;">
                                    <source src="videos/skill-<?php echo $skillId; ?>.mp4" type="video/mp4">
                                    المتصفح لا يدعم تشغيل الفيديو.
                                </video>
                            </div>
                            
                            <div class="video-controls-overlay">
                                <div class="video-progress-bar">
                                    <div class="progress-track">
                                        <div class="progress-fill" id="videoProgress"></div>
                                        <div class="progress-thumb" id="progressThumb"></div>
                                    </div>
                                </div>
                                
                                <div class="video-info">
                                    <div class="video-title"><?php echo htmlspecialchars($skill['title_ar']); ?></div>
                                    <div class="video-description">تعلم خطوة بخطوة مع أمثلة عملية</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Skill Content -->
                    <div class="skill-content-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="content-header">
                            <h3><i class="fas fa-book-open me-3"></i>محتوى المهارة</h3>
                            <div class="content-divider"></div>
                        </div>
                        
                        <div class="skill-content-body">
                            <div class="content-text">
                                <?php echo nl2br(htmlspecialchars($skill['content_ar'])); ?>
                            </div>

                            <?php if ($skill['practical_examples']): ?>
                            <div class="content-section examples-section" data-aos="fade-up" data-aos-delay="300">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <h4 class="section-title">أمثلة عملية من الواقع</h4>
                                </div>
                                <div class="section-content">
                                    <?php echo nl2br(htmlspecialchars($skill['practical_examples'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($skill['egyptian_context']): ?>
                            <div class="content-section context-section" data-aos="fade-up" data-aos-delay="400">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h4 class="section-title">في السياق المصري</h4>
                                </div>
                                <div class="section-content">
                                    <?php echo nl2br(htmlspecialchars($skill['egyptian_context'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Interactive Progress Tracking -->
                    <?php if (isLoggedIn()): ?>
                    <div class="progress-tracking-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="progress-header">
                            <h4><i class="fas fa-seedling me-3"></i>تتبع نموك في هذه المهارة</h4>
                            <div class="progress-divider"></div>
                        </div>
                        
                        <div class="progress-body">
                            <!-- Interactive Progress Bar -->
                            <div class="progress-container">
                                <div class="progress-label-container">
                                    <span class="progress-label">مستوى تقدمك</span>
                                    <span class="progress-percentage" id="progressPercentage">
                                        <?php echo isset($skill['user_progress']) && is_array($skill['user_progress']) ? round($skill['user_progress']['progress_percentage']) : 0; ?>%
                                    </span>
                                </div>
                                <div class="animated-progress">
                                    <div class="progress-fill" id="progressFill"
                                         style="width: <?php echo isset($skill['user_progress']) && is_array($skill['user_progress']) ? $skill['user_progress']['progress_percentage'] : 0; ?>%">
                                        <div class="progress-glow"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Milestones -->
                            <div class="progress-milestones">
                                <div class="milestone <?php echo (isset($skill['user_progress']) && $skill['user_progress']['progress_percentage'] >= 25) ? 'completed' : ''; ?>" 
                                     data-progress="25" onclick="updateProgress(25)">
                                    <div class="milestone-icon">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <div class="milestone-content">
                                        <span class="milestone-title">البداية</span>
                                        <span class="milestone-desc">25%</span>
                                    </div>
                                </div>
                                
                                <div class="milestone <?php echo (isset($skill['user_progress']) && $skill['user_progress']['progress_percentage'] >= 50) ? 'completed' : ''; ?>" 
                                     data-progress="50" onclick="updateProgress(50)">
                                    <div class="milestone-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <div class="milestone-content">
                                        <span class="milestone-title">النمو</span>
                                        <span class="milestone-desc">50%</span>
                                    </div>
                                </div>
                                
                                <div class="milestone <?php echo (isset($skill['user_progress']) && $skill['user_progress']['progress_percentage'] >= 75) ? 'completed' : ''; ?>" 
                                     data-progress="75" onclick="updateProgress(75)">
                                    <div class="milestone-icon">
                                        <i class="fas fa-tree"></i>
                                    </div>
                                    <div class="milestone-content">
                                        <span class="milestone-title">التطور</span>
                                        <span class="milestone-desc">75%</span>
                                    </div>
                                </div>
                                
                                <div class="milestone <?php echo (isset($skill['user_progress']) && $skill['user_progress']['progress_percentage'] >= 100) ? 'completed' : ''; ?>" 
                                     data-progress="100" onclick="updateProgress(100)">
                                    <div class="milestone-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="milestone-content">
                                        <span class="milestone-title">الإتقان</span>
                                        <span class="milestone-desc">100%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating System -->
                            <?php if (isset($skill['user_progress']) && is_array($skill['user_progress']) && $skill['user_progress']['progress_percentage'] > 0): ?>
                            <div class="rating-section">
                                <h6 class="rating-title">كيف كانت تجربة التعلم؟</h6>
                                <div class="rating-stars-container">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="star-wrapper">
                                        <i class="fas fa-star rating-star <?php echo isset($skill['user_progress']['rating']) && $skill['user_progress']['rating'] >= $i ? 'active' : ''; ?>"
                                           data-rating="<?php echo $i; ?>"></i>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Skills -->
                <?php if (!empty($skill['related_skills'])): ?>
                <div class="related-skills-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header">
                        <h5><i class="fas fa-link me-3"></i>مهارات مترابطة</h5>
                        <div class="header-decoration"></div>
                    </div>
                    <div class="card-body">
                        <?php foreach ($skill['related_skills'] as $index => $relatedSkill): ?>
                        <div class="related-skill-item" data-aos="fade-up" data-aos-delay="<?php echo 400 + ($index * 100); ?>">
                            <div class="skill-icon">
                                <i class="<?php echo htmlspecialchars($relatedSkill['icon_name'] ?? 'fas fa-lightbulb'); ?>"></i>
                            </div>
                            <div class="skill-info">
                                <h6 class="skill-title">
                                    <a href="skill.php?id=<?php echo $relatedSkill['id']; ?>">
                                        <?php echo htmlspecialchars($relatedSkill['title_ar']); ?>
                                    </a>
                                </h6>
                                <span class="skill-difficulty">
                                    <?php 
                                    $levels = ['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'];
                                    echo $levels[$relatedSkill['difficulty_level']] ?? 'مبتدئ';
                                    ?>
                                </span>
                            </div>
                            <div class="skill-arrow">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="quick-actions-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt me-3"></i>إجراءات سريعة</h5>
                        <div class="header-decoration"></div>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <a href="chat.php" class="action-btn chat-btn">
                                <div class="btn-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">اسأل المساعد الذكي</span>
                                    <span class="btn-description">احصل على مساعدة فورية</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </a>
                            
                            <a href="challenges.php" class="action-btn challenge-btn">
                                <div class="btn-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">التحديات اليومية</span>
                                    <span class="btn-description">اختبر مهاراتك الجديدة</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </a>
                            
                            <a href="skills.php?category=<?php echo $skill['category_id']; ?>" class="action-btn category-btn">
                                <div class="btn-icon">
                                    <i class="fas fa-th"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">مهارات مشابهة</span>
                                    <span class="btn-description">اكتشف المزيد</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </a>

                            <a href="progress.php" class="action-btn progress-btn">
                                <div class="btn-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">تقرير التقدم</span>
                                    <span class="btn-description">راجع إنجازاتك</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Learning Tips -->
                <div class="learning-tips-card" data-aos="fade-up" data-aos-delay="800">
                    <div class="card-header">
                        <h5><i class="fas fa-lightbulb me-3"></i>نصائح للنجاح</h5>
                        <div class="header-decoration"></div>
                    </div>
                    <div class="card-body">
                        <div class="tips-list">
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-title">خصص وقتاً يومياً</span>
                                    <span class="tip-text">حتى لو 15 دقيقة يومياً كافية لبناء العادة</span>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-notes-medical"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-title">دوّن ملاحظاتك</span>
                                    <span class="tip-text">اكتب النقاط المهمة والأفكار الجديدة</span>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-title">شارك مع الآخرين</span>
                                    <span class="tip-text">التعلم التشاركي يثبت المعلومة أكثر</span>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-redo"></i>
                                </div>
                                <div class="tip-content">
                                    <span class="tip-title">راجع بانتظام</span>
                                    <span class="tip-text">المراجعة الدورية تحسن الاحتفاظ بالمعلومة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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

<!-- Completion Celebration Modal -->
<div class="modal fade" id="completionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content celebration-modal">
            <div class="modal-body text-center">
                <div class="celebration-animation">
                    <div class="trophy-container">
                        <i class="fas fa-trophy celebration-trophy"></i>
                        <div class="trophy-glow"></div>
                    </div>
                    <div class="celebration-confetti" id="celebrationConfetti"></div>
                    <div class="celebration-fireworks"></div>
                </div>
                <h3 class="celebration-title">مبروك! 🎉</h3>
                <p class="celebration-text">لقد أكملت مهارة <strong><?php echo htmlspecialchars($skill['title_ar']); ?></strong> بنجاح!</p>
                <div class="achievement-badge">
                    <i class="fas fa-medal me-2"></i>حصلت على +50 نقطة تطوير
                </div>
                <div class="celebration-buttons">
                    <a href="skills.php" class="btn btn-celebration-primary">استكشف مهارات جديدة</a>
                    <a href="challenges.php" class="btn btn-celebration-secondary">جرب التحديات</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/index-script.js"></script>
<script src="js/skill-script.js"></script>

<script>
    // Initialize skill-specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Initialize skill page features
        initSkillPage();
    });

    function initSkillPage() {
        initProgressCircle();
        initVideoPlayer();
        initRatingSystem();
        initMilestones();
    }

    // Progress tracking
    function updateProgress(percentage) {
        <?php if (!isLoggedIn()): ?>
            showToast('يرجى تسجيل الدخول لتتبع التقدم', 'warning');
            return;
        <?php endif; ?>

        const progressFill = document.getElementById('progressFill');
        const progressPercentage = document.getElementById('progressPercentage');
        
        // Update UI with animation
        if (progressFill) {
            progressFill.style.width = percentage + '%';
            progressFill.classList.add('progress-updating');
            setTimeout(() => {
                progressFill.classList.remove('progress-updating');
            }, 1000);
        }
        
        if (progressPercentage) {
            progressPercentage.textContent = percentage + '%';
        }
        
        // Update milestones
        updateMilestones(percentage);
        
        // Send to server
        fetch('api/skills.php?action=update-progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                skill_id: <?php echo $skillId; ?>,
                progress: percentage,
                time_spent: 5
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم تحديث التقدم بنجاح! 🌱', 'success');
                if (percentage === 100) {
                    setTimeout(() => {
                        showCompletionCelebration();
                    }, 1000);
                }
            } else {
                showToast('حدث خطأ في حفظ التقدم', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال', 'error');
        });
    }

    // Share functionality
    function shareSkill() {
        if (navigator.share) {
            navigator.share({
                title: '<?php echo htmlspecialchars($skill['title_ar']); ?>',
                text: '<?php echo htmlspecialchars($skill['description_ar']); ?>',
                url: window.location.href
            }).then(() => {
                showToast('تم المشاركة بنجاح! 📤', 'success');
            }).catch(err => {
                console.error('Error sharing:', err);
                fallbackShare();
            });
        } else {
            fallbackShare();
        }
    }

    function fallbackShare() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('تم نسخ الرابط إلى الحافظة! 📋', 'success');
        }).catch(() => {
            showToast('حدث خطأ في المشاركة', 'error');
        });
    }

    // Add to favorites
    function addToFavorites() {
        <?php if (!isLoggedIn()): ?>
            showToast('يرجى تسجيل الدخول لإضافة المفضلة', 'warning');
            return;
        <?php endif; ?>

        fetch('api/skills.php?action=toggle-favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                skill_id: <?php echo $skillId; ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = event.target.closest('.btn');
                const icon = btn.querySelector('i');
                if (data.is_favorite) {
                    icon.className = 'fas fa-heart me-2';
                    btn.querySelector('span').textContent = 'إزالة من المفضلة';
                    showToast('تمت الإضافة للمفضلة! ❤️', 'success');
                } else {
                    icon.className = 'far fa-heart me-2';
                    btn.querySelector('span').textContent = 'إضافة للمفضلة';
                    showToast('تمت الإزالة من المفضلة', 'info');
                }
            } else {
                showToast('حدث خطأ', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال', 'error');
        });
    }

    function showCompletionCelebration() {
        const modal = new bootstrap.Modal(document.getElementById('completionModal'));
        modal.show();
        createConfetti();
    }

    function createConfetti() {
        const container = document.getElementById('celebrationConfetti');
        const colors = ['#10b981', '#f97316', '#3b82f6', '#ec4899', '#fbbf24'];
        
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti-piece';
            confetti.style.cssText = `
                position: absolute;
                width: 8px;
                height: 8px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation: confettiFall 3s ease-out forwards;
                animation-delay: ${Math.random() * 2}s;
                border-radius: 2px;
            `;
            container.appendChild(confetti);
            
            setTimeout(() => {
                if (confetti.parentNode) {
                    confetti.parentNode.removeChild(confetti);
                }
            }, 3000);
        }
    }

    // Video functionality
    function playVideo() {
        const placeholder = document.getElementById('videoPlaceholder');
        const video = document.getElementById('skillVideo');
        
        if (placeholder && video) {
            placeholder.style.display = 'none';
            video.style.display = 'block';
            video.play();
            showToast('بدأ تشغيل الفيديو التعليمي! 🎬', 'info');
        }
    }
</script>
</body>
</html>