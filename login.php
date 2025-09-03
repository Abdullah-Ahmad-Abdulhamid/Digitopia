<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirectTo('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'يرجى إدخال البريد الإلكتروني وكلمة المرور';
    } else {
        $user = new User();
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            redirectTo('index.php');
        } else {
            $error = $result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - مهاراتي</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="سجل دخولك إلى منصة مهاراتي لمتابعة رحلة تطوير مهاراتك الحياتية">
    <meta name="keywords" content="تسجيل دخول، مهارات حياتية، تعليم، شباب، تطوير شخصي">
    <meta name="author" content="فريق مهاراتي">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="تسجيل الدخول - منصة مهاراتي">
    <meta property="og:description" content="ادخل إلى حسابك واستمر في رحلة تطوير مهاراتك الحياتية">
    <meta property="og:image" content="/images/login-og-image.jpg">
    <meta property="og:url" content="https://maharati.app/login">
    
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
    <link href="css/login-styles.css" rel="stylesheet">
</head>

<body class="login-page">
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

    <!-- Background Gradient -->
    <div class="login-background">
        <div class="bg-pattern"></div>
        <div class="bg-overlay"></div>
    </div>

    <!-- Navigation Back Link -->
    <nav class="login-nav">
        <div class="container">
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side - Login Form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center order-2 order-lg-1">
                <div class="login-form-container" data-aos="fade-up" data-aos-duration="800">
                    <!-- Logo and Branding -->
                    <div class="login-header text-center mb-5">
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
                            <h2 class="welcome-title">مرحباً بعودتك</h2>
                            <p class="welcome-subtitle">تابع رحلة تطوير مهاراتك الحياتية وحقق أهدافك</p>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger modern-alert animate__animated animate__headShake" role="alert">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">خطأ في تسجيل الدخول</div>
                                <div class="alert-message"><?php echo htmlspecialchars($error); ?></div>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endif; ?>

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

                    <!-- Login Form -->
                    <form method="POST" class="modern-form" id="loginForm" data-aos="fade-up" data-aos-delay="200">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>
                                البريد الإلكتروني
                            </label>
                            <div class="input-wrapper">
                                <input type="email" 
                                       class="form-control modern-input" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                                       required 
                                       placeholder="أدخل بريدك الإلكتروني"
                                       autocomplete="email">
                                <div class="input-focus-line"></div>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                كلمة المرور
                            </label>
                            <div class="input-wrapper password-wrapper">
                                <input type="password" 
                                       class="form-control modern-input" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="أدخل كلمة المرور"
                                       autocomplete="current-password">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="input-focus-line"></div>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    <span class="checkmark">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    تذكرني لمدة أسبوع
                                </label>
                            </div>
                            <a href="forgot-password.php" class="forgot-link">
                                <i class="fas fa-key me-1"></i>
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        <button type="submit" class="btn btn-login-primary" id="loginBtn">
                            <span class="btn-content">
                                <span class="btn-icon">
                                    <i class="fas fa-seedling"></i>
                                </span>
                                <span class="btn-text">ابدأ رحلة التطوير</span>
                            </span>
                            <div class="btn-growth-wave"></div>
                            <div class="btn-particles"></div>
                        </button>

                        <div class="divider">
                            <div class="divider-line"></div>
                            <span class="divider-text">أو</span>
                            <div class="divider-line"></div>
                        </div>

                        <div class="auth-footer">
                            <div class="signup-card">
                                <div class="signup-content">
                                    <h6 class="signup-title">جديد على مهاراتي؟</h6>
                                    <p class="signup-description">انضم لآلاف الشباب وابدأ رحلة التطوير</p>
                                </div>
                                <a href="register.php" class="btn btn-signup">
                                    <i class="fas fa-user-plus me-2"></i>
                                    إنشاء حساب جديد
                                    <div class="btn-shine"></div>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Quick Access -->
                    <div class="quick-access" data-aos="fade-up" data-aos-delay="400">
                        <div class="quick-access-header">
                            <i class="fas fa-rocket me-2"></i>
                            للبدء السريع
                        </div>
                        <div class="quick-features">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <span>محتوى مجاني</span>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <span>على جميع الأجهزة</span>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <span>شهادات معتمدة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Inspiration Content -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center order-1 order-lg-2">
                <div class="inspiration-content" data-aos="fade-right" data-aos-duration="1000">
                    <!-- Growth Visualization -->
                    <div class="growth-visualization">
                        <div class="growth-ecosystem">
                            <!-- Central Growth Hub -->
                            <div class="ecosystem-hub">
                                <div class="hub-core">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="hub-rings">
                                    <div class="hub-ring ring-1"></div>
                                    <div class="hub-ring ring-2"></div>
                                    <div class="hub-ring ring-3"></div>
                                </div>
                            </div>

                            <!-- Orbiting Skills -->
                            <div class="skill-orbit orbit-1">
                                <div class="skill-planet planet-financial">
                                    <i class="fas fa-coins"></i>
                                    <span>إدارة المال</span>
                                </div>
                            </div>
                            <div class="skill-orbit orbit-2">
                                <div class="skill-planet planet-communication">
                                    <i class="fas fa-comments"></i>
                                    <span>التواصل</span>
                                </div>
                            </div>
                            <div class="skill-orbit orbit-3">
                                <div class="skill-planet planet-career">
                                    <i class="fas fa-briefcase"></i>
                                    <span>العمل</span>
                                </div>
                            </div>
                            <div class="skill-orbit orbit-4">
                                <div class="skill-planet planet-wellness">
                                    <i class="fas fa-heart"></i>
                                    <span>الصحة النفسية</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inspiration Message -->
                    <div class="inspiration-message">
                        <div class="message-badge">
                            <i class="fas fa-star me-2"></i>
                            <span>رحلة التطوير تبدأ هنا</span>
                        </div>
                        
                        <h3 class="inspiration-title">
                            <span class="gradient-text">ازرع بذور</span> 
                            <br>النجاح اليوم
                        </h3>
                        
                        <p class="inspiration-subtitle">
                            انضم لآلاف الشباب المصري الذين يطورون مهاراتهم الحياتية ويحققون أهدافهم كل يوم
                        </p>
                        
                        <!-- Live Stats -->
                        <div class="live-stats">
                            <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number counter" data-count="5247">0</div>
                                    <div class="stat-label">طالب نشط</div>
                                </div>
                                <div class="stat-glow"></div>
                            </div>
                            
                            <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                                <div class="stat-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number counter" data-count="52">0</div>
                                    <div class="stat-label">مهارة حياتية</div>
                                </div>
                                <div class="stat-glow"></div>
                            </div>
                            
                            <div class="stat-card" data-aos="zoom-in" data-aos-delay="600">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">96%</div>
                                    <div class="stat-label">معدل النجاح</div>
                                </div>
                                <div class="stat-glow"></div>
                            </div>
                        </div>

                        <!-- Featured Testimonial -->
                        <div class="testimonial-showcase" data-aos="fade-up" data-aos-delay="800">
                            <div class="testimonial-card">
                                <div class="quote-icon">
                                    <i class="fas fa-quote-right"></i>
                                </div>
                                <p class="testimonial-text">
                                    "منصة مهاراتي غيرت حياتي! تعلمت إدارة أموالي وطورت مهارات التواصل، والآن حصلت على وظيفة أحلامي في شركة رائدة"
                                </p>
                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <img src="https://images.pexels.com/photos/1674752/pexels-photo-1674752.jpeg?auto=compress&cs=tinysrgb&w=100" alt="أحمد محمد">
                                        <div class="avatar-ring"></div>
                                    </div>
                                    <div class="author-info">
                                        <div class="author-name">أحمد محمد</div>
                                        <div class="author-title">مطور برمجيات</div>
                                        <div class="success-badge">
                                            <i class="fas fa-medal me-1"></i>
                                            نجح في 15 مهارة
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-decoration">
                                    <div class="decoration-particle particle-1"></div>
                                    <div class="decoration-particle particle-2"></div>
                                    <div class="decoration-particle particle-3"></div>
                                </div>
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
                <div class="loader-text">جاري تسجيل الدخول...</div>
                <div class="loader-progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/login-script.js"></script>
    
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