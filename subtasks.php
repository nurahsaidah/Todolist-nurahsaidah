<?php
require 'db.php'; // Include your database connection file
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['task_id'])) {
    header("Location: login.php");
    exit;
}

$task_id = $_GET['task_id'];

// Handling the addition of new subtask
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    // Get form data
    $description = $_POST['description'];
    $due_date = $_POST['due_date']; // Get the due date
    $priority = $_POST['priority']; // Get the priority

    // Validate that the required fields are not empty
    if (empty($description) || empty($due_date) || empty($priority)) {
        echo "Please fill in all fields!";
        exit;
    }

    // Insert new subtask into the database
    $stmt = $pdo->prepare("INSERT INTO subtasks (task_id, description, due_date, priority) VALUES (?, ?, ?, ?)");
    $stmt->execute([$task_id, $description, $due_date, $priority]);
}

// Handling the completion status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subtask_id']) && isset($_POST['completed'])) {
    $subtask_id = $_POST['subtask_id'];
    $completed = $_POST['completed'] == 'on' ? 1 : 0; // Convert checkbox to boolean (1 or 0)
    $stmt = $pdo->prepare("UPDATE subtasks SET completed = ? WHERE id = ?");
    $stmt->execute([$completed, $subtask_id]);
}

// Handling delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['subtask_id'])) {
    $subtask_id = $_POST['subtask_id'];
    $stmt = $pdo->prepare("DELETE FROM subtasks WHERE id = ?");
    $stmt->execute([$subtask_id]);
}

// Retrieve active subtasks (completed = 0)
$subtasks = $pdo->prepare("SELECT * FROM subtasks WHERE task_id = ? AND completed = 0");
$subtasks->execute([$task_id]);

// Retrieve completed subtasks (completed = 1)
$completed_subtasks = $pdo->prepare("SELECT * FROM subtasks WHERE task_id = ? AND completed = 1");
$completed_subtasks->execute([$task_id]);

// Retrieve overdue subtasks (due_date < current date and completed = 0)
$overdue_subtasks = $pdo->prepare("SELECT * FROM subtasks WHERE task_id = ? AND completed = 0 AND due_date < CURDATE()");
$overdue_subtasks->execute([$task_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subtasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e1f0f6; /* Soft light blue background */
            margin: 0;
            padding: 0;
        }

        h1 {
            margin: 20px;
            font-size: 36px;
            color: #ffffff;
            background-color: #4a90e2;
            border-bottom: 2px solid #357ab7;
            padding-bottom: 10px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], input[type="date"], select {
            width: 80%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #a1c6ea;
            border-radius: 4px;
        }

        button {
            width: 100%; /* Make button full-width */
            padding: 8px; /* Padding remains the same */
            background-color: #e74c3c; /* Solid red color for delete button */
            color: white;
            border: 1px solid #e74c3c; /* Border matches button color */
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Keep the smaller font size */
        }

        button:hover {
            background-color: #c0392b; /* Darker red when hovering */
            border-color: #c0392b; /* Darker border when hovering */
        }

        .divider {
            width: 100%;
            height: 1px;
            background-color: #a1c6ea;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            padding: 0 20px;
        }

        table, th, td {
            border: 1px solid #a1c6ea;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4a90e2;
            color: white;
            border-bottom: 2px solid #357ab7;
        }

        td {
            background-color: #f9f9f9;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #4a90e2;
            text-decoration: none;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #4a90e2;
            border-radius: 4px;
        }

        a:hover {
            text-decoration: underline;
            background-color: #f2f2f2;
        }

        .checkbox {
            text-align: center;
        }

        .status {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Subtasks</h1>
</header>

<div class="container">
    <form method="POST">
        <input type="text" name="description" placeholder="SubTugas" required>
        <input type="date" name="due_date" min="<?= date('Y-m-d'); ?>" required> <!-- Set minimum date to today -->
        <select name="priority">
            <option value="Rendah" selected>Rendah</option>
            <option value="Sedang" selected>Sedang</option>
            <option value="Tinggi" selected>Tinggi</option>
        </select>
        <button type="submit">Tambah SubTugas</button>
    </form>

    <div class="divider"></div>

    <h2>SubTugas Aktif</h2>
    <table>
    <tr>
        <th>Tugas</th>
        <th>Selesai</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Prioritas</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($subtasks as $subtask): ?>
        <tr>
            <td><?= htmlspecialchars($subtask['description']) ?></td>
            <td class="checkbox">
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <input type="checkbox" name="completed" <?= $subtask['completed'] ? 'checked' : '' ?> 
                           onclick="this.form.submit()">
                </form>
            </td>
            <td class="status">
                <?= $subtask['completed'] ? 'Complete' : 'Incomplete' ?>
            </td>
            <td><?= htmlspecialchars($subtask['due_date']) ?></td>
            <td><?= htmlspecialchars($subtask['priority']) ?></td>
            <td>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <button type="submit" name="delete" value="1" onclick="return confirm('Are you sure you want to delete this subtask?');">Delete</button>
                </form>
                <a href="edit_subtask.php?subtask_id=<?= $subtask['id'] ?>" style="display: block; margin-top: 5px;">Edit</a> <!-- Tombol Edit -->
            </td>
        </tr>
    <?php endforeach; ?>
</table>

    <div class="divider"></div>

    <h2>SubTugas Selesai Dikerjakan</h2>
    <table>
    <tr>
        <th>Tugas</th>
        <th>Selesai</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Prioritas</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($completed_subtasks as $subtask): ?>
        <tr>
            <td><?= htmlspecialchars($subtask['description']) ?></td>
            <td class="checkbox">
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <input type="checkbox" name="completed" <?= $subtask['completed'] ? 'checked' : '' ?> 
                           onclick="this.form.submit()" disabled>
                </form>
            </td>
            <td class="status">
                <?= $subtask['completed'] ? 'Complete' : 'Incomplete' ?>
            </td>
            <td><?= htmlspecialchars($subtask['due_date']) ?></td>
            <td><?= htmlspecialchars($subtask['priority']) ?></td>
            <td>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <button type="submit" name="delete" value="1" onclick="return confirm('Are you sure you want to delete this subtask?');">Delete</button>
                </form>
               
        </tr>
    <?php endforeach; ?>
</table>

    <div class="divider"></div>

    <h2>SubTugas Terlewat</h2>
    <table>
    <tr>
        <th>Tugas</th>
        <th>Selesai</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Prioritas</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($completed_subtasks as $subtask): ?>
        <tr>
            <td><?= htmlspecialchars($subtask['description']) ?></td>
            <td class="checkbox">
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <input type="checkbox" name="completed" <?= $subtask['completed'] ? 'checked' : '' ?> 
                           onclick="this.form.submit()" disabled>
                </form>
            </td>
            <td class="status">
                <?= $subtask['completed'] ? 'Complete' : 'Incomplete' ?>
            </td>
            <td><?= htmlspecialchars($subtask['due_date']) ?></td>
            <td><?= htmlspecialchars($subtask['priority']) ?></td>
            <td>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="subtask_id" value="<?= $subtask['id'] ?>">
                    <button type="submit" name="delete" value="1" onclick="return confirm('Are you sure you want to delete this subtask?');">Delete</button>
                </form>
            
            </td>
        </tr>
    <?php endforeach; ?>
</table>

    <a href="tasks.php">Kembali</a>
</div>

</body>
</html> 