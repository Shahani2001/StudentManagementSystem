<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$batch_id = isset($_GET['batch_id']) ? (int)$_GET['batch_id'] : 0;
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

if (!$batch_id || !$course_id || !$month) {
    die("Invalid parameters for report generation.");
}

$report_month = date('m', strtotime($month));
$report_year = date('Y', strtotime($month));

$query = "
    SELECT 
        s.name,
        COUNT(a.id) AS total_classes,
        SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_classes,
        SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) AS absent_classes
    FROM students s
    LEFT JOIN attendance a ON s.student_id = a.student_id 
        AND a.course_id = ? 
        AND MONTH(a.date) = ? 
        AND YEAR(a.date) = ?
    WHERE s.batch_id = ?
    GROUP BY s.student_id, s.name
    ORDER BY s.name;
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iisi", $course_id, $report_month, $report_year, $batch_id);
$stmt->execute();
$report_result = $stmt->get_result();

$filename = "Attendance_Report_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Student Name', 'Total Classes', 'Present', 'Absent', 'Percentage'));

while ($row = $report_result->fetch_assoc()) {
    $total = (int)$row['total_classes'];
    $present = (int)$row['present_classes'];
    $absent = (int)$row['absent_classes'];
    $percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
    fputcsv($output, [
        htmlspecialchars_decode($row['name']),
        $total,
        $present,
        $absent,
        $percentage . '%'
    ]);
}
$stmt->close();
fclose($output);
exit();
?>
