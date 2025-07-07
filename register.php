<?php
include 'db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 300px; margin: 0 auto; }
        input { margin: 5px; padding: 5px; width: 100%; }
    </style>
</head>
<body>
    <form method="post">
        Matric: <input type="text" name="matric"><br>
        Name: <input type="text" name="name"><br>
        Password: <input type="password" name="password"><br>
        Role: <input type="text" name="role"><br>
        <input type="submit" value="Register">
    </form>
    <p><a href="login.php">Login</a></p>
</body>
</html>