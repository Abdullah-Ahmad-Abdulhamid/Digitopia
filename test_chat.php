<?php
// Simple test for chat API
require_once 'config/config.php';

echo "<h2>Chat API Test</h2>";

// Test 1: Check if classes load
echo "<h3>1. Testing Class Loading:</h3>";
try {
    $gemini = new GeminiAI();
    echo "✅ GeminiAI class loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading GeminiAI: " . $e->getMessage() . "<br>";
}

// Test 2: Check API key
echo "<h3>2. Testing API Key:</h3>";
echo "API Key: " . (defined('GEMINI_API_KEY') ? 'Defined' : 'Not defined') . "<br>";
echo "API Key value: " . (GEMINI_API_KEY !== 'your_gemini_api_key_here' ? 'Set' : 'Default') . "<br>";

// Test 3: Test connection
echo "<h3>3. Testing Connection:</h3>";
try {
    $gemini = new GeminiAI();
    $connectionTest = $gemini->testConnection();
    echo "Connection test: " . ($connectionTest ? '✅ Success' : '❌ Failed') . "<br>";
} catch (Exception $e) {
    echo "❌ Connection test error: " . $e->getMessage() . "<br>";
}

// Test 4: Test fallback response
echo "<h3>4. Testing Fallback Response:</h3>";
try {
    $testMessage = "مرحبا";
    
    // Include the fallback function from chat API
    function getFallbackResponse($message) {
        $message = strtolower(trim($message));
        
        // Simple keyword-based responses
        if (strpos($message, 'مرحبا') !== false || strpos($message, 'السلام') !== false) {
            return "مرحباً بك في منصة مهاراتي! 🇪🇬\n\nأنا مساعدك الذكي لتعلم المهارات الحياتية. يمكنني مساعدتك في:\n\n💰 إدارة الأموال والادخار\n💼 مهارات العمل والوظائف\n🗣️ التواصل والعلاقات\n🧠 الصحة النفسية\n\nما المهارة التي تود تعلمها اليوم؟";
        }
        
        return "شكراً لك على رسالتك! 😊\n\nحالياً الذكاء الاصطناعي غير متاح، لكن يمكنك:\n\n📚 تصفح المهارات المتاحة\n🎯 حل التحدي اليومي\n📈 متابعة تقدمك\n\nأو جرب كتابة كلمات مثل: مال، شغل، تواصل للحصول على نصائح سريعة.";
    }
    
    $fallbackResponse = getFallbackResponse($testMessage);
    echo "✅ Fallback response working<br>";
    echo "Response preview: " . substr($fallbackResponse, 0, 50) . "...<br>";
} catch (Exception $e) {
    echo "❌ Fallback response error: " . $e->getMessage() . "<br>";
}

// Test 5: Test actual API call
echo "<h3>5. Testing API Call:</h3>";
try {
    $postData = json_encode([
        'message' => 'مرحبا',
        'session_id' => 'test_session',
        'timestamp' => time()
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $postData
        ]
    ]);
    
    $response = file_get_contents('http://localhost/ay%20haga/api/chat.php', false, $context);
    
    if ($response === false) {
        echo "❌ Failed to call API<br>";
    } else {
        $data = json_decode($response, true);
        if ($data && isset($data['success'])) {
            echo "✅ API call successful<br>";
            echo "Success: " . ($data['success'] ? 'true' : 'false') . "<br>";
            if (isset($data['response'])) {
                echo "Response preview: " . substr($data['response'], 0, 50) . "...<br>";
            }
            if (isset($data['error'])) {
                echo "Error: " . $data['error'] . "<br>";
            }
        } else {
            echo "❌ Invalid JSON response<br>";
            echo "Raw response: " . htmlspecialchars($response) . "<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ API call error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='chat.php'>Go to Chat Page</a></p>";
echo "<p><a href='index.php'>Go to Homepage</a></p>";
?>
