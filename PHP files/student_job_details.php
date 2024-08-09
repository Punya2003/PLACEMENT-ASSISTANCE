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

// Fetch job details based on job_id
if (isset($_GET['job_id'])) {
    $job_id = (int)$_GET['job_id'];
    $stmt = $conn->prepare("SELECT * FROM job_listings WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job_result = $stmt->get_result();
    $job = $job_result->fetch_assoc();
    
    if (!$job) {
        echo "Job not found.";
        exit();
    }
} else {
    echo "No job selected.";
    exit();
}

// Fetch student information
$student_id = $_SESSION['student'];
$stmt = $conn->prepare("SELECT name, usn FROM student_registration WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Details</title>
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
            <p>Name: <?php echo htmlspecialchars($student['name']); ?></p>
            <p>USN: <?php echo htmlspecialchars($student['usn']); ?></p>
            <h3>Actions</h3>
            <ul>
                <li><a href="view_job_listing.php">View Job Listings</a></li>
                <li><a href="student_application_history.php">Application History</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2 class="centered-heading"><?php echo htmlspecialchars($job['job_title']); ?></h2>
            <p><strong>Average Salary:</strong> <?php echo htmlspecialchars($job['average_salary']); ?></p>
            <p><strong>Job Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
            <p><strong>Skills Required:</strong> <?php echo htmlspecialchars($job['skills_required']); ?></p>
            <p><strong>Total Vacancies:</strong> <?php echo htmlspecialchars($job['total_vacancies']); ?></p>
            <form action="apply_for_job.php" method="post">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
                <input type="submit" class="btn" value="Apply">
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
