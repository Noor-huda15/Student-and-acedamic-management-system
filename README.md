# 🎓 Student & Academic Management System

A web-based application designed to digitize and manage academic records. This system allows administrators to handle student enrollments, track courses, and manage grades efficiently using a PHP backend and MySQL database.

---

## ✨ Features
* **Admin Dashboard:** Real-time statistics of students, teachers, and courses.
* **Student Management:** Add, edit, view, and delete student profiles.
* **Course & Subject Tracking:** Organize academic curriculums and faculty assignments.
* **Grade Management:** Input and manage student marks and performance.
* **Secure Login:** Role-based access for system security.

🛠️ Tech Stack
Frontend: HTML5, CSS3, JavaScript
Backend: PHP
Database: MySQL (phpMyAdmin)
Server: Apache (XAMPP)

⚙️ How to Install and Run

1. Download the Project:
   Clone or download this repository to your local machine.

2. Move to Server Folder:
   Place the project folder inside your `htdocs` directory (if using XAMPP) or `www` directory (if using WAMP).

3. Import the Database:
   * Open phpMyAdmin in your browser.
   * Create a new database (e.g., `student_db`).
   * Click the Import tab and select the `database.sql` file provided in this repository.
   * Click Go.

4. Database Connection:
   Open your connection file (usually `db.php` or `config.php`) and ensure the database name, username, and password match your local settings.

5. Run in Browser:
   Go to `http://localhost/your-project-folder-name/`

---

 📂 Project Structure
Student-and-acedamic-management-system/

index.php – Main login or landing page.

admin.php – Admin dashboard and management logic.

config.php – Database connection and configuration settings.

faculty.php – Faculty management and dashboard.

get_courses.php – Backend logic for fetching course data.

logout.php – Logic for terminating user sessions.

student.php – Student portal and academic records page.

README.md – Documentation for the project.

student_and_acedamic_management_system.sql – The database export file (SQL schema).

Thank You :)