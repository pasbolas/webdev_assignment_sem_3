<?php
    require_once '../includes/db.php';
    include '../includes/header.php';

    if (empty($_SESSION['username'])) 
    {
        header('Location: /project/auth/login.php');
        exit;
    }

    // We are reading the search field here
    $title    = trim($_GET['title'] ?? '');
    $author   = trim($_GET['author'] ?? '');
    $cat_code = trim($_GET['cat_code'] ?? '');

    // fetching the categories for drop down menu
    $categories = [];
    $catSql = 'SELECT cat_code, cat_desc FROM category ORDER BY cat_desc';
    $catResult = mysqli_query($conn, $catSql);

    if ($catResult) // this is just making dictionary but for php
    {
        while ($row = mysqli_fetch_assoc($catResult)) 
        {
            $categories[] = $row;
        }
        mysqli_free_result($catResult); //pooopy garbage collection of mysql
    }


    // Simple query execution
    $sqlWhereParts = [];
    if ($title !== '') { $sqlWhereParts[] = "b.title LIKE '%$title%'"; }
    if ($author !== '') { $sqlWhereParts[] = "b.author LIKE '%$author%'"; }
    if ($cat_code !== '') { $sqlWhereParts[] = "b.cat_code = '$cat_code'"; }
    $sqlWhere = !empty($sqlWhereParts) ? implode(' AND ', $sqlWhereParts) : '1';

    // Now only fetch the books which mathces the query
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
        WHERE $sqlWhere
        ORDER BY b.title
    ";

    $books = [];
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        mysqli_free_result($result);
    }

    $message = $_GET['msg'] ?? '';
?>

<h2>Search for a Book</h2>

<!-- this just prints the message received back in the url -->
<?php if ($message): ?>
    <div class="success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<br>

<form method="get" action="">
    <label>Title (partial allowed):</label>
    <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">

    <label>Author (partial allowed):</label>
    <input type="text" name="author" value="<?= htmlspecialchars($author) ?>">

    <label>Category:</label>
    <select name="cat_code">
        <option value="">-- Any --</option>
        <?php foreach ($categories as $cat): ?>

            <option value="<?= $cat['cat_code'] ?> // we are just getting the cat_code var of cat object" 

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
