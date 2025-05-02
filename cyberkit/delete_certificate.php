<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cyberkit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the request
    $userId = $_POST['id'];

    // SQL query to delete the user using prepared statement
    $sql = "DELETE FROM certificates WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("i", $userId);

    // Execute the query
    if ($stmt->execute()) {
        echo "User deleted successfully.";
        // Redirect to the users page after deletion
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // If not a POST request, redirect back to users page
    header('Location: dashboard.php');
    exit;
}

// Close the database connection
$conn->close();
?>
