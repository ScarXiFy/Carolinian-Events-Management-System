<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Optional: Add a confirmation mechanism or rely on frontend confirm() as implemented in view.php
    $query = "DELETE FROM events WHERE id = $id";
    $conn->query($query);
}

header("Location: view.php?success=deleted");
exit;
