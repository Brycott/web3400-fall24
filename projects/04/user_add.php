<?php include 'config.php'; ?>
<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<?php  

if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect user to login page or display an error message
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract and sanitize input
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $phone = htmlspecialchars(trim($_POST['phone']));
    $role = $_POST['role'];
    $activation_code = uniqid();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->execute([$email]);
    $userExists = $stmt->fetch();

    if ($userExists) {
        // Email already exists, redirect back with error message
        $_SESSION['messages'][] = "That email already exists. Please choose another.";
        header('Location: users_manage.php');
        exit;
    } else {
        // Email is unique, proceed with inserting the user
         $insertStmt = $pdo->prepare("INSERT INTO `users`(`full_name`, `email`, `pass_hash`, `phone`, `sms`, `subscribe`,`activation_code`, `user_bio`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
         $insertStmt->execute([$full_name, $email, $password, $phone, $sms, $subscribe, $activation_code, $user_bio]);

        // Generate activation link. This is instead of sending a verification Email and or SMS message
        $activation_link = "?code=$activation_code";

        // Check if insertion was successful
        if ($stmt) {
            // Redirect back to users_manage.php with success message
            $_SESSION['messages'][] = "The user account for $full_name was created. They will need to login to activate their account.";
            header('Location: users_manage.php');
            exit;
        } else {
            // Insertion failed, redirect back with error message
            $_SESSION['messages'][] = "Failed to create the user. Please try again.";
            header('Location: users_manage.php');
            exit;
        }
    }
}
?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Add User</h1>
    <form action="user_add.php" method="post">
        <!-- Full Name -->
        <div class="field">
            <label class="label">Full Name</label>
            <div class="control">
                <input class="input" type="text" name="full_name" required>
            </div>
        </div>
        <!-- Email -->
        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input" type="email" name="email" required>
            </div>
        </div>
        <!-- Password -->
        <div class="field">
            <label class="label">Password</label>
            <div class="control">
                <input class="input" type="password" name="password" required>
            </div>
        </div>
        <!-- Phone -->
        <div class="field">
            <label class="label">Phone</label>
            <div class="control">
                <input class="input" type="tel" name="phone">
            </div>
        </div>
        <!-- Role -->
        <div class="field">
            <label class="label">Role</label>
            <div class="control">
                <div class="select">
                    <select name="role">
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="user" selected>User</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Submit -->
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Add User</button>
            </div>
            <div class="control">
                <a href="users_manage.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->
