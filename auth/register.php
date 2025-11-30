<?php
require_once '../includes/db.php';
include '../includes/header.php';

$errors = [];
$success = '';

$username = '';
$fullname = '';
$mobile   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $mobile   = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validation
    if ($username === '' || $fullname === '' || $mobile === '' || $password === '' || $confirm === '') {
        $errors[] = 'All fields are required.';
    }

    if (!preg_match('/^\d{10}$/', $mobile)) {
        $errors[] = 'Mobile number must be exactly 10 digits.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Password and confirmation do not match.';
    }

    // Check username unique
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Username already taken.';
        }
    }

    // Insert user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, fullname, mobile) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $hash, $fullname, $mobile]);

        $success = 'Registration successful. You can now log in.';
        // clear form
        $username = $fullname = $mobile = '';
    }
}
?>

<h2>Register</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="post" action="">
    <label>Username:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">

    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>">

    <label>Mobile (10 digits):</label>
    <input type="text" name="mobile" value="<?= htmlspecialchars($mobile) ?>">

    <label>Password (min 6 chars):</label>
    <input type="password" name="password">

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password">

    <button type="submit">Register</button>
</form>

<p>Already registered? <a href="login.php">Login here</a>.</p>

<?php
include '../includes/footer.php';
