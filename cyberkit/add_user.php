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
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = 'All fields are required!';
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = 'Password must have 8+ chars, an uppercase, a lowercase, a number, and a special character.';
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match!';
    }

    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Email already registered!';
    }

    // If no errors, insert user into database
    if (empty($errors)) {
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Failed to add user!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #1a1d23;
            color: white;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            padding: 20px;
            background-color: #2c2f33;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

           .form-control {
            background-color: #3b3f45;
            color: white;
            border: none;
        }
        .form-control:focus {
            background-color: #4b4f55;
        }
        .btn {
            background-color: #6c5ce7;
            border: none;
        }
        .btn:hover {
            background-color: #7a64e7;
        }
        .eye-icon {
            color: black;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
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
        <h2 class="text-center">Add User</h2>
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <i class="fa-solid fa-eye eye-icon" id="password-eye" onclick="togglePassword('password', 'password-eye')"></i>
            </div>
            <div class="mb-3 position-relative">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <i class="fa-solid fa-eye eye-icon" id="confirm-password-eye" onclick="togglePassword('confirm_password', 'confirm-password-eye')"></i>
            </div>
            <button type="submit" class="btn btn-primary w-100">Create User</button>
            <a class="back-link" href="dashboard.php">
    <i class="fa-solid fa-arrow-left"></i> Back
</a>
        </form>
    </div>

    <script>
        function togglePassword(id, eyeId) {
            const passwordInput = document.getElementById(id);
            const eyeIcon = document.getElementById(eyeId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
