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
    die("<script>
            alert('Database connection error!');
            window.history.back();
         </script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $plain_password = trim($_POST['password']);

    // Validate input fields
    if (empty($email) || empty($plain_password)) {
        echo "<script>
                alert('All fields are required!');
                window.history.back();
              </script>";
        exit;
    }

    // Check user in database
    $sql = "SELECT id, email, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo "<script>
                alert('Database error!');
                window.history.back();
              </script>";
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>
                alert('Email not registered!');
                window.history.back();
              </script>";
        exit;
    }

    $user = $result->fetch_assoc();
    
    // Direct password comparison (NO HASHING)
    if ($plain_password !== $user['password']) {
        echo "<script>
                alert('Incorrect password!');
                window.history.back();
              </script>";
        exit;
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'];

    // Redirect to dashboard
    echo "<script>
            alert('Login successful! Redirecting...');
            window.location.href = 'home.html';
          </script>";
    exit;
}

$conn->close();
?>
