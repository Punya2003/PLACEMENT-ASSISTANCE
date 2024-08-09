<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Start session to verify if student is logged in
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_id'])) {
    $job_id = (int)$_POST['job_id'];
    $student_id = $_SESSION['student'];

    // Insert application into database
    $stmt = $conn->prepare("INSERT INTO job_applications (job_id, student_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $job_id, $student_id);

    if ($stmt->execute()) {
        echo "<script>alert('Application successfully registered.'); window.location.href='view_job_listing.php';</script>";
    } else {
        echo "<script>alert('Failed to register application.'); window.location.href='view_job_listing.php';</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Job</title>
</head>
<body>
    <!-- The JavaScript alert will show the message and redirect to view_job_listing.php -->
</body>
</html>
