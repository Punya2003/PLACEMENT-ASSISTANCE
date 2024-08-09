<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admins WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (isset($row['Password']) && !empty($row['Password']) && password_verify($password, $row['Password'])) {
            // Password is correct, start a session
            $_SESSION['admin'] = $row['Id']; // Store the admin ID in the session
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with this email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Placement Assistance</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/admin_login.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="left-half">
            <img src="/Images/loginbg.jpg" alt="Placement Assistance" />
        </div>
        <div class="right-half">
            <div class="login-box">
                <h2>Admin Login</h2>
                <form action="admin_login.php" method="post">
                    <div class="textbox">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="textbox">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="buttons">
                        <input type="submit" class="btn" value="Login">
                    </div>
                    <div class="links">
                        <a href="forgot_password.php" class="link-btn">Forgot Password?</a>
                        <a href="new_admin_registration.php" class="link-btn">Register as New Admin</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
