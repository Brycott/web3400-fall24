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

// Check if the $_GET['id'] exists; if it does, get the ticket record from the database and store it in the associative array $ticket.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Fetch ticket details
    $sql = "SELECT * FROM tickets WHERE id = :ticket_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ticket_id', $ticket_id);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no ticket was found with that ID, display a message
    if (!$ticket) {
        echo "A ticket with that ID did not exist.";
        exit;
    }

    // Check if $_GET['confirm'] == 'yes'. This means they clicked the 'yes' button to confirm the removal of the record.
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        // Prepare and execute SQL DELETE statement to remove the ticket where id == the $_GET['id']
        $pdo->beginTransaction();
        
        // First, delete all comments associated with that ticket
        $deleteComments = "DELETE FROM ticket_comments WHERE ticket_id = :ticket_id";
        $stmt = $pdo->prepare($deleteComments);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        
        // Then, delete the ticket itself
        $deleteTicket = "DELETE FROM tickets WHERE id = :ticket_id";
        $stmt = $pdo->prepare($deleteTicket);
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        
        // Commit the transaction
        $pdo->commit();
        
        // Redirect to the tickets page after deletion
        $_SESSION['messages'][] = "Ticket has been deleted";
        echo '<meta http-equiv="refresh" content="0;url=tickets.php">'; 
        exit;
    }
} else {
    // If no 'id' is set in the URL, redirect to the tickets page
    echo "Ticket ID is required.";
    exit;
}

?>
<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Delete Ticket</h1>
    <p class="subtitle">Are you sure you want to delete ticket: <?= htmlspecialchars_decode($ticket['title']) ?></p>
    <div class="buttons">
        <a href="?id=<?= $ticket['id'] ?>&confirm=yes" class="button is-success">Yes</a>
        <a href="tickets.php" class="button is-danger">No</a>
    </div>
</section>
<!-- END YOUR CONTENT -->