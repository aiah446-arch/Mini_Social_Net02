-- =========================================================
-- MINI SOCIAL NETWORKING WEB APP
-- Database: social_app
-- =========================================================

DROP DATABASE IF EXISTS social_app;
CREATE DATABASE social_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE social_app;

-- =========================================================
-- TABLE: users
-- =========================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    bio TEXT DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- TABLE: posts
-- =========================================================
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================================================
-- TABLE: comments
-- =========================================================
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================================================
-- TABLE: likes
-- =========================================================
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =========================================================
-- SAMPLE DATA
-- =========================================================
-- Passwords below are hashes of: "password123"
-- (generated via PHP: password_hash('password123', PASSWORD_DEFAULT))

INSERT INTO users (username, password, full_name, bio, profile_image) VALUES
('juan',   '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1Hll.8Cz1e1P8FkfLQmZqtnKfXbCwq2', 'Juan Dela Cruz',   'Hello! I love coding and coffee ☕', 'default.png'),
('maria',  '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1Hll.8Cz1e1P8FkfLQmZqtnKfXbCwq2', 'Maria Santos',     'Coffee lover. Foodie. Dreamer.',    'default.png'),
('pedro',  '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1Hll.8Cz1e1P8FkfLQmZqtnKfXbCwq2', 'Pedro Reyes',      'Just here to share memes 😂',       'default.png');

INSERT INTO posts (user_id, content, image) VALUES
(1, 'Hello everyone! This is my first post on this mini social network! 🎉', NULL),
(2, 'Good morning! Starting my day with a hot cup of coffee ☕', NULL),
(3, 'Anyone else excited about the weekend? 🙌', NULL),
(1, 'Just finished my PHP project. MVC architecture is no joke! 💪', NULL),
(2, 'Trying out this new recipe tonight. Wish me luck! 🍳', NULL);

INSERT INTO comments (post_id, user_id, content) VALUES
(1, 2, 'Welcome to the platform, Juan! 🎊'),
(1, 3, 'Nice first post bro!'),
(2, 1, 'Same here! Coffee is life ☕'),
(3, 2, 'Me! Got any plans?'),
(4, 3, 'Congrats! MVC is tough but worth it.');

INSERT INTO likes (post_id, user_id) VALUES
(1, 2), (1, 3),
(2, 1), (2, 3),
(3, 1),
(4, 2), (4, 3);