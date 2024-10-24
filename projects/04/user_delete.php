<?php
include 'config.php';
include 'templates/head.php';
include 'templates/nav.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Prepare SQL statement to fetch the user record
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // Check if a user record with that ID exists
    if (!$user) {
        $_SESSION['messages'][] = "A user with that ID did not exist.";
        echo '<meta http-equiv="refresh" content="0;url=users_manage.php">'; // Redirect to prevent further processing
        exit;
    }
} else {
    $_SESSION['messages'][] = "User ID is not specified.";
    header('Location: users_manage.php');
    exit;
}

// Check if $_GET['confirm'] == 'yes'
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Prepare and execute SQL DELETE statement
    $deleteStmt = $pdo->prepare("DELETE FROM `users` WHERE `id` = ?");
    $deleteResult = $deleteStmt->execute([$user_id]);

    // Check if deletion was successful
    if ($deleteResult) {
        $_SESSION['messages'][] = "User account for {$user['full_name']} has been deleted.";
    } else {
        $_SESSION['messages'][] = "Failed to delete user account. Please try again.";
    }
    
    echo '<meta http-equiv="refresh" content="0;url=users_manage.php">'; // Redirect after deletion
    exit;
}

// If they clicked 'no', we can just redirect them back
if (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
    header('Location: users_manage.php');
    exit;
}
?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Delete User Account</h1>
    <p class="subtitle">Are you sure you want to delete the user: <?= htmlspecialchars($user['full_name']) ?></p>
    <div class="buttons">
        <a href="?id=<?= htmlspecialchars($user['id']) ?>&confirm=yes" class="button is-success">Yes</a>
        <a href="?id=<?= htmlspecialchars($user['id']) ?>&confirm=no" class="button is-danger">No</a>
    </div>
</section>
<!-- END YOUR CONTENT -->