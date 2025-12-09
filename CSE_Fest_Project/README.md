# 🌱 GreenLife – Smart Environmental Impact Dashboard

Welcome to GreenLife, a web-based system that allows users to calculate their daily carbon footprint based on vehicle and electricity usage. The system visualizes the results, provides AI-generated recommendations for improvement, and stores historical data in a MySQL database.

This project is built with a hybrid backend using PHP and Python, a vanilla HTML/CSS/JS frontend, and a MySQL database, designed to be run locally with XAMPP.

---

## ► Table of Contents
1.  [Stack Overview](#-stack-overview)
2.  [System Architecture](#-system-architecture)
    -   [High-Level Diagram](#high-level-diagram)
    -   [PHP to Python Communication](#php-to-python-communication)
3.  [Getting Started](#-getting-started)
    -   [Prerequisites](#prerequisites)
    -   [Installation Steps](#installation-steps)
4.  [How to Use](#-how-to-use)
5.  [Database Schema](#-database-schema)
6.  [Troubleshooting](#-troubleshooting)

---

## ► Stack Overview

-   **Primary Backend**: **PHP 8.x** (Handles frontend routing, API requests, and database interaction)
-   **Calculation Engine**: **Python 3.x** (Performs carbon footprint calculation and generates suggestions)
-   **Frontend**: Vanilla **HTML5**, **CSS3**, and **JavaScript (ES6)**
-   **Charts**: **Chart.js** (loaded via CDN)
-   **Database**: **MySQL**
-   **Local Server**: **XAMPP** (Apache + MySQL)

---

## ► System Architecture

The application follows a simple, multi-tiered architecture where the user-facing frontend is served by a primary PHP backend, which in turn calls a specialized Python script for heavy lifting and calculations.

### High-Level Diagram

Here is a block diagram illustrating the flow of data and control within the system:

```
+---------------+      +-----------------+      +-----------------+      
|   USER'S      |      |   APACHE SERVER |      |   PHP BACKEND   |      
|   BROWSER     |----->|   (in XAMPP)    |----->|  (calculate.php)|
+---------------+      +-----------------+      +-----------------+      
       ^                                                |
       |  (Renders HTML/CSS/JS)                         | (Calls via shell_exec)
       |                                                | 
       |      +-----------------+                       v
       |      | PYTHON SCRIPT   |      +-----------------+
       |      | (calculate.py)  |      |   MySQL DATABASE|
       |      +-----------------+      |   (in XAMPP)    |
       |                 |             +-----------------+
       |                 | (Returns JSON)        ^
       |                 |                       |
       |                 v                       | (Stores result)
       +-----------(Redirects to)----------------+
                   result.php
```

### PHP to Python Communication

A key part of this architecture is the communication between the PHP and Python scripts. This is achieved via a system command execution from PHP.

1.  **Invocation**: The `calculate.php` script uses the `shell_exec()` function to run the `python/calculate.py` script from the command line.
2.  **Data Passing**: User inputs (Car KM, Electricity Hours) are passed as **command-line arguments** to the Python script. To prevent security vulnerabilities (command injection), PHP's `escapeshellarg()` function is used to sanitize these arguments before they are added to the command string.
3.  **Data Return**: The Python script performs its calculations and generates a suggestion. It then bundles the results (footprint and suggestion) into a dictionary and prints it to **standard output (stdout)** as a **JSON formatted string**.
4.  **Data Capture**: The `shell_exec()` function in PHP captures this stdout string. The PHP script then uses `json_decode()` to parse the JSON string back into a PHP associative array, where the data can be easily accessed.

This method is simple, effective, and language-agnostic, allowing two different backend technologies to collaborate.

---

## ► Getting Started

Follow these instructions to set up and run the project on your local machine.

### Prerequisites

-   **XAMPP**: Install XAMPP from the [official Apache Friends website](https://www.apachefriends.org/index.html). This will provide you with Apache, MySQL, and PHP.
-   **Python**: Install Python 3.x from the [official Python website](https://www.python.org/downloads/). During installation, make sure to **check the box that says "Add Python to PATH"**.

### Installation Steps

**Step 1: Place Project Files**

1.  Download or clone this project repository.
2.  Move the entire project folder (e.g., `CSE_Fest`) into the `htdocs` directory inside your XAMPP installation folder.
    -   On Windows, this is typically `C:\xampp\htdocs\`.
    -   On macOS, this is typically `/Applications/XAMPP/htdocs/`.

**Step 2: Start XAMPP**

1.  Open the **XAMPP Control Panel**.
2.  Start the **Apache** and **MySQL** modules.

**Step 3: Create and Import the Database**

1.  Open your web browser and navigate to `http://localhost/phpmyadmin/`.
2.  Click on the **"New"** button in the left sidebar to create a new database.
3.  Enter the database name as `greenlife_db` and click **"Create"**.
4.  Once the database is created, select it from the left sidebar.
5.  Click on the **"Import"** tab at the top of the page.
6.  Click **"Choose File"** and select the `schema.sql` file located in the `sql/` directory of this project.
7.  Scroll down and click the **"Go"** button to run the SQL script. This will create the `footprints` table and insert the sample data.

**Step 4: Configure Database Connection (if needed)**

The file `config/db.php` is pre-configured for a default XAMPP setup (username `root` with no password). If your MySQL setup is different, open `config/db.php` and update the following lines with your credentials:

```php
define('DB_USERNAME', 'your_mysql_username');
define('DB_PASSWORD', 'your_mysql_password');
```

---

## ► How to Use

1.  With Apache and MySQL running, open your browser and navigate to the project's URL:
    -   `http://localhost/CSE_Fest/` (if you named the folder `CSE_Fest`)
2.  Enter your daily car travel distance and electricity usage hours into the form.
3.  Click the **"Calculate My Footprint"** button.
4.  The page will redirect to the results screen, showing you your calculated footprint, a suggestion, and a chart comparing your result to sample data.

---

## ► Database Schema

The project uses a single table named `footprints` with the following structure:

| Field         | Type            | Description                                                 |
|---------------|-----------------|-------------------------------------------------------------|
| `id`          | `INT` (PK, AI)  | A unique identifier for each entry.                         |
| `car_km`      | `FLOAT`         | The daily car kilometers input by the user.                 |
| `elect_hours` | `FLOAT`         | The daily electricity usage hours input by the user.        |
| `footprint`   | `FLOAT`         | The calculated carbon footprint in kg CO2e.                 |
| `suggestion`  | `TEXT`          | An AI-generated suggestion for reducing the footprint.      |
| `created_at`  | `TIMESTAMP`     | The timestamp of when the record was created.               |

*(PK = Primary Key, AI = Auto Increment)*

---

## ► Troubleshooting

-   **500 Internal Server Error or Blank Page**:
    -   This is often a PHP error. Check the Apache error log in XAMPP for details.
    -   Ensure `config/db.php` has the correct database credentials.
    -   Make sure the `python` command works on your system. Open a terminal/CMD and type `python --version`. If it's not found, you may need to add it to your system's PATH environment variable or use the full path to the executable in `calculate.php` (e.g., `C:\Python39\python.exe`).

-   **"Could not calculate the footprint" Error**:
    -   This means the PHP script could not get a valid JSON response from the Python script.
    -   Check for permissions issues. The web server might not have permission to execute the `.py` file.
    -   Temporarily modify `calculate.php` to print the output of `shell_exec($command)` to see if the Python script is throwing an error.

-   **Database Connection Error**:
    -   Ensure the **MySQL** service is running in your XAMPP Control Panel.
    -   Double-check that the database `greenlife_db` was created and that the table `footprints` exists.
    -   Verify your credentials in `config/db.php` are correct.



Here is a step-by-step guide to run your project easily when you clone this project file:

  Step 1: Start Your Local Server

   1. Open the XAMPP Control Panel on your computer.
   2. Make sure the Apache module is started (click the "Start" button).
   3. Make sure the MySQL module is started.

  Step 2: Make Sure the Project is in the Right Place

   1. Confirm that your project folder, CSE_Fest, is inside the htdocs directory of your XAMPP installation.
   2. The full path should be: C:\xampp\htdocs\CSE_Fest

  Step 3: Verify the Database

   1. Make sure you have successfully created the greenlife_db database in phpMyAdmin and imported the sql/schema.sql file. The footprints table should exist inside it.

  Step 4: Run the Application in Your Browser

   1. Open your web browser (like Chrome, Firefox, or Edge).
   2. In the address bar, type the following URL and press Enter:
   1     http://localhost/CSE_Fest_Project/

  That's it! The "GreenLife" carbon footprint calculator form should now be visible in your browser. You can enter the values and use the application.

  