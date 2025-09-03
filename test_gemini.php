<?php
require_once 'config/config.php';

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>اختبار Gemini AI</title>
    <link href='https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { direction: rtl; text-align: right; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 8px; overflow-x: auto; }
    </style>
</head>
<body>
<div class='container'>
    <h1 class='text-center mb-4'>🤖 اختبار Gemini AI</h1>";

try {
    // Test 1: Check API Key
    echo "<div class='info'>
            <h4>🔑 اختبار 1: فحص مفتاح API</h4>";
    
    $apiKey = GEMINI_API_KEY;
    echo "<p><strong>مفتاح API:</strong> " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -5) . "</p>";
    
    if ($apiKey === 'your_gemini_api_key_here') {
        echo "<p class='text-danger'>❌ مفتاح API لم يتم تعيينه</p>";
    } else {
        echo "<p class='text-success'>✅ مفتاح API تم تعيينه</p>";
    }
    echo "</div>";
    
    // Test 2: Initialize Gemini
    echo "<div class='info'>
            <h4>🚀 اختبار 2: تهيئة Gemini AI</h4>";
    
    $gemini = new GeminiAI();
    echo "<p class='text-success'>✅ تم إنشاء كائن Gemini بنجاح</p>";
    echo "</div>";
    
    // Test 3: Test Connection
    echo "<div class='info'>
            <h4>🌐 اختبار 3: اختبار الاتصال</h4>";
    
    $connectionTest = $gemini->testConnection();
    if ($connectionTest) {
        echo "<p class='text-success'>✅ اختبار الاتصال نجح</p>";
    } else {
        echo "<p class='text-warning'>⚠️ اختبار الاتصال فشل - سيتم استخدام الردود الافتراضية</p>";
    }
    echo "</div>";
    
    // Test 4: Direct API Call
    echo "<div class='info'>
            <h4>🔧 اختبار 4: استدعاء API مباشر</h4>";
    
    $testMessage = "مرحبا، كيف يمكنني توفير المال؟";
    echo "<p><strong>الرسالة:</strong> " . htmlspecialchars($testMessage) . "</p>";
    
    $response = $gemini->generateResponse($testMessage);
    echo "<p><strong>الرد:</strong></p>";
    echo "<div class='alert alert-light'>" . nl2br(htmlspecialchars($response)) . "</div>";
    
    // Check if it's a fallback response
    $fallbackResponses = [
        "عذراً، حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.",
        "عذراً، الخدمة غير متاحة حالياً. يرجى المحاولة لاحقاً.",
        "عذراً، خدمة الذكاء الاصطناعي غير متاحة حالياً. يرجى التواصل مع الإدارة."
    ];
    
    if (in_array($response, $fallbackResponses)) {
        echo "<p class='text-warning'>⚠️ هذا رد افتراضي - Gemini AI لا يعمل</p>";
    } else {
        echo "<p class='text-success'>✅ رد من Gemini AI</p>";
    }
    echo "</div>";
    
    // Test 5: Test with user profile
    echo "<div class='info'>
            <h4>👤 اختبار 5: اختبار مع ملف المستخدم</h4>";
    
    $userProfile = [
        'name' => 'أحمد محمد',
        'age' => 25,
        'governorate' => 'القاهرة',
        'current_income' => 3000,
        'education_level' => 'university'
    ];
    
    $testMessage2 = "أريد نصائح لإدارة راتبي";
    echo "<p><strong>الرسالة:</strong> " . htmlspecialchars($testMessage2) . "</p>";
    
    $response2 = $gemini->generateResponse($testMessage2, $userProfile);
    echo "<p><strong>الرد:</strong></p>";
    echo "<div class='alert alert-light'>" . nl2br(htmlspecialchars($response2)) . "</div>";
    echo "</div>";
    
    // Test 6: Manual cURL test with different models
    echo "<div class='info'>
            <h4>🌍 اختبار 6: اختبار cURL مباشر</h4>";

    $models = [
        'gemini-1.5-flash' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
        'gemini-1.5-pro' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent',
        'gemini-pro' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent'
    ];

    $requestData = [
        'contents' => [
            [
                'parts' => [
                    ['text' => 'مرحبا، أجب بالعربية']
                ]
            ]
        ]
    ];

    $success = false;
    $workingModel = '';

    foreach ($models as $modelName => $modelUrl) {
        echo "<p><strong>جاري اختبار النموذج:</strong> $modelName</p>";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $modelUrl . '?key=' . GEMINI_API_KEY,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($requestData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $curlResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (!$curlError && $httpCode === 200) {
            $decodedResponse = json_decode($curlResponse, true);
            if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
                echo "<p class='text-success'>✅ $modelName يعمل!</p>";
                $success = true;
                $workingModel = $modelName;
                $workingUrl = $modelUrl;
                echo "<div class='alert alert-success'>" . nl2br(htmlspecialchars($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) . "</div>";
                break;
            }
        } else {
            echo "<p class='text-warning'>⚠️ $modelName لا يعمل (HTTP: $httpCode)</p>";
        }
    }
    
    if ($success) {
        echo "<div class='success'>
                <h5>🎉 تم العثور على نموذج يعمل!</h5>
                <p><strong>النموذج:</strong> $workingModel</p>
                <p><strong>URL:</strong> $workingUrl</p>
              </div>";

        // Update config automatically
        echo "<div class='info'>
                <h5>🔧 تحديث الإعدادات تلقائياً</h5>";

        $configContent = file_get_contents('config/config.php');
        $newConfigContent = str_replace(
            "define('GEMINI_API_URL', '" . GEMINI_API_URL . "');",
            "define('GEMINI_API_URL', '$workingUrl');",
            $configContent
        );

        if (file_put_contents('config/config.php', $newConfigContent)) {
            echo "<p class='text-success'>✅ تم تحديث الإعدادات بنجاح!</p>";
        } else {
            echo "<p class='text-warning'>⚠️ لم يتم تحديث الإعدادات تلقائياً</p>";
            echo "<p>يرجى تحديث GEMINI_API_URL في config.php إلى:</p>";
            echo "<code>$workingUrl</code>";
        }
        echo "</div>";
    } else {
        echo "<div class='error'>
                <h5>❌ لا يوجد نموذج يعمل</h5>
                <p>جميع النماذج فشلت في الاختبار</p>
              </div>";
    }
    echo "</div>";
    
    // Summary
    echo "<div class='warning'>
            <h4>📋 الخلاصة</h4>";

    if ($success) {
        echo "<p class='text-success'><strong>✅ Gemini AI يعمل بشكل صحيح!</strong></p>
              <p>النموذج المستخدم: <strong>$workingModel</strong></p>
              <p>الآن يمكنك استخدام الشات بكامل قوته!</p>";
    } else {
        echo "<p class='text-warning'><strong>⚠️ Gemini AI لا يعمل حالياً</strong></p>
              <p>الأسباب المحتملة:</p>
              <ul>
                <li>مشكلة في الاتصال بالإنترنت</li>
                <li>مفتاح API غير صحيح أو منتهي الصلاحية</li>
                <li>حدود الاستخدام تم تجاوزها</li>
                <li>مشكلة في خوادم Google</li>
                <li>تغيير في أسماء النماذج من Google</li>
              </ul>
              <p class='text-info'><strong>لا تقلق!</strong> الشات سيعمل بالردود الذكية الافتراضية.</p>";
    }
    echo "</div>";
    
    echo "<hr>
          <div class='text-center'>
              <a href='chat.php' class='btn btn-primary me-2'>اختبار الشات</a>
              <a href='index.php' class='btn btn-outline-primary'>الرئيسية</a>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <h4>❌ خطأ في الاختبار</h4>
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
