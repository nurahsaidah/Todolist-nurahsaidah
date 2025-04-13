<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: tasks.php");
        exit;
    } else {
        echo "Username atau Password salah! .";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* White background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #ffffff; /* White background for container */
            padding: 30px;
            border-radius: 10px; /* Slightly rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-container-border {
            border: 3px solid #1e90ff; /* Dodger Blue border for contrast */
            background-origin: border-box;
            background-clip: content-box, border-box;
        }

        h1 {
            margin-bottom: 20px;
            color: #1e90ff; /* Dodger Blue color */
        }

        form {
            text-align: left;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            color: #333333; /* Dark Gray for labels */
        }

        form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #1e90ff; /* Dodger Blue border */
            border-radius: 8px; /* Slightly rounded edges */
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        form input:focus {
            border-color: #4682b4; /* Steel Blue on focus */
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #1e90ff; /* Dodger Blue background */
            color: white;
            border: 1px solid #4682b4; /* Steel Blue border */
            border-radius: 25px; /* Rounded button */
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        form button:hover {
            background-color: #4682b4; /* Steel Blue on hover */
            border-color: #1e90ff; /* Dodger Blue border on hover */
        }

        /* Style for the Register link */
        .register-link {
            margin-top: 10px;
        }

        .register-link a {
            display: inline-block;
            width: 93%;
            padding: 12px;
            background-color: #1e90ff; /* Dodger Blue background */
            color: white;
            border: 1px solid #4682b4; /* Steel Blue border */
            border-radius: 25px;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .register-link a:hover {
            background-color: #4682b4; /* Steel Blue on hover */
            border-color: #1e90ff; /* Dodger Blue border on hover */
        }

        /* Error message style */
        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container login-container-border">
        <h1>Login</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            <a href="register.php">Belum punya akun? Daftar di sini</a>
        </div>
    </div>
</body>
</html>
