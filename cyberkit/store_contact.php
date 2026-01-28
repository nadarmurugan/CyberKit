<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "cyberkit"; // Correct database variable name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if fields are not empty
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['message'])) {
        
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        // Execute query
        if ($stmt->execute()) {
            echo "<script>
                    alert('Message sent successfully!');
                    window.location.href = 'contact.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . $stmt->error . "');
                    window.location.href = 'contact.html';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('All fields are required!');
                window.location.href = 'contact.html';
              </script>";
    }
}

$conn->close();
?>
