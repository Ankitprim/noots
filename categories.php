<?php
    require './config/config.php';


if (isset($_GET['name'])) {
    $name = $_GET['name'];

    // Pagination setup
    $articlesPerPage = 8;
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, $currentPage);
    $offset = ($currentPage - 1) * $articlesPerPage;

    // Get total number of articles
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM article WHERE category = :name");
    $countStmt->execute([':name' => $name]);
    $totalArticles = $countStmt->fetchColumn();
    $totalPages = ceil($totalArticles / $articlesPerPage);

    // Fetch paginated articles
    $articlesStmt = $conn->prepare("SELECT article.id AS id, article.title AS title , article.category AS category , article.description AS description, article.image AS image , author.username AS author, article.created_at AS created_at  FROM article JOIN author ON article.author_id = author.id WHERE category = :name LIMIT :limit OFFSET :offset");
    $articlesStmt->bindValue(':name', $name, PDO::PARAM_STR);
    $articlesStmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
    $articlesStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $articlesStmt->execute();
    $allArticles = $articlesStmt->fetchAll(PDO::FETCH_OBJ);

    // Fetch category description
    $descStmt = $conn->prepare("SELECT description FROM categories WHERE Name = :name");
    $descStmt->execute([':name' => $name]);
    $allDesc = $descStmt->fetchAll(PDO::FETCH_OBJ);
}





?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>noots</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="./css/style.css"> -->
     <link rel="stylesheet" href="./css/category.css">

</head>
<body>
    <header>
        <?php require'./include/header.php'; ?>
    </header>
     <!-- Main Content -->
    <div class="container">
        <h1 class="page-title"><?php echo $name; ?></h1>
        <?php foreach($allDesc as $Descp):?>
        <p class="category-description">
            <?php echo $Descp->description ; ?>
        </p>
        <?php endforeach; ?>

        <!-- Featured Article -->
        <!-- <div class="featured-article">
            <div class="featured-image" style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80');"></div>
            <div class="featured-content">
                <span class="featured-tag">Top Story</span>
                <h2 class="featured-title">Global Summit Addresses Climate Crisis as Temperatures Reach Record Highs</h2>
                <p class="featured-excerpt">
                    World leaders convene in emergency session as heatwaves sweep across three continents. New data shows 2025 is on track to be the hottest year in recorded history, with scientists warning of irreversible damage.
                </p>
                <div class="article-meta">
                    <span>By Sarah Johnson</span>
                    <span>June 10, 2025 • 8 min read</span>
                </div>
            </div>
        </div> -->

        <!-- Articles Grid -->
        <div class="articles-grid">
            <!-- Article 1 -->
             <?php  if(count($allArticles) > 0 ):  ?>
                <?php foreach($allArticles as $article): ?>
                        <div class="article-card">
                            <a style="text-decoration:none"  href="<?php echo APPURL; ?>/article.php?id=<?php echo $article->id; ?>&title=<?php echo $article->title;?>">
                                <div class="article-image" style="background-image: url('<?php echo $article->image; ?>');">
                                    <span class="category-tag"><?php echo $article->category; ?></span>
                                </div>
                                <div class="article-content">
                                    <h3 class="article-title"><?php echo $article->title; ?></h3>
                                    <p class="article-excerpt"><?php echo $article->description; ?></p>
                                    <div class="article-meta">
                                        <span><?php echo $article->author; ?></span>
                                        <span><?php   
                                                $formattedDate = date(" F j, Y", strtotime($article->created_at));
                                                echo $formattedDate; 
                                            ?></span>
                                    </div>
                                </div>
                             </a>
                        </div>
                <?php endforeach; ?>    
                    
            <?php else: ?>
                <h2 style="color:#e94560; margin:4rem;">No Articles in <?php echo $name; ?>  Category just yet.</h2> 
            <?php endif; ?>    
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a class="page-btn" href="?name=<?= urlencode($name) ?>&page=<?= $currentPage - 1 ?>"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="page-btn <?= ($i == $currentPage) ? 'active' : '' ?>" href="?name=<?= urlencode($name) ?>&page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a class="page-btn" href="?name=<?= urlencode($name) ?>&page=<?= $currentPage + 1 ?>"><i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <!-- Newsletter -->
    
    </div>
    <footer>
        <?php require'./include/footer.php'; ?>
    </footer>
</body>
</html>