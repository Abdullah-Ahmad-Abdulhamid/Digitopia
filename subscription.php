<?php
require_once 'config/config.php';

// Get user data if logged in
$user = null;
$userSubscription = null;
if (isLoggedIn()) {
    $userObj = new User();
    $userResult = $userObj->getUserProfile($_SESSION['user_id']);
    if ($userResult['success']) {
        $user = $userResult['user'];
        // Get user subscription status (implement this method in your User class)
        // $userSubscription = $userObj->getSubscriptionStatus($_SESSION['user_id']);
    }
}

// Subscription plans
$plans = [
    'free' => [
        'name' => 'مجاني',
        'name_en' => 'Free',
        'price' => 0,
        'period' => 'دائماً',
        'description' => 'ابدأ رحلة التعلم مجاناً',
        'features' => [
            'الوصول لـ 5 مهارات أساسية',
            'تحدي يومي واحد',
            'الدعم المجتمعي',
            'شهادات إكمال بسيطة'
        ],
        'color' => 'secondary',
        'popular' => false
    ],
    'basic' => [
        'name' => 'أساسي',
        'name_en' => 'Basic',
        'price' => 99,
        'period' => 'شهرياً',
        'description' => 'للمتعلمين الجادين',
        'features' => [
            'الوصول لجميع المهارات',
            '3 تحديات يومية',
            'مساعد ذكي محدود',
            'شهادات معتمدة',
            'تتبع التقدم المتقدم',
            'مجتمع خاص'
        ],
        'color' => 'primary',
        'popular' => true
    ],
    'premium' => [
        'name' => 'مميز',
        'name_en' => 'Premium',
        'price' => 199,
        'period' => 'شهرياً',
        'description' => 'للخبراء والمحترفين',
        'features' => [
            'كل ما في الأساسي',
            'مساعد ذكي غير محدود',
            'جلسات فردية شهرية',
            'محتوى حصري',
            'أولوية في الدعم',
            'شارات وإنجازات خاصة',
            'تحليلات شخصية متقدمة'
        ],
        'color' => 'warning',
        'popular' => false
    ]
];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطط الاشتراك - مهاراتي</title>

    <!-- Meta Tags for SEO -->
    <meta name="description" content="اختر الخطة المناسبة لك وابدأ رحلة التعلم مع منصة مهاراتي">
    <meta name="keywords" content="اشتراك، خطط، أسعار، تعليم، مهارات، مصر">
    <meta name="author" content="فريق مهاراتي">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="خطط الاشتراك - منصة مهاراتي">
    <meta property="og:description" content="اختر الخطة المناسبة لك وابدأ رحلة التعلم المتقدمة">
    <meta property="og:image" content="/images/subscription-og.jpg">
    <meta property="og:url" content="https://maharati.app/subscription.php">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي - الاشتراك">

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
    <link href="css/subscription-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><i class="fas fa-seedling"></i></div>
        <div class="floating-element element-2"><i class="fas fa-crown"></i></div>
        <div class="floating-element element-3"><i class="fas fa-gem"></i></div>
        <div class="floating-element element-4"><i class="fas fa-star"></i></div>
        <div class="floating-element element-5"><i class="fas fa-trophy"></i></div>
        <div class="floating-element element-6"><i class="fas fa-rocket"></i></div>
        <div class="floating-element element-7"><i class="fas fa-heart"></i></div>
        <div class="floating-element element-8"><i class="fas fa-lightbulb"></i></div>
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
                                <li><a class="dropdown-item active" href="subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
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

    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="growth-particles"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-crown me-2"></i>
                            <span>خطط النمو والتطوير</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4">
                            استثمر في 
                            <span class="gradient-text">مستقبلك</span>
                            <div class="growth-animation">
                                <i class="fas fa-seedling"></i>
                                <div class="growth-leaves">
                                    <span class="leaf leaf-1"></span>
                                    <span class="leaf leaf-2"></span>
                                    <span class="leaf leaf-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5">
                            اختر الخطة التي تناسب أهدافك وابدأ رحلة التطوير مع منصة مهاراتي - 
                            استثمار ذكي في مهاراتك الحياتية مع ضمان النتائج
                        </p>
                        
                        <div class="hero-features">
                            <div class="feature-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>ضمان استرداد المال خلال 30 يوم</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-lock"></i>
                                <span>دفع آمن ومشفر 100%</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-sync-alt"></i>
                                <span>إلغاء الاشتراك في أي وقت</span>
                            </div>
                        </div>
                        
                        <div class="hero-buttons">
                            <?php if (!isLoggedIn()): ?>
                                <a href="register.php" class="btn btn-hero-primary btn-lg me-3 growth-pulse">
                                    <i class="fas fa-rocket me-2"></i>ابدأ مجاناً الآن
                                    <div class="btn-sparkle"></div>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-hero-primary btn-lg me-3 growth-pulse" onclick="selectPlan('basic')">
                                    <i class="fas fa-crown me-2"></i>اشترك الآن
                                    <div class="btn-sparkle"></div>
                                </button>
                            <?php endif; ?>
                            <a href="#pricing" class="btn btn-hero-outline btn-lg">
                                <i class="fas fa-eye me-2"></i>مقارنة الخطط
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="subscription-ecosystem">
                            <!-- Central Growth Hub -->
                            <div class="growth-hub">
                                <div class="hub-core">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="growth-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                            
                            <!-- Floating Subscription Benefits -->
                            <div class="benefit-node node-1">
                                <i class="fas fa-infinity"></i>
                                <span>محتوى لا محدود</span>
                            </div>
                            <div class="benefit-node node-2">
                                <i class="fas fa-robot"></i>
                                <span>مساعد ذكي</span>
                            </div>
                            <div class="benefit-node node-3">
                                <i class="fas fa-certificate"></i>
                                <span>شهادات معتمدة</span>
                            </div>
                            <div class="benefit-node node-4">
                                <i class="fas fa-headset"></i>
                                <span>دعم مميز</span>
                            </div>
                            <div class="benefit-node node-5">
                                <i class="fas fa-chart-line"></i>
                                <span>تحليلات متقدمة</span>
                            </div>
                            <div class="benefit-node node-6">
                                <i class="fas fa-users"></i>
                                <span>مجتمع خاص</span>
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

    <!-- Pricing Plans Section -->
    <section class="pricing-section py-5" id="pricing">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">خطط النمو والتطوير</h2>
                <p class="section-subtitle">اختر الخطة التي تناسب أهدافك التعليمية ومستوى طموحك</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php foreach ($plans as $planKey => $plan): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo array_search($planKey, array_keys($plans)) * 200; ?>">
                    <div class="pricing-card-growth <?php echo $plan['popular'] ? 'popular-plan' : ''; ?>" data-plan="<?php echo $planKey; ?>">
                        <?php if ($plan['popular']): ?>
                        <div class="popular-badge">
                            <i class="fas fa-crown me-1"></i>
                            الأكثر شعبية
                            <div class="badge-shine"></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="pricing-header">
                            <div class="plan-icon plan-<?php echo $plan['color']; ?>">
                                <i class="fas fa-<?php echo $planKey === 'free' ? 'gift' : ($planKey === 'basic' ? 'star' : 'crown'); ?>"></i>
                                <div class="icon-glow"></div>
                            </div>
                            <h3 class="plan-name"><?php echo $plan['name']; ?></h3>
                            <p class="plan-description"><?php echo $plan['description']; ?></p>
                        </div>
                        
                        <div class="pricing-amount">
                            <div class="price-display">
                                <?php if ($plan['price'] == 0): ?>
                                    <span class="price-free">مجاني</span>
                                    <span class="period">للأبد</span>
                                <?php else: ?>
                                    <div class="price-wrapper">
                                        <span class="currency">جنيه</span>
                                        <span class="price"><?php echo $plan['price']; ?></span>
                                        <span class="period">/ <?php echo $plan['period']; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="pricing-features">
                            <ul class="features-list">
                                <?php foreach ($plan['features'] as $feature): ?>
                                <li class="feature-item">
                                    <i class="fas fa-check-circle feature-check"></i>
                                    <span><?php echo $feature; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="pricing-action">
                            <?php if ($planKey === 'free'): ?>
                                <?php if (!isLoggedIn()): ?>
                                    <a href="register.php" class="btn btn-plan-select btn-<?php echo $plan['color']; ?>">
                                        <i class="fas fa-rocket me-2"></i>ابدأ مجاناً
                                        <div class="btn-growth-effect"></div>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-plan-select btn-current" disabled>
                                        <i class="fas fa-check me-2"></i>الخطة الحالية
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (isLoggedIn()): ?>
                                    <button class="btn btn-plan-select btn-<?php echo $plan['color']; ?>" onclick="selectPlan('<?php echo $planKey; ?>')">
                                        <i class="fas fa-crown me-2"></i>اختر هذه الخطة
                                        <div class="btn-growth-effect"></div>
                                    </button>
                                <?php else: ?>
                                    <a href="register.php" class="btn btn-plan-select btn-<?php echo $plan['color']; ?>">
                                        <i class="fas fa-user-plus me-2"></i>سجل للاشتراك
                                        <div class="btn-growth-effect"></div>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-glow card-glow-<?php echo $plan['color']; ?>"></div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Features Comparison Section -->
    <section class="comparison-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">مقارنة شاملة للخطط</h2>
                <p class="section-subtitle">اكتشف الفروقات بين كل خطة واختر الأنسب لاحتياجاتك</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="comparison-table-wrapper" data-aos="fade-up">
                <div class="comparison-table-modern">
                    <div class="table-header-modern">
                        <div class="feature-column">
                            <h4>الميزات والخدمات</h4>
                        </div>
                        <div class="plan-column">
                            <div class="plan-header free">
                                <i class="fas fa-gift"></i>
                                <h5>مجاني</h5>
                            </div>
                        </div>
                        <div class="plan-column">
                            <div class="plan-header basic popular">
                                <i class="fas fa-star"></i>
                                <h5>أساسي</h5>
                                <span class="popular-tag">الأكثر شعبية</span>
                            </div>
                        </div>
                        <div class="plan-column">
                            <div class="plan-header premium">
                                <i class="fas fa-crown"></i>
                                <h5>مميز</h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-body-modern">
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-book me-2"></i>
                                عدد المهارات المتاحة
                            </div>
                            <div class="feature-value">5 مهارات</div>
                            <div class="feature-value featured">جميع المهارات</div>
                            <div class="feature-value featured">جميع المهارات + حصرية</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-trophy me-2"></i>
                                التحديات اليومية
                            </div>
                            <div class="feature-value">1 تحدي</div>
                            <div class="feature-value featured">3 تحديات</div>
                            <div class="feature-value featured">تحديات غير محدودة</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-robot me-2"></i>
                                المساعد الذكي
                            </div>
                            <div class="feature-value"><i class="fas fa-times text-danger"></i></div>
                            <div class="feature-value featured"><i class="fas fa-check text-success"></i> محدود</div>
                            <div class="feature-value featured"><i class="fas fa-infinity text-success"></i> غير محدود</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-certificate me-2"></i>
                                الشهادات المعتمدة
                            </div>
                            <div class="feature-value">بسيطة</div>
                            <div class="feature-value featured">معتمدة رسمياً</div>
                            <div class="feature-value featured">معتمدة + مخصصة</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-headset me-2"></i>
                                الدعم الفني
                            </div>
                            <div class="feature-value">مجتمعي</div>
                            <div class="feature-value featured">بريد إلكتروني</div>
                            <div class="feature-value featured">أولوية + مكالمات</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-chart-line me-2"></i>
                                تحليلات التقدم
                            </div>
                            <div class="feature-value">أساسي</div>
                            <div class="feature-value featured">متقدم</div>
                            <div class="feature-value featured">شخصي ومفصل</div>
                        </div>
                        
                        <div class="feature-row">
                            <div class="feature-name">
                                <i class="fas fa-video me-2"></i>
                                جلسات فردية
                            </div>
                            <div class="feature-value"><i class="fas fa-times text-danger"></i></div>
                            <div class="feature-value"><i class="fas fa-times text-danger"></i></div>
                            <div class="feature-value featured"><i class="fas fa-check text-success"></i> شهرياً</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="success-stories-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">قصص نمو ملهمة</h2>
                <p class="section-subtitle">تجارب حقيقية من مشتركين حققوا نجاحات مذهلة مع خططنا المدفوعة</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/1674752/pexels-photo-1674752.jpeg?auto=compress&cs=tinysrgb&w=150" alt="أحمد محمد">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">أحمد محمد</h6>
                            <p class="story-plan">مشترك في الخطة الأساسية</p>
                            <div class="story-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="story-text">"الخطة الأساسية غيرت حياتي! تعلمت إدارة أموالي وادخرت 15,000 جنيه في 8 شهور. المساعد الذكي كان عظيم جداً."</p>
                            <div class="story-achievement">
                                <i class="fas fa-piggy-bank me-2"></i>
                                <span>حقق هدف الادخار</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=150" alt="فاطمة أحمد">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">فاطمة أحمد</h6>
                            <p class="story-plan">مشتركة في الخطة المميزة</p>
                            <div class="story-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="story-text">"الجلسات الفردية والمحتوى الحصري ساعدني أطور مهارات القيادة. حصلت على ترقية كبيرة في الشغل!"</p>
                            <div class="story-achievement">
                                <i class="fas fa-trophy me-2"></i>
                                <span>تحسين الوضع المهني</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="success-story-card">
                        <div class="story-avatar">
                            <div class="avatar-ring"></div>
                            <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150" alt="محمد علي">
                        </div>
                        <div class="story-content">
                            <h6 class="story-name">محمد علي</h6>
                            <p class="story-plan">مشترك في الخطة الأساسية</p>
                            <div class="story-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="story-text">"التحديات اليومية والشهادات المعتمدة ساعدتني أبني مهارات قوية. بقيت أكثر ثقة في نفسي وشغلي."</p>
                            <div class="story-achievement">
                                <i class="fas fa-brain me-2"></i>
                                <span>تطوير مهارات شخصية</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">الأسئلة الشائعة</h2>
                <p class="section-subtitle">إجابات على أكثر الأسئلة شيوعاً حول الاشتراك والخطط</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion modern-accordion" id="faqAccordion" data-aos="fade-up">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    <i class="fas fa-question-circle me-3"></i>
                                    هل يمكنني إلغاء الاشتراك في أي وقت؟
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم بالطبع! يمكنك إلغاء اشتراكك في أي وقت من خلال إعدادات حسابك. لن يتم خصم أي رسوم إضافية وستستمر في الاستفادة من جميع الخدمات حتى نهاية فترة الاشتراك المدفوعة مسبقاً.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    <i class="fas fa-credit-card me-3"></i>
                                    ما هي طرق الدفع المتاحة؟
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نقبل جميع البطاقات الائتمانية الرئيسية (فيزا، ماستركارد، أمريكان إكسبريس) بالإضافة إلى الدفع عبر فوري وانستاباي وفودافون كاش. جميع المعاملات مشفرة وآمنة بنسبة 100%.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    <i class="fas fa-shield-alt me-3"></i>
                                    هل هناك ضمان استرداد المال؟
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، نوفر ضمان استرداد كامل للمال خلال 30 يوم من بداية الاشتراك إذا لم تكن راضي عن الخدمة تماماً. لا توجد أسئلة معقدة أو شروط مخفية - رضاك هو هدفنا الأول.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    <i class="fas fa-arrow-up me-3"></i>
                                    هل يمكنني ترقية خطتي لاحقاً؟
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    بالطبع! يمكنك ترقية خطتك في أي وقت من إعدادات الحساب. سيتم احتساب الفرق في السعر بشكل تناسبي وستحصل على جميع الميزات الإضافية فوراً دون انتظار.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                    <i class="fas fa-certificate me-3"></i>
                                    هل الشهادات معتمدة رسمياً؟
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، جميع الشهادات في الخطط المدفوعة معتمدة رسمياً ومعترف بها في سوق العمل المصري. يمكنك إضافتها لسيرتك الذاتية أو LinkedIn. كل شهادة تحمل رقم تحقق فريد للمصادقة عليها.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                    <i class="fas fa-mobile-alt me-3"></i>
                                    هل يمكنني استخدام المنصة على الهاتف؟
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    بالطبع! المنصة متوافقة بالكامل مع جميع الأجهزة ويمكنك تثبيتها كتطبيق على هاتفك. ستحصل على نفس التجربة المميزة على الكمبيوتر والهاتف والتابلت.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="zoom-in">
                    <div class="cta-content">
                        <div class="cta-icon">
                            <i class="fas fa-rocket"></i>
                            <div class="icon-pulse"></div>
                        </div>
                        <h2 class="cta-title">جاهز لبدء رحلة النمو؟</h2>
                        <p class="cta-subtitle">
                            انضم لآلاف الشباب الذين استثمروا في أنفسهم وحققوا نجاحات مذهلة 
                            - ابدأ رحلتك اليوم واصنع مستقبلك بيدك
                        </p>
                        
                        <div class="cta-buttons">
                            <?php if (!isLoggedIn()): ?>
                                <a href="register.php" class="btn btn-cta-primary btn-lg">
                                    <i class="fas fa-seedling me-2"></i>ابدأ رحلة النمو مجاناً
                                    <div class="btn-energy-wave"></div>
                                </a>
                                <a href="skills.php" class="btn btn-cta-outline btn-lg">
                                    <i class="fas fa-compass me-2"></i>استكشف المحتوى
                                </a>
                            <?php else: ?>
                                <button class="btn btn-cta-primary btn-lg" onclick="selectPlan('basic')">
                                    <i class="fas fa-crown me-2"></i>اشترك في الخطة الأساسية
                                    <div class="btn-energy-wave"></div>
                                </button>
                                <button class="btn btn-cta-outline btn-lg" onclick="selectPlan('premium')">
                                    <i class="fas fa-gem me-2"></i>جرب الخطة المميزة
                                </button>
                            <?php endif; ?>
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
        <div class="fab-tooltip">مساعدك الذكي في اختيار الخطة المناسبة</div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-seedling me-2"></i>إتمام الاشتراك
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-summary" id="paymentSummary">
                        <!-- Payment details will be populated by JavaScript -->
                    </div>
                    
                    <div class="payment-form">
                        <form id="paymentForm">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="cardName" class="form-label">الاسم على البطاقة</label>
                                    <input type="text" class="form-control modern-input" id="cardName" required>
                                </div>
                                <div class="col-12">
                                    <label for="cardNumber" class="form-label">رقم البطاقة</label>
                                    <input type="text" class="form-control modern-input" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                                </div>
                                <div class="col-6">
                                    <label for="cardExpiry" class="form-label">تاريخ الانتهاء</label>
                                    <input type="text" class="form-control modern-input" id="cardExpiry" placeholder="MM/YY" required>
                                </div>
                                <div class="col-6">
                                    <label for="cardCvc" class="form-label">رمز الأمان</label>
                                    <input type="text" class="form-control modern-input" id="cardCvc" placeholder="123" required>
                                </div>
                            </div>
                            
                            <div class="payment-security mt-4">
                                <div class="security-notice">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <span>معاملتك آمنة ومشفرة بأحدث تقنيات الحماية</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button type="button" class="btn btn-success" id="confirmPayment">
                        <i class="fas fa-lock me-2"></i>دفع آمن
                        <div class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
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
    <script src="js/subscription-script.js"></script>
</body>
</html>