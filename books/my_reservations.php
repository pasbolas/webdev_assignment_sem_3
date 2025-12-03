<?php
    require_once '../includes/db.php';
    include '../includes/header.php';

    if (empty($_SESSION['username'])) {
        header('Location:../auth/login.php');
        exit;
    }

    $username = $_SESSION['username'];

    $sql = "
        SELECT rb.id, rb.reserved_date, b.isbn, b.title, b.author
        FROM reserved_books rb
        JOIN books b ON b.isbn = rb.isbn
        WHERE rb.username = '$username'
        ORDER BY rb.reserved_date DESC
    ";
    $reservations = [];

    $result = mysqli_query($conn, $sql);

    // if reservavtion exists we populate reservations array with rows
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $reservations[] = $row;
        }
        mysqli_free_result($result);
    }

    $message = $_GET['msg'] ?? '';
?>

<div class="card">
    <div class="content">
        <h2 class="h2">My Reserved Books</h2>

<!-- printing the message upon successful reservation removal -->
<?php if ($message): ?>
    <div class="alert success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($reservations): ?>
    <table class="table">
        <thead>
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Reserved Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res['isbn']) ?></td>
                <td><?= htmlspecialchars($res['title']) ?></td>
                <td><?= htmlspecialchars($res['author']) ?></td>
                <td><?= htmlspecialchars($res['reserved_date']) ?></td>
                <td>
                    <!-- send the post mesasge to remove the reservation which send the id to remove_reservation.php -->
                    <form method="post" action="remove_reservation.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $res['id'] ?>">
                        <button type="submit" class="btn secondary">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty">You have no current reservations.</div>
<?php endif; ?>

    </div>
</div>
<?php
include '../includes/footer.php';
