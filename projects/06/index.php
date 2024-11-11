<?php include 'config.php'; ?>
<?php include 'templates/head.php'; ?>
<?php include 'templates/nav.php'; ?>

<?php 
$stmt = $pdo->prepare('SELECT articles.*, users.full_name AS author FROM articles JOIN users ON articles.author_id = users.id WHERE is_published = 1 AND is_featured = 1 ORDER BY articles.created_at DESC LIMIT 10');
$stmt->execute();

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (!$articles) {
    $_SESSION['messages'][] = "There are no articles in the database.";
}

?>

    <!-- BEGIN YOUR CONTENT -->
<section class="section">
    <h1 class="title">Featured Articles</h1>
    <!-- articles List -->
    <?php foreach ($articles as $article) : ?>
        <div class="box">
            <article class="media">
                <figure class="media-left">
                    <p class="image is-128x128">
                        <img class="is-rounded" src="https://picsum.photos/128<?= $article['id'] ?>">
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                        <h4 class="title is-4"><a href="article.php?id=<?= $article['id'] ?>"><?= $article['title'] ?></a></h4>
                        <?= mb_substr($article['content'], 0, 200) . (mb_strlen($article['content']) > 200 ? "<a href=article.php?id={$article['id']}><strong> read more...</strong></a>" : "") ?>
                        </p>
                        <p>
                            <small><strong>Author: <?= $article['author'] ?></strong>
                                | Published: <?= time_ago($article['created_at']) ?>
                                <?php if ($article['modified_on'] !== NULL) : ?>
                                    | Updated: <?= time_ago($article['modified_on']) ?>
                                <?php endif; ?>
                            </small>
                        </p>
                    </div>
                    <p class="buttons">
                        <a class="button is-small is-rounded is-static">
                            <span class="icon is-small">
                            <i class="fas fa-thumbs-up"></i>
                            </span>
                            <span><?= $article['likes_count'] ?></span>
                        </a>
                        <a class="button is-small is-rounded is-static">
                            <span class="icon is-small">
                            <i class="fas fa-star"></i>
                            </span>
                            <span><?= $article['favs_count'] ?></span>
                        </a>
                        <a class="button is-small is-rounded is-static">
                            <span class="icon is-small">
                            <i class="fas fa-comment"></i>
                            </span>
                            <span><?= $article['comments_count'] ?></span>
                        </a>
                    </p>
                </div>
            </article>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<!-- END YOUR CONTENT -->

<?php include 'templates/footer.php'; ?>