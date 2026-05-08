/**
 * Carolinian Events Management System — Client-Side Interactivity
 * ================================================================
 * Features:
 *   1. Scroll Reveal Animations
 *   2. Real-time Search Filtering (dashboard table)
 *   3. Delete Confirmation Modal (styled)
 *   4. Form Validation (create/edit pages)
 *   5. Flash Message Auto-dismiss
 */

document.addEventListener("DOMContentLoaded", () => {

    // =========================================================================
    // 1. Scroll Reveal — Fade-in elements with .reveal class
    // =========================================================================
    const revealElements = document.querySelectorAll('.reveal');
    revealElements.forEach(el => el.classList.add('active'));


    // =========================================================================
    // 2. Real-time Search Filtering (Dashboard Table)
    // =========================================================================
    const searchInput = document.querySelector('.search-input');
    const eventsTable = document.querySelector('.events-table');

    if (searchInput && eventsTable) {
        const tbody = eventsTable.querySelector('tbody');
        const rows = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];

        // Debounce utility to avoid filtering on every keystroke
        let debounceTimer;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = e.target.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    // Skip the "No events found" empty row
                    if (row.querySelector('.empty-row')) return;

                    const eventName = row.querySelector('.event-name-cell');
                    const locationCell = row.cells[3]; // Location column
                    const categoryCell = row.cells[4]; // Category column

                    const nameText = eventName ? eventName.textContent.toLowerCase() : '';
                    const locationText = locationCell ? locationCell.textContent.toLowerCase() : '';
                    const categoryText = categoryCell ? categoryCell.textContent.toLowerCase() : '';

                    const matches = nameText.includes(query) ||
                                    locationText.includes(query) ||
                                    categoryText.includes(query);

                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });

                // Show/hide "no results" message
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
            }, 200); // 200ms debounce
        });

        // Prevent form submission on Enter for real-time search (keep server-side as fallback)
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    }


    // =========================================================================
    // 3. Delete Confirmation Modal (Styled)
    // =========================================================================
    // Create modal DOM once (reused for all delete actions)
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

    // Close modal
    function closeModal() {
        modalOverlay.classList.remove('modal-active');
        pendingDeleteId = null;
    }

    // Open modal
    function openDeleteModal(id) {
        pendingDeleteId = id;
        modalOverlay.classList.add('modal-active');
    }

    // Confirm delete
    if (modalConfirmBtn) {
        modalConfirmBtn.addEventListener('click', () => {
            if (pendingDeleteId !== null) {
                window.location.href = `delete.php?id=${pendingDeleteId}`;
            }
        });
    }

    // Cancel delete
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', closeModal);
    }

    // Close on overlay click (outside modal card)
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalOverlay.classList.contains('modal-active')) {
            closeModal();
        }
    });

    // Expose globally for inline onclick handlers
    window.confirmDelete = openDeleteModal;


    // =========================================================================
    // 4. Form Validation (Create & Edit Pages)
    // =========================================================================
    const eventForm = document.querySelector('form[action*="create.php"], form[action*="update.php"]');

    if (eventForm) {
        const fields = {
            event_name: { label: 'Event Name', minLength: 3 },
            organizer:  { label: 'Organizer',  minLength: 2 },
            event_date: { label: 'Event Date', type: 'date' },
            event_time: { label: 'Event Time', type: 'time' },
            location:   { label: 'Location',   minLength: 2 }
        };

        // Show inline validation error
        function showFieldError(input, message) {
            clearFieldError(input);
            input.classList.add('form-control-error');
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.textContent = message;
            input.parentNode.appendChild(errorEl);
        }

        // Clear inline validation error
        function clearFieldError(input) {
            input.classList.remove('form-control-error');
            const existing = input.parentNode.querySelector('.field-error');
            if (existing) existing.remove();
        }

        // Validate a single field
        function validateField(name, input) {
            const rules = fields[name];
            if (!rules) return true;

            const value = input.value.trim();

            if (!value) {
                showFieldError(input, `${rules.label} is required.`);
                return false;
            }

            if (rules.minLength && value.length < rules.minLength) {
                showFieldError(input, `${rules.label} must be at least ${rules.minLength} characters.`);
                return false;
            }

            if (rules.type === 'date') {
                const dateVal = new Date(value);
                if (isNaN(dateVal.getTime())) {
                    showFieldError(input, 'Please enter a valid date.');
                    return false;
                }
            }

            clearFieldError(input);
            return true;
        }

        // Real-time validation on blur
        Object.keys(fields).forEach(name => {
            const input = eventForm.querySelector(`[name="${name}"]`);
            if (input) {
                input.addEventListener('blur', () => validateField(name, input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('form-control-error')) {
                        validateField(name, input);
                    }
                });
            }
        });

        // Validate all on submit
        eventForm.addEventListener('submit', (e) => {
            let isValid = true;

            Object.keys(fields).forEach(name => {
                const input = eventForm.querySelector(`[name="${name}"]`);
                if (input && !validateField(name, input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = eventForm.querySelector('.form-control-error');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }


    // =========================================================================
    // 5. Flash Messages — Auto-dismiss success/error notifications
    // =========================================================================
    const urlParams = new URLSearchParams(window.location.search);
    const successType = urlParams.get('success');

    if (successType) {
        const messages = {
            created: '🎉 Event created successfully!',
            updated: '✅ Event updated successfully!',
            deleted: '🗑️ Event deleted successfully!'
        };

        const message = messages[successType];
        if (message) {
            // Create flash message element
            const flash = document.createElement('div');
            flash.className = 'flash-message flash-success';
            flash.innerHTML = `
                <span class="flash-text">${message}</span>
                <button class="flash-close" aria-label="Dismiss">&times;</button>
            `;
            document.body.appendChild(flash);

            // Animate in
            requestAnimationFrame(() => {
                flash.classList.add('flash-visible');
            });

            // Close button
            flash.querySelector('.flash-close').addEventListener('click', () => {
                flash.classList.remove('flash-visible');
                setTimeout(() => flash.remove(), 400);
            });

            // Auto-dismiss after 4 seconds
            setTimeout(() => {
                flash.classList.remove('flash-visible');
                setTimeout(() => flash.remove(), 400);
            }, 4000);

            // Clean URL (remove ?success=xxx) without reloading
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, '', cleanUrl);
        }
    }

});
