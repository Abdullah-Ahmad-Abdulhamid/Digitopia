-- Database Structure for Maharati Platform
-- Character set: UTF-8 for Arabic support

CREATE DATABASE IF NOT EXISTS maharati_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE maharati_db;

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    age INT,
    governorate VARCHAR(50),
    education_level ENUM('secondary', 'university', 'graduate', 'postgraduate'),
    current_income DECIMAL(10,2) DEFAULT 0.00,
    subscription_type ENUM('free', 'basic', 'premium') DEFAULT 'free',
    subscription_expires DATE,
    email_verified BOOLEAN DEFAULT FALSE,
    email_verification_token VARCHAR(100),
    password_reset_token VARCHAR(100),
    password_reset_expires TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_subscription (subscription_type, subscription_expires),
    INDEX idx_active (is_active)
);

-- Skills Categories
CREATE TABLE skill_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_ar VARCHAR(100) NOT NULL,
    description_ar TEXT,
    icon_class VARCHAR(50),
    color_code VARCHAR(7),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order),
    INDEX idx_active (is_active)
);

-- Individual Skills
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title_ar VARCHAR(150) NOT NULL,
    description_ar TEXT,
    content_ar LONGTEXT,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced'),
    estimated_time INT, -- in minutes
    practical_examples TEXT,
    egyptian_context TEXT,
    icon_name VARCHAR(50),
    is_premium BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    completion_rate DECIMAL(5,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES skill_categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_difficulty (difficulty_level),
    INDEX idx_premium (is_premium),
    INDEX idx_views (views_count),
    INDEX idx_active (is_active)
);

-- User Progress Tracking
CREATE TABLE user_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    completed_at TIMESTAMP NULL,
    time_spent INT DEFAULT 0, -- in minutes
    notes TEXT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_skill (user_id, skill_id),
    INDEX idx_user_progress (user_id, progress_percentage),
    INDEX idx_skill_progress (skill_id, progress_percentage),
    INDEX idx_completed (completed_at),
    INDEX idx_last_accessed (last_accessed)
);

-- Chat Conversations with Gemini
CREATE TABLE chat_conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_id VARCHAR(100) NOT NULL,
    message_type ENUM('user', 'bot') NOT NULL,
    message_content TEXT NOT NULL,
    skill_context INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_context) REFERENCES skills(id) ON DELETE SET NULL,
    INDEX idx_user_session (user_id, session_id),
    INDEX idx_created (created_at),
    INDEX idx_skill_context (skill_context)
);

-- Daily Challenges
CREATE TABLE daily_challenges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title_ar VARCHAR(200) NOT NULL,
    description_ar TEXT NOT NULL,
    challenge_date DATE NOT NULL,
    skill_category_id INT,
    difficulty ENUM('easy', 'medium', 'hard'),
    reward_points INT DEFAULT 10,
    egyptian_example TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (skill_category_id) REFERENCES skill_categories(id) ON DELETE SET NULL,
    UNIQUE KEY unique_date (challenge_date),
    INDEX idx_date (challenge_date),
    INDEX idx_category (skill_category_id),
    INDEX idx_difficulty (difficulty),
    INDEX idx_active (is_active)
);

-- User Challenge Completions
CREATE TABLE user_challenges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    challenge_id INT NOT NULL,
    completed_at TIMESTAMP NULL,
    user_response TEXT,
    earned_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES daily_challenges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_challenge (user_id, challenge_id),
    INDEX idx_user_completed (user_id, completed_at),
    INDEX idx_challenge (challenge_id),
    INDEX idx_points (earned_points)
);

-- User Points and Achievements
CREATE TABLE user_points (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_points INT DEFAULT 0,
    level_name VARCHAR(50) DEFAULT 'مبتدئ',
    achievements JSON,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id),
    INDEX idx_points (total_points)
);

-- System Settings
CREATE TABLE system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description_ar TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
);
