<?php
require_once 'config/config.php';

// Require login
requireLogin();

$success = '';
$errors = [];

// Get user profile
$userObj = new User();
$userResult = $userObj->getUserProfile($_SESSION['user_id']);

if (!$userResult['success']) {
    redirectTo('index.php');
}

$user = $userResult['user'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $data = [
            'name' => $_POST['name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'age' => $_POST['age'] ?? '',
            'governorate' => $_POST['governorate'] ?? '',
            'education_level' => $_POST['education_level'] ?? '',
            'current_income' => $_POST['current_income'] ?? ''
        ];
        
        $result = $userObj->updateProfile($_SESSION['user_id'], $data);
        
        if ($result['success']) {
            $success = $result['message'];
            // Update session name if changed
            if (isset($data['name']) && !empty($data['name'])) {
                $_SESSION['user_name'] = $data['name'];
            }
            // Refresh user data
            $userResult = $userObj->getUserProfile($_SESSION['user_id']);
            $user = $userResult['user'];
        } else {
            $errors['profile'] = $result['error'];
        }
    } elseif ($action === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if ($newPassword !== $confirmPassword) {
            $errors['password'] = 'كلمة المرور الجديدة غير متطابقة';
        } else {
            $result = $userObj->changePassword($_SESSION['user_id'], $currentPassword, $newPassword);
            
            if ($result['success']) {
                $success = $result['message'];
            } else {
                $errors['password'] = $result['error'];
            }
        }
    }
}

