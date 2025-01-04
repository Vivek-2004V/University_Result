<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rollNumber = $conn->real_escape_string($_POST['rollNumber']);
    $program = $conn->real_escape_string($_POST['program']);
    $semester = $conn->real_escape_string($_POST['semester']);
    $captcha = $_POST['captcha'];
    $captchaInput = $_POST['captchaInput'];

    // Validate captcha
    session_start();
    if ($_SESSION['captcha'] !== $captchaInput) {
        echo "Captcha validation failed!";
        exit();
    }

    // Query to fetch results
    $sql = "SELECT * FROM results WHERE roll_number = '$rollNumber' AND program = '$program' AND semester = '$semester'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output result data
        while ($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"] . " - Result: " . $row["result"];
        }
    } else {
        echo "No results found.";
    }
}
?>
 