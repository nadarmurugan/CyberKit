<?php
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cyberkit";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch all certificates (ensuring columns match your table structure)
$sql = "SELECT id, cert_name, cert_class, cert_score, cert_date, cert_time, cert_id, cert_pdf FROM certificates";
$result = $conn->query($sql);

$certificates = [];
while ($row = $result->fetch_assoc()) {
    $certificates[] = [
        "id" => $row["id"],
        "name" => $row["cert_name"],
        "class" => $row["cert_class"],
        "score" => $row["cert_score"] ?? "N/A",
        "date" => $row["cert_date"] ?? "N/A",
        "time" => $row["cert_time"] ?? "N/A",
        "cert_id" => $row["cert_id"] ?? "N/A",
        "download_link" => !empty($row["cert_pdf"]) ? "certificates/" . basename($row["cert_pdf"]) : "#"
    ];
}

// Return JSON response
echo json_encode($certificates ?: []);

$conn->close();
?>
