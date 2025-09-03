<?php
// Challenges Management Class for Maharati Platform

class Challenges {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getDailyChallenge($date = null, $userId = null) {
        try {
            $targetDate = $date ?: date('Y-m-d');
            
            $stmt = $this->db->prepare("
                SELECT dc.*, c.name_ar as category_name, c.color_code as category_color
                FROM daily_challenges dc
                LEFT JOIN skill_categories c ON dc.skill_category_id = c.id
                WHERE dc.challenge_date = ? AND dc.is_active = 1
            ");
            $stmt->execute([$targetDate]);
            
            $challenge = $stmt->fetch();
            
            if (!$challenge) {
                // Generate a challenge for today if none exists
                $challenge = $this->generateDailyChallenge($targetDate);
            }
            
            // Check if user completed the challenge
            if ($challenge && $userId) {
                $stmt = $this->db->prepare("
                    SELECT completed_at, earned_points, user_response
                    FROM user_challenges 
                    WHERE user_id = ? AND challenge_id = ?
                ");
                $stmt->execute([$userId, $challenge['id']]);
                $challenge['user_completion'] = $stmt->fetch();
            }
            
            return ['success' => true, 'data' => $challenge];
        } catch (Exception $e) {
            logError("Get daily challenge error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب التحدي'];
        }
    }
    
    public function completeChallenge($userId, $challengeId, $userResponse = '') {
        try {
            // Get challenge details
            $stmt = $this->db->prepare("
                SELECT reward_points, challenge_date 
                FROM daily_challenges 
                WHERE id = ? AND is_active = 1
            ");
            $stmt->execute([$challengeId]);
            $challenge = $stmt->fetch();
            
            if (!$challenge) {
                return ['success' => false, 'error' => 'التحدي غير موجود'];
            }
            
            // Check if already completed
            $stmt = $this->db->prepare("
                SELECT id FROM user_challenges 
                WHERE user_id = ? AND challenge_id = ?
            ");
            $stmt->execute([$userId, $challengeId]);
            
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'تم إكمال هذا التحدي بالفعل'];
            }
            
            // Complete the challenge
            $stmt = $this->db->prepare("
                INSERT INTO user_challenges (user_id, challenge_id, completed_at, user_response, earned_points)
                VALUES (?, ?, NOW(), ?, ?)
            ");
            
            $result = $stmt->execute([$userId, $challengeId, $userResponse, $challenge['reward_points']]);
            
            if ($result) {
                // Update user points
                $this->updateUserPoints($userId, $challenge['reward_points']);
                
                return [
                    'success' => true,
                    'message' => 'تم إكمال التحدي بنجاح! 🎉',
                    'earned_points' => $challenge['reward_points']
                ];
            }
            
            return ['success' => false, 'error' => 'خطأ في إكمال التحدي'];
        } catch (Exception $e) {
            logError("Complete challenge error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في إكمال التحدي'];
        }
    }
    
    public function getUserChallengeHistory($userId, $limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT dc.title_ar, dc.challenge_date, dc.difficulty,
                       uc.completed_at, uc.earned_points, uc.user_response,
                       c.name_ar as category_name, c.color_code as category_color
                FROM user_challenges uc
                JOIN daily_challenges dc ON uc.challenge_id = dc.id
                LEFT JOIN skill_categories c ON dc.skill_category_id = c.id
                WHERE uc.user_id = ?
                ORDER BY uc.completed_at DESC
                LIMIT ?
            ");
            $stmt->execute([$userId, $limit]);
            
            $history = $stmt->fetchAll();
            
            // Calculate stats
            $stats = $this->getUserChallengeStats($userId);
            
            return [
                'success' => true, 
                'data' => [
                    'history' => $history,
                    'stats' => $stats
                ]
            ];
        } catch (Exception $e) {
            logError("Get user challenge history error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب تاريخ التحديات'];
        }
    }
    
    public function getWeeklyChallenges($userId = null) {
        try {
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
            
            $stmt = $this->db->prepare("
                SELECT dc.*, c.name_ar as category_name, c.color_code as category_color
                FROM daily_challenges dc
                LEFT JOIN skill_categories c ON dc.skill_category_id = c.id
                WHERE dc.challenge_date BETWEEN ? AND ? AND dc.is_active = 1
                ORDER BY dc.challenge_date ASC
            ");
            $stmt->execute([$startDate, $endDate]);
            
            $challenges = $stmt->fetchAll();
            
            // Add completion status for user
            if ($userId && !empty($challenges)) {
                $challengeIds = array_column($challenges, 'id');
                $placeholders = str_repeat('?,', count($challengeIds) - 1) . '?';
                
                $stmt = $this->db->prepare("
                    SELECT challenge_id, completed_at, earned_points
                    FROM user_challenges 
                    WHERE user_id = ? AND challenge_id IN ($placeholders)
                ");
                $stmt->execute(array_merge([$userId], $challengeIds));
                
                $completions = [];
                while ($row = $stmt->fetch()) {
                    $completions[$row['challenge_id']] = $row;
                }
                
                foreach ($challenges as &$challenge) {
                    $challenge['user_completion'] = $completions[$challenge['id']] ?? null;
                }
            }
            
            return ['success' => true, 'data' => $challenges];
        } catch (Exception $e) {
            logError("Get weekly challenges error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب تحديات الأسبوع'];
        }
    }
    
    private function generateDailyChallenge($date) {
        try {
            $challenges = [
                [
                    'title_ar' => 'تحدي الادخار اليومي',
                    'description_ar' => 'احسب كم يمكنك توفيره إذا قللت مصروف المواصلات بـ 5 جنيه يومياً لمدة شهر',
                    'difficulty' => 'easy',
                    'reward_points' => 10,
                    'egyptian_example' => 'لو بتدفع 15 جنيه مواصلات يومياً، جرب تمشي جزء من الطريق أو استخدم وسيلة أرخص'
                ],
                [
                    'title_ar' => 'تحدي التواصل الإيجابي',
                    'description_ar' => 'اتصل بصديق أو قريب لم تتحدث معه منذ فترة واسأل عن أحواله',
                    'difficulty' => 'easy',
                    'reward_points' => 15,
                    'egyptian_example' => 'ممكن تكلم زميل من الجامعة أو الشغل، أو حد من الأهل مش بتكلمه كتير'
                ],
                [
                    'title_ar' => 'تحدي تنظيم الوقت',
                    'description_ar' => 'اكتب قائمة بـ 5 مهام تريد إنجازها غداً ورتبها حسب الأولوية',
                    'difficulty' => 'medium',
                    'reward_points' => 20,
                    'egyptian_example' => 'مثلاً: دفع فاتورة الكهرباء، شراء احتياجات البيت، مراجعة السيرة الذاتية'
                ],
                [
                    'title_ar' => 'تحدي التعلم الجديد',
                    'description_ar' => 'تعلم 5 كلمات جديدة باللغة الإنجليزية واستخدمها في جمل',
                    'difficulty' => 'medium',
                    'reward_points' => 25,
                    'egyptian_example' => 'ركز على كلمات مفيدة في العمل أو الحياة اليومية'
                ],
                [
                    'title_ar' => 'تحدي الصحة النفسية',
                    'description_ar' => 'اكتب 3 أشياء إيجابية حدثت لك اليوم واشكر نفسك عليها',
                    'difficulty' => 'easy',
                    'reward_points' => 15,
                    'egyptian_example' => 'حتى لو كانت أشياء بسيطة زي إنك صحيت بدري أو ساعدت حد'
                ]
            ];
            
            // Select random challenge
            $randomChallenge = $challenges[array_rand($challenges)];
            
            // Get random category
            $stmt = $this->db->query("SELECT id FROM skill_categories WHERE is_active = 1 ORDER BY RAND() LIMIT 1");
            $category = $stmt->fetch();
            
            // Insert challenge
            $stmt = $this->db->prepare("
                INSERT INTO daily_challenges (title_ar, description_ar, challenge_date, 
                                            skill_category_id, difficulty, reward_points, egyptian_example)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $randomChallenge['title_ar'],
                $randomChallenge['description_ar'],
                $date,
                $category['id'] ?? null,
                $randomChallenge['difficulty'],
                $randomChallenge['reward_points'],
                $randomChallenge['egyptian_example']
            ]);
            
            if ($result) {
                $challengeId = $this->db->lastInsertId();
                
                // Return the created challenge
                $stmt = $this->db->prepare("
                    SELECT dc.*, c.name_ar as category_name, c.color_code as category_color
                    FROM daily_challenges dc
                    LEFT JOIN skill_categories c ON dc.skill_category_id = c.id
                    WHERE dc.id = ?
                ");
                $stmt->execute([$challengeId]);
                return $stmt->fetch();
            }
            
            return null;
        } catch (Exception $e) {
            logError("Generate daily challenge error: " . $e->getMessage());
            return null;
        }
    }
    
    private function updateUserPoints($userId, $points) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO user_points (user_id, total_points) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE total_points = total_points + VALUES(total_points)
            ");
            $stmt->execute([$userId, $points]);
            
            // Update level if necessary
            $this->updateUserLevel($userId);
        } catch (Exception $e) {
            logError("Update user points error: " . $e->getMessage());
        }
    }
    
    private function updateUserLevel($userId) {
        try {
            $stmt = $this->db->prepare("SELECT total_points FROM user_points WHERE user_id = ?");
            $stmt->execute([$userId]);
            $userPoints = $stmt->fetch();
            
            if ($userPoints) {
                $points = $userPoints['total_points'];
                $level = 'مبتدئ';
                
                if ($points >= 500) $level = 'خبير';
                elseif ($points >= 200) $level = 'متقدم';
                elseif ($points >= 50) $level = 'متوسط';
                
                $stmt = $this->db->prepare("UPDATE user_points SET level_name = ? WHERE user_id = ?");
                $stmt->execute([$level, $userId]);
            }
        } catch (Exception $e) {
            logError("Update user level error: " . $e->getMessage());
        }
    }
    
    private function getUserChallengeStats($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_completed,
                    SUM(earned_points) as total_points_earned,
                    COUNT(CASE WHEN DATE(completed_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as completed_this_week,
                    COUNT(CASE WHEN DATE(completed_at) = CURDATE() THEN 1 END) as completed_today
                FROM user_challenges 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $stats = $stmt->fetch();
            
            return [
                'total_completed' => intval($stats['total_completed']),
                'total_points_earned' => intval($stats['total_points_earned']),
                'completed_this_week' => intval($stats['completed_this_week']),
                'completed_today' => intval($stats['completed_today'])
            ];
        } catch (Exception $e) {
            logError("Get user challenge stats error: " . $e->getMessage());
            return [];
        }
    }
}
?>
