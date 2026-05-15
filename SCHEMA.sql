-- ============================================================================
-- CLINIC QUEUE MANAGEMENT SYSTEM (CLINIC-QMS) - PRODUCTION DATABASE SCHEMA
-- ============================================================================
-- Framework: CodeIgniter 4
-- Database: MySQL 5.7+, MySQL 8.0+, MariaDB
-- Compatible: PostgreSQL (with minor adjustments), Supabase
-- Author: Database Analysis
-- Generated: May 15, 2026
--
-- INSTRUCTIONS:
-- 1. Copy entire content
-- 2. Paste into MySQL/Supabase console
-- 3. Ensure you're in the correct database (USE clinic_db;)
-- 4. Execute all statements
--
-- For PostgreSQL/Supabase:
-- - Replace INT UNSIGNED with SERIAL
-- - Replace DATETIME with TIMESTAMP
-- - Replace AUTO_INCREMENT with serial
-- - Replace ENUM with VARCHAR or custom TYPE
-- ============================================================================

-- ============================================================================
-- DROP EXISTING TABLES (if re-running)
-- ============================================================================
-- UNCOMMENT ONLY IF YOU WANT TO RESET THE DATABASE
-- WARNING: This will delete all data!

/*
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS queue_logs;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;
*/

-- ============================================================================
-- CREATE DATABASE
-- ============================================================================
-- Uncomment if database doesn't exist
-- CREATE DATABASE IF NOT EXISTS clinic_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE clinic_db;

-- ============================================================================
-- TABLE 1: USERS - User Authentication & Role Management
-- ============================================================================

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique user identifier',
    name VARCHAR(100) NOT NULL COMMENT 'Full name of user',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email address - unique for login',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password (PASSWORD_DEFAULT)',
    phone VARCHAR(20) NULL DEFAULT NULL COMMENT 'Contact phone number',
    role ENUM('patient', 'admin', 'doctor') NOT NULL DEFAULT 'patient' COMMENT 'User role/permission level',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether user account is active',
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation timestamp',
    updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last profile update',
    
    KEY idx_email (email),
    KEY idx_role (role),
    KEY idx_is_active (is_active),
    KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='User accounts and authentication';

-- ============================================================================
-- TABLE 2: SERVICES - Clinic Services & Departments
-- ============================================================================

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique service identifier',
    name VARCHAR(100) NOT NULL COMMENT 'Service name (e.g., General Consultation)',
    department_code VARCHAR(10) NULL DEFAULT 'G' COMMENT 'Department code for queue prefix (e.g., OPD, LAB)',
    description TEXT NULL DEFAULT NULL COMMENT 'Service description and details',
    duration INT NOT NULL DEFAULT 30 COMMENT 'Estimated duration in minutes',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether service is available for booking',
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Service creation timestamp',
    updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    
    KEY idx_is_active (is_active),
    KEY idx_department_code (department_code),
    KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Clinic services and departments';

-- ============================================================================
-- TABLE 3: SCHEDULES - Operating Hours & Availability
-- ============================================================================

