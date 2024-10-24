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
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);


    $stmt = $pdo->prepare('UPDATE articles SET title = ?, content = ? WHERE id = ?');

 
    if ($stmt->execute([$title, $content, $id])) {
 
        $_SESSION['messages'][] = "The article was successfully updated.";
        echo '<meta http-equiv="refresh" content="0;url=articles.php">';
        exit;
    } else {
               $_SESSION['messages'][] = "An error occurred while updating the article.";
    }
} else {
  
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        // Prepare SQL statement to fetch the article
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the article exists
        if (!$article) {
            $_SESSION['messages'][] = "Article not found.";
            header('Location: articles.php');
            exit;
        }
    } else {
        // Invalid article ID
        $_SESSION['messages'][] = "Invalid article ID.";
        echo '<meta http-equiv="refresh" content="0;url=articles.php">';
        exit;
    }
}




?>

<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Edit Article</h1>
    <form action="" method="post">
        <!-- ID -->
        <input type="hidden" name="id" value="<?= $article['id'] ?>">
        <!-- Title -->
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" type="text" name="title" value="<?= $article['title'] ?>" required>
            </div>
        </div>
        <!-- Content -->
        <div class="field">
            <label class="label">Content</label>
            <div class="control">
                <textarea class="textarea" id="content" name="content" required><?= $article['content'] ?></textarea>
            </div>
        </div>
        <!-- Submit -->
        <div class="field is-grouped">
            <div class="control">
                <button type="submit" class="button is-link">Update Article</button>
            </div>
            <div class="control">
                <a href="articles.php" class="button is-link is-light">Cancel</a>
            </div>
        </div>
    </form>
</section>
<!-- END YOUR CONTENT -->