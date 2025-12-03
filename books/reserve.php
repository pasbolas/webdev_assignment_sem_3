<?php
    require_once '../includes/db.php';
    session_start();

    // username check
    if (empty($_SESSION['username'])) {
        header('Location: ../auth/login.php');
        exit;
    }


    $isbn = trim($_POST['isbn'] ?? '');


    // Insert reservation
    $reserved_date = date('Y-m-d');
    mysqli_query($conn, 
    "INSERT INTO reserved_books (username, isbn, reserved_date) VALUES ('{$_SESSION['username']}', '$isbn', '$reserved_date')");

    header('Location: search.php?msg=' . urlencode('Book reserved successfully.'));
    exit;

?>