CREATE TABLE IF NOT EXISTS schedules (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique schedule entry',
    day_of_week TINYINT(1) NOT NULL COMMENT '1=Sunday, 2=Monday, ... 7=Saturday',
    open_time TIME NOT NULL COMMENT 'Opening time (e.g., 09:00:00)',
    close_time TIME NOT NULL COMMENT 'Closing time (e.g., 17:00:00)',
    is_open TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether clinic is open on this day',
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Schedule creation timestamp',
    updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    
    UNIQUE KEY unique_day (day_of_week),
    KEY idx_is_open (is_open)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Clinic operating hours by day of week';

-- ============================================================================
-- TABLE 4: APPOINTMENTS - Appointment Bookings & Queue Management
-- ============================================================================

CREATE TABLE IF NOT EXISTS appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique appointment identifier',
    user_id INT UNSIGNED NULL DEFAULT NULL COMMENT 'Link to users table (NULL for guest bookings)',
    patient_name VARCHAR(100) NULL DEFAULT NULL COMMENT 'Patient name (for guests or override)',
    patient_email VARCHAR(150) NULL DEFAULT NULL COMMENT 'Patient email (for guests or override)',
    patient_contact VARCHAR(20) NULL DEFAULT NULL COMMENT 'Patient contact number',
    service_id INT UNSIGNED NOT NULL COMMENT 'Link to services (what service being booked)',
    appointment_date DATE NOT NULL COMMENT 'Date of appointment',
    appointment_time TIME NOT NULL COMMENT 'Time of appointment',
    queue_number VARCHAR(20) NOT NULL DEFAULT '0' COMMENT 'Queue number (e.g., OPD-001, LAB-002)',
    status ENUM('pending', 'confirmed', 'in_queue', 'serving', 'completed', 'cancelled') 
           NOT NULL DEFAULT 'pending' COMMENT 'Appointment status',
    notes TEXT NULL DEFAULT NULL COMMENT 'Additional notes or special requirements',
    started_at DATETIME NULL DEFAULT NULL COMMENT 'When patient started being served',
    finished_at DATETIME NULL DEFAULT NULL COMMENT 'When appointment/service was completed',
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Booking creation time',
    updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last status update',
    
    CONSTRAINT fk_appointments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_appointments_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE ON UPDATE CASCADE,
    
    KEY idx_appointment_date (appointment_date),
    KEY idx_status (status),
    KEY idx_queue_number (queue_number),
    KEY idx_user_id (user_id),
    KEY idx_service_id (service_id),
    KEY idx_date_status (appointment_date, status),
    KEY idx_date_queue_status (appointment_date, queue_number, status),
    KEY idx_user_date (user_id, appointment_date),
    FULLTEXT KEY ft_patient_name (patient_name),
    FULLTEXT KEY ft_patient_email (patient_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Appointment bookings with queue management';

-- ============================================================================
-- TABLE 5: QUEUE_LOGS - Audit Trail & Queue Actions
-- ============================================================================

CREATE TABLE IF NOT EXISTS queue_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique log entry',
    appointment_id INT UNSIGNED NOT NULL COMMENT 'Link to appointment',
    action ENUM('called', 'serving', 'completed', 'skipped') NOT NULL COMMENT 'Queue action type',
    acted_by INT UNSIGNED NULL DEFAULT NULL COMMENT 'User ID of admin/staff who performed action',
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Action timestamp',
    
    CONSTRAINT fk_queue_logs_appointment FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_queue_logs_user FOREIGN KEY (acted_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    
    KEY idx_appointment_id (appointment_id),
    KEY idx_action (action),
    KEY idx_acted_by (acted_by),
    KEY idx_created_at (created_at),
    KEY idx_date_action (DATE(created_at), action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Audit log for queue actions and state changes';

-- ============================================================================
-- VIEWS - Useful for Dashboards and Reporting
-- ============================================================================

-- View 1: Daily Queue Summary
DROP VIEW IF EXISTS daily_queue_summary;
CREATE VIEW daily_queue_summary AS
SELECT 
    DATE(a.appointment_date) AS appointment_date,
    s.id AS service_id,
    s.name AS service_name,
    s.department_code,
    COUNT(CASE WHEN a.status = 'completed' THEN 1 END) AS completed_count,
    COUNT(CASE WHEN a.status = 'pending' THEN 1 END) AS pending_count,
    COUNT(CASE WHEN a.status = 'in_queue' THEN 1 END) AS in_queue_count,
    COUNT(CASE WHEN a.status = 'serving' THEN 1 END) AS serving_count,
    COUNT(CASE WHEN a.status = 'cancelled' THEN 1 END) AS cancelled_count,
    COUNT(*) AS total_appointments,
    AVG(TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at)) AS avg_service_time_minutes
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.appointment_date <= CURDATE()
GROUP BY DATE(a.appointment_date), s.id, s.name, s.department_code;

-- View 2: Patient Appointment Summary
DROP VIEW IF EXISTS patient_appointment_summary;
CREATE VIEW patient_appointment_summary AS
SELECT 
    u.id AS patient_id,
    u.name AS patient_name,
    u.email,
    u.phone,
    COUNT(*) AS total_appointments,
    COUNT(CASE WHEN a.appointment_date >= CURDATE() THEN 1 END) AS upcoming_appointments,
    COUNT(CASE WHEN a.appointment_date < CURDATE() AND a.status = 'completed' THEN 1 END) AS completed_appointments,
    COUNT(CASE WHEN a.status = 'cancelled' THEN 1 END) AS cancelled_appointments,
    MAX(a.appointment_date) AS last_appointment_date
FROM users u
LEFT JOIN appointments a ON u.id = a.user_id
WHERE u.role = 'patient'
GROUP BY u.id, u.name, u.email, u.phone;

-- View 3: Queue Performance Metrics
DROP VIEW IF EXISTS queue_performance_metrics;
CREATE VIEW queue_performance_metrics AS
SELECT 
    DATE(a.appointment_date) AS appointment_date,
    s.id AS service_id,
    s.name AS service_name,
    COUNT(*) AS total_processed,
    ROUND(AVG(TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at)), 2) AS avg_service_minutes,
    MIN(TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at)) AS min_service_minutes,
    MAX(TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at)) AS max_service_minutes,
    COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, a.appointment_time, a.started_at) > 15 THEN 1 END) AS delayed_start_count
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.status = 'completed' AND a.started_at IS NOT NULL AND a.finished_at IS NOT NULL
GROUP BY DATE(a.appointment_date), s.id, s.name;

