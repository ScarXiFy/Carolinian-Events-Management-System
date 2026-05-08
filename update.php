<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;

$error = '';

if (!isset($_GET['id'])) {
    header("Location: view.php");
    exit;
}
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $organizer = $conn->real_escape_string($_POST['organizer']);
    $description = $conn->real_escape_string($_POST['description']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $event_time = $conn->real_escape_string($_POST['event_time']);
    $location = $conn->real_escape_string($_POST['location']);
    $category = $conn->real_escape_string($_POST['category']);
    $status = $conn->real_escape_string($_POST['status']);

    if (empty($event_name) || empty($organizer) || empty($event_date) || empty($event_time) || empty($location)) {
        $error = "Please fill in all required fields.";
    } else {
        $query = "UPDATE events SET 
                    event_name='$event_name', 
                    organizer='$organizer', 
                    description='$description', 
                    event_date='$event_date', 
                    event_time='$event_time', 
                    location='$location', 
                    category='$category', 
                    status='$status' 
                  WHERE id=$id";
        
        if ($conn->query($query)) {
            header("Location: view.php?success=updated");
            exit;
        } else {
            $error = "Error updating event: " . $conn->error;
        }
    }
}

// Fetch current data
$result = $conn->query("SELECT * FROM events WHERE id=$id");
if ($result && $result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    header("Location: view.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Carolinian Events</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar scrolled" id="navbar">
        <div class="nav-container">
            <a href="index.php" class="brand">Carolinian<span>Events</span></a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="view.php">Events Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <div class="form-card reveal active">
            <h1>Edit Event</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form action="update.php?id=<?= $id ?>" method="POST">
                <div class="form-group">
                    <label for="event_name">Event Name *</label>
                    <input type="text" id="event_name" name="event_name" class="form-control" value="<?= htmlspecialchars($event['event_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="organizer">Organizer *</label>
                    <input type="text" id="organizer" name="organizer" class="form-control" value="<?= htmlspecialchars($event['organizer']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="event_date">Event Date *</label>
                        <input type="date" id="event_date" name="event_date" class="form-control" value="<?= $event['event_date'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="event_time">Event Time *</label>
                        <input type="time" id="event_time" name="event_time" class="form-control" value="<?= $event['event_time'] ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control">
                            <option value="Academic" <?= $event['category'] == 'Academic' ? 'selected' : '' ?>>Academic</option>
                            <option value="Cultural" <?= $event['category'] == 'Cultural' ? 'selected' : '' ?>>Cultural</option>
                            <option value="Sports" <?= $event['category'] == 'Sports' ? 'selected' : '' ?>>Sports</option>
                            <option value="Social" <?= $event['category'] == 'Social' ? 'selected' : '' ?>>Social</option>
                            <option value="Other" <?= $event['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="Upcoming" <?= $event['status'] == 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                            <option value="Ongoing" <?= $event['status'] == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                            <option value="Completed" <?= $event['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="view.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
