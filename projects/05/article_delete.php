<?php include 'config.php'; ?>
<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<?php 

if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== 'admin') {
   
    $_SESSION['messages'][] = "You must be an administrator to access that resource.";
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

   
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

  
    if (!$article) {
        $_SESSION['messages'][] = "An article with that ID did not exist.";
        header('Location: articles.php');
        exit;
    }
} else {
 
    $_SESSION['messages'][] = "Invalid article ID.";
    header('Location: articles.php');
    exit;
}


if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
   
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
    if ($stmt->execute([$id])) {
      
        $_SESSION['messages'][] = "You deleted the article";
        echo '<meta http-equiv="refresh" content="0;url=articles.php">';
        exit;
    } else {
       
        $_SESSION['messages'][] = "An error occurred while deleting the article.";
    }
}




?>



<!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Delete Article</h1>
    <p class="subtitle">Are you sure you want to delete the article: <?= $article['title'] ?></p>
    <div class="buttons">
        <a href="?id=<?= $article['id'] ?>&confirm=yes" class="button is-success">Yes</a>
        <a href="articles.php" class="button is-danger">No</a>
    </div>
</section>
<!-- END YOUR CONTENT -->