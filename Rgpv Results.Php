<?php
session_start(); // Start the session for captcha and other session variables

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rgpv_results";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate captcha if not set
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = generateCaptcha();
}

function generateCaptcha() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    return substr(str_shuffle($characters), 0, 6);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $enrollmentNo = $conn->real_escape_string($_POST['enrollmentNo']);
    $semester = $conn->real_escape_string($_POST['semester']);
    $gradingOption = $conn->real_escape_string($_POST['gradingOption']);
    $captchaInput = $_POST['captchaInput'];

    // Captcha validation
    if ($_SESSION['captcha'] !== $captchaInput) {
        echo "<script>alert('Invalid captcha! Please try again.');</script>";
    } else {
        // Query the database for results
        $sql = "SELECT * FROM results WHERE enrollment_no = '$enrollmentNo' AND semester = '$semester' AND grading_option = '$gradingOption'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='container mt-4'>";
            echo "<h3>Results:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "<p><strong>Subject:</strong> " . $row['subject'] . " | <strong>Grade:</strong> " . $row['grade'] . "</p>";
            }
            echo "</div>";
        } else {
            echo "<script>alert('No results found for the given details.');</script>";
        }
    }

    // Regenerate captcha after each submission
    $_SESSION['captcha'] = generateCaptcha();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RGPV Results Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path fill='%23dc3545' d='M224 32c-45.6 0-90.3 12.9-128 37.3C64.7 95.8 32 140.8 32 192c0 52 20 98.9 53.3 132.4C135 392.3 192 472 224 512c32-40 89-119.7 138.7-187.6C396 290.9 416 244 416 192c0-51.2-32.7-96.2-64-122.7C314.3 44.9 269.6 32 224 32z'/></svg>">
    <style>
        html, body { height: 100%; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .header { background: linear-gradient(90deg, #ff0000, #007bff); color: white; padding: 15px 0; text-align: center; }
        .form-container { margin: 30px auto; max-width: 500px; padding: 20px; background-color: white; border-radius: 10px; }
        .captcha-container { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px; }
        .captcha { background-color: #e8e9eb; color: #dc3545; padding: 10px 20px; font-size: 1.5rem; letter-spacing: 2px; border-radius: 5px; }
        .footer { background: linear-gradient(90deg, #007bff, #ff0000); color: white; text-align: center; margin-top: auto; }
        .footer-icons a { color: white; margin: 0 10px; font-size: 1.2rem; transition: transform 0.3s; }
        .footer-icons a:hover { transform: scale(1.2); }
    </style>
</head>

<body>
    <header class="header">
        <h1><i class="fas fa-university"></i> Rajiv Gandhi Proudyogiki Vishwavidyalaya, Bhopal</h1>
        <h2><i class="fas fa-laptop"></i> Results</h2>
    </header>

    <div class="container form-container">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="enrollmentNo" class="form-label"><i class="fas fa-id-card"></i> Enrollment No.</label>
                <input type="text" class="form-control" id="enrollmentNo" name="enrollmentNo" placeholder="Enter your enrollment number" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label"><i class="fas fa-calendar-alt"></i> Semester</label>
                <select class="form-select" id="semester" name="semester" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gradingOption" value="grading" id="grading" checked>
                    <label class="form-check-label" for="grading"><i class="fas fa-graduation-cap"></i> Grading</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gradingOption" value="nonGrading" id="nonGrading">
                    <label class="form-check-label" for="nonGrading"><i class="fas fa-book"></i> Non-Grading</label>
                </div>
            </div>
            <div class="captcha-container">
                <div class="captcha"><?php echo $_SESSION['captcha']; ?></div>
                <button type="button" id="refreshCaptcha" class="btn btn-secondary">Refresh</button>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="captchaInput" name="captchaInput" placeholder="Enter captcha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">View Result</button>
        </form>
    </div>

    <footer class="footer">
        <p>This portal is managed by <b>CRISP, Bhopal</b>.</p>
        <div class="footer-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <p>&copy; 2024 All Rights Reserved</p>
    </footer>

    <script>
        document.getElementById('refreshCaptcha').addEventListener('click', function () {
            location.reload();
        });
    </script>
</body>

</html>
