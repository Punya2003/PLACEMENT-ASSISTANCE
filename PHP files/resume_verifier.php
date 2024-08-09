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

// Handle file upload
$feedback = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $resume = $_FILES['resume'];
    
    // Check if the file is a PDF
    if ($resume['type'] == 'application/pdf') {
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($resume['name']);
        
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($resume['tmp_name'], $upload_file)) {
            // Process the resume
            $feedback = "Resume uploaded successfully. Analyzing...";
            // Here you can add the code to analyze the resume and provide feedback
            // For now, we'll just give a dummy feedback
            $feedback = "Your resume looks good. Make sure to include your latest projects and experiences. For more detailed info on your resume, <a href='https://resumeworded.com/' target='_blank'>Please Visit </a>.";
        } else {
            $feedback = "Failed to upload the resume.";
        }
    } else {
        $feedback = "Please upload a PDF file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resume Verifier</title>
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
            <h2>Resume Verifier</h2>
            <form action="resume_verifier.php" method="post" enctype="multipart/form-data">
                <div class="textbox">
                    <label for="resume">Upload your resume (PDF):</label>
                    <input type="file" name="resume" id="resume" required>
                </div>
                <div class="buttons">
                    <input type="submit" class="btn" value="Upload Resume">
                </div>
            </form>
            <?php if ($feedback) { ?>
                <div class="feedback">
                    <p><?php echo $feedback; ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
