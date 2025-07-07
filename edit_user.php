<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get user data
$matric = $_GET['matric'];
$sql = "SELECT * FROM users WHERE matric='$matric'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name='$name', role='$role', password='$hashed_password' WHERE matric='$matric'";
    } else {
        $sql = "UPDATE users SET name='$name', role='$role' WHERE matric='$matric'";
    }
    
    $conn->query($sql);
    header("Location: display.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: 0 auto; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post">
        <label>Matric:</label>
        <input type="text" value="<?php echo htmlspecialchars($user['matric']); ?>" disabled>
        
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        
        <label>Role:</label>
        <select name="role" required>
            <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
            <option value="teacher" <?php echo ($user['role'] == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
        
        <label>New Password (leave blank to keep current):</label>
        <input type="password" name="password">
        
        <button type="submit">Update User</button>
    </form>
</body>
</html>