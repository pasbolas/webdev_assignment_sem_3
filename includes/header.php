<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Reservation System</title>
    <link rel="stylesheet" href="/project/style.css">
</head>
<body>
<header>
    <h1>Library Reservation System</h1>
    <nav>
        <ul>
            <?php if (!empty($_SESSION['username'])): ?>
                <li><a href="../books/search.php">Search Books</a></li>
                <li><a href="../books/my_reservations.php">My Reservations</a></li>
                <li><a href="../auth/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
            <?php else: ?>
                <li><a href="../auth/login.php">Login</a></li>
                <li><a href="../auth/register.php">Register</a></li>
            <?php endif; ?>
            
        </ul>
        <hr>
    </nav>
</header>
<main>
