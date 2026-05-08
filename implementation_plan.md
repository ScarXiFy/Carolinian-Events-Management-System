# 🏛️ Carolinian Events Management System — Implementation Plan

## Overview
A CRUD web application for managing campus events at USC, built with **HTML/CSS/JS + PHP + MySQL (XAMPP)**.

---

## 📁 File Structure

```
Carolinian-Events-Management-System/
├── Documentation/
│   ├── Landing-Page-Wireframe.png
│   ├── Events-Dashboard-Wireframe.png
│   ├── Create-Event-Form-Wireframe.png
│   ├── use-case-diagram.png
│   └── erd.png                    ← To be created
├── database/
│   └── schema.sql                 ✅ DB schema + sample data
├── index.php                      ✅ Landing page
├── events.php                     ← Events dashboard (Read)
├── create.php                     ← Create event form + handler
├── update.php                     ← Edit event form + handler
├── delete.php                     ← Delete handler
├── view.php                       ← Single event detail view
├── dbconfig.php                   ✅ Database connection
├── style.css                      ✅ Full design system
├── script.js                      ← Client-side interactivity
├── hero-image.png                 ✅ Generated hero illustration
├── README.md
└── Instructions.md
```

---

## 🗄️ Database Schema

### Database: `carolinian_events_db`

### Table: `events`

| Column        | Type                | Constraints                  |
|---------------|---------------------|------------------------------|
| `id`          | INT(11)             | PRIMARY KEY, AUTO_INCREMENT  |
| `event_name`  | VARCHAR(255)        | NOT NULL                     |
| `organizer`   | VARCHAR(255)        | NOT NULL                     |
| `description` | TEXT                | NOT NULL                     |
| `event_date`  | DATE                | NOT NULL                     |
| `event_time`  | TIME                | NOT NULL                     |
| `location`    | VARCHAR(255)        | NOT NULL                     |
| `category`    | ENUM(...)           | NOT NULL, DEFAULT 'Academic' |
| `status`      | ENUM(...)           | NOT NULL, DEFAULT 'Upcoming' |
| `created_at`  | TIMESTAMP           | DEFAULT CURRENT_TIMESTAMP    |

**Category values:** `Academic`, `Cultural`, `Sports`, `Social`, `Other`  
**Status values:** `Upcoming`, `Ongoing`, `Completed`, `Cancelled`

---

## 🎨 Design System

### Color Palette (USC Carolinian-inspired)
| Token              | Value       | Usage                          |
|--------------------|-------------|--------------------------------|
| `--primary`        | `#1B3A5C`   | Navy blue — headers, accents   |
| `--primary-light`  | `#2A5A8C`   | Hover states                   |
| `--accent`         | `#D4A843`   | Gold — CTAs, highlights        |
| `--accent-light`   | `#E8C76A`   | Hover on gold elements         |
| `--bg`             | `#F8F9FA`   | Page background                |
| `--surface`        | `#FFFFFF`   | Cards, tables                  |
| `--text`           | `#1A1A2E`   | Primary text                   |
| `--text-muted`     | `#6B7280`   | Secondary text                 |
| `--success`        | `#10B981`   | Ongoing status badge           |
| `--warning`        | `#F59E0B`   | Upcoming status badge          |
| `--danger`         | `#EF4444`   | Delete buttons, Cancelled      |
| `--info`           | `#3B82F6`   | Completed badge                |

### Typography
- **Font:** `Inter` (Google Fonts) with fallback to system sans-serif
- **Headings:** Bold, tracking tight
- **Body:** 16px base, 1.6 line height

---

## 📄 Pages Breakdown

