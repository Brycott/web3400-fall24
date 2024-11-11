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

// Check if the 'id' parameter is passed
if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Get ticket details
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = :id");
    $stmt->execute(['id' => $ticket_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the ticket doesn't exist, redirect to the tickets list page
    if (!$ticket) {
        header("Location: tickets.php");
        exit();
    }

    // Fetch comments for the ticket
    $stmt_comments = $pdo->prepare("SELECT * FROM ticket_comments WHERE ticket_id = :ticket_id ORDER BY created_at DESC");
    $stmt_comments->execute(['ticket_id' => $ticket_id]);
    $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

    // Update ticket status when the user clicks the status link
    if (isset($_GET['status']) && in_array($_GET['status'], ['Open', 'In Progress', 'Closed'])) {
        $status = $_GET['status'];
        $update_stmt = $pdo->prepare("UPDATE tickets SET status = :status WHERE id = :id");
        $update_stmt->execute(['status' => $status, 'id' => $ticket_id]);

        // Redirect back to the ticket details page
        echo "<script>window.location.href = 'ticket_detail.php?id=" . $ticket_id . "';</script>";
        exit();
    }

    // Check if the comment form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg'])) {
        $msg = $_POST['msg'];

        // Insert the comment into the ticket_comments table
        $insert_stmt = $pdo->prepare("INSERT INTO ticket_comments (ticket_id, user_id, comment, created_at) VALUES (:ticket_id, :user_id, :comment, NOW())");
        $insert_stmt->execute(['ticket_id' => $ticket_id, 'user_id' => $_SESSION['user_id'], 'comment' => $msg]);

        // Redirect back to the ticket details page
        echo "<script>window.location.href = 'ticket_detail.php?id=" . $ticket_id . "';</script>";
        exit();
    }
} else {
    // If no ticket ID is provided, redirect to tickets page
    header("Location: tickets.php");
    exit();
}

?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Ticket Detail</h1>
    <p class="subtitle">
        <a href="tickets.php">View all tickets</a>
    </p>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <?= htmlspecialchars($ticket['title'], ENT_QUOTES) ?>
                &nbsp;
                <?php if ($ticket['priority'] == 'Low') : ?>
                    <span class="tag"><?= $ticket['priority'] ?></span>
                <?php elseif ($ticket['priority'] == 'Medium') : ?>
                    <span class="tag is-warning"><?= $ticket['priority'] ?></span>
                <?php elseif ($ticket['priority'] == 'High') : ?>
                    <span class="tag is-danger"><?= $ticket['priority'] ?></span>
                <?php endif; ?>
            </p>
            <button class="card-header-icon">
                <a href="ticket_detail.php?id=<?= $ticket['id'] ?>">
                    <span class="icon">
                        <?php if ($ticket['status'] == 'Open') : ?>
                            <i class="far fa-clock fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'In Progress') : ?>
                            <i class="fas fa-tasks fa-2x"></i>
                        <?php elseif ($ticket['status'] == 'Closed') : ?>
                            <i class="fas fa-times fa-2x"></i>
                        <?php endif; ?>
                    </span>
                </a>
            </button>
        </header>
        <div class="card-content">
            <div class="content">
                <time datetime="2016-1-1">Created: <?= date('F dS, G:ia', strtotime($ticket['created_at'])) ?></time>
                <br>
                <p><?= htmlspecialchars($ticket['description'], ENT_QUOTES) ?></p>
            </div>
        </div>
        <footer class="card-footer">
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=Closed" class="card-footer-item">
                <span class="icon"><i class="fas fa-times fa-2x"></i></span>
                <span>&nbsp;Close</span>
            </a>
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=In Progress" class="card-footer-item">
                <span><i class="fas fa-tasks fa_2x"></i></i></span>
                <span>&nbsp;In Progress</span>
            </a>
            <a href="ticket_detail.php?id=<?= $ticket['id'] ?>&status=Open" class="card-footer-item">
                <span><i class="far fa-clock fa-2x"></i></span>
                <span>&nbsp;Re-Open</span>
            </a>
        </footer>
    </div>
    <hr>
    <div class="block">
        <form action="" method="post">
            <div class="field">
                <label class="label"></label>
                <div class="control">
                    <textarea name="msg" class="textarea" placeholder="Enter your comment here..." required></textarea>
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-link">Post Comment</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="content">
            <h3 class="title is-4">Comments</h3>
            <?php foreach ($comments as $comment) : ?>
                <p class="box">
                    <span><i class="fas fa-comment"></i></span>
                    <?= date('F dS, G:ia', strtotime($comment['created_at'])) ?>
                    <br>
                    <?= nl2br(htmlspecialchars($comment['comment'], ENT_QUOTES)) ?>
                    <br>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- END YOUR CONTENT -->