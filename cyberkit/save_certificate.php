<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "cyberkit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure "certificates" folder exists
$certificatesDir = "certificates/";
if (!file_exists($certificatesDir)) {
    mkdir($certificatesDir, 0777, true);
}

// Get form data
$certName = $_POST['certName'];
$certClass = $_POST['certClass'];
$certScore = $_POST['certScore'];
$certID = $_POST['certID'];
$certDate = $_POST['certDate'];
$certTime = $_POST['certTime'];

// Handle file upload
if (isset($_FILES['certificateFile'])) {
    $fileTmpPath = $_FILES['certificateFile']['tmp_name'];
    $fileName = $certID . ".pdf";  // Save file as "certID.pdf"
    $destPath = $certificatesDir . $fileName;

    // Move uploaded file to "certificates" folder
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Save only the file path in the database
        $stmt = $conn->prepare("INSERT INTO certificates (cert_name, cert_class, cert_score, cert_date, cert_time, cert_id, cert_pdf) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissss", $certName, $certClass, $certScore, $certDate, $certTime, $certID, $destPath);

        if ($stmt->execute()) {
            echo "Certificate saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error moving uploaded file.";
    }
} else {
    echo "No file uploaded.";
}

$conn->close();
?>
