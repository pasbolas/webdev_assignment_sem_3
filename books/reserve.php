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
$result = mysqli_query($conn, "SELECT * FROM books WHERE isbn = '$isbn'");
$book = $result ? mysqli_fetch_assoc($result) : null;
if ($result) { mysqli_free_result($result); }

if (!$book) {
    header('Location: search.php?msg=' . urlencode('Book not found.'));
    exit;
}

// Check if already reserved
$result = mysqli_query($conn, "SELECT id FROM reserved_books WHERE isbn = '$isbn'");
if ($result && mysqli_fetch_assoc($result)) {
    header('Location: search.php?msg=' . urlencode('Book is already reserved.'));
    exit;
}
if ($result) { mysqli_free_result($result); }

// Insert reservation
$reserved_date = date('Y-m-d');
mysqli_query($conn, "INSERT INTO reserved_books (username, isbn, reserved_date) VALUES ('{$_SESSION['username']}', '$isbn', '$reserved_date')");

header('Location: search.php?msg=' . urlencode('Book reserved successfully.'));
exit;
