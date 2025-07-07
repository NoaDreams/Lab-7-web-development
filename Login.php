<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $matric;
            $_SESSION['role'] = $row['role'];
            header("Location: display.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No user found with this matric number";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
        }
        input {
            margin: 10px 0;
            padding: 12px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .login-btn {
            background-color:rgb(49, 162, 219);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        .login-btn:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .register-link {
            margin-top: 20px;
            display: block;
            color: #2196F3;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        .form-footer {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="text" name="matric" placeholder="Matric Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <div class="form-footer">
            Don't have an account? <a href="register.php" class="register-link">Register here</a>
        </div>
    </div>
</body>
</html>