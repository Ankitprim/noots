<?php
    require "./config/config.php";

    
if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']);
    
    // Example: Search in title or description
    $stmt = $conn->prepare("SELECT * FROM article WHERE title LIKE ? OR description LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    // var_dump($results);
}



?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link rel="stylesheet" href="./css/category.css">
         <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php     require "./include/header.php"; ?>
    <div class="container">  
        <?php if (!empty($results)): ?>
            <h2 style="color:green; margin:2rem;">Search Result for"<?php echo $search; ?>".</h2>   
            <div class="articles-grid">
                    <?php foreach($results as $article): ?>
                        <div class="article-card">
                            <a style="text-decoration:none"  href="<?php echo APPURL; ?>/article.php?id=<?php echo $article->id; ?>">
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
                        <h2 style="color:#e94560; margin:4rem;">No Search found.</h2> 
            </div>
        <?php endif; ?>  
    </div>


</body>
</html>