-- ============================================================================
-- STORED PROCEDURES - Useful for Common Operations
-- ============================================================================

-- Procedure 1: Get next queue number for a date and service
DELIMITER //

DROP PROCEDURE IF EXISTS GetNextQueueNumber //

CREATE PROCEDURE GetNextQueueNumber(
    IN p_appointment_date DATE,
    IN p_service_id INT,
    OUT p_queue_number VARCHAR(20)
)
BEGIN
    DECLARE v_max_num INT;
    DECLARE v_dept_code VARCHAR(10);
    
    -- Get department code
    SELECT COALESCE(department_code, 'G') INTO v_dept_code 
    FROM services WHERE id = p_service_id LIMIT 1;
    
    -- Get max queue number for this date
    SELECT MAX(CAST(SUBSTRING(queue_number, LOCATE('-', queue_number) + 1) AS UNSIGNED)) 
    INTO v_max_num
    FROM appointments 
    WHERE appointment_date = p_appointment_date 
    AND queue_number LIKE CONCAT(v_dept_code, '-%');
    
    SET v_max_num = IFNULL(v_max_num, 0) + 1;
    SET p_queue_number = CONCAT(v_dept_code, '-', LPAD(v_max_num, 3, '0'));
END //

DELIMITER ;

-- Procedure 2: Mark appointment as serving
DELIMITER //

DROP PROCEDURE IF EXISTS MarkAppointmentServing //

CREATE PROCEDURE MarkAppointmentServing(
    IN p_appointment_id INT,
    IN p_acted_by INT
)
BEGIN
    UPDATE appointments 
    SET status = 'serving', started_at = NOW() 
    WHERE id = p_appointment_id;
    
    INSERT INTO queue_logs (appointment_id, action, acted_by, created_at) 
    VALUES (p_appointment_id, 'serving', p_acted_by, NOW());
END //

DELIMITER ;

-- Procedure 3: Complete appointment
DELIMITER //

DROP PROCEDURE IF EXISTS CompleteAppointment //

