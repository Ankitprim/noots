
<?php
require './config/config.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Get user's IP and today's date
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $today = date('Y-m-d');

    // Check if the user has already viewed this article today
    $viewCheckStmt = $conn->prepare("
        SELECT COUNT(*) FROM article_views 
        WHERE article_id = ? AND ip_address = ? AND view_date = ?
    ");
    $viewCheckStmt->execute([$id, $user_ip, $today]);
    $already_viewed = $viewCheckStmt->fetchColumn();

    if (!$already_viewed) {
        // Insert a view record
        $insertViewStmt = $conn->prepare("
            INSERT INTO article_views (article_id, ip_address, view_date) 
            VALUES (?, ?, ?)
        ");
        $insertViewStmt->execute([$id, $user_ip, $today]);

        // Increment the article's view count
        $updateViewsStmt = $conn->prepare("
            UPDATE article SET views = views + 1 WHERE id = ?
        ");
        $updateViewsStmt->execute([$id]);
    }

        $querry = "
        SELECT 
            article.id AS article_id, 
            article.title AS title, 
            article.views AS views,
            article.category AS category, 
            article.description AS description,
            article.sub_title AS sub_title, 
            article.image AS article_image, 
            article.content AS content, 
            article.tags AS tags, 
            article.created_at AS created_at,
            author.id AS author_id,
            author.username AS author_name,
            author.img AS author_img
        FROM article 
        JOIN author ON article.author_id = author.id  
        WHERE article.id = ?
    ";

    $stmt = $conn->prepare($querry);
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($article);

    if (!$article) {
    echo "<h2>Article not found.</h2>";
    exit;
    }


// Latest
    $stmt1 = $conn->query("SELECT id, title, sub_title,image FROM article ORDER BY created_at DESC  LIMIT 3;");
    $stmt1->execute();
    $latestlist = $stmt1->fetchall(PDO::FETCH_ASSOC);



 // trnding
    $stmt2 = $conn->query("SELECT 
            article.title AS title, 
            tranding.id AS id, 
            article.category AS category, 
            author.username AS author, 
            article.id AS article_id 
        FROM tranding
        JOIN article ON article.id = tranding.article_id
        JOIN author ON article.author_id = author.id");
    $stmt2->execute();
    $trandinglist = $stmt2->fetchall(PDO::FETCH_ASSOC);

 }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']);?></title>
        <!-- Primary Meta Tags -->
    <meta name="title" content="noots">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://noots.great-site.net/">
    <meta property="og:title" content="noots">
    <meta property="og:description" content="<?php echo $article['description']; ?>">
    <meta property="og:site_name" content="noots">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="description" content="<?php echo $article['description']; ?>">
    <meta name="keywords" content="<?php echo $article['tags'];?>">
     <!-- <link rel="stylesheet" href="./css/style.css"> -->
    <link rel="stylesheet" href="./css/artical.css">
    <style>
              blockquote {
            border-left: 4px solid rgb(33, 69, 120);
            padding: 15px 20px;
            background: var(--light-bg);
            margin: 20px 0;
            font-style: italic;
        }
    </style>

</head>

<body>
    <?php require'./include/header.php'; ?>
    <div class="article-container">
        <main class="article-content-container">
            <div class="article-header">
                <div class="category"><?php echo $article['category']; ?></div>
                <h1 class="headline"><?php echo htmlspecialchars($article['title']); ?></h1>
                <p class="sub-headline"><?php echo htmlspecialchars($article['sub_title']); ?></p>

                <div class="byline">
                <img src="<?php echo htmlspecialchars($article['author_img'] ?? 'https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-portrait-176256935.jpg'); ?>" 
                    alt="<?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?>" 
                    class="author-img">

                    <div>
                        <strong><?php echo htmlspecialchars($article['author_name']); ?></strong><br>
                        
                    </div>
                </div>

                <div class="article-meta">
                    <div><i class="far fa-clock"></i> Published: <?php   
                                                $formattedDate = date(" F j, Y", strtotime($article['created_at']));
                                                echo $formattedDate; 
                                            ?></div>
                    <div><i class="far fa-eye"></i> <?php echo $article['views'] ?> views</div>
                    <!-- <div><i class="far fa-comments"></i> 1,243 comments</div> -->
                </div>
            </div>

            <img src="<?php echo htmlspecialchars($article['article_image']); ?>"
                alt="<?php echo htmlspecialchars($article['title']); ?>" class="featured-image">
            <!-- <div class="caption">Researchers working in the quantum computing laboratory at Stanford University. (Photo:
                Global Times)</div> -->

            <div class="article-content">
                <?php echo $article['content']; ?>
                
            </div>

            <div class="article-footer">
                <div class="social-sharing">
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>

                <div class="tags">
                    <span class="tag"><?php echo htmlspecialchars($article['tags']); ?></span>
                </div>
            </div>
        </main>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">Latest News</h3>
                <?php foreach($latestlist as $latest): ?>
                         <a style="text-decoration:none"  href="<?php echo APPURL; ?>/article.php?id=<?php echo $latest['id']; ?>&title=<?php echo $latest['title']; ?>"> 
                        <div class="related-article">
                            <img src="<?php echo $latest['image']; ?>"
                                alt="<?php echo $latest['title']; ?>">
                            <div>
                                <h4 style="color:black"><?php echo $latest['title']; ?></h4>
                                <p><?php echo $latest['sub_title']; ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Trending Now</h3>
                <ul class="trending-list">
                   <?php foreach($trandinglist as $list):?>
                         <a style="text-decoration:none"  href="<?php echo APPURL; ?>/article.php?id=<?php echo $list['article_id']; ?>&title= <?php echo $list['title']; ?>">                    
                            <li><span class="trending-number"><?php echo $list['id']; ?></span><?php echo $list['title']; ?></li>
                         </a>    
                    <?php endforeach; ?>     
                </ul>
            </div>

            <div class="ad-container">
                Advertisement Space
            </div>

            <div class="sidebar-section newsletter">
                <h3>Subscribe to Our Newsletter</h3>
                <p>Stay updated with the latest news and exclusive content.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </aside>
    </div>
    <?php require'./include/footer.php';?>
</body>

</html>