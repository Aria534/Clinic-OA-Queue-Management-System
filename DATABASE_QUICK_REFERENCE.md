# Clinic QMS - Database Quick Reference Guide

**Last Updated**: May 15, 2026  
**Project**: CLINIC-QMS  
**Framework**: CodeIgniter 4  

---

## 1. QUICK SCHEMA OVERVIEW

```
┌─ USERS (Authentication)
│  ├─ id, name, email, password, phone, role
│  ├─ Roles: patient, admin, doctor
│  └─ Unique: email
│
├─ SERVICES (Clinic Services)
│  ├─ id, name, department_code, duration, is_active
│  └─ Used by: appointments
│
├─ APPOINTMENTS (Bookings & Queue)
│  ├─ id, user_id (nullable), patient_name, patient_email, service_id
│  ├─ appointment_date, appointment_time, queue_number
│  ├─ status (pending → confirmed → in_queue → serving → completed/cancelled)
│  ├─ started_at, finished_at (timestamps for tracking)
│  └─ Foreign Keys: users (nullable), services
│
├─ QUEUE_LOGS (Audit Trail)
│  ├─ id, appointment_id, action, acted_by, created_at
│  ├─ Actions: called, serving, completed, skipped
│  └─ Foreign Keys: appointments, users
│
└─ SCHEDULES (Operating Hours)
   ├─ id, day_of_week, open_time, close_time, is_open
   └─ Day: 1=Sunday, 2=Monday, ..., 7=Saturday
```

---

## 2. COMMON SQL QUERIES

### Get Today's Queue
```sql
SELECT 
    a.id, a.queue_number, 
    COALESCE(a.patient_name, u.name) AS patient_name,
    s.name AS service_name,
    a.status,
    a.appointment_time
FROM appointments a
LEFT JOIN users u ON a.user_id = u.id
LEFT JOIN services s ON a.service_id = s.id
WHERE a.appointment_date = CURDATE()
AND a.status IN ('pending', 'confirmed', 'in_queue', 'serving')
ORDER BY a.queue_number ASC;
```

### Get Queue Statistics for Today
```sql
SELECT 
    s.name AS service,
    COUNT(*) AS total,
    COUNT(CASE WHEN a.status = 'completed' THEN 1 END) AS completed,
    COUNT(CASE WHEN a.status = 'serving' THEN 1 END) AS serving,
    COUNT(CASE WHEN a.status IN ('pending', 'confirmed', 'in_queue') THEN 1 END) AS waiting
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.appointment_date = CURDATE()
GROUP BY s.id, s.name;
```

### Get Patient Appointment History
```sql
SELECT 
    a.id, a.appointment_date, a.appointment_time,
    s.name AS service,
    a.status,
    TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at) AS duration_minutes
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.user_id = [USER_ID]
ORDER BY a.appointment_date DESC;
```

### Get Average Service Time
```sql
SELECT 
    s.name AS service,
    ROUND(AVG(TIMESTAMPDIFF(MINUTE, a.started_at, a.finished_at)), 2) AS avg_minutes,
    COUNT(*) AS completed_appointments
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.status = 'completed' 
AND a.started_at IS NOT NULL 
AND a.finished_at IS NOT NULL
AND a.appointment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY s.id, s.name
ORDER BY avg_minutes DESC;
```

### Get No-Shows for Analysis
```sql
SELECT 
    a.id, a.queue_number, 
    COALESCE(a.patient_name, u.name) AS patient_name,
    a.appointment_date, a.appointment_time,
    s.name AS service
FROM appointments a
LEFT JOIN users u ON a.user_id = u.id
LEFT JOIN services s ON a.service_id = s.id
WHERE a.status = 'cancelled' 
AND a.appointment_date >= CURDATE()
AND a.appointment_date < DATE_ADD(CURDATE(), INTERVAL 1 DAY);
```

### Most Active Services
```sql
SELECT 
    s.name AS service,
    COUNT(*) AS total_bookings,
    ROUND(COUNT(*) / (SELECT COUNT(*) FROM appointments) * 100, 2) AS percentage
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.appointment_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY s.id, s.name
ORDER BY total_bookings DESC
LIMIT 10;
```

---

## 3. APPOINTMENT STATUS FLOW

```
[PENDING] → [CONFIRMED] → [IN_QUEUE] → [SERVING] → [COMPLETED]
   ↓           ↓             ↓           ↓
   └─────────────────────────┴───────────┴────────→ [CANCELLED]

User Actions:
- Guest books appointment → PENDING
- Admin confirms → CONFIRMED
- Patient calls next → IN_QUEUE
- Staff serving → SERVING
- Appointment done → COMPLETED
- Anytime → CANCELLED
```

---

## 4. QUEUE NUMBER GENERATION

### Format: `[DEPARTMENT_CODE]-[NUMBER]`

Examples:
- `OPD-001` - First general consultation
- `LAB-015` - 15th lab test
- `DENT-003` - 3rd dental appointment
- `XRAY-008` - 8th X-ray appointment