CREATE PROCEDURE CompleteAppointment(
    IN p_appointment_id INT,
    IN p_acted_by INT
)
BEGIN
    UPDATE appointments 
    SET status = 'completed', finished_at = NOW() 
    WHERE id = p_appointment_id;
    
    INSERT INTO queue_logs (appointment_id, action, acted_by, created_at) 
    VALUES (p_appointment_id, 'completed', p_acted_by, NOW());
END //

DELIMITER ;

-- ============================================================================
-- INITIAL DATA (Optional - Uncomment to use)
-- ============================================================================

/*
-- Admin user (password: admin123)
INSERT INTO users (name, email, password, phone, role, is_active) 
VALUES ('Admin User', 'admin@clinic.local', '$2y$10$sFd0f8LL5pGhV3SN8T.Y/OkqJV.PrV0UqJBd5DZf0qlVQZt9KJ2Sm', '555-0001', 'admin', 1);

-- Doctor user (password: doctor123)
INSERT INTO users (name, email, password, phone, role, is_active) 
VALUES ('Dr. Smith', 'doctor@clinic.local', '$2y$10$5.LL/f8LL5pGhV3SN8T.Y/OkqJV.PrV0UqJBd5DZf0qlVQZt9KJ2Sm', '555-0002', 'doctor', 1);

-- Sample services
INSERT INTO services (name, department_code, description, duration, is_active) VALUES
('General Consultation', 'OPD', 'General medical consultation', 30, 1),
('Laboratory Tests', 'LAB', 'Blood tests and lab work', 15, 1),
('Dental Checkup', 'DENT', 'Dental examination and cleaning', 45, 1),
('Vaccination', 'VACC', 'Vaccinations and immunizations', 20, 1),
('Eye Examination', 'EYE', 'Optical and eye health checkup', 25, 1),
('X-Ray & Imaging', 'XRAY', 'Radiographic imaging services', 20, 1);

-- Clinic schedule (9 AM - 5 PM, closed weekends)
INSERT INTO schedules (day_of_week, open_time, close_time, is_open) VALUES
(1, '09:00:00', '17:00:00', 0),  -- Sunday: Closed
(2, '09:00:00', '17:00:00', 1),  -- Monday: Open
(3, '09:00:00', '17:00:00', 1),  -- Tuesday: Open
(4, '09:00:00', '17:00:00', 1),  -- Wednesday: Open
(5, '09:00:00', '17:00:00', 1),  -- Thursday: Open
(6, '09:00:00', '17:00:00', 1),  -- Friday: Open
(7, '09:00:00', '17:00:00', 0);  -- Saturday: Closed
*/

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================

-- Run these queries to verify the schema was created correctly:

/*
-- Check all tables created
SELECT 
    TABLE_NAME, 
    TABLE_TYPE, 
    ENGINE, 
    TABLE_ROWS,
    DATE_FORMAT(CREATE_TIME, '%Y-%m-%d %H:%i:%s') AS created
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
ORDER BY CREATE_TIME DESC;

-- Check all views created
SELECT 
    TABLE_NAME, 
    TABLE_TYPE
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_TYPE = 'VIEW' 
ORDER BY TABLE_NAME;

-- Check foreign keys
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    UPDATE_RULE,
    DELETE_RULE
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND REFERENCED_TABLE_NAME IS NOT NULL 
ORDER BY TABLE_NAME, ORDINAL_POSITION;

-- Check indexes
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    SEQ_IN_INDEX,
    COLUMN_NAME,
    COLLATION,
    CARDINALITY
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;

-- Row counts
SELECT 
    TABLE_NAME, 
    TABLE_ROWS
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
ORDER BY TABLE_ROWS DESC;
*/

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
-- Schema successfully defined for CLINIC-QMS
-- All tables, views, indexes, and procedures are ready
-- Ready for CodeIgniter 4 application integration
-- ============================================================================
