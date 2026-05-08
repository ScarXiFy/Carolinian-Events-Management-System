<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

$query = "SELECT * FROM events";
$conditions = [];
if (!empty($search)) {
    $searchEscaped = $conn->real_escape_string($search);
    $conditions[] = "event_name LIKE '%$searchEscaped%' OR location LIKE '%$searchEscaped%'";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

if ($sort === 'date_desc') {
    $query .= " ORDER BY created_at DESC, id DESC";
} elseif ($sort === 'date_asc') {
    $query .= " ORDER BY created_at ASC, id ASC";
} elseif ($sort === 'name_asc') {
    $query .= " ORDER BY event_name ASC";
} elseif ($sort === 'name_desc') {
    $query .= " ORDER BY event_name DESC";
} else {
    $query .= " ORDER BY created_at DESC, id DESC";
}

$result = $conn->query($query);
$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Dashboard - Carolinian Events</title>
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

    <div class="dashboard-container">
        <div class="dashboard-header reveal">
            <h1>Events Dashboard</h1>
            <a href="create.php" class="btn btn-primary">+ Add New Event</a>
        </div>

        <div class="dashboard-controls reveal stagger-1">
            <form method="GET" action="events.php" class="controls-form">
                <div class="control-group">
                    <input type="text" name="search" class="search-input" placeholder="Search events..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="control-group">
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="date_desc" <?= $sort == 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                        <option value="date_asc" <?= $sort == 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                        <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
                        <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
                    </select>
                </div>
                <noscript><button type="submit" class="btn btn-secondary noscript-apply">Apply</button></noscript>
            </form>
        </div>

        <div class="table-container reveal stagger-2">
            <table class="events-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="7" class="empty-row">No events found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $index => $event): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="event-name-cell"><?= htmlspecialchars($event['event_name']) ?></td>
                                <td>
                                    <?= date('M d, Y', strtotime($event['event_date'])) ?><br>
                                    <small class="time-muted"><?= date('h:i A', strtotime($event['event_time'])) ?></small>
                                </td>
                                <td><?= htmlspecialchars($event['location']) ?></td>
                                <td><?= htmlspecialchars($event['category']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $event['status'] ?>"><?= $event['status'] ?></span>
                                </td>
                                <td class="action-links">
                                    <a href="view.php?id=<?= $event['id'] ?>" class="action-view">View</a>
                                    <a href="update.php?id=<?= $event['id'] ?>" class="action-edit">Edit</a>
                                    <a href="#" onclick="confirmDelete(<?= $event['id'] ?>)" class="action-delete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