### SQL to Get Next Queue Number
```sql
SELECT 
    CONCAT(
        COALESCE(s.department_code, 'G'),
        '-',
        LPAD(
            COALESCE(
                MAX(CAST(SUBSTRING_INDEX(a.queue_number, '-', -1) AS UNSIGNED)),
                0
            ) + 1,
            3,
            '0'
        )
    ) AS next_queue_number
FROM appointments a
LEFT JOIN services s ON a.service_id = s.id
WHERE a.appointment_date = [DATE]
AND a.service_id = [SERVICE_ID];
```

---

## 5. IMPORTANT INDEXES

These are critical for performance:

| Index | Columns | Purpose |
|-------|---------|---------|
| `idx_appointment_date` | `appointment_date` | Daily queries |
| `idx_status` | `status` | Filtering by state |
| `idx_queue_number` | `queue_number` | Queue display |
| `idx_user_id` | `user_id` | Patient lookups |
| `idx_service_id` | `service_id` | Service analytics |
| `idx_date_status` | `(appointment_date, status)` | Combined filters |
| `idx_date_queue_status` | `(appointment_date, queue_number, status)` | Queue display |
| `ft_patient_name` | `patient_name` (FULLTEXT) | Search patients |

---

## 6. GUEST BOOKING vs REGISTERED USER

### Guest Booking Flow
```
Home::book() → 
  user_id = NULL ✓
  patient_name = captured
  patient_email = NULL
  Status = PENDING
```

### Registered User Booking Flow
```
Patient::book() → 
  user_id = [USER_ID]
  patient_name = captured
  Status = PENDING
```

### Key Point
- Both stored in same table
- `user_id` is nullable for guests
- Always use `COALESCE(patient_name, user.name)` in queries

---

## 7. BACKUP & RESTORE

### Backup Database
```bash
mysqldump -u root -p clinic_db > clinic_db_backup.sql
```

### Restore Database
```bash
mysql -u root -p clinic_db < clinic_db_backup.sql
```

### Backup Specific Table
```bash
mysqldump -u root -p clinic_db appointments > appointments_backup.sql
```

---

## 8. MAINTENANCE QUERIES

### Archive Old Appointments (before 90 days)
```sql
-- Create archive table first
CREATE TABLE appointments_archive LIKE appointments;

-- Move old data
INSERT INTO appointments_archive 
SELECT * FROM appointments 
WHERE appointment_date < DATE_SUB(CURDATE(), INTERVAL 90 DAY);

-- Delete from main table
DELETE FROM appointments 
WHERE appointment_date < DATE_SUB(CURDATE(), INTERVAL 90 DAY);
```

### Clean Up Completed Queue Logs
```sql
DELETE FROM queue_logs 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Rebuild Indexes
```sql
-- For specific table
OPTIMIZE TABLE appointments;

-- For all tables
OPTIMIZE TABLE users, services, appointments, queue_logs, schedules;
```

---

## 9. USER AUTHENTICATION

### Create User (from Auth Controller)
```php
// CodeIgniter code
$model = new UserModel();
$model->save([
    'name'     => 'John Doe',
    'email'    => 'john@example.com',
    'phone'    => '555-1234',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
    'role'     => 'patient',
]);
```

### Verify Password
```php
// CodeIgniter code
$user = $model->findByEmail($email);
if ($user && password_verify($plainPassword, $user['password'])) {
    // Valid password
}
```

### Direct SQL to Create Admin User
```sql
INSERT INTO users (name, email, password, phone, role, is_active) 
VALUES (
    'Admin',
    'admin@clinic.local',
    '$2y$10$sFd0f8LL5pGhV3SN8T.Y/OkqJV.PrV0UqJBd5DZf0qlVQZt9KJ2Sm',
    '555-0001',
    'admin',
    1
);
-- Note: Hash is for password 'admin123'
```

---

## 10. PERFORMANCE TIPS

### 1. Use Prepared Statements
```php
// Always use parameterized queries
$builder->where('id', $id);  // ✓ Good
$builder->where("id = $id"); // ✗ Dangerous
```

### 2. Limit Results
```sql
-- Always paginate large result sets
SELECT * FROM appointments LIMIT 50 OFFSET 0;  -- ✓ Good
SELECT * FROM appointments;                    -- ✗ Bad
```

### 3. Select Only Needed Columns
```sql
-- Good: Select specific columns
SELECT id, queue_number, status FROM appointments;

