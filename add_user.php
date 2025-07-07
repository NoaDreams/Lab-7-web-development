<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";
    $conn->query($sql);
    header("Location: display.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: 0 auto; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { background:rgb(71, 100, 217); color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Add New User</h1>
    <form method="post">
        <label>Matric:</label>
        <input type="text" name="matric" required>
        
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Role:</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Add User</button>
    </form>
</body>
</html>