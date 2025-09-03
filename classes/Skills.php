<?php
// Skills Management Class for Maharati Platform

class Skills {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getCategories() {
        try {
            $stmt = $this->db->query("
                SELECT c.*, COUNT(s.id) as skills_count
                FROM skill_categories c
                LEFT JOIN skills s ON c.id = s.category_id AND s.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC
            ");
            
            $categories = $stmt->fetchAll();
            return ['success' => true, 'data' => $categories];
        } catch (Exception $e) {
            logError("Get categories error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب التصنيفات'];
        }
    }
    
    public function getSkills($categoryId = null, $limit = 20, $offset = 0, $userId = null) {
        try {
            $whereClause = "WHERE s.is_active = 1";
            $params = [];
            
            if ($categoryId) {
                $whereClause .= " AND s.category_id = ?";
                $params[] = $categoryId;
            }
            
            $userProgressJoin = "";
            $userProgressSelect = "";
            if ($userId) {
                $userProgressJoin = "LEFT JOIN user_progress up ON s.id = up.skill_id AND up.user_id = ?";
                $userProgressSelect = ", up.progress_percentage as user_progress, up.completed_at as user_completed";
                $params[] = $userId;
            }
            
            $params = array_merge($params, [$limit, $offset]);
            
            $stmt = $this->db->prepare("
                SELECT s.*, c.name_ar as category_name,
                       AVG(up_all.progress_percentage) as avg_progress,
                       COUNT(DISTINCT up_all.user_id) as learners_count
                       $userProgressSelect
                FROM skills s
                LEFT JOIN skill_categories c ON s.category_id = c.id
                LEFT JOIN user_progress up_all ON s.id = up_all.skill_id
                $userProgressJoin
                $whereClause
                GROUP BY s.id
                ORDER BY s.views_count DESC
                LIMIT ? OFFSET ?
            ");
            
            $stmt->execute($params);
            $skills = $stmt->fetchAll();
            
            return ['success' => true, 'data' => $skills];
        } catch (Exception $e) {
            logError("Get skills error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب المهارات'];
        }
    }
    
    public function getSkill($skillId, $userId = null) {
        try {
            // Get skill details
            $stmt = $this->db->prepare("
                SELECT s.*, c.name_ar as category_name, c.color_code as category_color
                FROM skills s
                LEFT JOIN skill_categories c ON s.category_id = c.id
                WHERE s.id = ? AND s.is_active = 1
            ");
            $stmt->execute([$skillId]);
            $skill = $stmt->fetch();
            
            if (!$skill) {
                return ['success' => false, 'error' => 'المهارة غير موجودة'];
            }
            
            // Update view count
            $this->incrementViewCount($skillId);
            
            // Get user progress if logged in
            if ($userId) {
                $stmt = $this->db->prepare("
                    SELECT progress_percentage, completed_at, time_spent, rating, notes
                    FROM user_progress 
                    WHERE user_id = ? AND skill_id = ?
                ");
                $stmt->execute([$userId, $skillId]);
                $skill['user_progress'] = $stmt->fetch();
            }
            
            // Get related skills
            $skill['related_skills'] = $this->getRelatedSkills($skill['category_id'], $skillId, 3);
            
            return ['success' => true, 'data' => $skill];
        } catch (Exception $e) {
            logError("Get skill error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب المهارة'];
        }
    }
    
    public function updateProgress($userId, $skillId, $progress, $timeSpent = 0) {
        try {
            $completedAt = ($progress >= 100) ? 'NOW()' : 'NULL';
            
            $stmt = $this->db->prepare("
                INSERT INTO user_progress (user_id, skill_id, progress_percentage, time_spent, completed_at)
                VALUES (?, ?, ?, ?, $completedAt)
                ON DUPLICATE KEY UPDATE 
                    progress_percentage = VALUES(progress_percentage),
                    time_spent = time_spent + VALUES(time_spent),
                    completed_at = CASE WHEN VALUES(progress_percentage) >= 100 THEN NOW() ELSE completed_at END,
                    last_accessed = NOW()
            ");
            
            $result = $stmt->execute([$userId, $skillId, $progress, $timeSpent]);
            
            if ($result) {
                // Award points if skill completed
                if ($progress >= 100) {
                    $this->awardCompletionPoints($userId, $skillId);
                }
                
                return ['success' => true, 'message' => 'تم تحديث التقدم بنجاح'];
            }
            
            return ['success' => false, 'error' => 'خطأ في تحديث التقدم'];
        } catch (Exception $e) {
            logError("Update progress error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في تحديث التقدم'];
        }
    }
    
    public function rateSkill($userId, $skillId, $rating) {
        try {
            if ($rating < 1 || $rating > 5) {
                return ['success' => false, 'error' => 'التقييم يجب أن يكون بين 1 و 5'];
            }
            
            $stmt = $this->db->prepare("
                UPDATE user_progress 
                SET rating = ? 
                WHERE user_id = ? AND skill_id = ?
            ");
            
            $result = $stmt->execute([$rating, $userId, $skillId]);
            
            if ($result && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'تم تقييم المهارة بنجاح'];
            }
            
            return ['success' => false, 'error' => 'لا يمكن تقييم مهارة لم تبدأ بتعلمها'];
        } catch (Exception $e) {
            logError("Rate skill error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في التقييم'];
        }
    }
    
    public function getUserProgress($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT s.id, s.title_ar, s.icon_name, s.difficulty_level,
                       up.progress_percentage, up.completed_at, up.time_spent, up.rating,
                       c.name_ar as category_name, c.color_code as category_color
                FROM user_progress up
                JOIN skills s ON up.skill_id = s.id
                JOIN skill_categories c ON s.category_id = c.id
                WHERE up.user_id = ? AND s.is_active = 1
                ORDER BY up.last_accessed DESC
            ");
            $stmt->execute([$userId]);
            
            $progress = $stmt->fetchAll();
            
            // Calculate statistics
            $stats = $this->calculateUserStats($userId);
            
            return [
                'success' => true, 
                'data' => [
                    'progress' => $progress,
                    'stats' => $stats
                ]
            ];
        } catch (Exception $e) {
            logError("Get user progress error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في جلب التقدم'];
        }
    }
    
    public function searchSkills($query, $limit = 10) {
        try {
            $searchTerm = '%' . sanitizeInput($query) . '%';
            
            $stmt = $this->db->prepare("
                SELECT s.id, s.title_ar, s.description_ar, s.icon_name,
                       c.name_ar as category_name, c.color_code as category_color
                FROM skills s
                LEFT JOIN skill_categories c ON s.category_id = c.id
                WHERE s.is_active = 1 
                AND (s.title_ar LIKE ? OR s.description_ar LIKE ? OR s.practical_examples LIKE ?)
                ORDER BY s.views_count DESC
                LIMIT ?
            ");
            
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
            $results = $stmt->fetchAll();
            
            return ['success' => true, 'data' => $results];
        } catch (Exception $e) {
            logError("Search skills error: " . $e->getMessage());
            return ['success' => false, 'error' => 'خطأ في البحث'];
        }
    }
    
    private function incrementViewCount($skillId) {
        try {
            $stmt = $this->db->prepare("UPDATE skills SET views_count = views_count + 1 WHERE id = ?");
            $stmt->execute([$skillId]);
        } catch (Exception $e) {
            logError("Increment view count error: " . $e->getMessage());
        }
    }
    
    private function getRelatedSkills($categoryId, $excludeSkillId, $limit = 3) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, title_ar, icon_name, difficulty_level
                FROM skills 
                WHERE category_id = ? AND id != ? AND is_active = 1
                ORDER BY views_count DESC
                LIMIT ?
            ");
            $stmt->execute([$categoryId, $excludeSkillId, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            logError("Get related skills error: " . $e->getMessage());
            return [];
        }
    }
    
    private function awardCompletionPoints($userId, $skillId) {
        try {
            // Get skill difficulty to determine points
            $stmt = $this->db->prepare("SELECT difficulty_level FROM skills WHERE id = ?");
            $stmt->execute([$skillId]);
            $skill = $stmt->fetch();
            
            $points = 10; // Default points
            if ($skill) {
                switch ($skill['difficulty_level']) {
                    case 'beginner': $points = 10; break;
                    case 'intermediate': $points = 20; break;
                    case 'advanced': $points = 30; break;
                }
            }
            
            // Update user points
            $stmt = $this->db->prepare("
                UPDATE user_points 
                SET total_points = total_points + ? 
                WHERE user_id = ?
            ");
            $stmt->execute([$points, $userId]);
            
            // Update level if necessary
            $this->updateUserLevel($userId);
            
        } catch (Exception $e) {
            logError("Award completion points error: " . $e->getMessage());
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
    
    private function calculateUserStats($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_skills,
                    COUNT(CASE WHEN progress_percentage >= 100 THEN 1 END) as completed_skills,
                    AVG(progress_percentage) as avg_progress,
                    SUM(time_spent) as total_time,
                    AVG(rating) as avg_rating
                FROM user_progress 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $stats = $stmt->fetch();
            
            // Get user points and level
            $stmt = $this->db->prepare("SELECT total_points, level_name FROM user_points WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pointsData = $stmt->fetch();
            
            return [
                'total_skills' => intval($stats['total_skills']),
                'completed_skills' => intval($stats['completed_skills']),
                'avg_progress' => round(floatval($stats['avg_progress']), 1),
                'total_time' => intval($stats['total_time']),
                'avg_rating' => round(floatval($stats['avg_rating']), 1),
                'total_points' => intval($pointsData['total_points'] ?? 0),
                'level_name' => $pointsData['level_name'] ?? 'مبتدئ'
            ];
        } catch (Exception $e) {
            logError("Calculate user stats error: " . $e->getMessage());
            return [];
        }
    }
}
?>
