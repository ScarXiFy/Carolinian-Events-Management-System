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

        <div class="orbit-focal-point" id="orbit-system">
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
                <h3 class="counter" data-target="<?= $totalEvents ?>">0</h3>
                <p>Total Events</p>
            </div>
            <div class="stat-item reveal stagger-2">
                <h3 class="counter" data-target="<?= $upcomingEvents ?>">0</h3>
                <p>Upcoming</p>
            </div>
            <div class="stat-item reveal stagger-3">
                <h3 class="counter" data-target="<?= $ongoingEvents ?>">0</h3>
                <p>Ongoing</p>
            </div>
            <div class="stat-item reveal stagger-4">
                <h3 class="counter" data-target="<?= $completedEvents ?>">0</h3>
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
    <script>
        // 1. Intersection Observer for Scroll Reveals (Fade up on scroll)
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        };
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); // Stop observing once revealed
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // 2. Animated Numbers Counter
        const counterObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    const duration = 2000;
                    const start = performance.now();

                    function updateNumber(currentTime) {
                        const elapsed = currentTime - start;
                        const progress = Math.min(elapsed / duration, 1);
                        const easeOut = 1 - Math.pow(1 - progress, 3);
                        counter.innerText = Math.floor(easeOut * target);

                        if (progress < 1) {
                            requestAnimationFrame(updateNumber);
                        } else {
                            counter.innerText = target;
                        }
                    }
                    requestAnimationFrame(updateNumber);
                    obs.unobserve(counter);
                }
            });
        }, {
            threshold: 0.5
        });

        document.querySelectorAll('.counter').forEach(el => counterObserver.observe(el));

        // 3. Navbar solid background on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // 4. Subtle Antigravity Mouse Parallax Effect
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('parallax-container');
            const wrappers = document.querySelectorAll('.parallax-wrapper');

            if (window.matchMedia("(min-width: 968px)").matches) {
                container.addEventListener('mousemove', (e) => {
                    const x = e.clientX - window.innerWidth / 2;
                    const y = e.clientY - window.innerHeight / 2;

                    wrappers.forEach(wrapper => {
                        const speed = parseFloat(wrapper.getAttribute('data-speed'));
                        const xOffset = x * speed;
                        const yOffset = y * speed;
                        wrapper.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                    });
                });

                container.addEventListener('mouseleave', () => {
                    wrappers.forEach(wrapper => {
                        wrapper.style.transform = `translate(0px, 0px)`;
                    });
                });
            }
        });

        // 5. High-Performance Particle Background
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');

        let particlesArray = [];
        let mouse = {
            x: null,
            y: null,
            radius: 150
        };

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        window.addEventListener('mouseleave', () => {
            mouse.x = undefined;
            mouse.y = undefined;
        });

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            initParticles();
        });

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.baseX = this.x;
                this.baseY = this.y;
                this.density = (Math.random() * 20) + 1;
                this.color = Math.random() > 0.5 ? 'rgba(212, 168, 67, 0.4)' : 'rgba(45, 106, 79, 0.4)'; // Gold & Green
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();
            }

            update() {
                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);
                let forceDirectionX = dx / distance;
                let forceDirectionY = dy / distance;
                let maxDistance = mouse.radius;
                let force = (maxDistance - distance) / maxDistance;
                let directionX = forceDirectionX * force * this.density;
                let directionY = forceDirectionY * force * this.density;

                if (distance < maxDistance && mouse.x != null) {
                    // Attraction to cursor
                    this.x += directionX;
                    this.y += directionY;
                } else {
                    // Return to base gently
                    if (this.x !== this.baseX) {
                        let dx = this.x - this.baseX;
                        this.x -= dx / 25;
                    }
                    if (this.y !== this.baseY) {
                        let dy = this.y - this.baseY;
                        this.y -= dy / 25;
                    }
                }
                this.draw();
            }
        }

        function initParticles() {
            particlesArray = [];
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            let numberOfParticles = (canvas.width * canvas.height) / 8000;
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
            }
            requestAnimationFrame(animateParticles);
        }

        initParticles();
        animateParticles();

        // 6. Radial Orbital Timeline Data & Engine
        const rawDbEvents = <?php echo json_encode(isset($orbitEvents) ? $orbitEvents : []); ?>;
        const orbitSystem = document.getElementById('orbit-system');
        const ringsContainer = document.getElementById('orbit-rings');
        const orbitCards = [];

        let eventsData = [{
                id: 1,
                name: "USC Intramurals Opening",
                date: "OCT 24",
                time: "9:00 AM",
                status: "Completed",
                statusDot: "#E53E3E",
                colorClass: "glow-red"
            },
            {
                id: 2,
                name: "CES Leadership Summit",
                date: "NOV 02",
                time: "1:00 PM",
                status: "Ongoing",
                statusDot: "#2D6A4F",
                colorClass: "glow-green"
            },
            {
                id: 3,
                name: "CpE 3rd Year Gen Assembly",
                date: "NOV 15",
                time: "3:30 PM",
                status: "Upcoming",
                statusDot: "#3182CE",
                colorClass: "glow-blue"
            }
        ];

        // If DB has events, map them!
        if (rawDbEvents && rawDbEvents.length > 0) {
            eventsData = rawDbEvents.map((ev, index) => {
                let dateObj = new Date(ev.event_date);
                let timeParts = ev.event_time.split(':');
                let hours = parseInt(timeParts[0]);
                let ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                let formattedTime = hours + ':' + timeParts[1] + ' ' + ampm;

                let monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                let formattedDate = monthNames[dateObj.getMonth()] + " " + dateObj.getDate().toString().padStart(2, '0');

                let statusColorClass = 'glow-blue';
                let statusDotColor = '#3182CE';

                if (ev.status === 'Ongoing') {
                    statusColorClass = 'glow-green';
                    statusDotColor = '#2D6A4F';
                } else if (ev.status === 'Completed') {
                    statusColorClass = 'glow-red';
                    statusDotColor = '#E53E3E';
                }

                return {
                    id: index + 1,
                    name: ev.event_name,
                    date: formattedDate,
                    time: formattedTime,
                    status: ev.status,
                    statusDot: statusDotColor,
                    colorClass: statusColorClass
                };
            });
        }

        // Reduce cards on mobile to prevent clutter
        const isMobile = window.innerWidth < 768;
        const displayEvents = isMobile ? eventsData.slice(0, 3) : eventsData;

        // Generate Radial Timeline DOM Elements
        displayEvents.forEach((ev, index) => {
            // Assign Radial Properties (Inner = Sooner, Outer = Later)
            ev.radiusX = (isMobile ? 120 : 180) + (index * (isMobile ? 40 : 65));
            ev.radiusY = ev.radiusX * 0.55; // Isometric tilt calculation
            ev.speed = 0.0015 - (index * 0.00015); // Inner rings move slightly faster
            ev.angle = index * (Math.PI * 2 / displayEvents.length); // Space them out

            // Draw visual dashed ring
            const ring = document.createElement('div');
            ring.className = `orbit-ring ${ev.colorClass}`;
            ring.style.width = `${ev.radiusX * 2}px`;
            ring.style.height = `${ev.radiusX * 2}px`;
            ringsContainer.appendChild(ring);

            // Create Event Card
            const el = document.createElement('div');
            el.className = `orbit-card ${ev.colorClass}`;

            el.innerHTML = `
                <div class="timeline-connector"></div>
                <div class="card-datetime">${ev.date} • ${ev.time}</div>
                <div class="card-title">${ev.name}</div>
                <div class="card-footer">
                    <div class="avatar-group">
                        <div class="avatar-circle" style="background: ${ev.statusDot}"></div>
                    </div>
                    <div class="avatar-count">${ev.status}</div>
                </div>
            `;
            orbitSystem.appendChild(el);
            orbitCards.push({
                el,
                data: ev
            });
        });

        // Animation Loop
        function animateOrbit() {
            orbitCards.forEach(card => {
                const ev = card.data;
                ev.angle += ev.speed;

                // Timeline elliptical path
                const x = Math.cos(ev.angle) * ev.radiusX;
                const y = Math.sin(ev.angle) * ev.radiusY;
                const depth = Math.sin(ev.angle);

                const scale = 0.85 + (depth * 0.15);
                const opacity = 0.5 + (depth + 1) * 0.25;

                let zIndex = Math.floor(depth * 10) + 20;
                if (x < -100 && !isMobile) {
                    zIndex = 15; // Slide under headline
                }

                card.el.style.transform = `translate(${x}px, ${y}px) scale(${scale})`;
                card.el.style.zIndex = zIndex;
                card.el.style.opacity = opacity;

                if (depth < -0.5) {
                    card.el.style.filter = `blur(${Math.abs(depth)*1.5}px)`;
                } else {
                    card.el.style.filter = 'none';
                }
            });

            requestAnimationFrame(animateOrbit);
        }

        // Start animation
        requestAnimationFrame(animateOrbit);
    </script>
</body>

</html>