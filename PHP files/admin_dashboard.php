<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Start session to verify if admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch admin information
$admin_id = $_SESSION['admin'];
$admin_query = "SELECT * FROM admins WHERE Id = '$admin_id'";
$admin_result = $conn->query($admin_query);
$admin = $admin_result->fetch_assoc();

// Fetch placement statistics
$placement_query = "SELECT COUNT(*) AS total_students, SUM(placed) AS placed_students FROM students";
$placement_result = $conn->query($placement_query);
$placement_stats = $placement_result->fetch_assoc();
$total_students = $placement_stats['total_students'];
$placed_students = $placement_stats['placed_students'];

$placement_percentage = $total_students > 0 ? ($placed_students / $total_students) * 100 : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/admin_dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <div class="profile-picture">
                <img src="/Images/admin_image.jpg" alt="Admin Image">
            </div>
            <h3>Admin Information</h3>
            <p>Name: <?php echo $admin['Name']; ?></p>
            <h3>Actions</h3>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="upload_job_description.php">Upload Job Description</a></li>
                <li><a href="view_applications.php">View Applications</a></li>
                <!-- Add more links as needed -->
            </ul>
        </div>
        <div class="main-content">
            <div class="widgets">
                <div class="widget">
                    <h3>Placement Statistics</h3>
                    <p><?php echo round($placement_percentage, 2); ?>% of students placed</p>
                </div>
                <!-- Add more widgets as needed -->
            </div>
            <h2>Admin Dashboard</h2>
            <p>Select an action from the sidebar.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
