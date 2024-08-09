<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST["name"];
    $USN = $_POST["usn"];
    $Email = $_POST["email"];
    $Password = $_POST["password"];

    // Validate user against student_registration table
    $stmt = $conn->prepare("SELECT * FROM student_registration WHERE Name = ? AND USN = ? AND Email = ?");
    $stmt->bind_param("sss", $Name, $USN, $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($Password, $hashed_password)) {
            // Password is correct, login successful
            session_start();
            $_SESSION['student'] = $row['id'];  // Store student ID in session
            header("Location: student_dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password. Please try again.";
        }
    } else {
        // No matching user found
        echo "Invalid credentials. Please check your details.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Login - Placement Assistance</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/student_login.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="left-half">
            <img src="/Images/loginbg.jpg" alt="Placement Assistance" />
        </div>
        <div class="right-half">
            <div class="login-box">
                <h2>Student Login</h2>
                <form action="student_login.php" method="post">
                    <div class="textbox">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="textbox">
                        <label for="usn">USN</label>
                        <input type="text" id="usn" name="usn" placeholder="Enter your USN" pattern="[A-Za-z0-9]{10}" title="USN should be 10 alphanumeric characters" required>
                    </div>
                    <div class="textbox">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="textbox">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <input type="submit" class="btn" value="Login">
                </form>
                <div class="additional-links">
                    <a href="forgot_password.php" class="btn">Forgot Password</a>
                    <a href="new_student_registration.php" class="btn">Register as New Student</a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
