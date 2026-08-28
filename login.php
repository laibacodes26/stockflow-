<?php
include 'config.php';

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: products.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StockFlow</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 45px 40px;
            border-radius: 20px;
            width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo { text-align: center; font-size: 28px; font-weight: 800; margin-bottom: 8px; }
        .logo span { color: #ff7a00; }
        h2 { text-align: center; color: #1a1a2e; margin-bottom: 25px; font-size: 20px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #444; font-size: 14px; }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: border 0.3s;
            outline: none;
        }
        input:focus { border-color: #ff7a00; }
        .btn {
            width: 100%;
            padding: 13px;
            background: #ff7a00;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 5px;
        }
        .btn:hover { background: #e96e00; transform: translateY(-2px); }
        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            border-left: 4px solid #e74c3c;
        }
        .register-link { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .register-link a { color: #ff7a00; font-weight: 700; text-decoration: none; }
        .back-link { text-align: center; margin-top: 10px; font-size: 13px; }
        .back-link a { color: #999; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">Stock<span>Flow</span></div>
    <h2>Login to Your Account</h2>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="email@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register</a>
    </div>
    <div class="back-link">
        <a href="products.php">← Back to Products</a>
    </div>
</div>
</body>
</html>
