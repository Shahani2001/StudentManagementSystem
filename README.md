# Student Management System

A comprehensive Student Management System built with PHP and MySQL. This application is designed to manage student information, attendance, and course details efficiently. It provides a user-friendly interface with different access levels for administrators, lecturers, and students.

## Features

*   **User Authentication:** Secure login system for admins, lecturers, and students.
*   **Dashboard:** A central dashboard to view key information at a glance.
*   **Student Management:** Add, edit, view, and delete student records.
*   **Attendance Tracking:** Mark and view student attendance for different courses and batches.
*   **Course & Batch Management:** Easily manage courses and batches.
*   **Reporting:** Generate attendance reports to track student performance.
*   **Role-Based Access Control:** Different functionalities are available based on user roles (Admin, Lecturer, Student).

## User Roles

The system has three distinct user roles, each with specific permissions:

### 1. Administrator

The administrator has full control over the system. Their responsibilities include:
*   Managing student, lecturer, and other admin accounts.
*   Adding, editing, and deleting courses and batches.
*   Overseeing all student records and attendance data.
*   Generating comprehensive reports for the entire system.

### 2. Lecturer

Lecturers have permissions related to their assigned courses and students:
*   Marking attendance for students in their classes.
*   Viewing the profiles of students enrolled in their courses.
*   Generating attendance reports for their specific subjects.
*   Viewing course and batch information.

### 3. Student

Students have limited access to view their own information:
*   Viewing their own attendance records.
*   Accessing their personal profile and course details.
*   Viewing course materials or announcements posted by lecturers (if this feature is added).

## Technologies Used

*   **Backend:** PHP
*   **Database:** MySQL
*   **Frontend:** HTML, CSS, Bootstrap

## Setup and Installation

To get the project running on your local machine, follow these steps:

1.  **Prerequisites:**
    *   Make sure you have a local server environment like [XAMPP](https://www.apachefriends.org/index.html) or WAMP installed.

2.  **Clone the Repository:**
    ```bash
    git clone https://github.com/MohanaPrabath/StudentManagementSystem.git
    ```

3.  **Move to Server Directory:**
    *   Move the cloned project folder to the `htdocs` directory in your XAMPP installation (e.g., `C:/xampp/htdocs/StudentManagementSystem`).

4.  **Database Setup:**
    *   Start Apache and MySQL modules in your XAMPP Control Panel.
    *   Open your web browser and navigate to `http://localhost/phpmyadmin/`.
    *   The application will automatically create the database (`attendance_system`) and the required tables when you first run it. No manual `.sql` import is needed.

5.  **Run the Application:**
    *   Open your web browser and navigate to:
        ```
        http://localhost/StudentManagementSystem/
        ```

## Usage

*   **Admin Login:**
    *   Username: `admin`
    *   Password: `admin123`
*   **Lecturer Login:**
    *   Create lecturer accounts via the admin panel.
*   **Student Login:**
    *   Student credentials are created by the admin.

---

Thank you for using the Student Management System!
