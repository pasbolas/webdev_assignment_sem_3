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

    if (!ctype_digit($id)) {
        header('Location: my_reservations.php?msg=' . urlencode('Invalid reservation.'));
        exit;
    }

    // Only delete if it belongs to this user
    $stmt = $pdo->prepare('DELETE FROM reserved_books WHERE id = ? AND username = ?');
    $stmt->execute([$id, $_SESSION['username']]);

    header('Location: my_reservations.php?msg=' . urlencode('Reservation removed.'));
    exit;
?>
