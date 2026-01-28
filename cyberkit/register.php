<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cyberkit";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>document.getElementById('error-message').innerText = 'All fields are required!';</script>";
        exit;
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>document.getElementById('password-error').innerText = 'Password must have 8+ chars, an uppercase, a lowercase, a number, and a special character.';</script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>document.getElementById('confirm-password-error').innerText = 'Passwords do not match!';</script>";
        exit;
    }

    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>document.getElementById('error-message').innerText = 'Email already registered!';</script>";
        exit;
    }

    // Insert user into database with plain text password
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login page...'); window.location.href='login.html';</script>";
    } else {
        echo "<script>document.getElementById('error-message').innerText = 'Registration failed! Please try again.';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>