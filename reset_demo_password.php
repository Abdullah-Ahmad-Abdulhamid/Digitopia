<?php
require_once 'config/config.php';

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>إعادة تعيين كلمة مرور المستخدم التجريبي</title>
    <link href='https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { direction: rtl; text-align: right; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
    </style>
</head>
<body>
<div class='container'>
    <h1 class='text-center mb-4'>🔑 إعادة تعيين كلمة مرور المستخدم التجريبي</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if demo user exists
    $stmt = $db->prepare("SELECT id, name, email FROM users WHERE email = 'demo@maharati.com'");
    $stmt->execute();
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "<div class='error'>
                <h4>❌ المستخدم التجريبي غير موجود</h4>
                <p>يرجى تشغيل setup.php أولاً لإنشاء المستخدم التجريبي.</p>
                <a href='setup.php' class='btn btn-primary'>تشغيل الإعداد</a>
              </div>";
    } else {
        echo "<div class='success'>
                <h4>✅ تم العثور على المستخدم التجريبي</h4>
                <p><strong>الاسم:</strong> " . htmlspecialchars($user['name']) . "</p>
                <p><strong>البريد:</strong> " . htmlspecialchars($user['email']) . "</p>
              </div>";
        
        // Reset password
        $newPassword = '123456';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = 'demo@maharati.com'");
        $result = $stmt->execute([$hashedPassword]);
        
        if ($result) {
            echo "<div class='success'>
                    <h4>🎉 تم إعادة تعيين كلمة المرور بنجاح!</h4>
                    <p><strong>البريد الإلكتروني:</strong> demo@maharati.com</p>
                    <p><strong>كلمة المرور الجديدة:</strong> 123456</p>
                    <hr>
                    <h5>اختبار تسجيل الدخول:</h5>
                  </div>";
            
            // Test login
            $userObj = new User();
            $loginResult = $userObj->login('demo@maharati.com', '123456');
            
            if ($loginResult['success']) {
                echo "<div class='success'>
                        <h4>✅ اختبار تسجيل الدخول نجح!</h4>
                        <p>يمكنك الآن تسجيل الدخول بالبيانات التالية:</p>
                        <ul>
                            <li><strong>البريد:</strong> demo@maharati.com</li>
                            <li><strong>كلمة المرور:</strong> 123456</li>
                        </ul>
                      </div>";
            } else {
                echo "<div class='error'>
                        <h4>❌ فشل اختبار تسجيل الدخول</h4>
                        <p><strong>الخطأ:</strong> " . htmlspecialchars($loginResult['error']) . "</p>
                      </div>";
            }
        } else {
            echo "<div class='error'>
                    <h4>❌ فشل في إعادة تعيين كلمة المرور</h4>
                    <p>حدث خطأ في قاعدة البيانات.</p>
                  </div>";
        }
    }
    
    echo "<hr>
          <div class='text-center'>
              <a href='/maharati_platform/login.php' class='btn btn-primary me-2'>تسجيل الدخول</a>
              <a href='index.php' class='btn btn-outline-primary'>الرئيسية</a>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <h4>❌ خطأ في النظام</h4>
            <p><strong>رسالة الخطأ:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
          </div>";
}

echo "</div>
      <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
      </body>
      </html>";
?>
