<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "cyberkit";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $purpose = trim($_POST['purpose']);
    $tool_name = trim($_POST['tool_name']); // Get tool name

    if (!empty($user_name) && !empty($email) && !empty($purpose) && !empty($tool_name)) {
        $stmt = $conn->prepare("INSERT INTO downloads (username, email, purpose, tool_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $user_name, $email, $purpose, $tool_name);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Data stored successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error storing data"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
    }
}

$conn->close();
?>
