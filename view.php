<?php
$dbConfigPath = __DIR__ . '/dbconfig.php';
require_once $dbConfigPath;

// Determine mode: Dashboard (no id) or Single Event View (with id)
$mode = 'dashboard';
$event = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM events WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $mode = 'detail';
    } else {
        header("Location: view.php");
        exit;
    }
}

// Dashboard mode: fetch all events with search & sort
$events = [];
$search = '';
$sort = 'date_desc';
if ($mode === 'dashboard') {
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
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $mode === 'detail' ? htmlspecialchars($event['event_name']) . ' - ' : 'Events Dashboard - ' ?>Carolinian Events</title>
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

<?php if ($mode === 'dashboard'): ?>
    <!-- ===== DASHBOARD VIEW ===== -->
    <div class="dashboard-container">
        <div class="dashboard-header reveal">
            <h1>Events Dashboard</h1>
            <a href="create.php" class="btn btn-primary">+ Add New Event</a>
        </div>

        <div class="dashboard-controls reveal stagger-1">
            <form method="GET" action="view.php" class="controls-form">
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
                        <?php foreach ($events as $index => $ev): ?>
                            <tr>
                                <td data-label="#"><?= $index + 1 ?></td>
                                <td data-label="Event Name" class="event-name-cell"><?= htmlspecialchars($ev['event_name']) ?></td>
                                <td data-label="Date & Time">
                                    <?= date('M d, Y', strtotime($ev['event_date'])) ?><br>
                                    <small class="time-muted"><?= date('h:i A', strtotime($ev['event_time'])) ?></small>
                                </td>
                                <td data-label="Location"><?= htmlspecialchars($ev['location']) ?></td>
                                <td data-label="Category"><?= htmlspecialchars($ev['category']) ?></td>
                                <td data-label="Status">
                                    <span class="status-badge status-<?= $ev['status'] ?>"><?= $ev['status'] ?></span>
                                </td>
                                <td data-label="Actions" class="action-links">
                                    <a href="view.php?id=<?= $ev['id'] ?>" class="action-view">View</a>
                                    <a href="update.php?id=<?= $ev['id'] ?>" class="action-edit">Edit</a>
                                    <a href="#" onclick="confirmDelete(<?= $ev['id'] ?>)" class="action-delete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- ===== SINGLE EVENT DETAIL VIEW ===== -->
    <div class="view-container">
        <a href="view.php" class="btn btn-secondary back-btn">&larr; Back to Dashboard</a>
        
        <div class="event-card reveal active">
            <div class="event-header">
                <h1><?= htmlspecialchars($event['event_name']) ?></h1>
                <span class="status-badge detail-badge status-<?= $event['status'] ?>"><?= $event['status'] ?></span>
                
                <div class="event-meta">
                    <div class="meta-item">
                        <span>📅</span> <?= date('F j, Y', strtotime($event['event_date'])) ?>
                    </div>
                    <div class="meta-item">
                        <span>⏰</span> <?= date('h:i A', strtotime($event['event_time'])) ?>
                    </div>
                    <div class="meta-item">
                        <span>📍</span> <?= htmlspecialchars($event['location']) ?>
                    </div>
                </div>
            </div>
            
            <div class="event-body">
                <div class="event-section">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($event['description'])) ?: '<em>No description provided.</em>' ?></p>
                </div>
                
                <div class="event-detail-grid">
                    <div class="event-section">
                        <h3>Organizer</h3>
                        <p><?= htmlspecialchars($event['organizer']) ?></p>
                    </div>
                    <div class="event-section">
                        <h3>Category</h3>
                        <p><?= htmlspecialchars($event['category']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="event-footer">
                <div class="event-footer-date">
                    Added on <?= date('M d, Y', strtotime($event['created_at'])) ?>
                </div>
                <div class="action-group">
                    <a href="update.php?id=<?= $event['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="#" onclick="confirmDelete(<?= $event['id'] ?>)" class="btn btn-primary btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <script src="script.js"></script>
</body>
</html>
