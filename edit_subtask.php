<?php
require 'db.php'; // Include your database connection file
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['subtask_id'])) {
    header("Location: login.php");
    exit;
}

$subtask_id = $_GET['subtask_id'];

// Fetch subtask details
$stmt = $pdo->prepare("SELECT * FROM subtasks WHERE id = ?");
$stmt->execute([$subtask_id]);
$subtask = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subtask) {
    echo "Subtask not found!";
    exit;
}

// Handle form submission for editing subtask
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];

    // Validate that the required fields are not empty
    if (empty($description) || empty($due_date) || empty($priority)) {
        echo "Please fill in all fields!";
        exit;
    }

    // Update subtask in the database
    $stmt = $pdo->prepare("UPDATE subtasks SET description = ?, due_date = ?, priority = ? WHERE id = ?");
    $stmt->execute([$description, $due_date, $priority, $subtask_id]);

    // Redirect back to the subtasks page
    header("Location: subtasks.php?task_id=" . $subtask['task_id']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subtask</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #004d73;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #cfe0f1;
            border-radius: 6px;
            margin-bottom: 15px;
            background-color: #f9faff;
        }
        input[type="date"] {
            color: #004d73;
        }
        select {
            background-color: #f9faff;
            color: #004d73;
        }
        button {
            padding: 12px 25px;
            background-color: #0078d4;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #005a8a;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="date"] {
            min: <?= date('Y-m-d'); ?>;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Subtask</h1>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="description" value="<?= htmlspecialchars($subtask['description']) ?>" required>
            </div>
            <div class="form-group">
                <input type="date" name="due_date" value="<?= htmlspecialchars($subtask['due_date']) ?>" required min="<?= date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <select name="priority" required>
                    <option value="Rendah" <?= $subtask['priority'] === 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                    <option value="Sedang" <?= $subtask['priority'] === 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                    <option value="Tinggi" <?= $subtask['priority'] === 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
                </select>
            </div>
           
        <div>
          <button type="submit">Update Subtask</button> 
    </div>
    <div>
        <button type="submit" a href="subtasks.php">kembali</button> 
    </div>
            
        </form>
    </div>
</body>
</html>