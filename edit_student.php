<?php
include 'header.php';
// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission for updating a student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_student'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $batch_id = (int)$_POST['batch_id'];

    if (!empty($name) && !empty($email) && $batch_id > 0) {
        // Start transaction
        $conn->begin_transaction();
        try {
            // 1. Update students table
            $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, batch_id = ? WHERE student_id = ?");
            $stmt->bind_param("ssii", $name, $email, $batch_id, $student_id);
            $stmt->execute();
            $stmt->close();

            // 2. Update users table (username is the email)
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE student_id = ?");
            $stmt->bind_param("si", $email, $student_id);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();
            $success_message = "Student details updated successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            if ($conn->errno == 1062) {
                $error_message = "Error: Another student or user already exists with this email.";
            } else {
                $error_message = "An error occurred during the update. " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}

// Fetch the student's current details
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
if ($student_result->num_rows == 1) {
    $student = $student_result->fetch_assoc();
} else {
    // Redirect if student not found
    header("Location: students.php");
    exit();
}
$stmt->close();

// Fetch all batches for the dropdown
$batches_result = $conn->query("SELECT * FROM batches ORDER BY batch_name");
?>

<div class="container mt-4">
    <h1 class="mb-4">Edit Student</h1>

    <div class="card">
        <div class="card-header">
            <h3>Editing Details for <?php echo htmlspecialchars($student['name']); ?></h3>
        </div>
        <div class="card-body">
            <?php if(isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
            <?php if(isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>
            <form action="edit_student.php?id=<?php echo $student_id; ?>" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address (Username)</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="batch_id" class="form-label">Batch</label>
                    <select class="form-select" id="batch_id" name="batch_id" required>
                        <option value="">Select a Batch</option>
                        <?php while($batch = $batches_result->fetch_assoc()): ?>
                            <option value="<?php echo $batch['batch_id']; ?>" <?php if($student['batch_id'] == $batch['batch_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($batch['batch_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                <a href="students.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
