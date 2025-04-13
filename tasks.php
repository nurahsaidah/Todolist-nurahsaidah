<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];  // Menangkap deskripsi
    $priority = $_POST['priority'];        // Menangkap prioritas

    // Memastikan prioritas yang diterima adalah salah satu dari 'penting', 'biasa', atau 'tidak penting'
    $valid_priorities = ['penting', 'biasa', 'tidak penting'];
    if (!in_array($priority, $valid_priorities)) {
        // Jika prioritas tidak valid, beri nilai default 'penting'
        $priority = 'penting';
    }

    // Query untuk menyimpan tugas ke database
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, priority) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $priority]);

    echo "Tugas berhasil ditambahkan!";
}

$tasks = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$tasks->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb; /* Latar belakang abu-abu-biru lembut */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-container {
            border: 8px solid transparent;
            border-radius: 15px;
            padding: 30px;
            background-image: linear-gradient(to right, #e3e8f0, #d6dae6); /* Border gradien lembut */
            background-origin: border-box;
            background-clip: content-box, border-box;
            width: 80%;
            max-width: 1000px;
            box-sizing: border-box;
        }

        header {
            background-color: #1e3a8a; /* Header biru gelap */
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
            background: linear-gradient(to right, #f1f5f8, #cfd8e3); /* Teks gradien */
            -webkit-background-clip: text;
            color: transparent;
        }

        .container {
            width: 95%;
            padding: 20px;
            background-color: #fff; /* Latar belakang putih untuk konten */
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
            border: 1px solid #1e3a8a; /* Border biru lembut */
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #1d4ed8; /* Fokus border biru lebih gelap */
        }

        textarea {
            grid-column: span 2;
            height: 100px;
        }

        button {
            grid-column: span 2;
            padding: 12px;
            background-color: #1e3a8a; /* Latar belakang biru gelap */
            color: white;
            border: 1px solid #1d4ed8; /* Border biru lebih terang */
            border-radius: 25px; /* Tombol membulat */
            cursor: pointer;
            font-size: 16px;
            box-sizing: border-box;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        button:hover {
            background-color: #1d4ed8; /* Biru lebih terang saat hover */
            border-color: #1e3a8a; /* Border biru gelap */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #1e3a8a; /* Border biru lembut untuk tabel */
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #1e3a8a; /* Latar belakang biru lembut untuk header tabel */
            color: white; /* Teks putih pada header */
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .actions a {
            margin-right: 10px;
            color: #1d4ed8; /* Biru lembut untuk tautan */
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .logout-button {
            grid-column: span 2;
            margin-top: 10px;
            padding: 12px;
            background-color: #1e3a8a; /* Latar belakang biru gelap untuk tombol logout */
            color: white;
            border: 1px solid #1d4ed8; /* Border biru lebih terang */
            border-radius: 25px; /* Tombol membulat */
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #1d4ed8; /* Biru lebih terang saat hover */
            border-color: #1e3a8a; /* Border biru gelap */
        }

        /* Styling berdasarkan prioritas */
        .priority-penting {
            color: red;
            font-weight: bold;
        }

        .priority-biasa {
            color: orange;
            font-weight: bold;
        }

        .priority-tidak-penting {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="main-container">
    <header>
        <h1>Daftar Tugas</h1>
    </header>

    <div class="container">
        <form method="POST">
            <input type="text" name="title" placeholder="Judul Tugas" required>
            <textarea name="description" placeholder="Deskripsi Tugas"></textarea>
            <select name="priority">
                <option value="penting">Penting</option>
                <option value="biasa" selected>Biasa</option>
                <option value="tidak penting">Tidak Penting</option>
            </select>
            <button type="submit">Tambah Tugas</button>
        </form>

        <table>
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Prioritas</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars($task['description']) ?></td>
                <td class="<?= 'priority-' . htmlspecialchars($task['priority']) ?>">
                    <?= htmlspecialchars(ucfirst($task['priority'])) ?>
                </td>
                <td class="actions">
                    <a href="subtasks.php?task_id=<?= $task['id'] ?>">Lihat Subtugas</a>
                    <a href="delete_task.php?id=<?= $task['id'] ?>">Hapus</a>
                    <a href="edit.php?id=<?= $task['id'] ?>">Ubah</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <a href="logout.php" class="logout-button">Keluar</a>
</div>

</body>
</html>
