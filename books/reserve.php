<?php
require_once '../includes/db.php';
session_start();

if (empty($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: search.php');
    exit;
}

$isbn = trim($_POST['isbn'] ?? '');

if ($isbn === '') {
    header('Location: search.php?msg=' . urlencode('Invalid book selection.'));
    exit;
}

// Check book exists
$stmt = $pdo->prepare('SELECT * FROM books WHERE isbn = ?');
$stmt->execute([$isbn]);
$book = $stmt->fetch();

if (!$book) {
    header('Location: search.php?msg=' . urlencode('Book not found.'));
    exit;
}

// Check if already reserved
$stmt = $pdo->prepare('SELECT id FROM reserved_books WHERE isbn = ?');
$stmt->execute([$isbn]);
if ($stmt->fetch()) {
    header('Location: search.php?msg=' . urlencode('Book is already reserved.'));
    exit;
}

// Insert reservation
$stmt = $pdo->prepare('INSERT INTO reserved_books (username, isbn, reserved_date) VALUES (?, ?, ?)');
$stmt->execute([$_SESSION['username'], $isbn, date('Y-m-d')]);

header('Location: search.php?msg=' . urlencode('Book reserved successfully.'));
exit;
