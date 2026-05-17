document.addEventListener("DOMContentLoaded", () => {

    // =========================================================================
    // 1. Scroll Reveal — Fade-in elements with .reveal class
    // =========================================================================
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));


    // =========================================================================
    // 2. Navbar & Navigation Logic
    // =========================================================================
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }


    // =========================================================================
    // 3. Parallax Effect (Hero Section)
    // =========================================================================
    const parallaxContainer = document.getElementById('parallax-container');
    const parallaxWrappers = document.querySelectorAll('.parallax-wrapper');

    if (parallaxContainer && window.matchMedia("(min-width: 968px)").matches) {
        parallaxContainer.addEventListener('mousemove', (e) => {
            const x = e.clientX - window.innerWidth / 2;
            const y = e.clientY - window.innerHeight / 2;

            parallaxWrappers.forEach(wrapper => {
                const speed = parseFloat(wrapper.getAttribute('data-speed'));
                const xOffset = x * speed;
                const yOffset = y * speed;
                wrapper.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
        });

        parallaxContainer.addEventListener('mouseleave', () => {
            parallaxWrappers.forEach(wrapper => {
                wrapper.style.transform = `translate(0px, 0px)`;
            });
        });
    }


    // =========================================================================
    // 4. Particle Background Engine
    // =========================================================================
    const canvas = document.getElementById('particleCanvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particlesArray = [];
        let mouse = { x: null, y: null, radius: 150 };

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
                this.color = Math.random() > 0.5 ? 'rgba(212, 168, 67, 0.4)' : 'rgba(45, 106, 79, 0.4)';
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
                    this.x += directionX;
                    this.y += directionY;
                } else {
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
    }


    // =========================================================================
    // 5. Radial Orbital Timeline Engine
    // =========================================================================
    const orbitSystem = document.getElementById('orbit-system');
    const ringsContainer = document.getElementById('orbit-rings');

    if (orbitSystem && ringsContainer) {
        const rawDbEvents = JSON.parse(orbitSystem.getAttribute('data-events') || '[]');
        const orbitCards = [];

        let eventsData = [
            { id: 1, name: "CpE 3rd Year 2nd Semester", date: "JUN 01", time: "11:59 PM", status: "Ongoing", statusDot: "#2D6A4F", colorClass: "glow-green" },
            { id: 2, name: "Proposal Hearing", date: "MAY 08", time: "10:00 AM", status: "Completed", statusDot: "#E53E3E", colorClass: "glow-red" },
            { id: 3, name: "John Enico's Birthday", date: "AUG 28", time: "8:28 PM", status: "Upcoming", statusDot: "#3182CE", colorClass: "glow-blue" }
        ];

        if (rawDbEvents && rawDbEvents.length > 0) {
            eventsData = rawDbEvents.map((ev, index) => {
                const dateObj = new Date(ev.event_date);
                const timeParts = ev.event_time.split(':');
                let hours = parseInt(timeParts[0]);
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12 || 12;
                const formattedTime = `${hours}:${timeParts[1]} ${ampm}`;

                const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                const formattedDate = `${monthNames[dateObj.getMonth()]} ${dateObj.getDate().toString().padStart(2, '0')}`;

                let statusColorClass = 'glow-blue', statusDotColor = '#3182CE';
                if (ev.status === 'Ongoing') { statusColorClass = 'glow-green'; statusDotColor = '#2D6A4F'; }
                else if (ev.status === 'Completed') { statusColorClass = 'glow-red'; statusDotColor = '#E53E3E'; }

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

        const isMobile = window.innerWidth < 768;
        const displayEvents = isMobile ? eventsData.slice(0, 3) : eventsData;

        displayEvents.forEach((ev, index) => {
            ev.radiusX = (isMobile ? 120 : 180) + (index * (isMobile ? 40 : 65));
            ev.radiusY = ev.radiusX * 0.55;
            ev.speed = 0.0015 - (index * 0.00015);
            ev.angle = index * (Math.PI * 2 / displayEvents.length);

            const ring = document.createElement('div');
            ring.className = `orbit-ring ${ev.colorClass}`;
            ring.style.width = `${ev.radiusX * 2}px`;
            ring.style.height = `${ev.radiusX * 2}px`;
            ringsContainer.appendChild(ring);

            const el = document.createElement('div');
            el.className = `orbit-card ${ev.colorClass}`;
            el.innerHTML = `
                <div class="timeline-connector"></div>
                <div class="card-datetime">${ev.date} • ${ev.time}</div>
                <div class="card-title">${ev.name}</div>
                <div class="card-footer">
                    <div class="avatar-group"><div class="avatar-circle" style="background: ${ev.statusDot}"></div></div>
                    <div class="avatar-count">${ev.status}</div>
                </div>
            `;
            orbitSystem.appendChild(el);
            orbitCards.push({ el, data: ev });
        });

        function animateOrbit() {
            orbitCards.forEach(card => {
                const ev = card.data;
                ev.angle += ev.speed;
                const x = Math.cos(ev.angle) * ev.radiusX;
                const y = Math.sin(ev.angle) * ev.radiusY;
                const depth = Math.sin(ev.angle);
                const scale = 0.85 + (depth * 0.15);
                const opacity = 0.5 + (depth + 1) * 0.25;

                let zIndex = Math.floor(depth * 10) + 20;
                if (x < -100 && !isMobile) zIndex = 15;

                card.el.style.transform = `translate(${x}px, ${y}px) scale(${scale})`;
                card.el.style.zIndex = zIndex;
                card.el.style.opacity = opacity;
                card.el.style.filter = depth < -0.5 ? `blur(${Math.abs(depth)*1.5}px)` : 'none';
            });
            requestAnimationFrame(animateOrbit);
        }
        animateOrbit();
    }


    // =========================================================================
    // 6. Real-time Search Filtering (Dashboard Table)
    // =========================================================================
    const searchInput = document.querySelector('.search-input');
    const eventsTable = document.querySelector('.events-table');

    if (searchInput && eventsTable) {
        const tbody = eventsTable.querySelector('tbody');
        const rows = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];

        let debounceTimer;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = e.target.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('.empty-row')) return;
                    const eventName = row.querySelector('.event-name-cell');
                    const locationCell = row.cells[3];
                    const categoryCell = row.cells[4];
                    const nameText = eventName ? eventName.textContent.toLowerCase() : '';
                    const locationText = locationCell ? locationCell.textContent.toLowerCase() : '';
                    const categoryText = categoryCell ? categoryCell.textContent.toLowerCase() : '';

                    const matches = nameText.includes(query) || locationText.includes(query) || categoryText.includes(query);
                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });

                let noResultsRow = tbody.querySelector('.js-no-results');
                if (visibleCount === 0 && query.length > 0) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'js-no-results';
                        noResultsRow.innerHTML = '<td colspan="7" class="empty-row">No events match your search.</td>';
                        tbody.appendChild(noResultsRow);
                    }
                    noResultsRow.style.display = '';
                } else if (noResultsRow) {
                    noResultsRow.style.display = 'none';
                }
            }, 200);
        });

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });
    }


    // =========================================================================
    // 7. Delete Confirmation Modal (Styled)
    // =========================================================================
    if (!document.getElementById('deleteModal')) {
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'modal-overlay';
        modalOverlay.id = 'deleteModal';
        modalOverlay.innerHTML = `
            <div class="modal-card">
                <div class="modal-icon">⚠️</div>
                <h2 class="modal-title">Delete Event?</h2>
                <p class="modal-message">Are you sure you want to delete this event? This action cannot be undone.</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary modal-cancel" id="modalCancel">Cancel</button>
                    <button class="btn btn-primary btn-danger modal-confirm" id="modalConfirm">Delete</button>
                </div>
            </div>
        `;
        document.body.appendChild(modalOverlay);

        let pendingDeleteId = null;
        const modalCancelBtn = document.getElementById('modalCancel');
        const modalConfirmBtn = document.getElementById('modalConfirm');

        const closeModal = () => {
            modalOverlay.classList.remove('modal-active');
            pendingDeleteId = null;
        };

        const openDeleteModal = (id) => {
            pendingDeleteId = id;
            modalOverlay.classList.add('modal-active');
        };

        if (modalConfirmBtn) {
            modalConfirmBtn.addEventListener('click', () => {
                if (pendingDeleteId !== null) window.location.href = `delete.php?id=${pendingDeleteId}`;
            });
        }
        if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', (e) => { if (e.target === modalOverlay) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modalOverlay.classList.contains('modal-active')) closeModal(); });

        window.confirmDelete = openDeleteModal;
    }


    // =========================================================================
    // 8. Form Validation (Create & Edit Pages)
    // =========================================================================
    const eventForm = document.querySelector('form[action*="create.php"], form[action*="update.php"]');
    if (eventForm) {
        const fields = {
            event_name: { label: 'Event Name', minLength: 3 },
            organizer:  { label: 'Organizer',  minLength: 2 },
            event_date: { label: 'Event Date', type: 'date' },
            event_time: { label: 'Event Time', type: 'time' },
            location_select: { label: 'Location', minLength: 1 }
        };

        const showFieldError = (input, message) => {
            clearFieldError(input);
            input.classList.add('form-control-error');
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.textContent = message;
            input.parentNode.appendChild(errorEl);
        };

        const clearFieldError = (input) => {
            input.classList.remove('form-control-error');
            const existing = input.parentNode.querySelector('.field-error');
            if (existing) existing.remove();
        };

        const validateField = (name, input) => {
            const rules = fields[name];
            if (!rules) return true;
            const value = input.value.trim();
            if (!value) { showFieldError(input, `${rules.label} is required.`); return false; }
            if (rules.minLength && value.length < rules.minLength) { showFieldError(input, `${rules.label} must be at least ${rules.minLength} characters.`); return false; }
            if (rules.type === 'date') { const dateVal = new Date(value); if (isNaN(dateVal.getTime())) { showFieldError(input, 'Please enter a valid date.'); return false; } }
            clearFieldError(input);
            return true;
        };

        Object.keys(fields).forEach(name => {
            const input = eventForm.querySelector(`[name="${name}"]`);
            if (input) {
                input.addEventListener('blur', () => validateField(name, input));
                input.addEventListener('input', () => { if (input.classList.contains('form-control-error')) validateField(name, input); });
            }
        });

        eventForm.addEventListener('submit', (e) => {
            let isValid = true;
            Object.keys(fields).forEach(name => {
                const input = eventForm.querySelector(`[name="${name}"]`);
                if (input && !validateField(name, input)) isValid = false;
            });

            // Validate custom location if "Other" is selected
            const locSelect = eventForm.querySelector('#location_select');
            const locOther = eventForm.querySelector('#location_other');
            if (locSelect && locSelect.value === 'Other' && locOther && !locOther.value.trim()) {
                showFieldError(locOther, 'Please enter a custom location.');
                isValid = false;
            }

            // Validate custom category if "Other" is selected
            const catSelect = eventForm.querySelector('#category_select');
            const catOther = eventForm.querySelector('#category_other');
            if (catSelect && catSelect.value === 'Other' && catOther && !catOther.value.trim()) {
                showFieldError(catOther, 'Please enter a custom category.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                const firstError = eventForm.querySelector('.form-control-error');
                if (firstError) { firstError.focus(); firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
            }
        });
    }


    // =========================================================================
    // 8b. "Other" Toggle for Location & Category Dropdowns
    // =========================================================================
    const locationSelect = document.getElementById('location_select');
    const locationOther = document.getElementById('location_other');
    if (locationSelect && locationOther) {
        const toggleLocation = () => {
            if (locationSelect.value === 'Other') {
                locationOther.style.display = 'block';
                locationOther.required = true;
                locationSelect.removeAttribute('required');
            } else {
                locationOther.style.display = 'none';
                locationOther.required = false;
                locationOther.value = '';
            }
        };
        locationSelect.addEventListener('change', toggleLocation);
        // Run on load for edit pages with pre-selected "Other"
        toggleLocation();
    }

    const categorySelect = document.getElementById('category_select');
    const categoryOther = document.getElementById('category_other');
    const categoryHidden = document.getElementById('category_final');
    if (categorySelect && categoryOther && categoryHidden) {
        const syncCategoryHidden = () => {
            if (categorySelect.value === 'Other' && categoryOther.value.trim()) {
                categoryHidden.value = categoryOther.value.trim();
            } else if (categorySelect.value === 'Other') {
                categoryHidden.value = 'Other';
            } else {
                categoryHidden.value = categorySelect.value;
            }
        };

        const toggleCategory = () => {
            if (categorySelect.value === 'Other') {
                categoryOther.style.display = 'block';
                categoryOther.required = true;
            } else {
                categoryOther.style.display = 'none';
                categoryOther.required = false;
                categoryOther.value = '';
            }
            syncCategoryHidden();
        };
        categorySelect.addEventListener('change', toggleCategory);
        categoryOther.addEventListener('input', syncCategoryHidden);
        categoryOther.addEventListener('change', syncCategoryHidden);
        toggleCategory();

        // Failsafe: sync hidden field right before form submits
        const parentForm = categorySelect.closest('form');
        if (parentForm) {
            parentForm.addEventListener('submit', () => {
                syncCategoryHidden();
            });
        }
    }


    // =========================================================================
    // 8c. Auto-Compute Status from Event Date & Time
    // =========================================================================
    const eventDateInput = document.getElementById('event_date');
    const eventTimeInput = document.getElementById('event_time');
    const statusHidden = document.getElementById('status');
    const statusDisplay = document.getElementById('status_display');

    if (eventDateInput && eventTimeInput && statusHidden && statusDisplay) {
        const computeStatus = () => {
            const dateVal = eventDateInput.value;
            const timeVal = eventTimeInput.value;

            if (!dateVal || !timeVal) {
                statusDisplay.value = '\u2014';
                statusHidden.value = 'Upcoming';
                statusDisplay.className = 'form-control';
                return;
            }

            const now = new Date();
            const eventDateTime = new Date(`${dateVal}T${timeVal}`);
            const eventDateOnly = dateVal;
            const todayOnly = now.toISOString().slice(0, 10);

            let status;
            if (eventDateOnly < todayOnly) {
                status = 'Completed';
            } else if (eventDateOnly === todayOnly) {
                if (eventDateTime <= now) {
                    status = 'Completed';
                } else {
                    status = 'Ongoing';
                }
            } else {
                status = 'Upcoming';
            }

            statusHidden.value = status;
            statusDisplay.value = status;

            // Update visual style
            statusDisplay.className = 'form-control status-auto status-auto-' + status.toLowerCase();
        };

        eventDateInput.addEventListener('change', computeStatus);
        eventTimeInput.addEventListener('change', computeStatus);
        // Run on page load (for edit pages)
        computeStatus();
    }


    // =========================================================================
    // 9. Flash Messages Logic
    // =========================================================================
    const urlParams = new URLSearchParams(window.location.search);
    const successType = urlParams.get('success');

    if (successType) {
        const messages = { created: '🎉 Event created successfully!', updated: '✅ Event updated successfully!', deleted: '🗑️ Event deleted successfully!' };
        const message = messages[successType];
        if (message) {
            const flash = document.createElement('div');
            flash.className = 'flash-message flash-success';
            flash.innerHTML = `<span class="flash-text">${message}</span><button class="flash-close" aria-label="Dismiss">&times;</button>`;
            document.body.appendChild(flash);
            requestAnimationFrame(() => flash.classList.add('flash-visible'));
            flash.querySelector('.flash-close').addEventListener('click', () => { flash.classList.remove('flash-visible'); setTimeout(() => flash.remove(), 400); });
            setTimeout(() => { flash.classList.remove('flash-visible'); setTimeout(() => flash.remove(), 400); }, 4000);
            window.history.replaceState({}, '', window.location.pathname);
        }
    }

});
