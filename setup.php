<?php
// Maharati Platform Setup Script
// Run this file once to initialize the database

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'maharati_db';

// Check if reset is requested
$reset = isset($_GET['reset']) && $_GET['reset'] === 'true';

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>إعداد منصة مهاراتي</title>
    <link href='https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { direction: rtl; text-align: right; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .setup-container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .step { margin: 1rem 0; padding: 1rem; border-radius: 8px; }
        .step.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .step.error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .step.info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .step.warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
    </style>
</head>
<body>
<div class='container'>
    <div class='setup-container'>
        <h1 class='text-center mb-4'>🎓 إعداد منصة مهاراتي</h1>";

if ($reset) {
    echo "<div class='step warning'>
            <h4>⚠️ إعادة تعيين قاعدة البيانات</h4>
            <p>سيتم حذف جميع البيانات الموجودة وإعادة إنشاء قاعدة البيانات من جديد.</p>
          </div>";
}

try {
    // Step 1: Connect to MySQL
    echo "<div class='step info'>
            <h4>الخطوة 1: الاتصال بقاعدة البيانات</h4>
            <p>جاري الاتصال بخادم MySQL...</p>
          </div>";

    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "<div class='step success'>
            <h4>✅ تم الاتصال بنجاح</h4>
            <p>تم الاتصال بخادم MySQL بنجاح.</p>
          </div>";

    // Step 2: Create Database
    echo "<div class='step info'>
            <h4>الخطوة 2: إعداد قاعدة البيانات</h4>
            <p>جاري إعداد قاعدة البيانات '$database'...</p>
          </div>";

    if ($reset) {
        // Drop database if reset is requested
        $pdo->exec("DROP DATABASE IF EXISTS $database");
        echo "<div class='step warning'>
                <h4>🗑️ تم حذف قاعدة البيانات القديمة</h4>
                <p>تم حذف قاعدة البيانات القديمة لإعادة التعيين.</p>
              </div>";
    }

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE $database");

    echo "<div class='step success'>
            <h4>✅ تم إعداد قاعدة البيانات</h4>
            <p>تم إعداد قاعدة البيانات '$database' بنجاح.</p>
          </div>";

    // Step 3: Create Tables
    echo "<div class='step info'>
            <h4>الخطوة 3: إنشاء الجداول</h4>
            <p>جاري التحقق من الجداول وإنشاء المفقود منها...</p>
          </div>";

    $schemaFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("ملف schema.sql غير موجود في مجلد database");
    }

    // Check existing tables
    $stmt = $pdo->query("SHOW TABLES");
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $schema = file_get_contents($schemaFile);

    // Remove the CREATE DATABASE and USE statements since we already did that
    $schema = preg_replace('/CREATE DATABASE.*?;/i', '', $schema);
    $schema = preg_replace('/USE.*?;/i', '', $schema);

    // Replace CREATE TABLE with CREATE TABLE IF NOT EXISTS
    $schema = preg_replace('/CREATE TABLE\s+([a-zA-Z_]+)/i', 'CREATE TABLE IF NOT EXISTS $1', $schema);

    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    $createdTables = 0;
    $skippedTables = 0;

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                if (preg_match('/CREATE TABLE IF NOT EXISTS\s+([a-zA-Z_]+)/i', $statement, $matches)) {
                    $tableName = $matches[1];
                    if (!in_array($tableName, $existingTables)) {
                        $createdTables++;
                    } else {
                        $skippedTables++;
                    }
                }
            } catch (PDOException $e) {
                // Ignore table already exists errors
                if ($e->getCode() != '42S01') {
                    throw $e;
                }
            }
        }
    }

    echo "<div class='step success'>
            <h4>✅ تم التحقق من الجداول</h4>
            <p>تم إنشاء $createdTables جدول جديد، وتم تخطي $skippedTables جدول موجود مسبقاً.</p>
          </div>";

    // Step 4: Insert Sample Data
    echo "<div class='step info'>
            <h4>الخطوة 4: إدراج البيانات التجريبية</h4>
            <p>جاري التحقق من البيانات وإدراج المفقود منها...</p>
          </div>";

    // Check if sample data already exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM skill_categories");
    $categoriesCount = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
    $skillsCount = $stmt->fetchColumn();

    if ($categoriesCount == 0 || $skillsCount == 0) {
        $sampleDataFile = __DIR__ . '/database/sample_data.sql';
        if (!file_exists($sampleDataFile)) {
            throw new Exception("ملف sample_data.sql غير موجود في مجلد database");
        }

        $sampleData = file_get_contents($sampleDataFile);

        // Remove the USE statement
        $sampleData = preg_replace('/USE.*?;/i', '', $sampleData);

        // Split by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sampleData)));

        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Ignore duplicate entry errors
                    if ($e->getCode() != '23000') {
                        throw $e;
                    }
                }
            }
        }

        echo "<div class='step success'>
                <h4>✅ تم إدراج البيانات التجريبية</h4>
                <p>تم إدراج البيانات التجريبية بنجاح.</p>
              </div>";
    } else {
        echo "<div class='step success'>
                <h4>✅ البيانات التجريبية موجودة</h4>
                <p>البيانات التجريبية موجودة بالفعل ($categoriesCount تصنيف، $skillsCount مهارة).</p>
              </div>";
    }

    // Step 5: Create Demo User with proper password hash
    echo "<div class='step info'>
            <h4>الخطوة 5: إنشاء مستخدم تجريبي</h4>
            <p>جاري التحقق من المستخدم التجريبي...</p>
          </div>";

    $demoPassword = password_hash('123456', PASSWORD_DEFAULT); // Use standard PHP function

    // Check if demo user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'demo@maharati.com'");
    $stmt->execute();
    $existingUser = $stmt->fetch();

    if (!$existingUser) {
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password_hash, age, governorate, education_level, current_income, subscription_type, email_verified)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'أحمد محمد',
            'demo@maharati.com',
            $demoPassword,
            25,
            'القاهرة',
            'university',
            4000.00,
            'basic',
            1
        ]);

        $userId = $pdo->lastInsertId();

        // Initialize user points
        $stmt = $pdo->prepare("INSERT INTO user_points (user_id, total_points, level_name) VALUES (?, ?, ?)");
        $stmt->execute([$userId, 0, 'مبتدئ']);

        echo "<div class='step success'>
                <h4>✅ تم إنشاء المستخدم التجريبي</h4>
                <p>تم إنشاء مستخدم تجريبي بنجاح:</p>
                <ul>
                    <li><strong>البريد الإلكتروني:</strong> demo@maharati.com</li>
                    <li><strong>كلمة المرور:</strong> 123456</li>
                </ul>
              </div>";
    } else {
        echo "<div class='step success'>
                <h4>✅ المستخدم التجريبي موجود</h4>
                <p>المستخدم التجريبي موجود بالفعل:</p>
                <ul>
                    <li><strong>البريد الإلكتروني:</strong> demo@maharati.com</li>
                    <li><strong>كلمة المرور:</strong> 123456</li>
                </ul>
              </div>";
    }

    // Step 6: Verify Installation
    echo "<div class='step info'>
            <h4>الخطوة 6: التحقق من التثبيت</h4>
            <p>جاري التحقق من صحة التثبيت...</p>
          </div>";

    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $expectedTables = [
        'users', 'skill_categories', 'skills', 'user_progress', 
        'chat_conversations', 'daily_challenges', 'user_challenges', 
        'user_points', 'system_settings'
    ];
    
    $missingTables = array_diff($expectedTables, $tables);
    
    if (empty($missingTables)) {
        // Check data
        $stmt = $pdo->query("SELECT COUNT(*) FROM skill_categories");
        $categoriesCount = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
        $skillsCount = $stmt->fetchColumn();
        
        echo "<div class='step success'>
                <h4>✅ التثبيت مكتمل بنجاح!</h4>
                <p>تم التحقق من صحة التثبيت:</p>
                <ul>
                    <li>عدد الجداول: " . count($tables) . "</li>
                    <li>عدد تصنيفات المهارات: $categoriesCount</li>
                    <li>عدد المهارات: $skillsCount</li>
                </ul>
              </div>";
    } else {
        throw new Exception("جداول مفقودة: " . implode(', ', $missingTables));
    }

    // Final Success Message
    echo "<div class='alert alert-success mt-4'>
            <h3>🎉 تم إعداد منصة مهاراتي بنجاح!</h3>
            <p>يمكنك الآن استخدام المنصة:</p>
            <div class='d-grid gap-2 d-md-flex justify-content-md-start mt-3'>
                <a href='index.php' class='btn btn-primary'>الذهاب للرئيسية</a>
                <a href='/maharati_platform/login.php' class='btn btn-outline-primary'>تسجيل الدخول</a>
            </div>
            
            <hr class='my-3'>
            
            <h5>معلومات مهمة:</h5>
            <ul>
                <li><strong>مستخدم تجريبي:</strong> demo@maharati.com / 123456</li>
                <li><strong>تكوين Gemini AI:</strong> تأكد من إضافة مفتاح API في config/config.php</li>
                <li><strong>الأمان:</strong> احذف هذا الملف (setup.php) بعد التثبيت</li>
            </ul>

            <div class='mt-3'>
                <a href='setup.php?reset=true' class='btn btn-warning btn-sm' onclick='return confirm(\"هل أنت متأكد من إعادة تعيين قاعدة البيانات؟ سيتم حذف جميع البيانات!\")'>
                    🔄 إعادة تعيين قاعدة البيانات
                </a>
            </div>
          </div>";

} catch (Exception $e) {
    echo "<div class='step error'>
            <h4>❌ خطأ في التثبيت</h4>
            <p><strong>رسالة الخطأ:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p><strong>الحلول المقترحة:</strong></p>
            <ul>
                <li>تأكد من تشغيل خادم MySQL</li>
                <li>تحقق من بيانات الاتصال بقاعدة البيانات</li>
                <li>تأكد من وجود ملفات schema.sql و sample_data.sql</li>
                <li>تحقق من صلاحيات المستخدم لإنشاء قواعد البيانات</li>
                <li>إذا كانت قاعدة البيانات موجودة، جرب <a href='setup.php?reset=true' class='text-warning'>إعادة التعيين</a></li>
            </ul>

            <div class='mt-3'>
                <a href='setup.php?reset=true' class='btn btn-warning' onclick='return confirm(\"هل تريد إعادة تعيين قاعدة البيانات وحذف جميع البيانات؟\")'>
                    🔄 إعادة تعيين قاعدة البيانات
                </a>
                <a href='index.php' class='btn btn-primary ms-2'>
                    🏠 الذهاب للرئيسية
                </a>
            </div>
          </div>";
}

echo "    </div>
        </div>
        
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
      </body>
      </html>";
?>
