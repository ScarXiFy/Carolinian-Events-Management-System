<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;

$error = '';
$success = '';

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
        $query = "INSERT INTO events (event_name, organizer, description, event_date, event_time, location, category, status) 
                  VALUES ('$event_name', '$organizer', '$description', '$event_date', '$event_time', '$location', '$category', '$status')";
        
        if ($conn->query($query)) {
            header("Location: view.php?success=created");
            exit;
        } else {
            $error = "Error creating event: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Carolinian Events</title>
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
            <h1>Create New Event</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form action="create.php" method="POST">
                <div class="form-group">
                    <label for="event_name">Event Name *</label>
                    <input type="text" id="event_name" name="event_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="organizer">Organizer *</label>
                    <input type="text" id="organizer" name="organizer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="event_date">Event Date *</label>
                        <input type="date" id="event_date" name="event_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="event_time">Event Time *</label>
                        <input type="time" id="event_time" name="event_time" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control">
                            <option value="Academic">Academic</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Sports">Sports</option>
                            <option value="Social">Social</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="Upcoming">Upcoming</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="view.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Event</button>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
