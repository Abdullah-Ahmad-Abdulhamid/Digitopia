<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مهاراتي - منصة تعلم المهارات الحياتية</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="منصة مهاراتي - تعلم المهارات الحياتية الأساسية للشباب المصري">
    <meta name="keywords" content="مهارات حياتية، تعليم، شباب، مصر، إدارة المال، مهارات العمل">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="منصة مهاراتي - تعلم المهارات الحياتية">
    <meta property="og:description" content="منصة تعليمية متخصصة في تعليم المهارات الحياتية للشباب المصري">
    <meta property="og:image" content="/images/og-image.jpg">
    <meta property="og:url" content="https://maharati.app">
    
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
    <link href="css/index-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-2"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-3"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-4"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-5"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-6"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-7"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
        <div class="floating-element element-8"><img src="images/logo.png" alt="Logo" class="floating-logo" style="width: 60px; height: auto;"></div>
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
                        <a class="nav-link active" href="/maharati_platform/index.php"><i class="fas fa-home me-2"></i>الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/skills.php"><i class="fas fa-seedling me-2"></i>المهارات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/maharati_platform/progress.php"><i class="fas fa-chart-line me-2"></i>تقدمي</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                            <span class="user-name"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'زائر'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animated-dropdown">
                            <li><a class="dropdown-item" href="/maharati_platform/profile.php"><i class="fas fa-user-edit me-2"></i>الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="/maharati_platform/subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
                            <li><a class="dropdown-item text-danger" href="/maharati_platform/logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج</a></li>
                        </ul>
                    </li>
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
                            <img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px; margin-left: 10px;">
                            <span>منصة النمو والتطوير</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4">
                            اصنع مستقبلك
                            <span class="gradient-text">بمهارات حياتية</span>
                            <div class="growth-animation">
                                <img src="images/logo.png" alt="Logo" style="width: 90px; height: 90px; position: relative; z-index: 2;">
                                <div class="growth-leaves">
                                    <span class="leaf leaf-1"></span>
                                    <span class="leaf leaf-2"></span>
                                    <span class="leaf leaf-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5">
                            منصة مخصصة للشباب المصري لتعلم المهارات الحياتية الأساسية من إدارة المال 
                            إلى مهارات العمل والتواصل - بأمثلة عملية من واقع الحياة المصرية
                        </p>
                        
                        <div class="hero-buttons">
                            <a href="/maharati_platform/register.php" class="btn btn-hero-primary btn-lg me-3 growth-pulse">
                                <i class="fas fa-rocket me-2"></i>ابدأ رحلة التطوير
                                <div class="btn-sparkle"></div>
                            </a>
                            <a href="/maharati_platform/skills.php" class="btn btn-hero-outline btn-lg">
                                <i class="fas fa-compass me-2"></i>استكشف المهارات
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="learning-ecosystem">
                            <!-- Central Growth Hub -->
                            <div class="growth-hub">
                                <div class="hub-core">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="growth-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                            
                            <!-- Floating Skill Nodes -->
                            <div class="skill-node node-1">
                                <i class="fas fa-coins"></i>
                                <span>إدارة المال</span>
                            </div>
                            <div class="skill-node node-2">
                                <i class="fas fa-comments"></i>
                                <span>التواصل</span>
                            </div>
                            <div class="skill-node node-3">
                                <i class="fas fa-briefcase"></i>
                                <span>العمل</span>
                            </div>
                            <div class="skill-node node-4">
                                <i class="fas fa-heart"></i>
                                <span>الصحة النفسية</span>
                            </div>
                            <div class="skill-node node-5">
                                <i class="fas fa-users"></i>
                                <span>القيادة</span>
                            </div>
                            <div class="skill-node node-6">
                                <i class="fas fa-clock"></i>
                                <span>إدارة الوقت</span>
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

    <!-- User Stats Section -->
    <section class="user-stats-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="modern-stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="stats-number counter" data-count="12">0</div>
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
                        <div class="stats-number counter" data-count="8">0</div>
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
                        <div class="stats-number counter" data-count="2840">0</div>
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
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="stats-number">متقدم</div>
                        <div class="stats-label">مستوى التطوير</div>
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

    <!-- Daily Challenge Section -->
    <section class="daily-challenge-section py-5">
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
                        </div>
                        
                        <div class="challenge-content">
                            <h4 class="challenge-name">تحدي إدارة الوقت الذكية</h4>
                            <p class="challenge-description">
                                طبق تقنية البومودورو لمدة يوم كامل واكتشف كيف يمكن أن تزيد إنتاجيتك بنسبة 40%
                            </p>
                            
                            <div class="challenge-stats">
                                <div class="stat-item">
                                    <i class="fas fa-users"></i>
                                    <span>342 مشارك</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-gem"></i>
                                    <span>+50 نقطة</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    <span>15 دقيقة</span>
                                </div>
                            </div>
                            
                            <a href="/maharati_platform/challenges.php" class="btn btn-challenge-start">
                                <i class="fas fa-rocket me-2"></i>ابدأ التحدي الآن
                                <div class="btn-energy-wave"></div>
                            </a>
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

    <!-- Skills Categories Section -->
    <section class="categories-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">مجالات النمو والتطوير</h2>
                <p class="section-subtitle">اختر المجال الذي تريد البدء في تطوير مهاراتك فيه</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="category-card-growth" data-category="financial">
                        <div class="card-header">
                            <div class="category-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <h5 class="category-title">إدارة المال</h5>
                        </div>
                        <div class="card-body">
                            <p class="category-description">تعلم كيفية إدارة أموالك، الادخار، والاستثمار بطريقة ذكية</p>
                            <div class="skills-count">
                                <i class="fas fa-book me-2"></i>
                                12 مهارة
                            </div>
                        </div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card-growth" data-category="communication">
                        <div class="card-header">
                            <div class="category-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5 class="category-title">مهارات التواصل</h5>
                        </div>
                        <div class="card-body">
                            <p class="category-description">طور مهارات التواصل الفعال والثقة بالنفس في المحادثات</p>
                            <div class="skills-count">
                                <i class="fas fa-book me-2"></i>
                                9 مهارات
                            </div>
                        </div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card-growth" data-category="career">
                        <div class="card-header">
                            <div class="category-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h5 class="category-title">مهارات العمل</h5>
                        </div>
                        <div class="card-body">
                            <p class="category-description">احصل على المهارات المطلوبة في سوق العمل المصري</p>
                            <div class="skills-count">
                                <i class="fas fa-book me-2"></i>
                                15 مهارة
                            </div>
                        </div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card-growth" data-category="wellness">
                        <div class="card-header">
                            <div class="category-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h5 class="category-title">الصحة النفسية</h5>
                        </div>
                        <div class="card-body">
                            <p class="category-description">اهتم بصحتك النفسية وتعلم كيفية التعامل مع ضغوط الحياة</p>
                            <div class="skills-count">
                                <i class="fas fa-book me-2"></i>
                                11 مهارة
                            </div>
                        </div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Skills Section -->
    <section class="featured-skills-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">المهارات المميزة</h2>
                <p class="section-subtitle">ابدأ رحلة التطوير مع هذه المهارات المختارة بعناية</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="skill-card-modern">
                        <div class="skill-header">
                            <div class="skill-icon-wrapper">
                                <i class="fas fa-piggy-bank skill-icon"></i>
                            </div>
                            <div class="skill-meta">
                                <h6 class="skill-title">أساسيات الادخار</h6>
                                <span class="skill-category">إدارة المال</span>
                            </div>
                        </div>
                        
                        <div class="skill-body">
                            <p class="skill-description">تعلم كيفية بناء عادة الادخار وخطط مالية ذكية للمستقبل</p>
                            
                            <div class="skill-badges">
                                <span class="difficulty-badge difficulty-beginner">مبتدئ</span>
                                <span class="views-badge">
                                    <i class="fas fa-eye me-1"></i>1,234
                                </span>
                            </div>
                            
                            <div class="progress-section">
                                <div class="progress-info">
                                    <span>التقدم</span>
                                    <span>75%</span>
                                </div>
                                <div class="skill-progress">
                                    <div class="progress-fill" style="width: 75%"></div>
                                    <div class="progress-glow"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="skill-footer">
                            <a href="/maharati_platform/skill.php?id=1" class="btn btn-skill-start">
                                <i class="fas fa-play me-2"></i>متابعة التعلم
                                <div class="btn-growth-effect"></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="skill-card-modern">
                        <div class="skill-header">
                            <div class="skill-icon-wrapper">
                                <i class="fas fa-handshake skill-icon"></i>
                            </div>
                            <div class="skill-meta">
                                <h6 class="skill-title">فن التفاوض</h6>
                                <span class="skill-category">التواصل</span>
                            </div>
                        </div>
                        
                        <div class="skill-body">
                            <p class="skill-description">اتقن مهارات التفاوض للحصول على أفضل النتائج في حياتك المهنية</p>
                            
                            <div class="skill-badges">
                                <span class="difficulty-badge difficulty-intermediate">متوسط</span>
                                <span class="views-badge">
                                    <i class="fas fa-eye me-1"></i>892
                                </span>
                            </div>
                        </div>
                        
                        <div class="skill-footer">
                            <a href="/maharati_platform/skill.php?id=2" class="btn btn-skill-start">
                                <img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px; margin-left: 10px;">ابدأ النمو
                                <div class="btn-growth-effect"></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="skill-card-modern">
                        <div class="skill-header">
                            <div class="skill-icon-wrapper">
                                <i class="fas fa-brain skill-icon"></i>
                            </div>
                            <div class="skill-meta">
                                <h6 class="skill-title">إدارة الضغط النفسي</h6>
                                <span class="skill-category">الصحة النفسية</span>
                            </div>
                        </div>
                        
                        <div class="skill-body">
                            <p class="skill-description">تعلم تقنيات فعالة للتعامل مع التوتر وضغوط الحياة اليومية</p>
                            
                            <div class="skill-badges">
                                <span class="difficulty-badge difficulty-beginner">مبتدئ</span>
                                <span class="views-badge">
                                    <i class="fas fa-eye me-1"></i>1,567
                                </span>
                            </div>
                        </div>
                        
                        <div class="skill-footer">
                            <a href="/maharati_platform/skill.php?id=3" class="btn btn-skill-start">
                                <i class="fas fa-leaf me-2"></i>ابدأ الشفاء
                                <div class="btn-growth-effect"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="/maharati_platform/skills.php" class="btn btn-view-all">
                    <img src="images/logo.png" alt="Logo" style="width: 20px; height: 20px; margin-left: 5px;">اكتشف جميع المهارات
                </a>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="success-stories-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">قصص النجاح</h2>
                <p class="section-subtitle">انضم لآلاف الشباب الذين غيروا حياتهم للأفضل</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/1674752/pexels-photo-1674752.jpeg?auto=compress&cs=tinysrgb&w=150" alt="أحمد">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">أحمد محمد</h6>
                            <p class="story-text">"تعلمت إدارة أموالي وادخرت 10,000 جنيه في 6 شهور!"</p>
                            <div class="story-achievement">
                                <i class="fas fa-medal me-2"></i>
                                <span>حقق هدف الادخار</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=150" alt="فاطمة">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">فاطمة أحمد</h6>
                            <p class="story-text">"طورت مهارات التواصل وحصلت على ترقية في العمل!"</p>
                            <div class="story-achievement">
                                <img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px; margin-left: 10px;">
                                <span>تحسين الوضع المهني</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150" alt="محمد">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">محمد علي</h6>
                            <p class="story-text">"تغلبت على التوتر وأصبحت أكثر ثقة بنفسي!"</p>
                            <div class="story-achievement">
                                <img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px; margin-left: 10px;">
                                <span>تحسن الصحة النفسية</span>
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
</body>
</html>