### 1. Landing Page (`index.php`)
Maps to: [Landing-Page-Wireframe.png](file:///d:/BS%20CpE%203rd%20Year%20(2nd%20Sem)/CPE%203222/Carolinian-Events-Management-System/Documentation/Landing-Page-Wireframe.png)

- **Navbar** — Logo + nav links (Home, Events, Create)
- **Hero Section** — Bold heading, subtitle, two CTA buttons (Create Event → `create.php`, View Dashboard → `events.php`), hero illustration/image
- **Stats Section** — 4 stat cards (Total, Upcoming, Ongoing, Completed) dynamically fetched from DB
- **How It Works** — 3-step cards
- **Footer** — Branding + links

### 2. Events Dashboard (`events.php`)
Maps to: [Events-Dashboard-Wireframe.png](file:///d:/BS%20CpE%203rd%20Year%20(2nd%20Sem)/CPE%203222/Carolinian-Events-Management-System/Documentation/Events-Dashboard-Wireframe.png)

- **Search bar** — Filter events by name (JS + PHP)
- **Sort dropdown** — Sort by: Newest First, Oldest First, Name A-Z, Name Z-A (fulfills the "two sorting methods" requirement)
- **"+ Add New Event" button** → links to `create.php`
- **Events table** — Columns: #, Event, Date & Time, Location, Category, Status, Actions (View / Edit / Delete)
- **Load More / Pagination**

### 3. Create Event (`create.php`)
Maps to: [Create-Event-Form-Wireframe.png](file:///d:/BS%20CpE%203rd%20Year%20(2nd%20Sem)/CPE%203222/Carolinian-Events-Management-System/Documentation/Create-Event-Form-Wireframe.png)

- **Form fields:** Event Name, Description, Date, Time, Location, Organizer, Category (dropdown), Status (dropdown)
- **Buttons:** Cancel (→ `events.php`), + Create Event (submit)
- **Backend:** INSERT into `events` table, redirect to `events.php` with success message

### 4. Update Event (`update.php`)
- Same form as Create, pre-filled with existing data
- **Backend:** UPDATE query, redirect to `events.php`

### 5. Delete Event (`delete.php`)
- Confirmation modal (JS) before deletion
- **Backend:** DELETE query, redirect to `events.php`

### 6. View Event (`view.php`)
- Single event detail card with all fields displayed nicely
- Back to Dashboard button

---

## ✅ Build Checklist

### Phase 1: Foundation ✅
- [x] Create `dbconfig.php` — DB connection
- [x] Create SQL schema file (`database/schema.sql`) + 5 sample records
- [x] Create `style.css` — Full design system (variables, resets, utilities, component styles)

### Phase 2: Landing Page ✅
- [x] Build `index.php` — Navbar, Hero, Stats (dynamic), How It Works, Footer
- [x] Wire up dynamic stat counts from DB (auto-detects if DB exists)
- [x] Generate hero illustration (`hero-image.png`)

### Phase 3: CRUD — Core ✅
- [x] Build `create.php` — Form UI + INSERT logic
- [x] Build `events.php` — Dashboard table + READ logic
- [x] Build `update.php` — Edit form + UPDATE logic  
- [x] Build `delete.php` — DELETE logic with confirmation
- [x] Build `view.php` — Single event detail view

### Phase 4: Interactivity ✅
- [x] Build `script.js` — Search filtering, sort toggling, delete confirmation modal, form validation
- [x] Implement sorting (2 methods: by date, by name)

### Phase 5: Polish
- [ ] Add status badge colors
- [ ] Responsive design (mobile-friendly)
- [ ] Flash messages for success/error
- [ ] Final testing of all CRUD operations

### Phase 6: Documentation
- [ ] Create ERD diagram
- [ ] Verify all documentation is complete

---

## 🔑 Key Requirements Mapping

| Requirement | Implementation |
|---|---|
| HTML structure | All `.php` files with semantic HTML5 |
| CSS styling | `style.css` with modern design system |
| JavaScript interactivity | `script.js` — search, sort, modals, validation |
| Forms for input/edit | `create.php`, `update.php` |
| Display data from DB | `events.php`, `index.php` stats |
| Two sorting methods | Sort by Date + Sort by Name (dropdown on dashboard) |
| MySQL database | `carolinian_events_db` via phpMyAdmin |
| PHP backend + CRUD | `dbconfig.php`, `create.php`, `events.php`, `update.php`, `delete.php` |
| Use Case Diagram | ✅ Already done |
| Wireframes | ✅ Already done |
| ERD | ⬜ To be created |
