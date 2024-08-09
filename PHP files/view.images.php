<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch image paths from the database
$sql = "SELECT Name, Email, Phone_no, College_name, Upload_id FROM new_admin_registration";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Images - Placement Assistance</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/admin_images.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Uploaded Admin Images</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>College Name</th>
                    <th>Uploaded ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["Name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Phone_no"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["College_name"]) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row["Upload_id"]) . "' alt='Working ID' style='width:100px;'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
