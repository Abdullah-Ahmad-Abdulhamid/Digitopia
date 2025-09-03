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

// Get categories
$skillsObj = new Skills();
$categoriesResult = $skillsObj->getCategories();
$categories = $categoriesResult['success'] ? $categoriesResult['data'] : [];

// Get selected category
$selectedCategory = $_GET['category'] ?? null;

// Get skills
$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$skillsResult = $skillsObj->getSkills($selectedCategory, 20, 0, $userId);
$skills = $skillsResult['success'] ? $skillsResult['data'] : [];

// Get category name
$categoryName = 'جميع المهارات';
$categoryDescription = 'اكتشف وتعلم المهارات الحياتية التي تحتاجها للنجاح في الحياة والعمل';
if ($selectedCategory) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $selectedCategory) {
            $categoryName = $cat['name_ar'];
            $categoryDescription = $cat['description_ar'] ?? $categoryDescription;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المهارات - <?php echo SITE_NAME; ?></title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="تصفح واكتشف المهارات الحياتية المتنوعة في منصة مهاراتي">
    <meta name="keywords" content="مهارات حياتية، تعليم، تطوير مهارات، <?php echo htmlspecialchars($categoryName); ?>">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="المهارات - منصة مهاراتي">
    <meta property="og:description" content="<?php echo htmlspecialchars($categoryDescription); ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/images/skills-og.jpg">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/skills.php">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="مهاراتي">
    
    <!-- Icons -->
    <link rel="icon" type="image/png" href="images/logo.png">


    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="css/index-styles.css" rel="stylesheet">
    <link href="css/skills-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px;"></div>
        <div class="floating-element element-2"><img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px;"></div>
        <div class="floating-element element-3"><img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px;"></div>
        <div class="floating-element element-4"><img src="images/logo.png" alt="Logo" style="width: 45px; height: 45px;"></div>
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

    <!-- Skills Hero Section -->
    <section class="skills-hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="growth-particles"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-content">
                        <div class="hero-badge" data-aos="zoom-in" data-aos-delay="200">
                            <img src="images/logo.png" alt="Logo" style="width: 25px; height: 25px; margin-left: 8px;">
                            <span>مكتبة النمو والتطوير</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4" data-aos="fade-up" data-aos-delay="300">
                            استكشف 
                            <span class="gradient-text"><?php echo htmlspecialchars($categoryName); ?></span>
                            <div class="growth-animation">
                                <i class="fas fa-book-open"></i>
                                <div class="growth-leaves">
                                    <span class="leaf leaf-1"></span>
                                    <span class="leaf leaf-2"></span>
                                    <span class="leaf leaf-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5" data-aos="fade-up" data-aos-delay="400">
                            <?php echo htmlspecialchars($categoryDescription); ?>
                        </p>
                        
                        <div class="skills-stats-hero" data-aos="fade-up" data-aos-delay="500">
                            <div class="stat-item-hero">
                                <div class="stat-icon-hero">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="stat-content-hero">
                                    <div class="stat-number-hero counter" data-count="<?php echo count($skills); ?>">0</div>
                                    <div class="stat-label-hero">مهارة متاحة</div>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-hero">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="stat-content-hero">
                                    <div class="stat-number-hero counter" data-count="<?php echo count($categories); ?>">0</div>
                                    <div class="stat-label-hero">تصنيف</div>
                                </div>
                            </div>
                            <div class="stat-item-hero">
                                <div class="stat-icon-hero">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content-hero">
                                    <div class="stat-number-hero counter" data-count="15000">0</div>
                                    <div class="stat-label-hero">متعلم نشط</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="hero-visual">
                        <div class="skills-ecosystem">
                            <!-- Central Knowledge Hub -->
                            <div class="knowledge-hub">
                                <div class="hub-core">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="knowledge-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                            
                            <!-- Floating Skill Categories -->
                            <div class="floating-skill skill-1">
                                <i class="fas fa-lightbulb"></i>
                                <span>إبداع</span>
                            </div>
                            <div class="floating-skill skill-2">
                                <i class="fas fa-seedling"></i>
                                <span>نمو</span>
                            </div>
                            <div class="floating-skill skill-3">
                                <i class="fas fa-rocket"></i>
                                <span>إنجاز</span>
                            </div>
                            <div class="floating-skill skill-4">
                                <i class="fas fa-heart"></i>
                                <span>عاطفة</span>
                            </div>
                            <div class="floating-skill skill-5">
                                <i class="fas fa-star"></i>
                                <span>تميز</span>
                            </div>
                            <div class="floating-skill skill-6">
                                <i class="fas fa-trophy"></i>
                                <span>إنجاز</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-text">اكتشف المهارات</div>
            <div class="scroll-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="search-filter-section py-5">
        <div class="container">
            <div class="search-filter-card glass-card" data-aos="zoom-in" data-aos-delay="200">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="search-box-wrapper">
                            <div class="search-box">
                                <input type="text" class="search-input" id="searchInput" placeholder="ابحث عن مهارة معينة...">
                                <button class="search-btn" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                    <div class="btn-ripple"></div>
                                </button>
                            </div>
                            <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="filter-tabs">
                            <a href="skills.php" class="filter-tab <?php echo !$selectedCategory ? 'active' : ''; ?>" data-aos="fade-left" data-aos-delay="100">
                                <i class="fas fa-th-large me-2"></i>الكل
                                <div class="tab-glow"></div>
                            </a>
                            <?php foreach (array_slice($categories, 0, 4) as $index => $category): ?>
                            <a href="skills.php?category=<?php echo $category['id']; ?>" 
                               class="filter-tab <?php echo $selectedCategory == $category['id'] ? 'active' : ''; ?>" 
                               data-aos="fade-left" data-aos-delay="<?php echo ($index + 2) * 100; ?>">
                                <i class="<?php echo htmlspecialchars($category['icon_class'] ?? 'fas fa-star'); ?> me-2"></i>
                                <?php echo htmlspecialchars($category['name_ar']); ?>
                                <div class="tab-glow"></div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Grid -->
    <section class="skills-grid-section py-5">
        <div class="container">
            <?php if (empty($skills)): ?>
                <div class="empty-state" data-aos="zoom-in">
                    <div class="empty-icon">
                        <img src="images/logo.png" alt="Logo" style="width: 60px; height: 60px;">
                        <div class="icon-growth-ring"></div>
                    </div>
                    <h3 class="empty-title">لا توجد مهارات في هذا التصنيف بعد</h3>
                    <p class="empty-description">
                        نحن نعمل باستمرار على إضافة مهارات جديدة لتطوير قدراتك.<br>
                        جرب تصنيفاً آخر أو عد لاحقاً لاكتشاف المحتوى الجديد!
                    </p>
                    <div class="empty-actions">
                        <a href="skills.php" class="btn btn-hero-primary btn-lg me-3">
                            <i class="fas fa-seedling me-2"></i>تصفح جميع المهارات
                            <div class="btn-sparkle"></div>
                        </a>
                        <a href="index.php" class="btn btn-hero-outline btn-lg">
                            <i class="fas fa-home me-2"></i>العودة للرئيسية
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="skills-grid" id="skillsGrid">
                    <?php foreach ($skills as $index => $skill): ?>
                    <div class="skill-card-container" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                        <div class="skill-card-growth" data-tilt>
                            <div class="skill-header">
                                <div class="skill-icon-container">
                                    <div class="skill-icon-wrapper">
                                        <i class="<?php echo htmlspecialchars($skill['icon_name'] ?? 'fas fa-lightbulb'); ?> skill-icon"></i>
                                    </div>
                                    <div class="skill-glow"></div>
                                </div>
                                <div class="skill-meta">
                                    <h5 class="skill-title"><?php echo htmlspecialchars($skill['title_ar']); ?></h5>
                                    <span class="skill-category"><?php echo htmlspecialchars($skill['category_name']); ?></span>
                                </div>
                                <?php if ($skill['is_premium'] && (!isLoggedIn() || $_SESSION['subscription_type'] === 'free')): ?>
                                    <div class="premium-badge">
                                        <i class="fas fa-crown"></i>
                                        <div class="premium-sparkle"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="skill-body">
                                <p class="skill-description"><?php echo htmlspecialchars(substr($skill['description_ar'], 0, 120)) . '...'; ?></p>
                                
                                <div class="skill-features">
                                    <div class="feature-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?php echo $skill['estimated_time'] ?? 30; ?> دقيقة</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-eye"></i>
                                        <span><?php echo number_format($skill['views_count']); ?> مشاهدة</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($skill['learners_count'] ?? 0); ?> متعلم</span>
                                    </div>
                                </div>
                                
                                <div class="skill-badges">
                                    <span class="difficulty-badge difficulty-<?php echo $skill['difficulty_level']; ?>">
                                        <?php 
                                        $levels = ['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم'];
                                        echo $levels[$skill['difficulty_level']] ?? 'مبتدئ';
                                        ?>
                                    </span>
                                    <span class="rating-badge">
                                        <i class="fas fa-star"></i>
                                        <?php echo number_format($skill['rating'] ?? 4.5, 1); ?>
                                    </span>
                                </div>
                                
                                <?php if (isset($skill['user_progress']) && $skill['user_progress']): ?>
                                <div class="progress-section">
                                    <div class="progress-info">
                                        <span>التقدم</span>
                                        <span><?php echo round($skill['user_progress']); ?>%</span>
                                    </div>
                                    <div class="skill-progress">
                                        <div class="progress-fill" style="width: <?php echo $skill['user_progress']; ?>%"></div>
                                        <div class="progress-glow"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="skill-footer">
                                <?php if ($skill['is_premium'] && (!isLoggedIn() || $_SESSION['subscription_type'] === 'free')): ?>
                                    <a href="subscription.php" class="btn btn-premium-upgrade w-100">
                                        <i class="fas fa-crown me-2"></i>ترقية الاشتراك
                                        <div class="btn-growth-effect"></div>
                                    </a>
                                <?php else: ?>
                                    <a href="skill.php?id=<?php echo $skill['id']; ?>" class="btn btn-skill-growth w-100">
                                        <i class="fas fa-play me-2"></i>
                                        <?php echo isset($skill['user_progress']) && $skill['user_progress'] > 0 ? 'متابعة النمو' : 'ابدأ رحلة التعلم'; ?>
                                        <div class="btn-growth-effect"></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-shine"></div>
                            <div class="growth-indicator">
                                <div class="growth-line"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Load More Section -->
                <div class="text-center mt-5" data-aos="zoom-in">
                    <button class="btn btn-load-more btn-lg" id="loadMoreBtn">
                        <i class="fas fa-seedling me-2"></i>اكتشف المزيد من المهارات
                        <div class="btn-bloom-effect"></div>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Categories Showcase -->
    <section class="categories-showcase-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">استكشف مجالات التطوير</h2>
                <p class="section-subtitle">اختر المجال الذي يلهمك وابدأ رحلة النمو الشخصي</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="categories-grid">
                <?php foreach ($categories as $index => $category): ?>
                <div class="category-showcase-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="category-card-growth" data-category="<?php echo strtolower($category['name_ar']); ?>" onclick="location.href='skills.php?category=<?php echo $category['id']; ?>'">
                        <div class="card-header">
                            <div class="category-icon">
                                <i class="<?php echo htmlspecialchars($category['icon_class'] ?? 'fas fa-star'); ?>"></i>
                            </div>
                            <h5 class="category-title"><?php echo htmlspecialchars($category['name_ar']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="category-description"><?php echo htmlspecialchars($category['description_ar']); ?></p>
                            <div class="skills-count">
                                <i class="fas fa-book me-2"></i>
                                <?php echo $category['skills_count']; ?> مهارة
                            </div>
                        </div>
                        <div class="growth-indicator">
                            <div class="growth-line"></div>
                        </div>
                        <div class="card-glow"></div>
                    </div>
                </div>
                <?php endforeach; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script src="js/index-script.js"></script>
    <script src="js/skills-script.js"></script>
</body>
</html>