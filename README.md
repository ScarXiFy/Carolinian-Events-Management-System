# Carolinian Events Management System

The **Carolinian Events Management System** is a robust, full-stack CRUD web application designed to help the University of San Carlos (Carolinian) community seamlessly manage campus events. It provides an intuitive platform where users can discover upcoming activities, track ongoing ones, and fully administer event records—from creation and detailed viewing to updates and secure deletion—all within a dynamic, responsive interface.

## Features

Based on the implemented features in the application, the system supports:
- **Event Dashboard:** A central hub (`events.php`) displaying all events in an organized table layout.
- **Search and Sort:** Quickly find events using the search bar or sort them by newest/oldest dates, or alphabetically by name (A-Z, Z-A).
- **Create Events:** Add new campus events with comprehensive details including name, date, time, location, category, and status (`create.php`).
- **Read & View Details:** Read a summary of all events on the dashboard or view complete individual event details (`view.php`).
- **Update Events:** Edit and modify existing event information effortlessly (`update.php`).
- **Delete Events:** Securely remove event records with interactive confirmation prompts (`delete.php`).
- **Landing Page Stats:** A dynamic landing page (`index.php`) featuring a modern UI with parallax and particle effects, highlighting live statistics (Total, Upcoming, Ongoing, and Completed events).

## Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Backend:** PHP
- **Database:** MySQL (via phpMyAdmin)
- **Environment:** XAMPP

## File Structure

```text
Carolinian-Events-Management-System/
├── database/
│   └── schema.sql             # SQL database dump to import
├── Documentation/             # Project documentation (Diagrams, Wireframes)
├── create.php                 # Page to create/add new events
├── dbconfig.php               # Database connection configuration
├── delete.php                 # Logic to handle event deletion
├── events.php                 # Main dashboard listing all events
├── index.php                  # Landing page with statistics and navigation
├── script.js                  # Frontend interactivity and animations
├── style.css                  # Custom styling for the entire application
├── update.php                 # Page to edit/update existing events
└── view.php                   # Page to view specific event details
```

## XAMPP Setup Instructions

Follow these beginner-friendly steps to run the application locally on your machine:

1. **Install XAMPP:** Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/).
2. **Start Services:** Open the XAMPP Control Panel and click **Start** for both **Apache** and **MySQL**.
3. **Set Up the Project Folder:**
   - Locate your XAMPP installation folder (usually `C:\xampp` on Windows).
   - Open the `htdocs` directory (`C:\xampp\htdocs`).
   - Move or clone the entire `Carolinian-Events-Management-System` project folder into this `htdocs` directory.
4. **Import the Database:**
   - Open your web browser and go to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
   - In the left sidebar, click **New** to create a new database.
   - Name the database `carolinian_events_db` and click **Create**.
   - Select the newly created `carolinian_events_db` database.
   - Click the **Import** tab at the top.
   - Click **Choose File** and navigate to `htdocs/Carolinian-Events-Management-System/database/schema.sql`.
   - Scroll down and click **Import** to populate the tables.
5. **Access the Application:**
   - Open your web browser and navigate to [http://localhost/Carolinian-Events-Management-System/](http://localhost/Carolinian-Events-Management-System/).
   - The application landing page should now load correctly!

## Usage Notes

- **Database Errors:** If you see a "Database Connection Failed" screen, ensure that XAMPP's Apache and MySQL services are actively running and that the database name matches exactly (`carolinian_events_db`).
- **Responsive Design:** The app is designed to work on both desktop and mobile screens. Resize your browser window to test the responsive layout.
- **Event Status:** When creating or updating an event, use statuses like "Upcoming", "Ongoing", or "Completed" to accurately reflect them in the landing page statistics.
