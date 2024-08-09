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

// Fetch student information
$student_id = $_SESSION['student'];
$stmt = $conn->prepare("SELECT name, usn FROM student_registration WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();

// Fetch job listings
$job_query = "SELECT job_title, average_salary, job_description, id, company_name FROM job_listings";
$job_result = $conn->query($job_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Listings</title>
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
            <h2 class="centered-heading">Job Listings</h2>
            <div class="widgets">
                <?php while ($job = $job_result->fetch_assoc()) { ?>
                    <div class="widget">
                        <h3><?php echo htmlspecialchars($job['company_name']); ?></h3>
                        <p><strong>Job Title:</strong> <?php echo htmlspecialchars($job['job_title']); ?></p>
                        <p><strong>Average Salary:</strong> <?php echo htmlspecialchars($job['average_salary']); ?></p>
                        <p><?php echo htmlspecialchars(substr($job['job_description'], 0, 100)); ?>... <a href="student_job_details.php?job_id=<?php echo htmlspecialchars($job['id']); ?>">Read More</a></p>
                        <form action="apply_for_job.php" method="post">
                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
                            <input type="submit" class="btn" value="Apply">
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
