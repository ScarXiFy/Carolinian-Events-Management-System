-- ============================================
-- Carolinian Events Management System
-- Database Schema — schema.sql
-- ============================================
--
-- HOW TO USE:
-- 1. Open XAMPP and start Apache + MySQL
-- 2. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 3. Click "SQL" tab at the top
-- 4. Copy-paste this entire file and click "Go"
--    OR import this file via the "Import" tab
-- ============================================

-- -----------------------------------------------
-- Step 1: Create the database
-- -----------------------------------------------
CREATE DATABASE IF NOT EXISTS carolinian_events_db
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE carolinian_events_db;

-- -----------------------------------------------
-- Step 2: Create the events table
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS events (
    id          INT(11)         NOT NULL AUTO_INCREMENT,
    event_name  VARCHAR(255)    NOT NULL,
    organizer   VARCHAR(255)    NOT NULL,
    description TEXT            NOT NULL,
    event_date  DATE            NOT NULL,
    event_time  TIME            NOT NULL,
    location    VARCHAR(255)    NOT NULL,
    category    ENUM('Academic', 'Cultural', 'Sports', 'Social', 'Other')
                                NOT NULL DEFAULT 'Academic',
    status      ENUM('Upcoming', 'Ongoing', 'Completed')
                                NOT NULL DEFAULT 'Upcoming',
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Step 3: Insert sample data (optional)
-- -----------------------------------------------
-- These sample records let you test the system immediately.
-- Feel free to delete them once real data is entered.

INSERT INTO events (event_name, organizer, description, event_date, event_time, location, category, status) VALUES
(
    'Mock Presentation',
    'GROUP F',
    'A practice presentation session for CPE students to rehearse and refine their project demonstrations before the final defense.',
    '2026-05-05',
    '14:00:00',
    'NCR Lab',
    'Academic',
    'Upcoming'
),
(
    'Proposal Hearing 2026',
    'CPE Department',
    'Annual thesis and capstone proposal hearing for 3rd year Computer Engineering students. Present your project proposals to the panel.',
    '2026-05-08',
    '10:00:00',
    'Bunzel Building',
    'Academic',
    'Ongoing'
),
(
    'Carolinian Week 2026',
    'USC Student Council',
    'The annual week-long celebration of Carolinian culture featuring sports tournaments, talent shows, food fairs, and community outreach programs.',
    '2026-05-15',
    '08:00:00',
    'USC Main Campus',
    'Cultural',
    'Upcoming'
),
(
    'Intramurals 2026',
    'USC Athletics',
    'University-wide intramural sports competition. Events include basketball, volleyball, badminton, table tennis, and track and field.',
    '2026-04-20',
    '07:30:00',
    'USC Gymnasium',
    'Sports',
    'Completed'
),
(
    'Tech Talk: AI in Engineering',
    'CPE Society',
    'A guest lecture exploring the latest advancements in Artificial Intelligence and how they are reshaping the field of Computer Engineering.',
    '2026-05-22',
    '13:00:00',
    'Engineering Auditorium',
    'Academic',
    'Upcoming'
);
