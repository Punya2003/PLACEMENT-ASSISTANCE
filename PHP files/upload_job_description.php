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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $conn->real_escape_string($_POST["company_name"]);
    $job_title = $conn->real_escape_string($_POST["job_title"]);
    $job_description = $conn->real_escape_string($_POST["job_description"]);
    $average_salary = $conn->real_escape_string($_POST["average_salary"]);
    $skills_required = $conn->real_escape_string($_POST["skills_required"]);
    $total_vacancies = (int)$_POST["total_vacancies"];

    $sql = "INSERT INTO job_listings (company_name, job_title, job_description, average_salary, skills_required, total_vacancies)
            VALUES ('$company_name', '$job_title', '$job_description', '$average_salary', '$skills_required', $total_vacancies)";

    if ($conn->query($sql) === TRUE) {
        echo "Job details uploaded successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch admin information
$admin_id = $_SESSION['admin'];
$admin_query = "SELECT * FROM admins WHERE Id = '$admin_id'";
$admin_result = $conn->query($admin_query);
$admin = $admin_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Job Description</title>
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
                <!-- Add more links as needed -->
            </ul>
        </div>
        <div class="main-content">
            <h2>Upload Job Description</h2>
            <form action="upload_job_description.php" method="post">
                <div class="textbox">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Enter company name" required>
                </div>
                <div class="textbox">
                    <label for="job_title">Job Title</label>
                    <input type="text" id="job_title" name="job_title" placeholder="Enter job title" required>
                </div>
                <div class="textbox">
                    <label for="job_description">Job Description</label>
                    <textarea id="job_description" name="job_description" placeholder="Enter job description" required></textarea>
                </div>
                <div class="textbox">
                    <label for="average_salary">Average Salary</label>
                    <input type="text" id="average_salary" name="average_salary" placeholder="Enter average salary" required>
                </div>
                <div class="textbox">
                    <label for="skills_required">Skills Required</label>
                    <input type="text" id="skills_required" name="skills_required" placeholder="Enter skills required" required>
                </div>
                <div class="textbox">
                    <label for="total_vacancies">Total Vacancies</label>
                    <input type="number" id="total_vacancies" name="total_vacancies" placeholder="Enter total vacancies" required>
                </div>
                <div class="buttons">
                    <input type="submit" class="btn" value="Upload Job Details">
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