$governorates = [
    'القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'البحيرة', 'الفيوم', 'الغربية', 'الإسماعيلية',
    'المنوفية', 'المنيا', 'القليوبية', 'الوادي الجديد', 'السويس', 'أسوان', 'أسيوط', 'بني سويف',
    'بورسعيد', 'دمياط', 'الشرقية', 'جنوب سيناء', 'كفر الشيخ', 'مطروح', 'الأقصر', 'قنا',
    'شمال سيناء', 'سوهاج', 'الأحمر'
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - مهاراتي</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="إدارة الملف الشخصي وإعدادات الحساب في منصة مهاراتي">
    <meta name="keywords" content="الملف الشخصي، إعدادات الحساب، مهاراتي">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="الملف الشخصي - منصة مهاراتي">
    <meta property="og:description" content="إدارة بياناتك الشخصية وإعدادات الحساب في منصة مهاراتي">
    <meta property="og:image" content="/images/og-image.jpg">
    <meta property="og:url" content="https://maharati.app/profile">
    
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
    <link href="css/profile-styles.css" rel="stylesheet">
</head>

<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element element-1"><i class="fas fa-user-cog"></i></div>
        <div class="floating-element element-2"><i class="fas fa-shield-alt"></i></div>
        <div class="floating-element element-3"><i class="fas fa-user-edit"></i></div>
        <div class="floating-element element-4"><i class="fas fa-key"></i></div>
        <div class="floating-element element-5"><i class="fas fa-crown"></i></div>
        <div class="floating-element element-6"><i class="fas fa-chart-line"></i></div>
        <div class="floating-element element-7"><i class="fas fa-seedling"></i></div>
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
                        <a class="nav-link" href="skills.php"><i class="fas fa-seedling me-2"></i>المهارات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="challenges.php"><i class="fas fa-trophy me-2"></i>التحديات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="chat.php"><i class="fas fa-robot me-2"></i>المساعد الذكي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="progress.php"><i class="fas fa-chart-line me-2"></i>تقدمي</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name"><?php echo htmlspecialchars($user['name'] ?? 'المستخدم'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animated-dropdown">
                            <li><a class="dropdown-item active" href="profile.php"><i class="fas fa-user-edit me-2"></i>الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="subscription.php"><i class="fas fa-crown me-2"></i>الاشتراك</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>تسجيل خروج</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Header Section -->
    <section class="profile-hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="growth-particles"></div>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="fas fa-user-cog me-2"></i>
                            <span>إدارة الحساب الشخصي</span>
                            <div class="badge-glow"></div>
                        </div>
                        
                        <h1 class="hero-title mb-4">
                            طور <span class="gradient-text">ملفك الشخصي</span>
                            <div class="growth-animation">
                                <i class="fas fa-user-edit"></i>
                                <div class="growth-leaves">
                                    <span class="leaf leaf-1"></span>
                                    <span class="leaf leaf-2"></span>
                                    <span class="leaf leaf-3"></span>
                                </div>
                            </div>
                        </h1>
                        
                        <p class="hero-subtitle mb-5">
                            إدارة بياناتك الشخصية وإعدادات الحساب لتجربة تعلم مخصصة ومناسبة لاحتياجاتك
                        </p>

                        <div class="hero-buttons">
                            <a href="#profile-form" class="btn btn-hero-primary btn-lg me-3 growth-pulse">
                                <i class="fas fa-edit me-2"></i>تحديث البيانات
                                <div class="btn-sparkle"></div>
                            </a>
                            <a href="progress.php" class="btn btn-hero-outline btn-lg">
                                <i class="fas fa-chart-line me-2"></i>عرض التقدم
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="profile-hero-visual">
                        <div class="profile-ecosystem">
                            <!-- Central Profile Hub -->
                            <div class="profile-hub">
                                <div class="hub-core">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="growth-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                </div>
                            </div>
                            
                            <!-- Floating Profile Features -->
                            <div class="profile-feature feature-1">
                                <i class="fas fa-shield-alt"></i>
                                <span>الحماية</span>
                            </div>
                            <div class="profile-feature feature-2">
                                <i class="fas fa-cog"></i>
                                <span>الإعدادات</span>
                            </div>
                            <div class="profile-feature feature-3">
                                <i class="fas fa-crown"></i>
                                <span>الاشتراك</span>
                            </div>
                            <div class="profile-feature feature-4">
                                <i class="fas fa-chart-line"></i>
                                <span>الإحصائيات</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="profile-content-section py-5" id="profile-form">
        <div class="container">
            <?php if ($success): ?>
                <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert" data-aos="zoom-in">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h6 class="alert-title">نجح التحديث!</h6>
                        <p class="alert-message"><?php echo htmlspecialchars($success); ?></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Profile Information -->
                <div class="col-lg-8">
                    <!-- Personal Information Card -->
                    <div class="modern-card profile-form-card" data-aos="fade-up">
                        <div class="card-header">
                            <div class="header-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="header-content">
                                <h4>البيانات الشخصية</h4>
                                <p>إدارة المعلومات الأساسية لحسابك</p>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <?php if (isset($errors['profile'])): ?>
                                <div class="alert alert-danger alert-modern">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        <p class="alert-message"><?php echo htmlspecialchars($errors['profile']); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="modern-form">
                                <input type="hidden" name="action" value="update_profile">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user me-2"></i>
                                                الاسم الكامل <span class="required">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control modern-input" name="name" 
                                                       value="<?php echo htmlspecialchars($user['name']); ?>" 
                                                       required placeholder="أدخل اسمك الكامل">
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-envelope me-2"></i>
                                                البريد الإلكتروني
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="email" class="form-control modern-input" 
                                                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                                                       disabled placeholder="البريد الإلكتروني">
                                                <div class="input-underline"></div>
                                            </div>
                                            <small class="form-text">لا يمكن تغيير البريد الإلكتروني</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-phone me-2"></i>
                                                رقم الهاتف
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="tel" class="form-control modern-input" name="phone" 
                                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                                       placeholder="01xxxxxxxxx">
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar me-2"></i>
                                                العمر
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="number" class="form-control modern-input" name="age" 
                                                       value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>" 
                                                       min="13" max="100" placeholder="العمر">
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                المحافظة
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select modern-select" name="governorate">
                                                    <option value="">اختر المحافظة</option>
                                                    <?php foreach ($governorates as $gov): ?>
                                                        <option value="<?php echo $gov; ?>" 
                                                                <?php echo ($user['governorate'] ?? '') === $gov ? 'selected' : ''; ?>>
                                                            <?php echo $gov; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-graduation-cap me-2"></i>
                                                المستوى التعليمي
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select modern-select" name="education_level">
                                                    <option value="">اختر المستوى التعليمي</option>
                                                    <option value="secondary" <?php echo ($user['education_level'] ?? '') === 'secondary' ? 'selected' : ''; ?>>ثانوية عامة</option>
                                                    <option value="university" <?php echo ($user['education_level'] ?? '') === 'university' ? 'selected' : ''; ?>>جامعي</option>
                                                    <option value="graduate" <?php echo ($user['education_level'] ?? '') === 'graduate' ? 'selected' : ''; ?>>خريج</option>
                                                    <option value="postgraduate" <?php echo ($user['education_level'] ?? '') === 'postgraduate' ? 'selected' : ''; ?>>دراسات عليا</option>
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-money-bill me-2"></i>
                                                الراتب الشهري (جنيه مصري)
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="number" class="form-control modern-input" name="current_income" 
                                                       value="<?php echo htmlspecialchars($user['current_income'] ?? ''); ?>" 
                                                       min="0" step="100" placeholder="0">
                                                <div class="input-suffix">جنيه</div>
                                                <div class="input-underline"></div>
                                            </div>
                                            <small class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                هذه المعلومة تساعدنا في تقديم نصائح مالية مناسبة
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary btn-modern">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ التغييرات
                                        <div class="btn-ripple"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password Card -->
                    <div class="modern-card password-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header">
                            <div class="header-icon warning">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="header-content">
                                <h5>تغيير كلمة المرور</h5>
                                <p>حماية حسابك بكلمة مرور قوية وآمنة</p>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <?php if (isset($errors['password'])): ?>
                                <div class="alert alert-danger alert-modern">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="alert-content">
                                        <p class="alert-message"><?php echo htmlspecialchars($errors['password']); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="modern-form">
                                <input type="hidden" name="action" value="change_password">
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        كلمة المرور الحالية <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="password" class="form-control modern-input" name="current_password" 
                                               required placeholder="أدخل كلمة المرور الحالية">
                                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="input-underline"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-key me-2"></i>
                                                كلمة المرور الجديدة <span class="required">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="password" class="form-control modern-input" name="new_password" 
                                                       minlength="8" required placeholder="أدخل كلمة المرور الجديدة">
                                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-key me-2"></i>
                                                تأكيد كلمة المرور <span class="required">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="password" class="form-control modern-input" name="confirm_password" 
                                                       minlength="8" required placeholder="أعد إدخال كلمة المرور">
                                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-warning btn-modern">
                                        <i class="fas fa-key me-2"></i>
                                        تغيير كلمة المرور
                                        <div class="btn-ripple"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- User Profile Card -->
                    <div class="modern-card profile-sidebar-card" data-aos="fade-up">
                        <div class="card-body text-center">
                            <div class="profile-avatar-large">
                                <div class="avatar-wrapper">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="avatar-ring"></div>
                                <div class="status-indicator online"></div>
                            </div>
                            
                            <h5 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                            
                            <div class="profile-metrics">
                                <div class="metric-item">
                                    <div class="metric-value counter" data-count="<?php echo $user['total_points'] ?? 0; ?>">0</div>
                                    <div class="metric-label">نقطة تطوير</div>
                                </div>
                                <div class="metric-divider"></div>
                                <div class="metric-item">
                                    <div class="metric-value"><?php echo $user['level_name'] ?? 'مبتدئ'; ?></div>
                                    <div class="metric-label">المستوى</div>
                                </div>
                            </div>

                            <div class="level-progress">
                                <div class="progress-info">
                                    <span>التقدم للمستوى التالي</span>
                                    <span>75%</span>
                                </div>
                                <div class="skill-progress">
                                    <div class="progress-fill" style="width: 75%"></div>
                                    <div class="progress-glow"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Card -->
                    <div class="modern-card subscription-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <div class="header-icon premium">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="header-content">
                                <h6>معلومات الاشتراك</h6>
                                <p>إدارة خطة اشتراكك ومميزاتك</p>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="subscription-info">
                                <div class="info-item">
                                    <span class="info-label">نوع الاشتراك:</span>
                                    <span class="badge subscription-badge badge-<?php echo $user['subscription_type'] === 'free' ? 'secondary' : ($user['subscription_type'] === 'basic' ? 'primary' : 'warning'); ?>">
                                        <?php 
                                        $subscriptionTypes = [
                                            'free' => 'مجاني',
                                            'basic' => 'أساسي',
                                            'premium' => 'مميز'
                                        ];
                                        echo $subscriptionTypes[$user['subscription_type']] ?? 'مجاني';
                                        ?>
                                    </span>
                                </div>
                                
                                <?php if ($user['subscription_expires']): ?>
                                <div class="info-item">
                                    <span class="info-label">تاريخ الانتهاء:</span>
                                    <span class="info-value"><?php echo date('d/m/Y', strtotime($user['subscription_expires'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <a href="subscription.php" class="btn btn-outline-primary btn-modern w-100">
                                <i class="fas fa-crown me-2"></i>إدارة الاشتراك
                                <div class="btn-growth-effect"></div>
                            </a>
                        </div>
                    </div>

                    <!-- Account Stats Card -->
                    <div class="modern-card stats-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header">
                            <div class="header-icon info">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="header-content">
                                <h6>إحصائيات الحساب</h6>
                                <p>معلومات مفيدة عن نشاطك</p>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="stats-list">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">تاريخ التسجيل</span>
                                        <span class="stat-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">آخر دخول</span>
                                        <span class="stat-value"><?php echo date('d/m/Y', strtotime($user['last_login'])); ?></span>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">مهارات مكتملة</span>
                                        <span class="stat-value counter" data-count="<?php echo $user['completed_skills'] ?? 0; ?>">0</span>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-envelope-check"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label">حالة البريد</span>
                                        <span class="badge verification-badge badge-<?php echo $user['email_verified'] ? 'success' : 'warning'; ?>">
                                            <?php echo $user['email_verified'] ? 'مؤكد' : 'غير مؤكد'; ?>
                                        </span>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/index-script.js"></script>
    <script src="js/profile-script.js"></script>
</body>
</html>