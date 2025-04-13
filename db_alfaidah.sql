-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Mar 2025 pada 07.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nurah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `due_date` date DEFAULT NULL,
  `priority` enum('Rendah','Sedang','Tinggi') DEFAULT 'Rendah',
  `status` enum('aktif','terlewat','selesai') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subtasks`
--

INSERT INTO `subtasks` (`id`, `task_id`, `description`, `completed`, `due_date`, `priority`, `status`) VALUES
(1, 1, 'squid game 2', 0, NULL, '', 'aktif'),
(3, 5, 'memasak', 1, NULL, '', 'selesai'),
(4, 5, 'belanja', 0, NULL, '', 'terlewat'),
(9, 9, 'scsscbi', 0, '2025-02-22', '', 'aktif'),
(15, 6, 'dvdvdvdv', 1, '2025-02-23', 'Sedang', 'aktif'),
(16, 6, 'ydrdrydrydryryry', 1, '2025-03-03', 'Tinggi', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(100) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('penting','tidak penting','biasa') DEFAULT 'penting',
  `status` enum('ditunda','belum dikerjakan','selesai') DEFAULT 'ditunda'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `created_at`, `description`, `due_date`, `priority`, `status`) VALUES
(1, 1, 'nonton film', '2025-01-24 13:54:57', NULL, NULL, 'penting', 'ditunda'),
(4, 3, 'tugas sekolah', '2025-02-04 04:03:53', 'bahasa inggris', '2025-02-05', 'penting', 'belum dikerjakan'),
(5, 4, 'Rumah', '2025-02-10 13:57:22', 'beres beres', '2025-02-18', 'penting', 'belum dikerjakan'),
(6, 5, 'dwwqdwadwd', '2025-02-22 14:15:25', 'wdwewweww', NULL, 'biasa', 'ditunda'),
(7, 5, 'dwwqdwadwd', '2025-02-22 14:29:11', 'wdwewweww', NULL, 'biasa', 'ditunda'),
(8, 5, 'dwwqdwadwd', '2025-02-22 14:29:17', 'wdwewweww', NULL, 'biasa', 'ditunda'),
(9, 5, 'dwwqdwadwd', '2025-02-22 14:30:02', 'wdwewweww', NULL, 'biasa', 'ditunda'),
(10, 5, 'fasafsa', '2025-02-23 01:16:40', 'afasfasf', NULL, 'penting', 'ditunda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'refa', '$2y$10$pnHbTXIueQAF5S/d7y7Og.8qbDHZ6Zz7/w.6KVyQfOrHF8S.M0dum'),
(2, 'tikaa', '$2y$10$lcQzmSbgkJwYIUqUkyTOxe6lAGGf4BpnSsApUsm94C5/OquB.eHfq'),
(3, 'kartika', '$2y$10$Qw4C6VOpR7hHfYm8zfsKQe4hUtWOHxh931dJxZxnJLlJza/kRUUAS'),
(4, 'nurah', '$2y$10$JlmqLcisVLeZ4Gwqvlhw7eppohpOI0cFACvtvmrmMqTmZ9t/IMLvm'),
(5, 'nurnur', '$2y$10$cVBL.iIUgJH8/.hZRFlH3.p3iqj3nJ.aUFz9X0g4d8r1KZz3cNq3a');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indeks untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
