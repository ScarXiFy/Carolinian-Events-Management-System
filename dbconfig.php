<?php
// ============================================
// Carolinian Events Management System
// Database Configuration — dbconfig.php
// ============================================

// MySQL credentials (XAMPP defaults)
$host     = "localhost";
$username = "root";
$password = "";           // Default XAMPP has no password
$database = "carolinian_events_db";

// Create connection using MySQLi (Object-Oriented)
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(
        '<div style="
            font-family: Inter, sans-serif;
            max-width: 520px;
            margin: 80px auto;
            padding: 32px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 12px;
            color: #991B1B;
            text-align: center;
        ">
            <h2 style="margin-bottom: 12px;">⚠️ Database Connection Failed</h2>
            <p style="margin-bottom: 16px;">Could not connect to MySQL. Please make sure:</p>
            <ul style="text-align: left; list-style: disc; padding-left: 20px; line-height: 2;">
                <li>XAMPP is running (Apache + MySQL)</li>
                <li>The database <strong>' . $database . '</strong> exists in phpMyAdmin</li>
                <li>You have run the SQL schema from <code>database/schema.sql</code></li>
            </ul>
            <p style="margin-top: 16px; font-size: 0.85rem; color: #B91C1C;">
                Error: ' . $conn->connect_error . '
            </p>
        </div>'
    );
}

// Set charset to UTF-8 for proper character handling
$conn->set_charset("utf8mb4");

// Keep older local databases compatible with custom event categories.
// Previous schemas used an ENUM, which cannot store custom "Other" text.
$categoryColumn = $conn->query("SHOW COLUMNS FROM events LIKE 'category'");
if ($categoryColumn && $categoryColumn->num_rows > 0) {
    $column = $categoryColumn->fetch_assoc();
    if (isset($column['Type']) && stripos($column['Type'], 'enum(') === 0) {
        $conn->query("ALTER TABLE events MODIFY category VARCHAR(255) NOT NULL DEFAULT 'Academic'");
    }
}
?>
