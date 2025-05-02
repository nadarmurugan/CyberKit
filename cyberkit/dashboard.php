<?php

// Database connection
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

// Fetch data from all tables
$users = $conn->query("SELECT * FROM users");
$contacts = $conn->query("SELECT * FROM contact_messages");
$downloads = $conn->query("SELECT * FROM downloads");
$certs = $conn->query("SELECT * FROM certificates");

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberKit Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --cyber-blue: #00f3ff;
            --matrix-green: #00ff4c;
            --dark-bg: #0a0a0a;
            --terminal-text: #00ff00;
            --light-gray: #333;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--cyber-blue);
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.9);
            padding: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--cyber-blue);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background: #ff003b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .table-section {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid var(--cyber-blue);
            margin-bottom: 2rem;
            padding: 1rem;
            box-shadow: 0 0 15px var(--cyber-blue);
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--cyber-blue);
        }

        th {
            background-color: rgba(0, 243, 255, 0.1);
        }

        tr:hover {
            background-color: rgba(0, 243, 255, 0.05);
        }

        .action-btns .btn {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-edit {
            color: var(--matrix-green);
            border-color: var(--matrix-green);
        }

        .btn-delete {
            color: #ff003b;
            border-color: #ff003b;
        }

        .btn-copy {
            color: #ffd700;
            border-color: #ffd700;
        }

        h2 {
            color: var(--matrix-green);
            border-left: 4px solid var(--cyber-blue);
            padding-left: 10px;
            margin-bottom: 10px;
        }

        .add-user {
            background: var(--matrix-green);
            color: black;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
            border-radius: 4px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>CyberKit Dashboard <i class="fas fa-terminal"></i></h1>
        <a href="logout.php" class="logout-btn"><i class="fas fa-power-off"></i> Logout</a>
    </nav>

    <div class="container">
        <!-- Users Table -->
        <div class="table-section">
            <h2><i class="fas fa-users"></i> Users <a href="add_user.php" class="add-user"><i class="fas fa-user-plus"></i> Add User</a></h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['password'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td class="action-btns">
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">
                    <i class="fas fa-edit"></i>
                     </a>                       
                     <form action="user_delete.php" method="post" style="display: inline;">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="btn btn-delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Contact Messages -->
        <div class="table-section">
            <h2><i class="fas fa-envelope"></i> Contact Messages</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $contacts->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= substr($row['message'], 0, 50) ?>...</td>
                    <td><?= $row['created_at'] ?></td>
                    <td class="action-btns">
                      
                    <form action="delete_message.php" method="post" style="display: inline;">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="btn btn-delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>               
     </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

      <!-- Downloads -->
<div class="table-section">
    <h2><i class="fas fa-download"></i> Downloads</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tool Name</th>
            <th>Email</th>
            <th>Purpose</th>
            <th>Download Time</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $downloads->fetch_assoc()): ?>
        <tr>
            <td><?= isset($row['id']) ? $row['id'] : 'N/A'; ?></td>
            <td><?= isset($row['tool_name']) ? $row['tool_name'] : 'N/A'; ?></td>
            <td><?= isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
            <td><?= isset($row['purpose']) ? $row['purpose'] : 'N/A'; ?></td>
            <td><?= isset($row['download_time']) ? $row['download_time'] : 'N/A'; ?></td>
            <td class="action-btns">
                <form action="delete_downloads.php" method="post" style="display: inline;">
                    <input type="hidden" name="id" value="<?= isset($row['id']) ? $row['id'] : ''; ?>">
                    <button type="submit" class="btn btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>


        <!-- Certificates -->
        <div class="table-section">
            <h2><i class="fas fa-certificate"></i> Certificates</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Certificate Name</th>
                    <th>Certificate Class</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Certificate ID</th>
                    <th>PDF</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $certs->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['cert_name'] ?></td>
                    <td><?= $row['cert_class'] ?></td>
                    <td><?= $row['cert_score'] ?></td>
                    <td><?= $row['cert_date'] ?></td>
                    <td><?= $row['cert_time'] ?></td>
                    <td><?= $row['cert_id'] ?></td>
                    <td><?= $row['cert_pdf'] ?></td>
                    <td class="action-btns">
                      
                    <form action="delete_certificate.php" method="post" style="display: inline;">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="btn btn-delete">
            <i class="fas fa-trash"></i>
        </button>
    </form>                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <script>
        // Add confirmation for delete actions
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this item?')) {
                    // Add delete logic here
                }
            });
        });
    </script>
</body>
</html>
