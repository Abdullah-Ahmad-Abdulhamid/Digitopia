<?php
// User Management Class for Maharati Platform

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function register($data) {
        try {
            // Validate input
            $errors = $this->validateRegistrationData($data);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }
            
            // Check if email already exists
            if ($this->emailExists($data['email'])) {
                return ['success' => false, 'errors' => ['email' => 'البريد الإلكتروني مستخدم بالفعل']];
            }
            
            // Hash password
            $passwordHash = hashPassword($data['password']);
            
            // Generate email verification token
            $verificationToken = generateToken();
            
            // Insert user
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password_hash, phone, age, governorate, 
                                 education_level, current_income, email_verification_token) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                sanitizeInput($data['name']),
                sanitizeInput($data['email']),
                $passwordHash,
                sanitizeInput($data['phone'] ?? ''),
                intval($data['age'] ?? 0),
                sanitizeInput($data['governorate'] ?? ''),
                $data['education_level'] ?? 'university',
                floatval($data['current_income'] ?? 0),
                $verificationToken
            ]);
            
            if ($result) {
                $userId = $this->db->lastInsertId();
                
                // Initialize user points
                $this->initializeUserPoints($userId);
                
                // Send verification email (implement later)
                // $this->sendVerificationEmail($data['email'], $verificationToken);
                
                return [
                    'success' => true, 
                    'user_id' => $userId,
                    'message' => 'تم إنشاء الحساب بنجاح'
                ];
            }
            
            return ['success' => false, 'errors' => ['general' => 'حدث خطأ أثناء إنشاء الحساب']];
            
        } catch (Exception $e) {
            logError("User registration error: " . $e->getMessage());
            return ['success' => false, 'errors' => ['general' => 'حدث خطأ في النظام']];
        }
    }
    
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, password_hash, is_active, subscription_type, subscription_expires
                FROM users 
                WHERE email = ? AND is_active = 1
            ");
            $stmt->execute([sanitizeInput($email)]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'error' => 'البريد الإلكتروني غير مسجل'];
            }
            
            if (!verifyPassword($password, $user['password_hash'])) {
                return ['success' => false, 'error' => 'كلمة المرور غير صحيحة'];
            }
            
            // Update last login
            $this->updateLastLogin($user['id']);
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['subscription_type'] = $user['subscription_type'];
            $_SESSION['subscription_expires'] = $user['subscription_expires'];
            
            return [
                'success' => true, 
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'subscription_type' => $user['subscription_type']
                ]
            ];
            
        } catch (Exception $e) {
            logError("User login error: " . $e->getMessage());
            return ['success' => false, 'error' => 'حدث خطأ في النظام'];
        }
    }
    
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'تم تسجيل الخروج بنجاح'];
    }
    
    public function getUserProfile($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.*, up.total_points, up.level_name
                FROM users u
                LEFT JOIN user_points up ON u.id = up.user_id
                WHERE u.id = ? AND u.is_active = 1
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                unset($user['password_hash']);
                return ['success' => true, 'user' => $user];
            }
            
            return ['success' => false, 'error' => 'المستخدم غير موجود'];
            
        } catch (Exception $e) {
            logError("Get user profile error: " . $e->getMessage());
            return ['success' => false, 'error' => 'حدث خطأ في النظام'];
        }
    }
    
    public function updateProfile($userId, $data) {
        try {
            $allowedFields = ['name', 'phone', 'age', 'governorate', 'education_level', 'current_income'];
            $updateFields = [];
            $updateValues = [];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateFields[] = "$field = ?";
                    $updateValues[] = sanitizeInput($data[$field]);
                }
            }
            
            if (empty($updateFields)) {
                return ['success' => false, 'error' => 'لا توجد بيانات للتحديث'];
            }
            
            $updateValues[] = $userId;
            
            $stmt = $this->db->prepare("
                UPDATE users 
                SET " . implode(', ', $updateFields) . "
                WHERE id = ? AND is_active = 1
            ");
            
            $result = $stmt->execute($updateValues);
            
            if ($result && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'تم تحديث الملف الشخصي بنجاح'];
            }
            
            return ['success' => false, 'error' => 'لم يتم تحديث أي بيانات'];
            
        } catch (Exception $e) {
            logError("Update profile error: " . $e->getMessage());
            return ['success' => false, 'error' => 'حدث خطأ في النظام'];
        }
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Get current password hash
            $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user || !verifyPassword($currentPassword, $user['password_hash'])) {
                return ['success' => false, 'error' => 'كلمة المرور الحالية غير صحيحة'];
            }
            
            // Validate new password
            if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
                return ['success' => false, 'error' => 'كلمة المرور الجديدة قصيرة جداً'];
            }
            
            // Update password
            $newPasswordHash = hashPassword($newPassword);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $result = $stmt->execute([$newPasswordHash, $userId]);
            
            if ($result) {
                return ['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح'];
            }
            
            return ['success' => false, 'error' => 'حدث خطأ أثناء تغيير كلمة المرور'];
            
        } catch (Exception $e) {
            logError("Change password error: " . $e->getMessage());
            return ['success' => false, 'error' => 'حدث خطأ في النظام'];
        }
    }
    
    private function validateRegistrationData($data) {
        $errors = [];
        
        if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
            $errors['name'] = 'الاسم مطلوب ويجب أن يكون أكثر من حرفين';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'البريد الإلكتروني غير صحيح';
        }
        
        if (empty($data['password']) || strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            $errors['password'] = 'كلمة المرور يجب أن تكون ' . PASSWORD_MIN_LENGTH . ' أحرف على الأقل';
        }
        
        if (isset($data['age']) && ($data['age'] < 13 || $data['age'] > 100)) {
            $errors['age'] = 'العمر يجب أن يكون بين 13 و 100 سنة';
        }
        
        return $errors;
    }
    
    private function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([sanitizeInput($email)]);
        return $stmt->fetch() !== false;
    }
    
    private function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }
    
    private function initializeUserPoints($userId) {
        $stmt = $this->db->prepare("
            INSERT INTO user_points (user_id, total_points, level_name) 
            VALUES (?, 0, 'مبتدئ')
        ");
        $stmt->execute([$userId]);
    }
    
    public function hasActiveSubscription($userId) {
        $stmt = $this->db->prepare("
            SELECT subscription_type, subscription_expires 
            FROM users 
            WHERE id = ? AND is_active = 1
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user || $user['subscription_type'] === 'free') {
            return false;
        }
        
        if ($user['subscription_expires'] && $user['subscription_expires'] < date('Y-m-d')) {
            return false;
        }
        
        return true;
    }
}
?>
