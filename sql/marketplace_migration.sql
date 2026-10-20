-- Run this in MySQL Workbench AFTER selecting the existing social_app database.
-- This adds only the marketplace table; it does not drop or reset existing data.
CREATE TABLE IF NOT EXISTS marketplace_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    category VARCHAR(50) NOT NULL DEFAULT 'Other',
    condition_label VARCHAR(20) NOT NULL DEFAULT 'Good',
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('available','sold') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_marketplace_status_created (status, created_at),
    CONSTRAINT fk_marketplace_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
