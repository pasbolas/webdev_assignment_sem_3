<?php
    require_once '../includes/db.php';
    include '../includes/header.php';

    $errors = [];
    $username = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errors[] = 'Username and password are required.';
        } 
        else 
        {
            
            // is this a bday question?
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?'); // preparing the cake 
            $stmt->execute([$username]); // lighting up the candles for execution
            $user = $stmt->fetch(); // getting the first slice of cake

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                header('Location: ../books/search.php'); // go strraight here now, we will do the search page
                exit;
            } else {
                $errors[] = 'Invalid username or password.'; // you didnt get the invite buddy, get out.
            }
        }
    }
?>


<!-- Tha main thing here -->

<h2>Login</h2>


<!-- Printing errors here -->
<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<!-- the main form that shows on the site -->
<form method="post" action="">

    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">
    <label>Password:</label>
    <input type="password" name="password">

    <button type="submit">Login</button>
</form>

<p>Not registered? <a href="register.php">Register here</a>.</p>

<?php
include '../includes/footer.php';
