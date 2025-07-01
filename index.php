<?php
    require 'config/config.php';   

    $query  = "SELECT * FROM article  ORDER BY created_at DESC";
    $stmt = $conn->query($query);
    $stmt->execute();
    $allarticle = $stmt->fetchall(PDO::FETCH_ASSOC);

    $query1 = $conn->prepare("SELECT * FROM disclaimer ORDER BY created_at DESC LIMIT 2 ");
    $query1->execute();
    $alldiscl = $query1->fetchall(PDO::FETCH_OBJ);

 // trnding
    $min_time = date('Y-m-d H:i:s', strtotime('-7 days'));
    $stmt2 = $conn->prepare("SELECT article.id AS article_id,
     title, views,category, created_at,
     author.username AS author
     FROM article JOIN author ON article.author_id = author.id WHERE created_at >= :date ORDER BY views DESC LIMIT 5");
    $stmt2->bindParam(':date', $min_time, PDO::PARAM_STR);
    $stmt2->execute();
    $trandinglist = $stmt2->fetchall(PDO::FETCH_ASSOC);

 // featured
    $stmt2 = $conn->query("SELECT article.title AS title, article.description AS description, author.username AS author, article.id AS article_id, article.image AS image, article.created_at AS created_at FROM article JOIN featured ON article.id = article_id JOIN author ON article.author_id = author.id ORDER BY featured.created_at DESC LIMIT 1;");
    $stmt2->execute();
    $featuredlist = $stmt2->fetchall(PDO::FETCH_ASSOC);

 // top story
    $stmt3 = $conn->query("SELECT article.title AS title, article.description AS description, author.username AS author, article.id AS article_id, article.image AS image, article.category AS category, article.created_at AS created_at FROM article JOIN top_story ON article.id = article_id JOIN author ON article.author_id = author.id ORDER BY top_story.created_at DESC LIMIT 4;");
    $stmt3->execute();
    $storylist = $stmt3->fetchall(PDO::FETCH_ASSOC);

    // var_dump($storylist);




?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary Meta Tags -->
    <title>noots</title>
    <meta name="title" content="noots">
    <meta name="description" content="noots is your trusted source for insightful blogs, trending articles, and the latest news across technology, lifestyle, health, education, and more.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://noots.great-site.net/">
    <meta property="og:title" content="noots">
    <meta property="og:description" content="Explore blogs, trending articles, and current news in various categories like tech, health, lifestyle, and more at noots.">
    <meta property="og:image" content="https://yourwebsite.com/images/noots-preview.jpg">
    <meta property="og:site_name" content="noots">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://noots.great-site.net/">
    <meta name="twitter:title" content="noots ">
    <meta name="twitter:description" content="Discover insightful blogs, hot articles, and up-to-date news from multiple fields on noots.">
    <meta name="twitter:image" content="https://yourwebsite.com/images/noots-preview.jpg">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/category.css">

</head>

<body>
<header>
    <?php require'./include/header.php';   ?>
</header>


    <!-- Main Content -->
    <div class="container">
        <div class="slider">
            <?php foreach($alldiscl as $disclaimer):?>
                 <marquee behavior="" direction="left" > <?php echo $disclaimer->content; ?></marquee>
            <?php endforeach; ?>    
        </div>  

    <div class="main-content">
        <!-- Main Articles -->
        <div class="articles">
            <h2 class="section-title">Top Stories</h2>
            <div class="articles-grid">
                <?php foreach($storylist as $list): ?>
                    <a href="article.php?id=<?php echo($list['article_id']);?>&title=<?php echo $list['title']; ?>">                         
                        <div class="article-card">
                            <img src="<?php echo $list['image']; ?>"
                                alt="<?php echo $list['title']; ?>" class="article-img">
                            <div class="article-content">
                                <span class="article-category"><?php echo $list['category']; ?></span>
                                <h3 class="article-title"><?php echo $list['title']; ?></h3>
                                <p class="article-excerpt"><?php echo $list['description']; ?></p>
                                <div class="article-meta">
                                    <span><i class="far fa-user"></i> <?php echo $list['author']; ?></span>
                                    <span><i class="far fa-clock"></i>
                                        <?php 
                                            $formattedDate = date("F j, Y", strtotime($list['created_at']));
                                            echo $formattedDate;
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>    
                <?php endforeach; ?>
            </div>

            <h2 class="section-title">Health</h2>                
            <div class="card-1-container">
                <?php foreach($allarticle as $article): ?>
                    <?php if($article['category'] == 'Health'): ?>
                        <a href="article.php?id=<?php echo($article['id']);?>&name=<?php echo $article['title']; ?>">                    
                            <div class="card-1">                   
                                <div class="card-1-content">
                                    <div>
                                        <h3 class="card-title"><?php echo $article['title']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </a> 
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>       
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <h2 class="section-title">Trending Now</h2>
                <ul class="trending-list">
                    <?php  $i = 1; ?>
                    <?php foreach($trandinglist as $list): ?>
                        <li class="trending-item">
                            <a href="<?php echo APPURL; ?>/article.php?id=<?php echo $list['article_id']; ?>$title=<?php echo $list['title'] ?>">
                                <div class="trending-number">#<?php echo $i ?></div>
                                <div class="trending-content">
                                    <h4><?php echo $list['title']; ?></h4>
                                    <p><?php echo $list['category']; ?> · <?php echo $list['author']; ?></p>
                                </div>
                            </a>
                        </li>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </ul>


            <div class="newsletter">
                <h3>Subscribe to Our Newsletter</h3>
                <p>Stay updated with our latest news and exclusive content delivered directly to your inbox.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your email address">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>



                <!-- Featured Article -->
        <div class="featured-article">
            <?php foreach($featuredlist as $featured): ?>
                <a href="article.php?id=<?php echo($featured['article_id']);?>&title=<?php echo $featured['title']; ?>">                
                <div class="featured-content">
                    <span class="featured-tag">Featured Story</span>
                    <h2 style="color:#1a202c" class="featured-title"><?php echo $featured['title']; ?></h2>
                    <p style="color:#2d3748" class="featured-excerpt"><?php echo $featured['description']; ?></p>
                    <div class="article-meta">
                        <span><?php echo $featured['author']; ?></span>
                        <span><?php $formattedDate =date(" F j, Y", strtotime($featured['created_at']));
                                    echo $formattedDate; ?>
                        </span>
                    </div>
                </div>
                </a>
                <div class="featured-image"
                    style="background-image: url('<?php echo $featured['image'];?>');">
                </div>
            <?php endforeach; ?>
        </div>

     <h2 class="section-title">Science</h2>                
        
        <div class="cards-container">
            <?php foreach($allarticle as $article): ?>
                <?php if($article['category']=='Science'): ?>
                    <a href="article.php?id=<?php echo($article['id']);?>&title=<?php echo $article['title'];?>">
                            <div class="card">
                                <div class="card-img">
                                    <img src="<?php echo $article['image']; ?>" alt="">
                                </div>
                                <div class="card-content">
                                    <div>
                                        <h3 class="card-title"><?php echo $article['title'];  ?></h3>
                                    </div>
                                    <div class="card-footer">
                                        <span><i class="far fa-calendar"></i> <?php   
                                                $formattedDate = date(" F j, Y", strtotime($article['created_at']));
                                                echo $formattedDate; 
                                            ?></span>
                                    </div>
                                </div>
                            </div>  
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>        
        </div>

    <h2 class="section-title">Culture</h2>                

        <!-- Card Type 2: Title Overlay -->
        <div class="cards-grid">
            <?php foreach($allarticle as $article): ?>
                <?php if($article['category']=='Culture'): ?>
                    <a href="article.php?id=<?php echo($article['id']) ?>&title=<?php echo $article['title']; ?>">
                        <div class="card-2">
                            <div class="card-2-img">
                                <img src="<?php echo($article['image']); ?>" alt="<?php echo($article['title']); ?>">
                            </div>
                            <h3 class="card-2-title"><?php echo($article['title']); ?></h3>
                        </div>
                    </a>
                <?php endif; ?>    
            <?php endforeach; ?>

        </div>

    </div>
<footer>
    <?php require'./include/footer.php'; ?>

</footer>
</body>

</html>