-- Bad: Select all columns
SELECT * FROM appointments;
```

### 4. Cache Query Results
```php
// CodeIgniter caching
$cache = cache();
$data = $cache->get('queue_today');
if (!$data) {
    $data = $model->getTodayQueue();
    $cache->save('queue_today', $data, 300); // 5 minutes
}
```

### 5. Monitor Query Performance
```sql
-- Enable slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check query execution plan
EXPLAIN SELECT * FROM appointments WHERE appointment_date = CURDATE();
```

---

## 11. COMMON ERRORS & SOLUTIONS

### Error: "Cannot delete or update a parent row"
**Cause**: Trying to delete user with related appointments  
**Solution**: Delete appointments first or cascade delete is not configured
```sql
-- Check constraints
SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
WHERE TABLE_SCHEMA = 'clinic_db';
```

### Error: "Duplicate entry for key 'email'"
**Cause**: Email already exists in users table  
**Solution**: Check for existing email first
```sql
SELECT COUNT(*) FROM users WHERE email = 'example@test.com';
```

### Error: "Appointment queue_number is NULL"
**Cause**: Queue generation failed  
**Solution**: Ensure service_id and appointment_date are valid
```sql
SELECT * FROM appointments WHERE queue_number IS NULL OR queue_number = '0';
```

### Performance: Slow queue queries
**Cause**: Missing indexes on appointment_date and status  
**Solution**: Rebuild indexes
```sql
OPTIMIZE TABLE appointments;
-- Or recreate indexes
ALTER TABLE appointments ADD INDEX idx_date_status (appointment_date, status);
```

---

## 12. MIGRATION CHECKLIST

When moving from development to production:

- [ ] All migrations have been run (`php spark migrate`)
- [ ] Database has UTF8MB4 charset
- [ ] Foreign key constraints are enabled
- [ ] Indexes are created
- [ ] Backup created before migration
- [ ] Test all CRUD operations
- [ ] Test authentication flows
- [ ] Test queue operations
- [ ] Load testing completed
- [ ] Security audit passed
- [ ] Backup schedule configured

---

## 13. VIEWS REFERENCE

Three pre-built views are included in the schema:

### daily_queue_summary
Groups daily appointments by service with count breakdowns
```sql
SELECT * FROM daily_queue_summary 
WHERE appointment_date = CURDATE();
```

### patient_appointment_summary
Shows patient booking history and statistics
```sql
SELECT * FROM patient_appointment_summary;
```

### queue_performance_metrics
Analyzes service time and efficiency metrics
```sql
SELECT * FROM queue_performance_metrics 
WHERE appointment_date = CURDATE();
```

---

## 14. STORED PROCEDURES

Three procedures included for common operations:

### GetNextQueueNumber
```sql
CALL GetNextQueueNumber('2026-05-15', 1, @queueNum);
SELECT @queueNum;
```

### MarkAppointmentServing
```sql
CALL MarkAppointmentServing(123, 45);
-- appointment_id = 123, acted_by user = 45
```

### CompleteAppointment
```sql
CALL CompleteAppointment(123, 45);
-- appointment_id = 123, acted_by user = 45
```

---

## 15. SAMPLE DATA INITIALIZATION

To populate with test data, uncomment the section in `SCHEMA.sql`:

```sql
-- Creates:
-- - 1 Admin user
-- - 1 Doctor user
-- - 6 Sample services
-- - 7 Schedule entries (Mon-Fri open, Weekends closed)
```

---

## 16. USEFUL MYSQL COMMANDS

```bash
# Connect to database
mysql -u root -p clinic_db

# Show current database size
SELECT 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
FROM information_schema.tables 
WHERE table_schema = 'clinic_db';

# Show table sizes
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE table_schema = 'clinic_db' 
ORDER BY (data_length + index_length) DESC;

# Show connected users
SHOW PROCESSLIST;

# Kill a process
KILL [process_id];

# Export specific table
mysqldump -u root -p clinic_db appointments > appointments.sql

# Import data
mysql -u root -p clinic_db < appointments.sql
```

---

## 17. DATABASE CONNECTION STRING

### CodeIgniter 4 Config (app/Config/Database.php)
```php
public array $default = [
    'DSN'          => '',
    'hostname'     => 'localhost',
    'username'     => 'root',
    'password'     => 'admin',
    'database'     => 'clinic_db',
    'DBDriver'     => 'MySQLi',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8mb4',
    'DBCollat'     => 'utf8mb4_general_ci',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 3306,
];
```

### PostgreSQL (For future use)
```php
public array $postgre = [
    'DSN'        => '',
    'hostname'   => 'localhost',
    'username'   => 'postgres',
    'password'   => 'password',
    'database'   => 'clinic_db',
    'schema'     => 'public',
    'DBDriver'   => 'Postgre',
    'charset'    => 'utf8',
    'port'       => 5432,
];
```

---

## 18. TROUBLESHOOTING CHECKLIST

- [ ] Is database running? `SHOW DATABASES;`
- [ ] Are all tables created? `SHOW TABLES;`
- [ ] Check table structure: `DESCRIBE appointments;`
- [ ] Verify foreign keys: `SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS;`
- [ ] Check for locks: `SHOW PROCESSLIST;`
- [ ] Verify permissions: `SHOW GRANTS;`
- [ ] Check recent errors: `SHOW ENGINE INNODB STATUS;`
- [ ] Test connectivity: `SELECT 1;`

---

**End of Quick Reference Guide**

For detailed analysis, see: `DATABASE_SCHEMA_ANALYSIS.md`  
For SQL schema, see: `SCHEMA.sql`  
