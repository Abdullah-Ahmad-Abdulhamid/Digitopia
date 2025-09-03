<?php
require_once 'config/config.php';

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>إصلاح المستخدم التجريبي</title>
    <link href='https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { direction: rtl; text-align: right; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
    </style>
</head>
<body>
<div class='container'>
    <h1 class='text-center mb-4'>🔧 إصلاح المستخدم التجريبي</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<div class='info'>
            <h4>🔍 جاري فحص المستخدم التجريبي...</h4>
          </div>";
    
    // Delete existing demo user if exists
    $stmt = $db->prepare("DELETE FROM users WHERE email = 'demo@maharati.com'");
    $stmt->execute();
    
    echo "<div class='success'>
            <h4>🗑️ تم حذف المستخدم التجريبي القديم (إن وجد)</h4>
          </div>";
    
    // Create new demo user with correct password
    $demoPassword = password_hash('123456', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, age, governorate, education_level, current_income, subscription_type, email_verified, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $result = $stmt->execute([
        'أحمد محمد',
        'demo@maharati.com',
        $demoPassword,
        25,
        'القاهرة',
        'university',
        3000,
        'free',
        1,
        1
    ]);
    
    if ($result) {
        echo "<div class='success'>
                <h4>✅ تم إنشاء المستخدم التجريبي بنجاح!</h4>
                <p><strong>البريد الإلكتروني:</strong> demo@maharati.com</p>
                <p><strong>كلمة المرور:</strong> 123456</p>
                <p><strong>الاسم:</strong> أحمد محمد</p>
                <p><strong>العمر:</strong> 25 سنة</p>
                <p><strong>المحافظة:</strong> القاهرة</p>
              </div>";
        
        // Test login
        echo "<div class='info'>
                <h4>🧪 اختبار تسجيل الدخول...</h4>
              </div>";
        
        $userObj = new User();
        $loginResult = $userObj->login('demo@maharati.com', '123456');
        
        if ($loginResult['success']) {
            echo "<div class='success'>
                    <h4>🎉 نجح اختبار تسجيل الدخول!</h4>
                    <p>يمكنك الآن تسجيل الدخول بالبيانات التالية:</p>
                    <div class='alert alert-primary'>
                        <strong>البريد الإلكتروني:</strong> demo@maharati.com<br>
                        <strong>كلمة المرور:</strong> 123456
                    </div>
                  </div>";
            
            // Test password verification directly
            echo "<div class='info'>
                    <h4>🔐 اختبار التحقق من كلمة المرور...</h4>";
            
            $stmt = $db->prepare("SELECT password_hash FROM users WHERE email = 'demo@maharati.com'");
            $stmt->execute();
            $user = $stmt->fetch();
            
            if ($user && password_verify('123456', $user['password_hash'])) {
                echo "<p>✅ التحقق من كلمة المرور يعمل بشكل صحيح</p>";
            } else {
                echo "<p>❌ مشكلة في التحقق من كلمة المرور</p>";
            }
            
            echo "</div>";
            
        } else {
            echo "<div class='error'>
                    <h4>❌ فشل اختبار تسجيل الدخول</h4>
                    <p><strong>الخطأ:</strong> " . htmlspecialchars($loginResult['error']) . "</p>
                  </div>";
        }
        
    } else {
        echo "<div class='error'>
                <h4>❌ فشل في إنشاء المستخدم التجريبي</h4>
                <p>حدث خطأ في قاعدة البيانات.</p>
              </div>";
    }
    
    echo "<hr>
          <div class='text-center'>
              <a href='/maharati_platform/login.php' class='btn btn-primary me-2'>تسجيل الدخول</a>
              <a href='index.php' class='btn btn-outline-primary me-2'>الرئيسية</a>
              <a href='setup.php' class='btn btn-outline-secondary'>الإعداد</a>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <h4>❌ خطأ في النظام</h4>
            <p><strong>رسالة الخطأ:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p><strong>الملف:</strong> " . htmlspecialchars($e->getFile()) . "</p>
            <p><strong>السطر:</strong> " . $e->getLine() . "</p>
          </div>";
}

echo "</div>
      <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
      </body>
      </html>";
?>
