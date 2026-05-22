<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;
require_once __DIR__ . '/event_helpers.php';

$error = '';

if (!isset($_GET['id'])) {
    header("Location: view.php");
    exit;
}
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
<<<<<<< HEAD
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $organizer = $conn->real_escape_string($_POST['organizer']);
    $description = $conn->real_escape_string($_POST['description']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $event_time = $conn->real_escape_string($_POST['event_time']);
    // Category: use custom input when "Other" is selected
    $rawCategory = isset($_POST['category_select']) ? trim($_POST['category_select']) : '';
    if ($rawCategory === 'Other' && isset($_POST['category_other']) && trim($_POST['category_other']) !== '') {
        $category = $conn->real_escape_string(trim($_POST['category_other']));
    } elseif ($rawCategory !== '') {
        $category = $conn->real_escape_string($rawCategory);
    } else {
        $category = 'Academic';
    }
=======
    $event = event_build_form_data($_POST);
    $error = event_validate_form_data($event);
>>>>>>> origin/enricode-Laptop

    if ($error === '' && event_duplicate_exists($conn, $event, $id)) {
        $error = event_duplicate_message();
    }

    if ($error === '') {
        $status = event_compute_status($event['event_date'], $event['event_time']);
        $query = "UPDATE events SET
                    event_name = ?,
                    organizer = ?,
                    description = ?,
                    event_date = ?,
                    event_time = ?,
                    location = ?,
                    category = ?,
                    status = ?
                  WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            $error = "Error updating event: " . $conn->error;
        } else {
            $stmt->bind_param(
                'ssssssssi',
                $event['event_name'],
                $event['organizer'],
                $event['description'],
                $event['event_date'],
                $event['event_time'],
                $event['location'],
                $event['category'],
                $status,
                $id
            );

            if ($stmt->execute()) {
                header("Location: view.php?success=updated");
                exit;
            } else {
                $error = "Error updating event: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
}

if ($result && $result->num_rows > 0) {
    $event = $result->fetch_assoc();
    if (isset($stmt)) {
        $stmt->close();
    }
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
                <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
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
                    <label for="location_select">Location *</label>
                    <?php
                    $presetLocations = ['Bunzel Building', 'Rigney Hall', 'LRC Building', 'SMED Building', 'PE Building', 'SAFAD Theatre', 'MR Hall', 'Basketball Court', 'Soccer Field'];
                    $currentLocation = $event['location'];
                    $isCustomLocation = !in_array($currentLocation, $presetLocations);
                    ?>
                    <select id="location_select" name="location_select" class="form-control" required>
                        <option value="" disabled>Select a location</option>
                        <?php foreach ($presetLocations as $loc): ?>
                            <option value="<?= $loc ?>" <?= ($currentLocation === $loc) ? 'selected' : '' ?>><?= $loc ?></option>
                        <?php endforeach; ?>
                        <option value="Other" <?= $isCustomLocation ? 'selected' : '' ?>>Other</option>
                    </select>
                    <input type="text" id="location_other" name="location_other" class="form-control other-input" placeholder="Enter custom location" style="display:<?= $isCustomLocation ? 'block' : 'none' ?>; margin-top:0.5rem;" value="<?= $isCustomLocation ? htmlspecialchars($currentLocation) : '' ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_select">Category</label>
                        <?php
                        $presetCategories = ['Academic', 'Cultural', 'Sports', 'Social'];
                        $currentCategory = $event['category'];
                        $isCustomCategory = !in_array($currentCategory, $presetCategories);
                        ?>
                        <select id="category_select" name="category_select" class="form-control">
                            <?php foreach ($presetCategories as $cat): ?>
                                <option value="<?= $cat ?>" <?= ($currentCategory === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                            <option value="Other" <?= $isCustomCategory ? 'selected' : '' ?>>Other</option>
                        </select>
                        <input type="text" id="category_other" name="category_other" class="form-control other-input" placeholder="Enter custom category" style="display:<?= $isCustomCategory ? 'block' : 'none' ?>; margin-top:0.5rem;" value="<?= $isCustomCategory ? htmlspecialchars($currentCategory) : '' ?>">
                        <input type="hidden" id="category_final" name="category" value="<?= htmlspecialchars($currentCategory) ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_display">Status</label>
                        <input type="text" id="status_display" class="form-control" value="<?= htmlspecialchars($event['status']) ?>" readonly>
                        <input type="hidden" id="status" name="status" value="<?= htmlspecialchars($event['status']) ?>">
                        <small class="status-hint">Auto-set based on event date & time</small>
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
