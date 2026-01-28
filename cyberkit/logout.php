<?php
session_start();

// Check if the user confirmed logout
if (isset($_POST['confirm_logout'])) {
    // Destroy the session and redirect to index.html
    session_destroy();
    header("Location: index.html");
    exit();
} elseif (isset($_POST['cancel_logout'])) {
    // Redirect back to home page without destroying session
    header("Location: home.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .container {
            padding: 20px;
            border: 1px solid #ddd;
            display: inline-block;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        button {
            margin: 10px;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .confirm {
            background-color: #d9534f;
            color: white;
        }
        .cancel {
            background-color: #5bc0de;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Are you sure you want to log out?</h2>
        <form method="post">
            <button type="submit" name="confirm_logout" class="confirm">Yes, Logout</button>
            <button type="submit" name="cancel_logout" class="cancel">Cancel</button>
        </form>
    </div>
</body>
</html>
