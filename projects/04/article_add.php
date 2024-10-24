<?php 
include 'config.php';
include 'templates/head.php';
include 'templates/nav.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect user to login page or display an error message
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author_id = $_SESSION['user_id']; // Assuming you store the user_id in session

    // Prepare SQL INSERT statement
    $stmt = $pdo->prepare('INSERT INTO articles (title, content, author_id, is_featured, is_published, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
    
    // Set default values for is_featured and is_published
    $is_featured = 0; // Default not featured
    $is_published = 0; // Default not published

    // Execute the statement with the provided values
    if ($stmt->execute([$title, $content, $author_id, $is_featured, $is_published])) {
        // Redirect back to the articles.php page with a success message
        $_SESSION['messages'][] = "The article was successfully added.";
        echo '<meta http-equiv="refresh" content="0;url=articles.php">';
        exit;
    } else {
        // Handle error (optional)
        $_SESSION['messages'][] = "An error occurred while adding the article.";
    }
}


?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Write an article</h1>
    <form action="" method="post">
        <!-- Title -->
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" type="text" name="title" required>
            </div>
        </div>
        <!-- Content -->
        <div class="field">
            <label class="label">Content</label>
            <div class="control">
                <textarea class="textarea" id="content" name="content" required></textarea>
            </div>
        </div>
        <!-- Submit -->
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Add Post</button>
            </div>
            <div class="control">
                <a href="articles.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->