<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST["name"];
    $Email = $_POST["email"];
    $Password = $_POST["password"];
    $Confirm_password = $_POST["confirm_password"];
    $Phone_no = $_POST["phone"];
    $College_name = $_POST["college_name"];

    // File upload logic
    $uploads_dir = 'uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }
    $Upload_id = $uploads_dir . "/" . basename($_FILES["working_id"]["name"]);

    $uploadOk = 1;
    $allowed_image_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_file_size = 5000000; // 5MB

    // Check file type and size for working ID
    if (!in_array($_FILES["working_id"]["type"], $allowed_image_types) || $_FILES["working_id"]["size"] > $max_file_size) {
        echo "Invalid working ID file.";
        $uploadOk = 0;
    }

    if ($uploadOk && $Password === $Confirm_password) {  // Ensure password confirmation matches
        if (move_uploaded_file($_FILES["working_id"]["tmp_name"], $Upload_id)) {
            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO admins (Name, Email, Password, Phone_no, College_name, Upload_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $Name, $Email, $hashed_password, $Phone_no, $College_name, $Upload_id);

            if ($stmt->execute()) {
                echo "New admin registered successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading files.";
        }
    } else {
        if ($Password !== $Confirm_password) {
            echo "Passwords do not match.";
        }
        if (!$uploadOk) {
            echo "File upload error.";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration - Placement Assistance</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/new_admin_registration.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="left-half">
            <img src="/Images/loginbg.jpg" alt="Placement Assistance" />
        </div>
        <div class="right-half">
            <div class="registration-box">
                <h2>Admin Registration</h2>
                <form action="new_admin_registration.php" method="post" enctype="multipart/form-data">
                    <div class="textbox">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="textbox">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="textbox">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="textbox">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <div class="textbox">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="textbox">
                        <label for="college_name">College Name</label>
                        <input type="text" id="college_name" name="college_name" placeholder="Enter your college name" required>
                    </div>
                    <div class="textbox">
                        <label for="working_id">Upload Working ID</label>
                        <input type="file" id="working_id" name="working_id" accept=".jpg, .jpeg, .png" required>
                    </div>
                    <div class="buttons">
                        <input type="submit" class="btn" value="Register">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
