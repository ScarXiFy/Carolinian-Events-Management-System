<?php
// Database connection and stat fetching logic
$totalEvents = 0;
$upcomingEvents = 0;
$ongoingEvents = 0;
$completedEvents = 0;
$dbConfigPath = __DIR__ . '/dbconfig.php';
if (file_exists($dbConfigPath)) {
    include $dbConfigPath;
    if (isset($conn) && $conn) {
        $result = $conn->query("SELECT COUNT(*) AS count FROM events");
        if ($result) $totalEvents = $result->fetch_assoc()['count'];
        $result = $conn->query("SELECT COUNT(*) AS count FROM events WHERE status = 'Upcoming'");
        if ($result) $upcomingEvents = $result->fetch_assoc()['count'];
        $result = $conn->query("SELECT COUNT(*) AS count FROM events WHERE status = 'Ongoing'");
        if ($result) $ongoingEvents = $result->fetch_assoc()['count'];
        $result = $conn->query("SELECT COUNT(*) AS count FROM events WHERE status = 'Completed'");
        if ($result) $completedEvents = $result->fetch_assoc()['count'];

        $orbitEvents = [];
        $orbitResult = $conn->query("SELECT event_name, event_date, event_time, status, category FROM events ORDER BY event_date ASC LIMIT 5");
        if ($orbitResult) {
            while ($row = $orbitResult->fetch_assoc()) {
                $orbitEvents[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carolinian Events Management</title>
    <link rel="stylesheet" href="style.css">
    <!-- Premium Sans-Serif Font (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <canvas id="particleCanvas"></canvas>

    <div class="ambient-glow glow-green"></div>
    <div class="ambient-glow glow-gold"></div>

    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="brand">Carolinian<span>Events</span></a>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#features">Features</a>
                <a href="view.php">Events Dashboard</a>
            </div>
        </div>
    </nav>

    <header class="hero" id="parallax-container">

        <div class="hero-content parallax-wrapper" data-speed="0.02">
            <h1 class="title">
                <span class="title-line title-line-1">Everything happening at USC,</span>
                <span class="title-line title-line-2">in one place.</span>
            </h1>

            <p class="subtitle">
                As a Carolinian, quickly find, create, and manage events across campus.
            </p>

            <div class="cta-group">
                <a href="create.php" class="btn btn-glass-primary">Create Event</a>
                <a href="events.php" class="btn btn-glass-secondary">Explore Events</a>
            </div>
        </div>

        <div class="scroll-indicator">
            <div class="mouse"></div>
        </div>

        <div class="orbit-focal-point" id="orbit-system" data-events='<?= htmlspecialchars(json_encode(isset($orbitEvents) ? $orbitEvents : []), ENT_QUOTES, 'UTF-8') ?>'>
            <div class="orbit-rings" id="orbit-rings"></div>
            <div class="orbit-core"></div>
            <!-- Cards injected via JS -->
        </div>
    </header>

    <section class="features" id="features">
        <div class="section-header reveal">
            <h2>Seamless Management</h2>
            <p>Everything you need to run successful campus events</p>
        </div>
        <div class="marquee-container reveal">
            <div class="marquee-track">
                <!-- Group 1 -->
                <div class="marquee-content">
                    <div class="card">
                        <div class="icon">✨</div>
                        <h3>Create & Customize</h3>
                        <p>Launch events with beautiful landing pages, rich descriptions, and custom ticketing options effortlessly.</p>
                    </div>
                    <div class="card">
                        <div class="icon">👥</div>
                        <h3>Manage Attendees</h3>
                        <p>Track RSVPs in real-time, scan digital tickets at the door, and communicate with guests easily.</p>
                    </div>
                    <div class="card">
                        <div class="icon">📊</div>
                        <h3>Live Analytics</h3>
                        <p>Get powerful insights into attendance, engagement, and post-event feedback automatically.</p>
                    </div>
                    <div class="card">
                        <div class="icon">✨</div>
                        <h3>Create & Customize</h3>
                        <p>Launch events with beautiful landing pages, rich descriptions, and custom ticketing options effortlessly.</p>
                    </div>
                    <div class="card">
                        <div class="icon">👥</div>
                        <h3>Manage Attendees</h3>
                        <p>Track RSVPs in real-time, scan digital tickets at the door, and communicate with guests easily.</p>
                    </div>
                    <div class="card">
                        <div class="icon">📊</div>
                        <h3>Live Analytics</h3>
                        <p>Get powerful insights into attendance, engagement, and post-event feedback automatically.</p>
                    </div>
                </div>

                <!-- Group 2 for seamless infinite scroll -->
                <div class="marquee-content">
                    <div class="card">
                        <div class="icon">✨</div>
                        <h3>Create & Customize</h3>
                        <p>Launch events with beautiful landing pages, rich descriptions, and custom ticketing options effortlessly.</p>
                    </div>
                    <div class="card">
                        <div class="icon">👥</div>
                        <h3>Manage Attendees</h3>
                        <p>Track RSVPs in real-time, scan digital tickets at the door, and communicate with guests easily.</p>
                    </div>
                    <div class="card">
                        <div class="icon">📊</div>
                        <h3>Live Analytics</h3>
                        <p>Get powerful insights into attendance, engagement, and post-event feedback automatically.</p>
                    </div>
                    <div class="card">
                        <div class="icon">✨</div>
                        <h3>Create & Customize</h3>
                        <p>Launch events with beautiful landing pages, rich descriptions, and custom ticketing options effortlessly.</p>
                    </div>
                    <div class="card">
                        <div class="icon">👥</div>
                        <h3>Manage Attendees</h3>
                        <p>Track RSVPs in real-time, scan digital tickets at the door, and communicate with guests easily.</p>
                    </div>
                    <div class="card">
                        <div class="icon">📊</div>
                        <h3>Live Analytics</h3>
                        <p>Get powerful insights into attendance, engagement, and post-event feedback automatically.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="grid stats-grid">
            <div class="stat-item reveal stagger-1">
                <h3><?= $totalEvents ?></h3>
                <p>Total Events</p>
            </div>
            <div class="stat-item reveal stagger-2">
                <h3><?= $upcomingEvents ?></h3>
                <p>Upcoming</p>
            </div>
            <div class="stat-item reveal stagger-3">
                <h3><?= $ongoingEvents ?></h3>
                <p>Ongoing</p>
            </div>
            <div class="stat-item reveal stagger-4">
                <h3><?= $completedEvents ?></h3>
                <p>Completed</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="cta-box reveal">
            <h2>Ready to host your next big event?</h2>
            <p>Join hundreds of Carolinians managing their events seamlessly.</p>
            <a href="create.php" class="btn btn-primary">Get Started Now</a>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> Carolinian Events Management System. All rights reserved.</p>
    </footer>

    <!-- Vanilla JS for Scroll Animation Triggers & Logic -->
    <script src="script.js"></script>
</body>

</html>