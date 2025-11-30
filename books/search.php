<?php
    require_once '../includes/db.php';
    include '../includes/header.php';

    if (empty($_SESSION['username'])) 
    {
        header('Location: /project/auth/login.php');
        exit;
    }

    // We are readin gthe search field here
    $title    = trim($_GET['title'] ?? '');
    $author   = trim($_GET['author'] ?? '');
    $cat_code = trim($_GET['cat_code'] ?? '');

    // Build WHERE
    $where  = [];
    $params = [];

    if ($title !== '')     
    {
        $where[]  = 'b.title LIKE ?';
        $params[] = '%' . $title . '%';
    }
    if ($author !== '') 
    {
        $where[]  = 'b.author LIKE ?';
        $params[] = '%' . $author . '%';
    }
    if ($cat_code !== '') 
    {
        $where[]  = 'b.cat_code = ?';
        $params[] = $cat_code;
    }

    $whereSql = $where ? implode(' AND ', $where) : '1'; // followed this tutorial https://www.w3schools.com/php/func_string_implode.asp

    // fetching the categories for drop down menu
    $catStmt = $pdo->query('SELECT cat_code, cat_desc FROM category ORDER BY cat_desc');
    $categories = $catStmt->fetchAll();

    // Fetch ALL books
    $sql = "
        SELECT 
            b.isbn, 
            b.title, 
            b.author, 
            c.cat_desc,
            rb.id AS reservation_id
        FROM books b
        JOIN category c ON c.cat_code = b.cat_code
        LEFT JOIN reserved_books rb ON rb.isbn = b.isbn
        WHERE $whereSql
        ORDER BY b.title
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); // we are basically pluggin in the params here as a big single string
    $books = $stmt->fetchAll(); // books object here is the all books rows with those params

    $message = $_GET['msg'] ?? '';
?>

<h2>Search for a Book</h2>

<?php if ($message): ?>
    <div class="success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="get" action="">
    <label>Title (partial allowed):</label>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">

    <label>Author (partial allowed):</label>
    <input type="text" name="author" value="<?= htmlspecialchars($author) ?>">

    <label>Category:</label>
    <select name="cat_code">
        <option value="">-- Any --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['cat_code'] ?>"
                <?= ($cat_code !== '' && $cat_code == $cat['cat_code']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['cat_desc']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Search</button>
</form>

<?php if (!empty($books)): ?>
    <h3>Search Results (<?= count($books) ?>)</h3>

    <table>
        <thead>
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Status</th>
                <th>Reserve</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['isbn']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['cat_desc']) ?></td>

                <td>
                    <?php if ($book['reservation_id']): ?>
                        Reserved
                    <?php else: ?>
                        Available
                    <?php endif; ?>
                </td>

                <td>
                    <?php if (!$book['reservation_id']): ?>
                        <form method="post" action="reserve.php" style="display:inline;">
                            <input type="hidden" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>">
                            <button type="submit">Reserve</button>
                        </form>
                    <?php else: ?>
                        <button disabled>Reserve</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

<?php else: ?>
    <p>No books found for that search.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
