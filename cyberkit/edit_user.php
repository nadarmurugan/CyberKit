<?php
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

// Initialize error variables
$error_message = $password_error = $confirm_password_error = '';

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
} else {
    die("Invalid request.");
}

// Update user details
if (isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'All fields are required!';
    }
    // Validate password strength
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $password_error = 'Password must have 8+ chars, an uppercase, a lowercase, a number, and a special character.';
    }
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $confirm_password_error = 'Passwords do not match!';
    }
    // Check if email exists (excluding current user)
    else {
        $check_query = "SELECT * FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Email already registered!';
        } else {
            // Update query
            $updateQuery = "UPDATE users SET 
                            username = '$username', 
                            email = '$email', 
                            password = '$password' 
                            WHERE id = '$id'";
            
            if ($conn->query($updateQuery)) {
                echo "<script>
                        alert('User details updated successfully.');
                        window.location.href='dashboard.php';
                      </script>";
                exit;
            } else {
                $error_message = "Error updating user: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #1a1d23; /* Dark background */
    background-image: linear-gradient(to bottom, #1a1d23, #2c3e50); /* Gradient effect */
}

.container {
    max-width: 500px;
    margin: 40px auto;
    padding: 30px;
    background: #2f343a; /* Darker background for container */
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.5); /* Enhanced shadow */
}

h2 {
    color: #66d9ef; /* Brighter title color */
    margin-bottom: 25px;
    text-align: center; /* Centered title */
}

.form-group {
    margin-bottom: 25px;
    position: relative;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #95a5a6; /* Lighter label color */
    font-weight: 500;
}

input {
    width: 100%;
    height: 45px;
    padding: 10px 15px;
    border: 2px solid #3b4148; /* Darker border */
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background-color: #2f343a; /* Same background as container */
    color: white; /* White text */
}

input:focus {
    border-color: #66d9ef; /* Brighter focus color */
    outline: none;
}

.btn-submit {
    background: #3498db; /* Blue submit button */
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

.btn-submit:hover {
    background: #2980b9; /* Darker hover effect */
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #95a5a6; /* Lighter toggle color */
}

.error-message {
    color: #e74c3c; /* Red error color */
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.fa-edit {
    margin-right: 10px;
    color: #66d9ef; /* Brighter icon color */
}



.back-link {
            margin-top: 30px;
            margin-left: 200px;
    display: inline-block;
    padding: 10px;
    background-color: #6c5ce7;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.back-link:hover {
    background-color: #7a64e7;
}

.back-link i {
    margin-right: 5px;
}
        </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-edit"></i>Edit User</h2>
    
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" 
                   value="<?= htmlspecialchars($row['username']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" 
                   value="<?= htmlspecialchars($row['password']) ?>" required>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            <?php if (!empty($password_error)): ?>
                <span class="error-message"><?= htmlspecialchars($password_error) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
            <?php if (!empty($confirm_password_error)): ?>
                <span class="error-message"><?= htmlspecialchars($confirm_password_error) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" name="update" class="btn-submit">Update User</button>

        <a class="back-link" href="dashboard.php">
    <i class="fa-solid fa-arrow-left"></i> Back
</a>
    </form>


</div>

<script>
    // Password toggle functionality
    const togglePassword = (inputId, iconId) => {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        icon.addEventListener('click', () => {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Initialize toggles
    togglePassword('password', 'togglePassword');
    togglePassword('confirm_password', 'toggleConfirmPassword');
</script>

</body>
</html>
