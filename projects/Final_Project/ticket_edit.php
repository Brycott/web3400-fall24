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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $ticket_id = $_GET['id']; // Get ticket ID from URL
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];

    // Prepare SQL query to update ticket details
    $sql = "UPDATE tickets SET title = :title, description = :description, priority = :priority, updated_at = NOW() WHERE id = :ticket_id";

    // Prepare statement
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':ticket_id', $ticket_id);

    // Execute query
    if ($stmt->execute()) {
        // Redirect back to ticket detail page after successful update
        echo "<script>window.location.href = 'ticket_detail.php?id=" . $ticket_id . "';</script>";
        exit;
    } else {
        echo "Error updating ticket.";
    }
} else {
    // Else, it's an initial page request; fetch the ticket record from the database
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "Ticket ID is required.";
        exit;
    }

    // Fetch ticket record based on the provided ticket ID
    $ticket_id = $_GET['id'];
    $sql = "SELECT * FROM tickets WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ticket_id', $ticket_id);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo "Ticket not found.";
        exit;
    }
}
?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Edit Ticket</h1>
    <form action="" method="post">
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" type="text" name="title" value="<?= htmlspecialchars_decode($ticket['title']) ?>" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Description</label>
            <div class="control">
                <textarea class="textarea" name="description" required><?= htmlspecialchars_decode($ticket['description']) ?></textarea>
            </div>
        </div>
        <div class="field">
            <label class="label">Priority</label>
            <div class="control">
                <div class="select">
                    <select name="priority">
                        <option value="Low" <?= ($ticket['priority'] == 'Low') ? 'selected' : '' ?>>Low</option>
                        <option value="Medium" <?= ($ticket['priority'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                        <option value="High" <?= ($ticket['priority'] == 'High') ? 'selected' : '' ?>>High</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Update Ticket</button>
            </div>
            <div class="control">
                <a href="tickets.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->