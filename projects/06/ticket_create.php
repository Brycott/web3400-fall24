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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize input
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES);
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id']; // Ensure the user ID is stored in the session

    // Prepare and execute the insert query
    $query = "INSERT INTO tickets (title, description, priority, status, created_at, user_id) 
              VALUES (:title, :description, :priority, 'Open', NOW(), :user_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        echo '<meta http-equiv="refresh" content="0;url=tickets.php">';
        $_SESSION['messages'][] = "The ticket was successfully added";
        exit;
    } else {
        echo "<p>There was an error creating the ticket. Please try again.</p>";
    }
}


?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Create Ticket</h1>
    <form action="" method="post">
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" type="text" name="title" placeholder="Ticket title" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Description</label>
            <div class="control">
                <textarea class="textarea" name="description" placeholder="Ticket description" required></textarea>
            </div>
        </div>
        <div class="field">
            <label class="label">Priority</label>
            <div class="control">
                <div class="select">
                    <select name="priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Create Ticket</button>
            </div>
            <div class="control">
                <a href="tickets.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->