<?php
// Start the session to use session variables
session_start();
// Include only the database connection, not the full header
include 'db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "You are not authorized to perform this action.";
    $_SESSION['message_type'] = "danger";
    header("Location: dashboard.php");
    exit();
}

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id > 0) {
    // Start transaction for safety
    $conn->begin_transaction();
    try {
        // Temporarily disable foreign key checks to bypass the faulty constraint
        $conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Step 1: Delete all attendance records for the student.
        $stmt_att = $conn->prepare("DELETE FROM attendance WHERE student_id = ?");
        $stmt_att->bind_param("i", $student_id);
        $stmt_att->execute();
        $stmt_att->close();

        // Step 2: Delete the student's login account from the users table.
        $stmt_user = $conn->prepare("DELETE FROM users WHERE student_id = ?");
        $stmt_user->bind_param("i", $student_id);
        $stmt_user->execute();
        $stmt_user->close();

        // Step 3: Finally, delete the student from the students table.
        $stmt_student = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt_student->bind_param("i", $student_id);
        $stmt_student->execute();
        $stmt_student->close();

        // Re-enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        // If all steps succeed, commit the transaction
        $conn->commit();
        
        $_SESSION['message'] = "Student and all related records deleted successfully!";
        $_SESSION['message_type'] = "success";

    } catch (Exception $e) {
        // If any step fails, roll back the entire transaction
        $conn->rollback();
        // Ensure foreign key checks are re-enabled even if an error occurs
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        $_SESSION['message'] = "Error deleting student: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid student ID.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to the students list page to show the result
header("Location: students.php");
exit();
?>
