<?php
// Gemini AI Integration Class for Maharati Platform

class GeminiAI {
    private $apiKey;
    private $apiUrl;
    private $basePrompt;
    
    public function __construct() {
        $this->apiKey = GEMINI_API_KEY;
        $this->apiUrl = GEMINI_API_URL;
        $this->basePrompt = $this->getBasePrompt();
    }
    
    public function generateResponse($userMessage, $userProfile = [], $conversationHistory = []) {
        if (empty($this->apiKey) || $this->apiKey === 'AIzaSyARwfE40onw7xRsNUOFQhrVRUd0vd7qTBg') {
            return "عذراً، خدمة الذكاء الاصطناعي غير متاحة حالياً. يرجى التواصل مع الإدارة.";
        }
        
        $contextualPrompt = $this->buildContextualPrompt($userMessage, $userProfile, $conversationHistory);
        
        $requestData = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $contextualPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 1024,
                'stopSequences' => []
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . '?key=' . $this->apiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($requestData, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Maharati Platform/1.0'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            logError("Gemini API cURL Error: " . $error);
            return "عذراً، حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.";
        }
        
        if ($httpCode !== 200) {
            logError("Gemini API HTTP Error: " . $httpCode . " - " . $response);
            return "عذراً، الخدمة غير متاحة حالياً. يرجى المحاولة لاحقاً.";
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $aiResponse = $decodedResponse['candidates'][0]['content']['parts'][0]['text'];
            return $this->postProcessResponse($aiResponse);
        }
        
        if (isset($decodedResponse['error'])) {
            logError("Gemini API Error: " . json_encode($decodedResponse['error']));
            return "عذراً، حدث خطأ في معالجة طلبك. يرجى إعادة صياغة السؤال.";
        }
        
        return "عذراً، لم أتمكن من فهم سؤالك. يمكنك إعادة صياغته؟";
    }
    
    private function buildContextualPrompt($userMessage, $userProfile, $conversationHistory) {
        $contextInfo = "";
        
        // Add user profile context
        if (!empty($userProfile)) {
            $contextInfo .= "\nمعلومات المستخدم:\n";
            if (isset($userProfile['name'])) $contextInfo .= "- الاسم: " . $userProfile['name'] . "\n";
            if (isset($userProfile['age'])) $contextInfo .= "- العمر: " . $userProfile['age'] . " سنة\n";
            if (isset($userProfile['governorate'])) $contextInfo .= "- المحافظة: " . $userProfile['governorate'] . "\n";
            if (isset($userProfile['current_income'])) $contextInfo .= "- الراتب الشهري: " . $userProfile['current_income'] . " جنيه\n";
            if (isset($userProfile['education_level'])) {
                $educationLevels = [
                    'secondary' => 'ثانوية عامة',
                    'university' => 'جامعي',
                    'graduate' => 'خريج',
                    'postgraduate' => 'دراسات عليا'
                ];
                $contextInfo .= "- المستوى التعليمي: " . ($educationLevels[$userProfile['education_level']] ?? $userProfile['education_level']) . "\n";
            }
        }
        
        // Add conversation history
        if (!empty($conversationHistory)) {
            $contextInfo .= "\nالمحادثات السابقة:\n";
            foreach (array_slice($conversationHistory, -5) as $msg) { // Last 5 messages
                $msgType = $msg['message_type'] === 'user' ? 'المستخدم' : 'المساعد';
                $contextInfo .= "- " . $msgType . ": " . substr($msg['message_content'], 0, 100) . "\n";
            }
        }
        
        return $this->basePrompt . $contextInfo . "\n\nرسالة المستخدم الحالية: " . $userMessage;
    }
    
    private function getBasePrompt() {
        return "أنت 'مهاراتي بوت' - مساعد ذكي متخصص في تعليم المهارات الحياتية للشباب المصري (13-25 سنة).

الهوية والشخصية:
- اسمك: مساعد مهاراتي
- الشخصية: ودود، صبور، محفز، يتحدث بأسلوب شبابي مصري معاصر
- تستخدم أمثلة من الحياة المصرية اليومية
- تراعي الثقافة والتقاليد المصرية
- تتحدث بالعربية الفصحى المبسطة مع لمسات من العامية المصرية عند الحاجة

القواعد الأساسية:
1. ابدأ كل رد بالسلام والترحيب باسم المستخدم إذا كان متاحاً
2. استخدم الأمثلة المصرية دائماً (راتب بالجنيه، أحياء مصرية، مشاكل محلية)
3. اذكر أيقونات ومواضع الوضع الاجتماعي المصري
4. قدم حلول عملية قابلة للتطبيق فوراً
5. اختتم كل رد بسؤال لإشراك المستخدم أكثر
6. استخدم الرموز التعبيرية المناسبة للثقافة المصرية 🇪🇬

مجالات التخصص:
- إدارة المال والادخار (بالجنيه المصري)
- مهارات العمل في السوق المصري
- التواصل والعلاقات الاجتماعية
- الصحة النفسية وإدارة الضغوط
- التعامل مع البيروقراطية الحكومية

أسلوب الرد:
- اجعل الرد بين 50-150 كلمة
- استخدم نقاط مرقمة للخطوات العملية
- اذكر مثال عملي في كل رد
- اقترح تحدي يومي بسيط

تجنب:
- النصائح العامة غير العملية
- المصطلحات الأكاديمية المعقدة  
- التحدث عن الأوضاع السياسية
- إعطاء نصائح طبية متخصصة";
    }
    
    private function postProcessResponse($response) {
        // Clean up the response
        $response = trim($response);
        
        // Add Egyptian context if missing
        if (!preg_match('/جنيه|مصر|القاهرة|الإسكندرية/', $response)) {
            // Add a subtle Egyptian touch if none exists
            $response = str_replace('دولار', 'جنيه', $response);
        }
        
        // Ensure proper Arabic formatting
        $response = preg_replace('/\s+/', ' ', $response);
        
        return $response;
    }
    
    public function testConnection() {
        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return false;
        }
        
        $testMessage = "مرحبا";
        $response = $this->generateResponse($testMessage);
        
        return !in_array($response, [
            "عذراً، حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.",
            "عذراً، الخدمة غير متاحة حالياً. يرجى المحاولة لاحقاً.",
            "عذراً، خدمة الذكاء الاصطناعي غير متاحة حالياً. يرجى التواصل مع الإدارة."
        ]);
    }
}
?>
