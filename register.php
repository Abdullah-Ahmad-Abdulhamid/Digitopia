<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirectTo('index.php');
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'age' => $_POST['age'] ?? '',
        'governorate' => $_POST['governorate'] ?? '',
        'education_level' => $_POST['education_level'] ?? '',
        'current_income' => $_POST['current_income'] ?? ''
    ];

    // Validate password confirmation
    if ($data['password'] !== ($_POST['password_confirm'] ?? '')) {
        $errors['password_confirm'] = 'كلمة المرور غير متطابقة';
    }

    if (empty($errors)) {
        $user = new User();
        $result = $user->register($data);
        
        if ($result['success']) {
            $success = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.';
            // Clear form data
            $data = [];
        } else {
            $errors = $result['errors'];
        }
    }
}

$governorates = [
    'القاهرة', 'الجيزة', 'الإسكندرية', 'الدقهلية', 'البحيرة', 'الفيوم', 
    'الغربية', 'الإسماعيلية', 'المنوفية', 'المنيا', 'القليوبية', 'الوادي الجديد', 
    'السويس', 'أسوان', 'أسيوط', 'بني سويف', 'بورسعيد', 'دمياط', 
    'الشرقية', 'جنوب سيناء', 'كفر الشيخ', 'مطروح', 'الأقصر', 'قنا', 
    'شمال سيناء', 'سوهاج', 'الأحمر'
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انضم إلى مهاراتي - منصة تعلم المهارات الحياتية</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="انضم إلى منصة مهاراتي - ابدأ رحلة تطوير المهارات الحياتية مع آلاف الشباب المصري">
    <meta name="keywords" content="تسجيل، مهارات حياتية، تعليم، شباب، مصر، تطوير شخصي">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="انضم إلى مهاراتي - منصة تعلم المهارات الحياتية">
    <meta property="og:description" content="ابدأ رحلة تطوير مهاراتك الحياتية واحصل على محتوى مخصص للثقافة المصرية">
    <meta property="og:image" content="/images/register-og-image.jpg">
    <meta property="og:url" content="https://maharati.app/register">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <link rel="icon" type="image/png" href="images/logo.png">


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="css/register-styles.css" rel="stylesheet">
</head>

<body class="register-page">
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
        <div class="floating-element element-9"><i class="fas fa-graduation-cap"></i></div>
        <div class="floating-element element-10"><i class="fas fa-compass"></i></div>
    </div>

    <!-- Background Gradient -->
    <div class="register-background">
        <div class="bg-pattern"></div>
        <div class="bg-overlay"></div>
    </div>

    <!-- Navigation Back Link -->
    <nav class="register-nav">
        <div class="container">
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side - Inspiration Content -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center order-2 order-lg-1">
                <div class="inspiration-content" data-aos="fade-left" data-aos-duration="1000">
                    <!-- Growth Journey Visualization -->
                    <div class="growth-journey">
                        <div class="journey-path">
                            <!-- Start Point -->
                            <div class="journey-point point-start">
                                <div class="point-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="point-label">البداية</div>
                                <div class="point-glow"></div>
                            </div>

                            <!-- Skill Development -->
                            <div class="journey-point point-skill">
                                <div class="point-icon">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="point-label">تطوير المهارات</div>
                                <div class="point-glow"></div>
                            </div>

                            <!-- Achievement -->
                            <div class="journey-point point-achievement">
                                <div class="point-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="point-label">الإنجازات</div>
                                <div class="point-glow"></div>
                            </div>

                            <!-- Success -->
                            <div class="journey-point point-success">
                                <div class="point-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="point-label">النجاح</div>
                                <div class="point-glow"></div>
                            </div>

                            <!-- Connecting Path -->
                            <svg class="journey-path-line" viewBox="0 0 300 400">
                                <path d="M50 50 Q150 150 50 250 Q150 350 250 400" stroke="rgba(255, 255, 255, 0.3)" stroke-width="2" fill="none" stroke-dasharray="10,5">
                                    <animate attributeName="stroke-dashoffset" values="0;100" dur="3s" repeatCount="indefinite"/>
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Inspiration Message -->
                    <div class="inspiration-message">
                        <div class="message-badge">
                            <i class="fas fa-rocket me-2"></i>
                            <span>انضم للمجتمع</span>
                        </div>
                        
                        <h3 class="inspiration-title">
                            <span class="gradient-text">ازرع بذور</span> 
                            <br>مستقبلك اليوم
                        </h3>
                        
                        <p class="inspiration-subtitle">
                            انضم لأكثر من 5,000 شاب مصري يطورون مهاراتهم الحياتية ويبنون مستقبلاً أفضل كل يوم
                        </p>
                        
                        <!-- Platform Benefits -->
                        <div class="platform-benefits">
                            <div class="benefit-item" data-aos="zoom-in" data-aos-delay="200">
                                <div class="benefit-icon">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>محتوى مخصص</h6>
                                    <p>للثقافة المصرية</p>
                                </div>
                                <div class="benefit-glow"></div>
                            </div>
                            
                            <div class="benefit-item" data-aos="zoom-in" data-aos-delay="400">
                                <div class="benefit-icon">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>مساعد ذكي</h6>
                                    <p>متاح 24/7</p>
                                </div>
                                <div class="benefit-glow"></div>
                            </div>
                            
                            <div class="benefit-item" data-aos="zoom-in" data-aos-delay="600">
                                <div class="benefit-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>شهادات معتمدة</h6>
                                    <p>في كل مهارة</p>
                                </div>
                                <div class="benefit-glow"></div>
                            </div>
                            
                            <div class="benefit-item" data-aos="zoom-in" data-aos-delay="800">
                                <div class="benefit-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>مجتمع نشط</h6>
                                    <p>للدعم والتشجيع</p>
                                </div>
                                <div class="benefit-glow"></div>
                            </div>
                        </div>

                        <!-- Success Story -->
                        <div class="success-story" data-aos="fade-up" data-aos-delay="1000">
                            <div class="story-card">
                                <div class="quote-icon">
                                    <i class="fas fa-quote-right"></i>
                                </div>
                                <p class="story-text">
                                    "بدأت رحلتي مع مهاراتي وأنا طالب جامعي، والآن أعمل في شركة عالمية وأطور مشروعي الخاص!"
                                </p>
                                <div class="story-author">
                                    <div class="author-avatar">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100" alt="سارة أحمد">
                                        <div class="avatar-ring"></div>
                                    </div>
                                    <div class="author-info">
                                        <div class="author-name">سارة أحمد</div>
                                        <div class="author-title">رائدة أعمال</div>
                                        <div class="success-badge">
                                            <i class="fas fa-crown me-1"></i>
                                            أتمت 23 مهارة
                                        </div>
                                    </div>
                                </div>
                                <div class="story-decoration">
                                    <div class="decoration-particle particle-1"></div>
                                    <div class="decoration-particle particle-2"></div>
                                    <div class="decoration-particle particle-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center order-1 order-lg-2">
                <div class="register-form-container" data-aos="fade-up" data-aos-duration="800">
                    <!-- Logo and Branding -->
                    <div class="register-header text-center mb-5">
                        <div class="brand-logo-container">
                            <div class="brand-icon-wrapper">
                                <i class="fas fa-seedling brand-icon"></i>
                                <div class="icon-sparkle"></div>
                                <div class="growth-rings">
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                </div>
                            </div>
                            <h1 class="brand-title">مهاراتي</h1>
                        </div>
                        <div class="welcome-message">
                            <h2 class="welcome-title">انضم إلى رحلة النمو</h2>
                            <p class="welcome-subtitle">ابدأ تطوير مهاراتك الحياتية مع آلاف الشباب المصري</p>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if ($success): ?>
                        <div class="alert alert-success modern-alert animate__animated animate__bounceIn" role="alert">
                            <div class="alert-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">تم بنجاح</div>
                                <div class="alert-message"><?php echo htmlspecialchars($success); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger modern-alert animate__animated animate__headShake" role="alert">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">يرجى تصحيح الأخطاء التالية</div>
                                <div class="alert-message">
                                    <?php foreach ($errors as $error): ?>
                                        <div>• <?php echo htmlspecialchars($error); ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Registration Form -->
                    <form method="POST" class="modern-form" id="registerForm" data-aos="fade-up" data-aos-delay="200" novalidate>
                        <!-- Progress Indicator -->
                        <div class="form-progress">
                            <div class="progress-step active" data-step="1">
                                <div class="step-icon"><i class="fas fa-user"></i></div>
                                <span>البيانات الأساسية</span>
                            </div>
                            <div class="progress-step" data-step="2">
                                <div class="step-icon"><i class="fas fa-id-card"></i></div>
                                <span>المعلومات الشخصية</span>
                            </div>
                            <div class="progress-line">
                                <div class="progress-fill"></div>
                            </div>
                        </div>

                        <!-- Step 1: Basic Information -->
                        <div class="form-step active" id="step1">
                            <div class="step-header">
                                <h4 class="step-title">
                                    <i class="fas fa-user-circle me-2"></i>
                                    البيانات الأساسية
                                </h4>
                                <p class="step-subtitle">املأ بياناتك الأساسية لإنشاء حسابك</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-2"></i>
                                            الاسم الكامل
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="text" 
                                                   class="form-control modern-input <?php echo isset($errors['name']) ? 'error' : ''; ?>" 
                                                   id="name" 
                                                   name="name" 
                                                   value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>" 
                                                   required 
                                                   placeholder="أدخل اسمك الكامل"
                                                   autocomplete="name">
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <?php if (isset($errors['name'])): ?>
                                                <div class="field-error"><?php echo $errors['name']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>
                                            البريد الإلكتروني
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="email" 
                                                   class="form-control modern-input <?php echo isset($errors['email']) ? 'error' : ''; ?>" 
                                                   id="email" 
                                                   name="email" 
                                                   value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" 
                                                   required 
                                                   placeholder="أدخل بريدك الإلكتروني"
                                                   autocomplete="email">
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <?php if (isset($errors['email'])): ?>
                                                <div class="field-error"><?php echo $errors['email']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>
                                            كلمة المرور
                                        </label>
                                        <div class="input-wrapper password-wrapper">
                                            <input type="password" 
                                                   class="form-control modern-input <?php echo isset($errors['password']) ? 'error' : ''; ?>" 
                                                   id="password" 
                                                   name="password" 
                                                   required 
                                                   placeholder="أدخل كلمة المرور"
                                                   autocomplete="new-password">
                                            <button type="button" class="password-toggle" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-lock"></i>
                                            </div>
                                            <div class="password-strength">
                                                <div class="strength-meter">
                                                    <div class="strength-fill"></div>
                                                </div>
                                                <div class="strength-text">قوة كلمة المرور</div>
                                            </div>
                                            <?php if (isset($errors['password'])): ?>
                                                <div class="field-error"><?php echo $errors['password']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirm" class="form-label">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            تأكيد كلمة المرور
                                        </label>
                                        <div class="input-wrapper password-wrapper">
                                            <input type="password" 
                                                   class="form-control modern-input <?php echo isset($errors['password_confirm']) ? 'error' : ''; ?>" 
                                                   id="password_confirm" 
                                                   name="password_confirm" 
                                                   required 
                                                   placeholder="أعد إدخال كلمة المرور"
                                                   autocomplete="new-password">
                                            <button type="button" class="password-toggle" id="togglePasswordConfirm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <div class="password-match-indicator">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <?php if (isset($errors['password_confirm'])): ?>
                                                <div class="field-error"><?php echo $errors['password_confirm']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="step-navigation">
                                <button type="button" class="btn btn-next" id="nextStep1">
                                    <span>التالي</span>
                                    <i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Personal Information -->
                        <div class="form-step" id="step2">
                            <div class="step-header">
                                <h4 class="step-title">
                                    <i class="fas fa-id-card me-2"></i>
                                    المعلومات الشخصية
                                </h4>
                                <p class="step-subtitle">ساعدنا في تخصيص تجربة التعلم لك</p>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-2"></i>
                                            رقم الهاتف (اختياري)
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="tel" 
                                                   class="form-control modern-input" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>" 
                                                   placeholder="01xxxxxxxxx"
                                                   autocomplete="tel">
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="age" class="form-label">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            العمر (اختياري)
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="number" 
                                                   class="form-control modern-input" 
                                                   id="age" 
                                                   name="age" 
                                                   value="<?php echo htmlspecialchars($data['age'] ?? ''); ?>" 
                                                   min="13" 
                                                   max="100" 
                                                   placeholder="18">
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="governorate" class="form-label">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            المحافظة (اختياري)
                                        </label>
                                        <div class="input-wrapper">
                                            <select class="form-control modern-input" id="governorate" name="governorate">
                                                <option value="">اختر المحافظة</option>
                                                <?php foreach ($governorates as $gov): ?>
                                                    <option value="<?php echo $gov; ?>" 
                                                            <?php echo ($data['governorate'] ?? '') === $gov ? 'selected' : ''; ?>>
                                                        <?php echo $gov; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div class="select-arrow">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="education_level" class="form-label">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            المستوى التعليمي (اختياري)
                                        </label>
                                        <div class="input-wrapper">
                                            <select class="form-control modern-input" id="education_level" name="education_level">
                                                <option value="">اختر المستوى التعليمي</option>
                                                <option value="secondary" <?php echo ($data['education_level'] ?? '') === 'secondary' ? 'selected' : ''; ?>>ثانوية عامة</option>
                                                <option value="university" <?php echo ($data['education_level'] ?? '') === 'university' ? 'selected' : ''; ?>>جامعي</option>
                                                <option value="graduate" <?php echo ($data['education_level'] ?? '') === 'graduate' ? 'selected' : ''; ?>>خريج</option>
                                                <option value="postgraduate" <?php echo ($data['education_level'] ?? '') === 'postgraduate' ? 'selected' : ''; ?>>دراسات عليا</option>
                                            </select>
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="select-arrow">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="current_income" class="form-label">
                                            <i class="fas fa-coins me-2"></i>
                                            الراتب الشهري (اختياري)
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="number" 
                                                   class="form-control modern-input" 
                                                   id="current_income" 
                                                   name="current_income" 
                                                   value="<?php echo htmlspecialchars($data['current_income'] ?? ''); ?>" 
                                                   min="0" 
                                                   step="100" 
                                                   placeholder="مثال: 3000">
                                            <div class="input-focus-line"></div>
                                            <div class="input-icon">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                            <div class="input-helper">
                                                <small class="helper-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    سيساعدنا هذا في تقديم نصائح مالية مناسبة لك
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="step-navigation">
                                <button type="button" class="btn btn-next" id="nextStep2">
                                    <span>التالي</span>
                                    <i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Terms and Submit -->
                        <div class="form-step" id="step3">
                            <div class="step-header">
                                <h4 class="step-title">
                                    <i class="fas fa-check-circle me-2"></i>
                                    إنهاء التسجيل
                                </h4>
                                <p class="step-subtitle">اقرأ ووافق على الشروط لإكمال التسجيل</p>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="form-options">
                                <div class="custom-checkbox">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        <span class="checkmark">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        أوافق على 
                                        <a href="terms.php" target="_blank" class="terms-link">شروط الاستخدام</a> 
                                        و 
                                        <a href="privacy.php" target="_blank" class="terms-link">سياسة الخصوصية</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Newsletter Checkbox -->
                            <div class="form-options">
                                <div class="custom-checkbox">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" checked>
                                    <label class="form-check-label" for="newsletter">
                                        <span class="checkmark">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        أريد استقبال نصائح التطوير والمحتوى الجديد عبر البريد الإلكتروني
                                    </label>
                                </div>
                            </div>

                            <div class="step-navigation">
                                <button type="button" class="btn btn-back" id="backStep">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    <span>السابق</span>
                                </button>
                                <button type="submit" class="btn btn-register-primary" id="registerBtn">
                                    <span class="btn-content">
                                        <span class="btn-icon">
                                            <i class="fas fa-rocket"></i>
                                        </span>
                                        <span class="btn-text">ابدأ رحلة التطوير</span>
                                    </span>
                                    <div class="btn-growth-wave"></div>
                                    <div class="btn-particles"></div>
                                </button>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-line"></div>
                            <span class="divider-text">أو</span>
                            <div class="divider-line"></div>
                        </div>

                        <div class="auth-footer">
                            <div class="login-card">
                                <div class="login-content">
                                    <h6 class="login-title">لديك حساب بالفعل؟</h6>
                                    <p class="login-description">سجل دخولك وتابع رحلة التطوير</p>
                                </div>
                                <a href="login.php" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    تسجيل الدخول
                                    <div class="btn-shine"></div>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Platform Highlights -->
                    <div class="platform-highlights" data-aos="fade-up" data-aos-delay="400">
                        <div class="highlights-header">
                            <i class="fas fa-star me-2"></i>
                            لماذا مهاراتي؟
                        </div>
                        <div class="highlight-features">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <span>محتوى مجاني</span>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <span>على جميع الأجهزة</span>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <span>شهادات معتمدة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="growth-loader">
                <div class="loader-plant">
                    <i class="fas fa-seedling"></i>
                    <div class="loader-roots">
                        <div class="root root-1"></div>
                        <div class="root root-2"></div>
                        <div class="root root-3"></div>
                    </div>
                </div>
                <div class="loader-text">جاري إنشاء حسابك...</div>
                <div class="loader-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/register-script.js"></script>
    
    <script>
        // Initialize AOS
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                once: true,
                offset: 120,
                disable: function() {
                    return window.innerWidth < 768;
                }
            });
        }
        
        // Add loaded class when page is ready
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>