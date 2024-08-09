<?php
// Database connection
$servername = "localhost";
$username = "prajeeth";
$password = "Prajeeth29";
$dbname = "placementassistance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Define the handleFileUpload function
function handleFileUpload($file, $allowedTypes, $maxSize) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($file["name"]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check file size
  if ($file["size"] > $maxSize) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if (!in_array($fileType, $allowedTypes)) {
    echo "Sorry, only " . implode(", ", $allowedTypes) . " files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  } else {
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
      return $target_file;
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
  return null; // Return null if upload fails
}

// Define allowed file types and maximum file size
$allowedImageTypes = array("jpg", "jpeg", "png");
$allowedResumeTypes = array("pdf");
$maxFileSize = 5000000; // 5MB

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect all form data with checks for existence and proper validation

  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $usn = filter_var($_POST['usn'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password']; // Raw password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashed password
  $govProof = isset($_FILES['govProof']) ? handleFileUpload($_FILES['govProof'], $allowedImageTypes, $maxFileSize) : null;
  $photo = isset($_FILES['photo']) ? handleFileUpload($_FILES['photo'], $allowedImageTypes, $maxFileSize) : null;
  $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
  $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
  $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING); // Assuming date format is validated elsewhere
  $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
  $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
  $about = filter_var($_POST['about'], FILTER_SANITIZE_STRING);
  $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
  $nationality = filter_var($_POST['nationality'], FILTER_SANITIZE_STRING);

  // Ensure tenth grade percentage is valid FLOAT and not null
  $tenth_grade_percentage = filter_var($_POST['tenth'], FILTER_VALIDATE_FLOAT);
  if ($tenth_grade_percentage === false) {
    die("Error: Tenth Grade Percentage is required.");
  }

  $twelfth_grade_percentage = filter_var($_POST['twelfth'], FILTER_VALIDATE_FLOAT);
  $diploma_marks = isset($_POST['diploma']) ? filter_var($_POST['diploma'], FILTER_VALIDATE_FLOAT) : null;
  $branch = filter_var($_POST['branch'], FILTER_SANITIZE_STRING);
  $engineering_gpa = isset($_POST['engineeringGPA']) ? filter_var($_POST['engineeringGPA'], FILTER_VALIDATE_FLOAT) : null;
  $backlogs = filter_var($_POST['backlogs'], FILTER_SANITIZE_NUMBER_INT);

  // Handle GMAT score (ensure integer and prevent SQL injection)
  $gmat_score = null;
  if (isset($_POST['gmat']) && is_numeric($_POST['gmat'])) {
    $gmat_score = (int)$_POST['gmat']; // Convert to integer and prevent injection
  }

  $gre_score = isset($_POST['gre']) ? filter_var($_POST['gre'], FILTER_SANITIZE_NUMBER_INT) : null;
  $gate_score = isset($_POST['gate']) ? filter_var($_POST['gate'], FILTER_SANITIZE_NUMBER_INT) : null;
  $ielts_score = isset($_POST['ielts']) ? filter_var($_POST['ielts'], FILTER_SANITIZE_NUMBER_FLOAT) : null;
  $toefl_score = isset($_POST['toefl']) ? filter_var($_POST['toefl'], FILTER_SANITIZE_NUMBER_FLOAT) : null;
  $internship_domains = isset($_POST['internships']) ? filter_var($_POST['internships'], FILTER_SANITIZE_STRING) : null;
  $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
  $current_semester = filter_var($_POST['current_semester'], FILTER_SANITIZE_NUMBER_INT);
  $year_of_passing = filter_var($_POST['year_of_passing'], FILTER_SANITIZE_NUMBER_INT);
  $linkedin = isset($_POST['linkedin']) ? filter_var($_POST['linkedin'], FILTER_SANITIZE_URL) : null;
  $github = isset($_POST['github']) ? filter_var($_POST['github'], FILTER_SANITIZE_URL) : null;
  $college_name = filter_var($_POST['college_name'], FILTER_SANITIZE_STRING);
  $college_address = filter_var($_POST['college_address'], FILTER_SANITIZE_STRING);
  $expected_salary = filter_var($_POST['expectedSalary'], FILTER_SANITIZE_NUMBER_INT);
  $job_location = filter_var($_POST['jobLocation'], FILTER_SANITIZE_STRING);
  $skills = filter_var($_POST['skills'], FILTER_SANITIZE_STRING);

  // Prepare SQL statement (using parameterized query to prevent injection)
  $stmt = $conn->prepare("
    INSERT INTO student_registration (
      name, usn, email, password, gov_proof, photo, phone, address, dob, city, state, about, gender, nationality,
      tenth_grade_percentage, twelfth_grade_percentage, diploma_marks, branch, engineering_gpa, backlogs, resume,
      gmat_score, gre_score, gate_score, ielts_score, toefl_score, internship_domains, qualification, current_semester,
      year_of_passing, linkedin, github, college_name, college_address, expected_salary, job_location, skills
    ) VALUES (
      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?
    )
  ");

  // Bind parameters with sanitized/validated data
  $stmt->bind_param(
    'sssssssssssssssdddsdisiiidississsssds', // Adjust according to your fields
    $name, $usn, $email, $hashed_password, $govProof, $photo, $phone, $address, $dob, $city, $state, $about, $gender, $nationality,
    $tenth_grade_percentage, $twelfth_grade_percentage, $diploma_marks, $branch, $engineering_gpa, $backlogs, $resume,
    $gmat_score, $gre_score, $gate_score, $ielts_score, $toefl_score, $internship_domains, $qualification, $current_semester,
    $year_of_passing, $linkedin, $github, $college_name, $college_address, $expected_salary, $job_location, $skills
  );

  // Execute the prepared statement with sanitized/validated data
  if ($stmt->execute()) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>New Student Registration - Placement Assistance</title>
    <link rel="stylesheet" type="text/css" href="/CSS files/new_student_registration.css">
    <style>
        .container {
            position: relative;
            padding: 20px;
            background: #f9f9f9;
            box-shadow: 0px 0px 10px 0px #ccc;
            margin: 50px auto;
            max-width: 800px;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="date"], input[type="file"], select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
    </style>
    <script>
        function showCategory(categoryId) {
            var categories = document.getElementsByClassName("category");
            for (var i = 0; i < categories.length; i++) {
                categories[i].style.display = "none";
            }
            document.getElementById(categoryId).style.display = "block";
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="overlay"></div>
        <div class="content">
            <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <!-- Personal Information Category -->
                <div class="category" id="personalInfo" style="display: block;">
                    <h2>Personal Information</h2>
                    <div class="subcategory">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="subcategory">
                        <label for="usn">USN</label>
                        <input type="text" id="usn" name="usn" required pattern="[A-Za-z0-9]{10}">
                    </div>
                    <div class="subcategory">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="subcategory">
                        <label for="govProof">Government Proof</label>
                        <input type="file" id="govProof" name="govProof" accept=".jpg, .jpeg, .png" required>
                    </div>
                    <div class="subcategory">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png" required>
                    </div>
                    <div class="subcategory">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="subcategory">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="4" required></textarea>
                    </div>
                    <div class="subcategory">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="subcategory">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="subcategory">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" required>
                    </div>

                    <div class="subcategory">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
                    
                    <div class="subcategory">
                        <label for="about">About</label>
                        <textarea id="about" name="about" rows="4" required></textarea>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('demographics')">Next</button>
                    </div>
                </div>

                <!-- Demographics Category -->
                <div class="category" id="demographics" style="display: none;">
                    <h2>Demographics</h2>
                    <div class="subcategory">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="subcategory">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality" required>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('personalInfo')">Previous</button>
                        <button type="button" class="btn" onclick="showCategory('education')">Next</button>
                    </div>
                </div>

                <!-- Education Category -->
                <div class="category" id="education" style="display: none;">
                    <h2>Education</h2>
                    <div class="subcategory">
                        <label for="tenth">10th Grade Percentage</label>
                        <input type="text" id="tenth" name="tenth" required>
                    </div>
                    <div class="subcategory">
                        <label for="twelfth">12th Grade Percentage</label>
                        <input type="text" id="twelfth" name="twelfth">
                    </div>
                    <div class="subcategory">
                        <label for="diploma">Diploma Marks</label>
                        <input type="text" id="diploma" name="diploma">
                    </div>
                    <div class="subcategory">
                        <label for="branch">Branch</label>
                        <input type="text" id="branch" name="branch" required>
                    </div>
                    <div class="subcategory">
                        <label for="engineeringGPA">Engineering GPA</label>
                        <input type="text" id="engineeringGPA" name="engineeringGPA">
                    </div>
                    <div class="subcategory">
                        <label for="backlogs">Backlogs (if any)</label>
                        <input type="text" id="backlogs" name="backlogs">
                    </div>
                    <div class="subcategory">
                        <label for="resume">Upload Resume</label>
                        <input type="file" id="resume" name="resume" accept=".pdf">
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('demographics')">Previous</button>
                        <button type="button" class="btn" onclick="showCategory('testScores')">Next</button>
                    </div>
                </div>

                <!-- Test Scores Category -->
                <div class="category" id="testScores" style="display: none;">
                    <h2>Test Scores</h2>
                    <div class="subcategory">
                        <label for="gmat">GMAT Score</label>
                        <input type="text" id="gmat" name="gmat">
                    </div>
                    <div class="subcategory">
                        <label for="gre">GRE Score</label>
                        <input type="text" id="gre" name="gre">
                    </div>
                    <div class="subcategory">
                        <label for="gate">GATE Score</label>
                        <input type="text" id="gate" name="gate">
                    </div>
                    <div class="subcategory">
                        <label for="ielts">IELTS Score</label>
                        <input type="text" id="ielts" name="ielts">
                    </div>
                    <div class="subcategory">
                        <label for="toefl">TOEFL Score</label>
                        <input type="text" id="toefl" name="toefl">
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('education')">Previous</button>
                        <button type="button" class="btn" onclick="showCategory('internship')">Next</button>
                    </div>
                </div>

                <!-- Internship Category -->
                <div class="category" id="internship" style="display: none;">
                    <h2>Internship</h2>
                    <div class="subcategory">
                        <label for="internship_domains">Domains Interested in for Internship</label>
                        <input type="text" id="internship_domains" name="internship_domains">
                    </div>
                    <div class="subcategory">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" required>
                    </div>
                    <div class="subcategory">
                        <label for="current_semester">Current Semester</label>
                        <input type="text" id="current_semester" name="current_semester" required>
                    </div>
                    <div class="subcategory">
                        <label for="year_of_passing">Year of Passing</label>
                        <input type="text" id="year_of_passing" name="year_of_passing" required>
                    </div>
                    <div class="subcategory">
                        <label for="linkedin">LinkedIn Profile</label>
                        <input type="text" id="linkedin" name="linkedin">
                    </div>
                    <div class="subcategory">
                        <label for="github">GitHub Profile</label>
                        <input type="text" id="github" name="github">
                    </div>
                    <div class="subcategory">
                        <label for="college_name">College Name</label>
                        <input type="text" id="college_name" name="college_name" required>
                    </div>
                    <div class="subcategory">
                        <label for="college_address">College Address</label>
                        <textarea id="college_address" name="college_address" rows="4" required></textarea>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('testScores')">Previous</button>
                        <button type="button" class="btn" onclick="showCategory('employment')">Next</button>
                    </div>
                </div>

                <!-- Employment Category -->
                <div class="category" id="employment" style="display: none;">
                    <h2>Employment</h2>
                    <div class="subcategory">
                        <label for="expectedSalary">Expected Salary</label>
                        <input type="text" id="expectedSalary" name="expectedSalary" required>
                    </div>
                    <div class="subcategory">
                        <label for="jobLocation">Preferred Job Location</label>
                        <input type="text" id="jobLocation" name="jobLocation" required>
                    </div>
                    <div class="subcategory">
                        <label for="skills">Skills</label>
                        <textarea id="skills" name="skills" rows="4" required></textarea>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn" onclick="showCategory('internship')">Previous</button>
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
