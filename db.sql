-- 1. users (minimal — hanya untuk admin, karena pengguna biasa tidak login)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- bcrypt
    role ENUM('admin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. exercise_categories
CREATE TABLE exercise_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,  -- e.g., "Cardio", "Strength"
    slug VARCHAR(100) NOT NULL UNIQUE,  -- untuk URL: /exercises/cardio
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. exercises
CREATE TABLE exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    difficulty ENUM('beginner','intermediate','advanced') NOT NULL,
    duration_estimate VARCHAR(50),  -- e.g., "10-15 menit"
    equipment_needed VARCHAR(255),   -- e.g., "None, Yoga Mat"
    youtube_url VARCHAR(255),        -- validasi format YouTube
    thumbnail VARCHAR(255),          -- path: uploads/exercises/thumb_xxx.jpg
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES exercise_categories(id) ON DELETE CASCADE
);

-- 4. nutrition_categories
CREATE TABLE nutrition_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,  -- e.g., "Muscle Gain", "Weight Loss"
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. nutritions
CREATE TABLE nutritions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    calories INT DEFAULT 0,
    protein INT DEFAULT 0,     -- dalam gram
    carbs INT DEFAULT 0,       -- dalam gram
    fat INT DEFAULT 0,         -- dalam gram
    thumbnail VARCHAR(255),    -- uploads/nutrition/thumb_xxx.jpg
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES nutrition_categories(id) ON DELETE CASCADE
);

-- 6. roadmap_steps (urutan penting → pakai 'order_index')
CREATE TABLE roadmap_steps (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,         -- e.g., "Week 1: Build Habit"
    content TEXT NOT NULL,               -- penjelasan langkah
    order_index INT NOT NULL,            -- 1, 2, 3... (admin bisa ubah urutan)
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. discussions (top-level posts)
CREATE TABLE discussions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nickname VARCHAR(50) NOT NULL DEFAULT 'Anon',  -- opsional, bisa diisi acak
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. discussion_replies (untuk threaded reply)
CREATE TABLE discussion_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    discussion_id INT NOT NULL,
    parent_reply_id INT NULL,  -- NULL = balas ke post utama; NOT NULL = balas ke reply lain
    nickname VARCHAR(50) NOT NULL DEFAULT 'Anon',
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (discussion_id) REFERENCES discussions(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_reply_id) REFERENCES discussion_replies(id) ON DELETE CASCADE
);