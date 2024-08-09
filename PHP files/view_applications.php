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

// Fetch job applications grouped by company name
$applications_query = "
    SELECT ja.job_id, ja.student_id, jr.job_title, sr.name, sr.usn, jr.company_name
    FROM job_applications ja
    JOIN job_listings jr ON ja.job_id = jr.id
    JOIN student_registration sr ON ja.student_id = sr.id
    ORDER BY jr.company_name, jr.job_title
";
$applications_result = $conn->query($applications_query);

// Fetch all data into an array for easier processing
$applications = [];
while ($row = $applications_result->fetch_assoc()) {
    $applications[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Applications</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/admin_dashboard.css">
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h3 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <div class="profile-picture">
                <img src="/Images/admin_image.jpg" alt="Admin Image">
            </div>
            <h3>Admin Information</h3>
            <p>Name: <?php echo htmlspecialchars($admin['Name']); ?></p>
            <h3>Actions</h3>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="upload_job_description.php">Upload Job Description</a></li>
                <li><a href="view_applications.php">View Applications</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2 class="centered-heading">Job Applications</h2>
            <?php
            $current_company = '';
            foreach ($applications as $application) {
                if ($application['company_name'] !== $current_company) {
                    if ($current_company !== '') {
                        echo '</table>'; // Close previous table
                    }
                    $current_company = $application['company_name'];
                    echo "<h3>{$current_company}</h3>";
                    echo '<table>';
                    echo '<tr><th>Job Title</th><th>Student Name</th><th>Student USN</th></tr>';
                }
                echo '<tr>';
                echo '<td>' . htmlspecialchars($application['job_title']) . '</td>';
                echo '<td>' . htmlspecialchars($application['name']) . '</td>';
                echo '<td>' . htmlspecialchars($application['usn']) . '</td>';
                echo '</tr>';
            }
            if ($current_company !== '') {
                echo '</table>'; // Close last table
            }
            ?>
            <form action="export_to_excel.php" method="post">
                <input type="submit" class="btn" value="Export to Excel">
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
