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

    // Category: final value is resolved by JS into the hidden 'category' field
    $category = isset($_POST['category']) ? $conn->real_escape_string(trim($_POST['category'])) : 'Other';

    // Handle location: use custom input if "Other" selected
    $rawLocation = isset($_POST['location_select']) ? trim($_POST['location_select']) : '';
    if ($rawLocation === 'Other' && isset($_POST['location_other']) && trim($_POST['location_other']) !== '') {
        $location = $conn->real_escape_string(trim($_POST['location_other']));
    } elseif ($rawLocation !== '') {
        $location = $conn->real_escape_string($rawLocation);
    } else {
        $location = '';
    }

    // Auto-compute status based on event date & time
    $eventDateTime = new DateTime("$event_date $event_time");
    $now = new DateTime();
    $eventDateOnly = (new DateTime($event_date))->format('Y-m-d');
    $todayOnly = $now->format('Y-m-d');

    if ($eventDateOnly < $todayOnly) {
        $status = 'Completed';
    } elseif ($eventDateOnly === $todayOnly) {
        // Same day: check if event time has passed
        if ($eventDateTime <= $now) {
            $status = 'Completed';
        } else {
            $status = 'Ongoing';
        }
    } else {
        $status = 'Upcoming';
    }

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
                    <label for="location_select">Location *</label>
                    <select id="location_select" name="location_select" class="form-control" required>
                        <option value="" disabled selected>Select a location</option>
                        <option value="Bunzel Building">Bunzel Building</option>
                        <option value="Rigney Hall">Rigney Hall</option>
                        <option value="LRC Building">LRC Building</option>
                        <option value="SMED Building">SMED Building</option>
                        <option value="PE Building">PE Building</option>
                        <option value="SAFAD Theatre">SAFAD Theatre</option>
                        <option value="MR Hall">MR Hall</option>
                        <option value="Basketball Court">Basketball Court</option>
                        <option value="Soccer Field">Soccer Field</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" id="location_other" name="location_other" class="form-control other-input" placeholder="Enter custom location" style="display:none; margin-top:0.5rem;">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_select">Category</label>
                        <select id="category_select" name="category_select" class="form-control">
                            <option value="Academic">Academic</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Sports">Sports</option>
                            <option value="Social">Social</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" id="category_other" name="category_other" class="form-control other-input" placeholder="Enter custom category" style="display:none; margin-top:0.5rem;">
                        <input type="hidden" id="category_final" name="category" value="Academic">
                    </div>
                    <div class="form-group">
                        <label for="status_display">Status</label>
                        <input type="text" id="status_display" class="form-control" value="—" readonly>
                        <input type="hidden" id="status" name="status" value="Upcoming">
                        <small class="status-hint">Auto-set based on event date & time</small>
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
