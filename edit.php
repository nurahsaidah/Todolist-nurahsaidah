<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pastikan ada parameter 'id' yang valid di URL
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red; text-align:center;'>ID tugas tidak valid atau tidak tersedia.</p>";
    exit;
}

$id = $_GET['id'];

// Ambil data tugas berdasarkan id
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    echo "<p style='color:red; text-align:center;'>Tugas tidak ditemukan atau Anda tidak memiliki izin untuk mengedit tugas ini.</p>";
    exit;
}

// Proses update ketika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'], $_POST['priority'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];

    // Update data tugas tanpa kolom due_date
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, priority = ? WHERE id = ?");
    $updated = $stmt->execute([$title, $description, $priority, $id]);

    if ($updated) {
        echo "<p style='color:green; text-align:center;'>Tugas berhasil diperbarui!</p>";
        header("Refresh: 2; URL=tasks.php"); // Redirect ke halaman utama setelah sukses
    } else {
        echo "<p style='color:red; text-align:center;'>Terjadi kesalahan. Gagal memperbarui tugas.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-container {
            border: 10px solid transparent;
            border-radius: 15px;
            padding: 30px;
            background-image: linear-gradient(to right, #e0f7fa, #b3e5fc);
            background-origin: border-box;
            background-clip: content-box, border-box;
            width: 80%;
            max-width: 1000px;
            box-sizing: border-box;
        }

        header {
            background-color: #4f83cc;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 50px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.5em;
            margin: 0;
            background: linear-gradient(to right, #b3e5fc, #4f83cc);
            -webkit-background-clip: text;
            color: transparent;
        }

        .container {
            width: 95%;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"],
        textarea,
        select {
            padding: 10px;
            border: 1px solid #4f83cc;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #1e6091;
        }

        textarea {
            grid-column: span 2;
            height: 100px;
        }

        button {
            grid-column: span 2;
            padding: 12px;
            background-color: #4f83cc;
            color: white;
            border: 1px solid #1e6091;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        button:hover {
            background-color: #1e6091;
            border-color: #4f83cc;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #4f83cc;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="main-container">
    <header>
        <h1>Edit Tugas</h1>
    </header>

    <div class="container">
        <form method="POST">
            <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" placeholder="Judul Tugas" required>
            <textarea name="description" placeholder="Deskripsi tugas"><?= htmlspecialchars($task['description']) ?></textarea>
            <select name="priority">
                <option value="penting" <?= ($task['priority'] == 'penting') ? 'selected' : '' ?>>Penting</option>
                <option value="tidak penting" <?= ($task['priority'] == 'tidak penting') ? 'selected' : '' ?>>Tidak Penting</option>
                <option value="biasa" <?= ($task['priority'] == 'biasa') ? 'selected' : '' ?>>Biasa</option>
            </select>
            <button type="submit">Update Tugas</button>
        </form>

        <div class="back-link">
            <a href="tasks.php">Kembali ke Daftar Tugas</a>
        </div>
    </div>
</div>

</body>
</html>
