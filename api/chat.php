<?php
require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['message']) || empty(trim($input['message']))) {
    jsonResponse(['success' => false, 'error' => 'رسالة فارغة'], 400);
}

try {
    // Log the incoming request for debugging
    error_log("Chat API: Received message: " . $input['message']);
    $db = Database::getInstance()->getConnection();
    
    // Get user info if logged in
    $userProfile = [];
    $userId = null;
    if (isLoggedIn()) {
        $userId = $_SESSION['user_id'];
        $stmt = $db->prepare("SELECT name, age, governorate, current_income, education_level FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $userProfile = $stmt->fetch() ?: [];
    }
    
    // Get conversation history
    $conversationHistory = [];
    if (isset($input['session_id']) && $userId) {
        $stmt = $db->prepare("
            SELECT message_type, message_content 
            FROM chat_conversations 
            WHERE user_id = ? AND session_id = ? 
            ORDER BY created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$userId, $input['session_id']]);
        $conversationHistory = array_reverse($stmt->fetchAll());
    }
    
    // Initialize Gemini AI
    $gemini = new GeminiAI();
    
    // Test connection first
    if (!$gemini->testConnection()) {
        // Fallback response when AI is not available
        $response = getFallbackResponse($input['message']);
    } else {
        $response = $gemini->generateResponse($input['message'], $userProfile, $conversationHistory);
    }
    
    // Save conversation to database if user is logged in
    if ($userId && isset($input['session_id'])) {
        try {
            // Save user message
            $stmt = $db->prepare("
                INSERT INTO chat_conversations (user_id, session_id, message_type, message_content) 
                VALUES (?, ?, 'user', ?)
            ");
            $stmt->execute([$userId, $input['session_id'], $input['message']]);
            
            // Save bot response
            $stmt = $db->prepare("
                INSERT INTO chat_conversations (user_id, session_id, message_type, message_content) 
                VALUES (?, ?, 'bot', ?)
            ");
            $stmt->execute([$userId, $input['session_id'], $response]);
        } catch (Exception $e) {
            logError("Failed to save chat conversation: " . $e->getMessage());
        }
    }
    
    // Add typing delay for better UX
    usleep(500000); // 0.5 second delay
    
    jsonResponse([
        'success' => true,
        'response' => $response,
        'timestamp' => date('c'),
        'session_id' => $input['session_id'] ?? 'anonymous'
    ]);
    
} catch (Exception $e) {
    error_log("Chat API Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'عذراً، حدث خطأ في الخدمة: ' . $e->getMessage()], 500);
} catch (Error $e) {
    error_log("Chat API Fatal Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'خطأ فادح في الخدمة: ' . $e->getMessage()], 500);
}

function getFallbackResponse($message) {
    $message = strtolower(trim($message));

    // Simple keyword-based responses
    if (strpos($message, 'مرحبا') !== false || strpos($message, 'السلام') !== false || strpos($message, 'هلا') !== false || strpos($message, 'أهلا') !== false) {
        return "مرحباً بك في منصة مهاراتي! 🇪🇬\n\nأنا مساعدك الذكي لتعلم المهارات الحياتية. يمكنني مساعدتك في:\n\n💰 إدارة الأموال والادخار\n💼 مهارات العمل والوظائف\n🗣️ التواصل والعلاقات\n🧠 الصحة النفسية\n\nما المهارة التي تود تعلمها اليوم؟";
    }

    if (strpos($message, 'ازيك') !== false || strpos($message, 'عامل ايه') !== false || strpos($message, 'كيفك') !== false) {
        return "الحمد لله، كله تمام! 😊\n\nأنا هنا عشان أساعدك تتعلم مهارات جديدة وتطور نفسك.\n\nإيه اللي محتاج مساعدة فيه النهاردة؟\n\n💡 جرب تكتب: مال، شغل، تواصل، أو صحة نفسية";
    }
    
    if (strpos($message, 'مال') !== false || strpos($message, 'فلوس') !== false || strpos($message, 'ادخار') !== false) {
        return "إدارة المال مهارة مهمة جداً! 💰\n\nإليك بعض النصائح السريعة:\n\n1. اكتب مصروفاتك يومياً لمدة أسبوع\n2. قسم راتبك: 50% احتياجات، 30% رغبات، 20% ادخار\n3. ابدأ بادخار 10 جنيه يومياً\n\nمثال: لو راتبك 3000 جنيه، وفر 600 جنيه شهرياً\n\nعايز تعرف أكتر عن موضوع معين في إدارة المال؟";
    }
    
    if (strpos($message, 'شغل') !== false || strpos($message, 'وظيفة') !== false || strpos($message, 'عمل') !== false) {
        return "البحث عن شغل في مصر محتاج استراتيجية! 💼\n\nخطوات مهمة:\n\n1. اعمل CV قوي باللغتين العربي والإنجليزي\n2. استخدم LinkedIn و Wuzzuf\n3. اتدرب على أسئلة المقابلات\n4. اعرف راتب المجال بتاعك\n\nمثال: مطور ويب مبتدئ في القاهرة: 4000-7000 جنيه\n\nعايز مساعدة في إيه بالضبط؟";
    }
    
    if (strpos($message, 'تواصل') !== false || strpos($message, 'علاقات') !== false) {
        return "التواصل الفعال أساس النجاح! 🗣️\n\nنصائح للتواصل الجيد:\n\n1. اسمع أكتر ما تتكلم\n2. اسأل أسئلة مفتوحة\n3. استخدم لغة الجسد الإيجابية\n4. تجنب الانتقاد المباشر\n\nمثال: بدل 'إنت غلطان' قول 'ممكن نشوف حل تاني؟'\n\nإيه التحدي اللي بتواجهه في التواصل؟";
    }

    if (strpos($message, 'صحة نفسية') !== false || strpos($message, 'قلق') !== false || strpos($message, 'توتر') !== false) {
        return "الصحة النفسية مهمة زي الصحة الجسدية! 🧠💚\n\nنصائح للصحة النفسية:\n\n1. مارس الرياضة 30 دقيقة يومياً\n2. نام 7-8 ساعات\n3. اتكلم مع حد تثق فيه\n4. مارس تمارين التنفس\n\nتمرين سريع: خد نفس عميق لـ4 ثواني، احبسه لـ4، اطلعه في 4\n\nمحتاج مساعدة في إيه بالضبط؟";
    }

    if (strpos($message, 'توفير') !== false || strpos($message, 'ادخار') !== false || strpos($message, 'فلوس') !== false) {
        return "التوفير مهارة مهمة جداً! 💰\n\nخطة التوفير السريعة:\n\n1. اكتب كل مصروفاتك لمدة أسبوع\n2. قسم راتبك: 50% ضروريات، 30% رغبات، 20% ادخار\n3. ابدأ بـ10 جنيه يومياً\n4. استخدم حصالة أو حساب منفصل\n\nمثال: راتب 3000 جنيه = وفر 600 جنيه شهرياً\n\nعايز خطة مخصوصة لراتبك؟";
    }

    if (strpos($message, 'شغال') !== false || strpos($message, 'تعمل') !== false || strpos($message, 'بتشتغل') !== false) {
        return "أيوة، أنا شغال وجاهز أساعدك! 🤖✨\n\nأنا مساعد ذكي متخصص في:\n\n💰 المهارات المالية\n💼 تطوير المهنة\n🗣️ مهارات التواصل\n🧠 الصحة النفسية\n\nقولي محتاج إيه وهساعدك فوراً!";
    }

    // Default response
    return "شكراً لك على رسالتك! 😊\n\nحالياً الذكاء الاصطناعي المتقدم غير متاح، لكن يمكنني مساعدتك بـ:\n\n📚 نصائح سريعة للمهارات\n🎯 حل التحدي اليومي\n📈 متابعة تقدمك\n\nجرب كتابة: مال، شغل، تواصل، صحة نفسية، أو توفير";
}
?>
