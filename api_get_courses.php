<?php
include 'db.php';

header('Content-Type: application/json');

$batch_id = isset($_GET['batch_id']) ? (int)$_GET['batch_id'] : 0;

if ($batch_id > 0) {
    $stmt = $conn->prepare("SELECT c.course_id, c.course_name FROM courses c JOIN batch_courses bc ON c.course_id = bc.course_id WHERE bc.batch_id = ? ORDER BY c.course_name");
    $stmt->bind_param("i", $batch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    echo json_encode($courses);
} else {
    echo json_encode([]);
}
?>
