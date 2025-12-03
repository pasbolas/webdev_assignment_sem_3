<?php
    require_once '../includes/db.php';
    session_start();

    if (empty($_SESSION['username'])) {
        header('Location: ../auth/login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: my_reservations.php');
        exit;
    }

    $id = $_POST['id'] ?? '';

    // Only delete if it belongs to this user
    mysqli_query($conn, "DELETE FROM reserved_books WHERE id = $id AND username = '{$_SESSION['username']}'");

    header('Location: my_reservations.php?msg=' . urlencode('Reservation removed.'));
    exit;
?>
