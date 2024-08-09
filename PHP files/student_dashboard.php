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

// Fetch student information from the database
$student_id = $_SESSION['student'];
$query = "SELECT name, usn FROM student_registration WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['Name'] = $row['name'];
    $_SESSION['usn'] = $row['usn'];
} else {
    // Redirect to login if student info is not found
    header("Location: student_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/student_dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <div class="profile-picture">
                <img src="/Images/student_dashboard.jpg" alt="Student Image">
            </div>
            <h3>Student Information</h3>
            <p>Name: <?php echo isset($_SESSION['Name']) ? $_SESSION['Name'] : 'N/A'; ?></p>
            <p>USN: <?php echo isset($_SESSION['usn']) ? $_SESSION['usn'] : 'N/A'; ?></p>
            <h3>Actions</h3>
            <ul>
                <li><a href="view_job_listing.php">View Job Listings</a></li>
                <li><a href="student_application_history.php">Application History</a></li>
                <li><a href="resume_verifier.php">Resume Verifier</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2>Welcome People!</h2>
            <p>Select an action from the sidebar.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
