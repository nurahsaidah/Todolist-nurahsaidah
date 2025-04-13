<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<p class='error'>Username sudah terdaftar, silahkan pilih username lain.</p>";
    } else {
        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        echo "<p class='success'>Daftar Berhasil!. <a href='login.php'>Login</a></p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8; /* Soft gray-blue background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff; /* White background */
            padding: 30px;
            border-radius: 10px; /* Slightly rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .container-border {
            border: 3px solid #1e3a8a; /* Dark blue border for contrast */
            background-origin: border-box;
            background-clip: content-box, border-box;
        }

        h2 {
            margin-bottom: 20px;
            color: #1e3a8a; /* Dark blue */
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #4b5563; /* Dark gray for labels */
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #d1d5db; /* Light gray border */
            border-radius: 8px; /* Slightly rounded edges */
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #1e3a8a; /* Dark blue border on focus */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1e3a8a; /* Dark blue background */
            color: white;
            border: 1px solid #1d4ed8; /* Slightly lighter blue border */
            border-radius: 25px; /* Rounded button */
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        button:hover {
            background-color: #1d4ed8; /* Lighter blue on hover */
            border-color: #1e3a8a; /* Dark blue border on hover */
        }

        .success {
            color: green;
            font-size: 14px;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .register-link {
            margin-top: 10px;
        }

        .register-link a {
            display: inline-block;
            width: 93%;
            padding: 12px;
            background-color: #3b82f6; /* Medium blue background */
            color: white;
            border: 1px solid #1d4ed8; /* Blue border */
            border-radius: 25px;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .register-link a:hover {
            background-color: #1d4ed8; /* Darker blue on hover */
            border-color: #1e3a8a;
        }
    </style>
</head>
<body>

<div class="container container-border">
    <h2>DAFTAR</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Daftar</button>
    </form>

    <div class="register-link">
        <a href="login.php">Jika sudah mempunyai akun, Login disini!</a>
    </div>
</div>

</body>
</html>
