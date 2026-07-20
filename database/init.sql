-- =========================================
-- Smart Food Redistribution Platform
-- Database Initialization Script
-- =========================================

-- Create database
CREATE DATABASE IF NOT EXISTS smartfood;
USE smartfood;

-- =========================================
-- Table: users
-- =========================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('donor', 'ngo', 'admin') NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: ngos
-- =========================================
CREATE TABLE ngos (
    ngo_id INT PRIMARY KEY,
    organization_name VARCHAR(150) NOT NULL,
    registration_number VARCHAR(100) UNIQUE NOT NULL,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    FOREIGN KEY (ngo_id) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id)
);

-- =========================================
-- Table: donations
-- =========================================
CREATE TABLE donations (
    donation_id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT,
    food_name VARCHAR(100) NOT NULL,
    quantity_value DECIMAL(10,2) NOT NULL,
    quantity_unit VARCHAR(20) NOT NULL,
    food_type VARCHAR(50) NOT NULL,
    preparation_date DATETIME NOT NULL,
    expiry_time DATETIME NOT NULL,
    pickup_location TEXT NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    status ENUM('available', 'requested', 'completed', 'expired') DEFAULT 'available',
    image_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(user_id)
);

-- =========================================
-- Table: requests
-- =========================================
CREATE TABLE requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    donation_id INT,
    ngo_id INT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donation_id) REFERENCES donations(donation_id),
    FOREIGN KEY (ngo_id) REFERENCES users(user_id)
);

-- =========================================
-- Table: feedback
-- =========================================
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    donation_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (donation_id) REFERENCES donations(donation_id)
);

-- =========================================
-- Table: notifications
-- =========================================
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('new_donation', 'request_submitted', 'request_approved', 'request_rejected', 'food_collected') NOT NULL,
    message TEXT NOT NULL,
    related_donation_id INT NULL,
    related_request_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (related_donation_id) REFERENCES donations(donation_id),
    FOREIGN KEY (related_request_id) REFERENCES requests(request_id)
);

-- =========================================
-- Insert default admin user
-- Password: admin123 (hashed with password_hash)
-- =========================================
INSERT INTO users (name, email, phone, password, role, address) 
VALUES ('Admin', 'admin@smartfood.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin Office');

-- =========================================
-- Create indexes for better performance
-- =========================================
CREATE INDEX idx_donations_status ON donations(status);
CREATE INDEX idx_donations_donor ON donations(donor_id);
CREATE INDEX idx_donations_expiry ON donations(expiry_time);
CREATE INDEX idx_requests_status ON requests(status);
CREATE INDEX idx_requests_ngo ON requests(ngo_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(is_read);
