<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Check if current user is admin
$isAdmin = ($_SESSION['role'] === 'admin');

// Handle delete action
if ($isAdmin && isset($_GET['delete'])) {
    $matric = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    header("Location: display.php");
    exit();
}

// Get all users (admin sees all, others see only themselves)
if ($isAdmin) {
    $sql = "SELECT * FROM users";
} else {
    $sql = "SELECT * FROM users WHERE matric = '".$_SESSION['matric']."'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .edit-btn {
            background-color:rgb(175, 76, 145);
            color: white;
        }
        .edit-btn:hover {
            background-color:rgb(160, 69, 128);
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .add-btn {
            background-color:rgb(243, 135, 33);
            color: white;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .add-btn:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        
        <?php if ($isAdmin): ?>
            <a href="add_user.php" class="action-btn add-btn">Add New User</a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Role</th>
                    <?php if ($isAdmin): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['matric']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <?php if ($isAdmin): ?>
                        <td>
                            <a href="edit_user.php?matric=<?php echo $row['matric']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="display.php?delete=<?php echo $row['matric']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>