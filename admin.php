<?php 
    require './config/config.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
    header("Location: ./auth/login.php");
    exit;
    }

    $id = $_SESSION['user_id'];

    $query0 = $conn->prepare("SELECT * FROM admin WHERE id = $id");
    $query0->execute();
    $admin = $query0->fetchall(PDO::FETCH_OBJ); 
    // var_dump($admin);

    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;



    $query = "SELECT 
                author.id AS id,
                author.email AS email,
                author.username AS username,
                author.expertise AS expertise,
                author.img AS author_img,
                author.joined AS joined,

                article.id AS article_id,
                article.title AS title,
                COUNT(article.title) AS article_count,
                COUNT(article_views.id) AS view_count,
                article.category AS category,
                article.author_id AS author_id,
                article.created_at AS created_at,

                categories.Name AS category_name,
                categories.description AS cat_descp,
                categories.created_at AS cat_create

            FROM article
            JOIN author ON article.author_id = author.id
            LEFT JOIN article_views ON article.id = article_views.article_id
            JOIN categories ON article.category = categories.Name
            GROUP BY article.id
            ORDER BY article.created_at DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $allDetails = $stmt->fetchALL(PDO::FETCH_OBJ);

    //  Fetch total articles to calculate number of pages
    $totalQuery = "SELECT COUNT(*) FROM article";
    $totalStmt = $conn->prepare($totalQuery);
    $totalStmt->execute();
    $totalArticles = $totalStmt->fetchColumn();
    $totalPages = ceil($totalArticles / $limit);


    // article
        $stmt1 = $conn->prepare("SELECT * FROM article ");
        $stmt1->execute();
        $article_details = $stmt1->fetchAll(PDO::FETCH_OBJ);
    // views
        $stmt2 = $conn->prepare("SELECT * FROM article_views ");
        $stmt2->execute();
        $views_details = $stmt2->fetchAll(PDO::FETCH_OBJ);
    // category
        $stmt4 = $conn->prepare("SELECT * FROM categories ");
        $stmt4->execute();
        $categories_details = $stmt4->fetchAll(PDO::FETCH_OBJ);
    // top-story
        $stmt7i = $conn->prepare("SELECT article.id AS id, title, headlines.created_at AS created_at FROM article JOIN headlines ON article.id = headlines.article_id;");
        $stmt7i->execute();
        $headlines = $stmt7i->fetchAll(PDO::FETCH_OBJ);    
    // top-story
        $stmt7 = $conn->prepare("SELECT article.id AS id, title, top_story.created_at AS created_at FROM article JOIN top_story ON article.id = top_story.article_id;");
        $stmt7->execute();
        $topStory = $stmt7->fetchAll(PDO::FETCH_OBJ);
    //featured
        $stetment = $conn->prepare("SELECT article.id AS id, title, featured.created_at AS created_at FROM article JOIN featured ON article.id = featured.article_id;");
        $stetment->execute();
        $featured = $stetment->fetchAll(PDO::FETCH_OBJ);
    //disclaimer
        $stetment1 = $conn->prepare("SELECT id, content, created_at FROM disclaimer ORDER BY `created_at` DESC;"); 
        $stetment1->execute();
        $disclaimer = $stetment1->fetchAll(PDO::FETCH_OBJ);

    // delete article
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $deleteId = (int) $_POST['delete_id'];

        $stmt5 = $conn->prepare("DELETE FROM article WHERE id = :id");
        $stmt5->bindParam(':id', $deleteId, PDO::PARAM_INT);

        if ($stmt5->execute()) {
            // Redirect to the same page to avoid re-submitting the form
            $_SESSION['flash_message'] = "Article deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete article.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }

    // add category
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])){
        $name = $_POST['category-name'];
        $descp = $_POST['category-description'];
        $stmt6 = $conn->prepare("INSERT INTO categories (Name, description) VALUES(:name, :desp)");

        $stmt6->execute([
            ':name' => $name,
            ':desp' => $descp
        ]);

        // redirect after submiting

        header("Location: ". $_SERVER['PHP_SELF']);
        exit;
    }

    // delete category
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cat'])) {
        $deleteId = (int) $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect to the same page to avoid re-submitting the form
            $_SESSION['flash_message'] = "Article deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete article.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }
    //add headline
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['headline'])){
        $id = $_POST['headline_id'];
        $stmt_h = $conn->prepare("INSERT INTO headlines (article_id) VALUES(:id)");
        $stmt_h->execute([
            ':id' => $id
        ]);
        header("Location: ". $_SERVER['PHP_SELF']);
        exit;

    }

    // delete headline
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_headline'])){
        $deleteId  = (int) $_POST['headline_id'];
        $stmt_H = $conn->prepare("DELETE FROM headlines WHERE article_id = :id");
        $stmt_H->bindParam(':id', $deleteId, PDO::PARAM_INT);
        if($stmt_H->execute()){
            $_SESSION['flash_message'] = "Headline deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete Headline.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }


    //add top stroy
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['top-story'])){
        $id = $_POST['top-id'];
        $stmt8 = $conn->prepare("INSERT INTO top_story (article_id) VALUES(:id)");
        $stmt8->execute([
            ':id' => $id
        ]);
        header("Location: ". $_SERVER['PHP_SELF']);
        exit;

    }

    // delete top-stroy
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_top'])){
        $deleteId  = (int) $_POST['top_id'];
        $stmt9 = $conn->prepare("DELETE FROM top_story WHERE article_id = :id");
        $stmt9->bindParam(':id', $deleteId, PDO::PARAM_INT);
        if($stmt9->execute()){
            $_SESSION['flash_message'] = "Top-story deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete Top-story.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }

    //add featured stroy
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['featured-story'])){
        $id = $_POST['featured-id'];
        $stmt10 = $conn->prepare("INSERT INTO featured (article_id) VALUES(:id)");
        $stmt10->execute([
            ':id' => $id
        ]);
        header("Location: ". $_SERVER['PHP_SELF']);
        exit;

    }

    // delete featured-stroy
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_featured'])){
        $deleteId  = (int) $_POST['featured_id'];
        $stmt11 = $conn->prepare("DELETE FROM featured WHERE article_id = :id");
        $stmt11->bindParam(':id', $deleteId, PDO::PARAM_INT);
        if($stmt11->execute()){
            $_SESSION['flash_message'] = "featured-story deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete featured-story.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }  

    // disclaimer
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disclaimer'])){
        $content = $_POST['desc'];
        $stetment1 = $conn->prepare("INSERT INTO disclaimer (content) VALUES(?) ");
        $stetment1->execute([$content]);
        header("Location: ". $_SERVER['PHP_SELF']);
        exit;
    }

    //delete disclaimer
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_disc'])){
        $id = (int) $_POST['disc_id'];
        $stetment1 = $conn->prepare("DELETE FROM disclaimer WHERE id = :id ");
        $stetment1->bindParam(':id',$id, PDO::PARAM_INT);
        if($stetment1->execute()){
            $_SESSION['flash_message'] = "disclaimer deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            $_SESSION['flash_message'] = "Failed to delete disclaimer.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    } 


    // author
    $query = "SELECT 
                author.id AS id,
                author.email AS email,
                author.username AS username,
                author.expertise AS expertise,
                author.img AS author_img,
                author.joined AS joined,

                COUNT(DISTINCT article.id) AS article_count,
                COUNT(article_views.id) AS view_count

            FROM author
            LEFT JOIN article ON article.author_id = author.id
            LEFT JOIN article_views ON article.id = article_views.article_id

            GROUP BY author.id
            ORDER BY MAX(article.created_at) DESC
            LIMIT $limit OFFSET $offset
            ";
    $stmt3 = $conn->prepare($query);
    $stmt3->execute();
    $author_details =$stmt3->fetchALL(PDO::FETCH_OBJ);   

    // delete author delete_auth
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_auth'])) {
        $deleteId = (int) $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM author WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }

    // Add member
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $img = trim($_POST['img']);

        if (strtolower($role) === 'author') {
            $stmt = $conn->prepare("INSERT INTO author (username, email, password, img) VALUES (:username, :email, :password, :img)");
            $stmt->execute([
                ':username' => $name,
                ':email' => $email,
                ':password' => $password,
                ':img' => $img
            ]);
        } else {
            $stmt = $conn->prepare("INSERT INTO admin (name, email, password, image) VALUES (:name, :email, :password, :image)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':image' => $img
            ]);
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
   // Update member
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
        $id = $_SESSION['user_id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $img = trim($_POST['img']);

        // Ensure the ID is valid
        if ($id > 0 && !empty($name) && !empty($email)) {
            // Only hash and update password if it's provided
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ?, password = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $email, $hashedPassword, $img, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $email, $img, $id]);
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Invalid input or missing ID.";
        }
    }

    // Admin
    $query1 = $conn->prepare("SELECT * FROM admin");
    $query1->execute();
    $admin_details = $query1->fetchall(PDO::FETCH_OBJ);

    // delete admin

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_auth'])) {
        $deleteId = (int) $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM admin WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        }
    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>noots - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #16213e;
            --secondary: #0f3460;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --sidebar-width: 260px;
            --header-height: 70px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h2 i {
            color: var(--accent);
        }

        .close-sidebar {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(0, 0, 0, 0.2);
            border-left: 3px solid var(--accent);
        }

        .menu-item i {
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--dark);
            display: none;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 8px 15px 8px 35px;
            border-radius: 20px;
            border: 1px solid #ddd;
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }

        .notification, .user-profile {
            position: relative;
            cursor: pointer;
        }

        .notification i, .user-profile i {
            font-size: 20px;
            color: var(--dark);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        .page-title {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h1 {
            font-size: 24px;
            color: var(--dark);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--secondary);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #219653;
        }
        
        .btn-danger {
            background: var(--accent);
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }

        .stat-icon.blue {
            background: rgba(52, 152, 219, 0.2);
            color: var(--secondary);
        }

        .stat-icon.green {
            background: rgba(46, 204, 113, 0.2);
            color: var(--success);
        }

        .stat-icon.orange {
            background: rgba(241, 196, 15, 0.2);
            color: var(--warning);
        }

        .stat-icon.red {
            background: rgba(231, 76, 60, 0.2);
            color: var(--accent);
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #777;
            font-size: 14px;
        }

        /* Charts and Tables */
        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h2 {
            font-size: 18px;
            color: var(--dark);
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Forms */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        /* Recent Articles */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
        }
        a{
            text-decoration: none;
            color:var(--dark);
        }
        tr:hover {
            background: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.published {
            background: rgba(46, 204, 113, 0.2);
            color: var(--success);
        }

        .status.draft {
            background: rgba(241, 196, 15, 0.2);
            color: var(--warning);
        }

        .status.pending {
            background: rgba(52, 152, 219, 0.2);
            color: var(--secondary);
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
            color: #777;
            transition: var(--transition);
        }

        .action-btn:hover {
            color: var(--secondary);
        }
        /* peganation */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ccc;
            color: #333;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }


        /* Recent Activity */
        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--secondary);
            flex-shrink: 0;
        }

        .activity-content h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .activity-content p {
            font-size: 13px;
            color: #777;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Author Cards */
        .authors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .author-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }
        
        .author-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .author-header {
            padding: 20px;
            text-align: center;
            background: var(--primary);
            color: white;
        }
        
        .author-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }
        
        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-body {
            padding: 20px;
        }
        
        .author-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .meta-item {
            text-align: center;
        }
        
        .meta-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .meta-label {
            font-size: 12px;
            color: #777;
        }

        /* form  */
     .form-container {
            padding: 50px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .form-group {
            margin-bottom: 28px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.05rem;
            padding-left: 5px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 18px;
            transition: color 0.3s;
            z-index: 2;
        }
        
        .form-control {
            width: 100%;
            padding: 16px 52px 16px 52px; /* Increased right padding for eye icon */
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
            background: #fafbff;
            color: var(--dark);
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
            position: relative;
            z-index: 1;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }
        
        .form-control:focus + i {
            color: var(--primary);
        }
        
        .password-container {
            position: relative;
        }
        
        /* Fixed positioning for eye icon */
        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
            font-size: 18px;
            transition: color 0.3s;
            z-index: 2;
            background: transparent;
            border: none;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .toggle-password:hover {
            color: var(--primary);
        }
        
        .image-preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .image-preview {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #eef2ff;
            background: #f8f9ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }
        
        .image-preview:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        
        .image-preview i {
            font-size: 3.5rem;
            color: #d0d5ff;
        }
        
        .role-container {
            position: relative;
        }
        
        /* Fixed dropdown arrow styling */
        .role-container::after {
            content: "\f078";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            pointer-events: none;
            transition: color 0.3s;
            z-index: 2;
        }
        
        select:focus + .role-container::after {
            color: var(--primary);
        }
        
        /* Fix for select element to show arrow properly */
        .role-container select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 50px; /* Space for arrow */
        }

        .btn-add {
            display: block;
            width: 100%;
            padding: 18px;
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            box-shadow: 0 2px 15px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-add::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        }
        
        .btn-add:hover::before {
            left: 100%;
        }
        
        .btn-add:active {
            transform: translateY(0);
        }
        
        
        .btn-add i {
            margin-right: 10px;
        }
        .error-message {
            color: var(--error);
            font-size: 0.92rem;
            margin-top: 8px;
            display: none;
            font-weight: 500;
            padding-left: 5px;
            animation: shake 0.4s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-4px); }
            40%, 80% { transform: translateX(4px); }
        }
        
        .password-strength {
            display: flex;
            gap: 6px;
            margin-top: 15px;
        }
        
        .strength-bar {
            height: 6px;
            flex: 1;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .strength-bar span {
            display: block;
            height: 100%;
            width: 0;
            transition: width 0.3s, background 0.3s;
        }
        
        .strength-text {
            margin-top: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray);
        }
        
        .form-note {
            font-size: 0.9rem;
            color: var(--gray);
            margin-top: 6px;
            padding-left: 5px;
        }

        

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
        
        /* Page Transitions */
        .page {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .page.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: block;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .search-box {
                display: none;
            }
            
            .close-sidebar {
                display: block;
            }
            
            .overlay {
                display: block;
                opacity: 0;
                pointer-events: none;
                transition: var(--transition);
            }
            
            .overlay.active {
                opacity: 1;
                pointer-events: all;
            }
        }

        @media (max-width: 480px) {

            .form-container {
                padding: 25px 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        /* Chart placeholder */
        .chart-placeholder {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            border-radius: 4px;
            color: #777;
            font-style: italic;
        }
        
        /* Logout page */
        .logout-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            text-align: center;
        }
        
        .logout-icon {
            font-size: 80px;
            color: var(--accent);
            margin-bottom: 30px;
        }
        
        .logout-container h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .logout-container p {
            font-size: 18px;
            color: #777;
            max-width: 500px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Overlay for mobile sidebar -->
        <div class="overlay" id="overlay"></div>
        
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fa-solid fa-user-tie"></i> noots Admin</h2>
                <button class="close-sidebar" id="closeSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-menu">
                <div class="menu-item active" data-page="dashboard-page">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </div>
                <div class="menu-item" data-page="articles-page">
                    <i class="fas fa-file-alt"></i>
                    <span>Articles</span>
                </div>
                <div class="menu-item" data-page="categories-page">
                    <i class="fas fa-list"></i>
                    <span>Categories</span>
                </div>
                <div class="menu-item" data-page="headlines-page">
                    <i class="fa-solid fa-heading"></i>
                    <span>Headlines</span>
                </div>
                <div class="menu-item" data-page="top-stories-page">
                    <i class="fa-solid fa-window-restore"></i>
                    <span>Top Stories</span>
                </div>
                <div class="menu-item" data-page="featured-stories-page">
                    <i class="fa-solid fa-diamond"></i>
                    <span>Featured Stories</span>
                </div>
                <div class="menu-item" data-page="disclimer-page">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Disclaimer</span>
                </div>
                <!-- <div class="menu-item" data-page="Tranding-page">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Tranding</span>
                </div> -->
                <div class="menu-item" data-page="authors-page">
                    <i class="fas fa-users"></i>
                    <span>Authors</span>
                </div>
                <div class="menu-item" data-page="members-page">
                    <i class="fa-solid fa-people-group"></i>
                    <span>Add Mamber</span>
                </div>
                <div class="menu-item" data-page="admin-page">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Admin</span>
                </div>
                <div class="menu-item" data-page="update-page">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Update Profile</span>
                </div>
                <div class="menu-item" data-page="logout-page">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <?php foreach($admin as $details): ?>
                <div class="wish"><h3>Hello, <?php echo $details->name; ?>!</h3></div>
                <div class="header-right">
                    <!-- <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div> -->
                    <!-- <div class="notification">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </div> -->
                    <div class="user-profile">
                        <img src="<?php echo $details->image; ?>" alt="Admin">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Dashboard Page -->
                <div id="dashboard-page" class="page active">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                        <!-- <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Article
                        </button> -->
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo count($article_details); ?></h3>
                                <p>Total Articles</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo count($views_details); ?></h3>
                                <p>Total Views</p>
                            </div>
                        </div>
                        <!-- <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="stat-info">
                                <h3>1,842</h3>
                                <p>New Comments</p>
                            </div>
                        </div> -->
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3><?php echo count($author_details); ?></h3>
                                <p>Active Authors</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Content -->
                    <div class="grid-container">
                        <div class="card">
                            <div class="card-header">
                                <h2>Traffic Overview</h2>
                                <select>
                                    <option>Last 7 Days</option>
                                    <option>Last 30 Days</option>
                                    <option>Last 90 Days</option>
                                </select>
                            </div>
                            <div class="chart-container">
                                <div class="chart-placeholder">
                                    <i class="fas fa-chart-bar" style="font-size: 40px; margin-bottom: 15px;"></i>
                                    <p>Traffic Analytics Chart</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="card">
                            <div class="card-header">
                                <h2>Recent Activity</h2>
                            </div>
                            <ul class="activity-list">
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h4>New Article Published</h4>
                                        <p>"Global Climate Summit Reaches Historic Agreement" by John Smith</p>
                                        <div class="activity-time">10 minutes ago</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h4>New Comment</h4>
                                        <p>David Wilson commented on "Tech Innovations 2023"</p>
                                        <div class="activity-time">45 minutes ago</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h4>New Author Registered</h4>
                                        <p>Sarah Johnson joined as a new contributor</p>
                                        <div class="activity-time">2 hours ago</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h4>Image Uploaded</h4>
                                        <p>15 new images added to the media library</p>
                                        <div class="activity-time">5 hours ago</div>
                                    </div>
                                </li>
                            </ul>
                        </div> -->
                    </div>

                    <!-- Recent Articles -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Recent Articles</h2>
                            <button class="btn" onClick="window.location.reload()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($allDetails as $list): ?>
                                            <tr>
                                                <td><a href="./article.php?id=<?php echo $list->article_id; ?>"><?php echo $list->title; ?></a></td>
                                                <td><?php echo $list->username; ?></td>
                                                <td><?php echo $list->category; ?></td>
                                                <td><?php $date = date("d M Y", strtotime($list->created_at));
                                        echo $date; ?></td>
                                                <td><span class="status published">Published</span></td>
                                                <td><?php echo $list->view_count; ?></td>
                                                <td>
                                                    <!-- <button class="action-btn"><i class="fas fa-edit"></i></button> -->
                                                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $list->article_id; ?>">
                                                    <button class="action-btn" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Articles Page -->
                <div id="articles-page" class="page">
                    <div class="page-title">
                        <h1>Article Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Article
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Filter Articles</h2>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="search">Search Articles</label>
                                <input type="text" id="search" placeholder="Search by title, author, or keyword...">
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category">
                                    <option value="">All Categories</option>
                                    <option value="politics">Politics</option>
                                    <option value="technology">Technology</option>
                                    <option value="business">Business</option>
                                    <option value="health">Health</option>
                                    <option value="culture">Culture</option>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status">
                                    <option value="">All Statuses</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending Review</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date Range</label>
                                <input type="text" id="date" placeholder="Select date range...">
                            </div> -->
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All Articles (<?php echo count($article_details); ?>)</h2>
                            <div>
                                <!-- <button class="btn">
                                    <i class="fas fa-file-export"></i> Export
                                </button> -->
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($allDetails as $list): ?>
                                            <tr>
                                                <td><?php echo $list->article_id; ?></td>
                                                <td><a href="./article.php?id=<?php echo $list->article_id; ?>"><?php echo $list->title; ?></a></td>
                                                <td><?php echo $list->username; ?></td>
                                                <td><?php echo $list->category; ?></td>
                                                <td><?php $date = date("d M Y", strtotime($list->created_at));
                                        echo $date; ?></td>
                                                <td><span class="status published">Published</span></td>
                                                <td><?php echo $list->view_count; ?></td>
                                                <td>
                                                    <!-- <button class="action-btn"><i class="fas fa-edit"></i></button> -->
                                                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $list->article_id; ?>">
                                                    <button class="action-btn" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                        <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" class="<?php if($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                                <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Categories Page -->
                <div id="categories-page" class="page">
                    <div class="page-title">
                        <h1>Category Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Category
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Add New Category</h2>
                        </div>
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category-name">Category Name</label>
                                        <input type="text" id="category-name" name="category-name" placeholder="Enter category name...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="category-description">Description</label>
                                    <textarea id="category-description" name="category-description" rows="4" placeholder="Enter category description..."></textarea>
                                </div>
                                <div class="form-actions">
                                    <button class="btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button class="btn btn-primary" name="category" type="submit">
                                        <i class="fas fa-save"></i> Save Category
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All Categories (<?php echo count($categories_details); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>                              
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Articles</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories_details as $list): ?>
                                        <tr>
                                            <td><?php echo $list->Name; ?></td>
                                            <td><?php
                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM article WHERE category = :cat");
                                                $stmt->bindParam(':cat', $list->Name);
                                                $stmt->execute();
                                                $count = $stmt->fetch(PDO::FETCH_OBJ)->total;
                                                echo $count;
                                                ?>
                                            </td>
                                            <td><?php $formanted = date("d M Y", strtotime    ($list->created_at));
                                                echo $formanted; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_cat">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- headline Page -->
                 <div id="headlines-page" class="page">
                    <div class="page-title">
                        <h1>Headlines Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Headline
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Add Headline</h2>
                        </div>
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="headine_id">Article Id</label>
                                        <input type="number" id="headline_id" name="headline_id" placeholder="Enter article id...">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button class="btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button class="btn btn-primary" name="headline" type="submit">
                                        <i class="fas fa-save"></i> Save Story
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All Headlines (<?php echo count($headlines); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>                              
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>title</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($headlines as $list): ?>
                                        <tr>
                                            <td><?php echo $list->id; ?></td>
                                            <td><?php echo $list->title;?>
                                            </td>
                                            <td><?php $formanted = date("d M Y", strtotime    ($list->created_at));
                                                echo $formanted; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this headline?');">
                                                    <input type="hidden" name="headline_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_headline">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top-story Page -->
                 <div id="top-stories-page" class="page">
                    <div class="page-title">
                        <h1>Top Story Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> top story
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Add Top Story</h2>
                        </div>
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="top-id">Article Id</label>
                                        <input type="number" id="top-id" name="top-id" placeholder="Enter article id...">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button class="btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button class="btn btn-primary" name="top-story" type="submit">
                                        <i class="fas fa-save"></i> Save Story
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All Top Stroy (<?php echo count($topStory); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>                              
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>title</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topStory as $list): ?>
                                        <tr>
                                            <td><?php echo $list->id; ?></td>
                                            <td><?php echo $list->title;?>
                                            </td>
                                            <td><?php $formanted = date("d M Y", strtotime    ($list->created_at));
                                                echo $formanted; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this top story?');">
                                                    <input type="hidden" name="top_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_top">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Featured-story Page -->
                 <div id="featured-stories-page" class="page">
                    <div class="page-title">
                        <h1>Featured Story Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> featured story
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Add featured Story</h2>
                        </div>
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="featured-id">Article Id</label>
                                        <input type="number" id="featured-id" name="featured-id" placeholder="Enter article id...">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button class="btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button class="btn btn-primary" name="featured-story" type="submit">
                                        <i class="fas fa-save"></i> Save Story
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All featured Stroy (<?php echo count($featured); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>                              
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>title</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($featured as $list): ?>
                                        <tr>
                                            <td><?php echo $list->id; ?></td>
                                            <td><?php echo $list->title;?>
                                            </td>
                                            <td><?php $formanted = date("d M Y", strtotime    ($list->created_at));
                                                echo $formanted; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this featured story?');">
                                                    <input type="hidden" name="featured_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_featured">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Disclaimer Page -->
                 <div id="disclimer-page" class="page">
                    <div class="page-title">
                        <h1>Disclaimer Management</h1>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus"></i> Disclaimer
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Add Disclaimer</h2>
                        </div>
                        <div class="form-container">
                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="disc">Disclaimer</label>
                                        <input type="text" id="disc" name="desc" placeholder="Enter disclaimer">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button class="btn">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button class="btn btn-primary" name="disclaimer" type="submit">
                                        <i class="fas fa-save"></i> Save Disclaimer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>All disclaimer (<?php echo count($disclaimer); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>                              
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>content</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($disclaimer as $list): ?>
                                        <tr>
                                            <td><?php echo $list->id; ?></td>
                                            <td><?php echo $list->content;?>
                                            </td>
                                            <td><?php $formanted = date("d M Y", strtotime($list->created_at));
                                                echo $formanted; ?>
                                            </td>
                                            <td>
                                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this disclaimer?');">
                                                    <input type="hidden" name="disc_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_disc">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Authors Page -->
                <div id="authors-page" class="page">
                    <div class="page-title">
                        <h1>Author Management</h1>
                    </div>
                       
                    <div class="card">
                        <div class="card-header">
                            <h2>All Authors (<?php echo count($author_details); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="authors-grid">
                            <?php foreach($author_details as $list): ?>
                                <div class="author-card">
                                    <div class="author-header">
                                        <div class="author-avatar">
                                            <img src="<?php echo $list->author_img ;?>" alt="John Smith">
                                        </div>
                                        <h3><?php echo $list->username; ?></h3>
                                        <p>Senior Editor</p>
                                    </div>
                                    <div class="author-body">
                                        <p><i class="fas fa-envelope"></i> Email:  <?php  echo $list->email; ?></p>
                                        <p><i class="fas fa-calendar-alt"></i> Joined: <?php $date = date("d M Y", strtotime($list->joined));
                                        echo $date; ?></p>
                                        <div class="author-meta">
                                            <div class="meta-item">
                                                <div class="meta-value"><?php echo $list->article_count; ?></div>
                                                <div class="meta-label">Articles</div>
                                            </div>
                                            <div class="meta-item">
                                                <div class="meta-value"><?php echo $list->view_count; ?></div>
                                                <div class="meta-label">Views</div>
                                            </div>
                                            <div class="meta-item">
                                                <div class="meta-value">
                                                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this author?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $list->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete_auth">
                                                            <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    </form>
                                                </div>
                                                <div class="meta-label">Delete</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Add admin & author -->
                 <div id="members-page" class="page">
                    <div class="page-title">
                        <h1>Add Member</h1>
                    </div>                    
                    <div class="card">
                        <div class="form-container">
                            <form class="form" method="post" action="">
                                <div class="form-grid">
                                    <div>
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <div class="input-with-icon">
                                                <input type="text" class="name-input" name="name" class="form-control" placeholder="akt Doe">
                                            </div>
                                            <div class="error-message" id="name-error">Please enter your full name</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <div class="input-with-icon">
                                                <input type="email" id="email" class="email-input" name="email" class="form-control" placeholder="john@example.com">
                                            </div>
                                            <div class="error-message" id="email-error">Please enter a valid email</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">Create New Password</label>
                                            <div class="input-with-icon password-container">
                                                <input type="password" id="password" class="password-input" class="form-control" name="password" placeholder="Create a strong password">
                                                <button type="button" class="toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength">
                                                <div class="strength-bar"><span class="strength-1"></span></div>
                                                <div class="strength-bar"><span class="strength-2"></span></div>
                                                <div class="strength-bar"><span class="strength-3"></span></div>
                                                <div class="strength-bar"><span class="strength-4"></span></div>
                                            </div>
                                            <div class="strength-text" id="strength-text">Password strength</div>
                                            <div class="error-message" id="password-error">Password must be at least 8 characters</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="form-group">
                                            <label for="imgUrl">Profile Image URL</label>
                                            <div class="input-with-icon">
                                                <input type="url" id="imgUrl" class="img-url" class="form-control" name="img" placeholder="https://example.com/photo.jpg">
                                            </div>
                                            <div class="error-message" id="image-error">Please enter a valid image URL</div>
                                            
                                            <div class="image-preview-container">
                                                <div class="image-preview">
                                                    <i class="fas fa-user"></i>
                                                    <img src="" alt="Preview" class="preview-img">
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="form-group">
                                            <label for="role">Account Role</label>
                                            <div class="role-container">
                                                <select id="role" class="form-control" name="role">
                                                    <option value="">Select your role</option>
                                                    <option value="author">Author</option>
                                                    <option value="admin">Administrator</option>
                                                </select>
                                            </div>
                                            <div class="error-message" id="role-error">Please select a role</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-add" name="add_member" >
                                    <i class="fas fa-user-plus"></i> Add Member
                                </button>
                            </form>
                        </div>
                    </div>
                 </div>
                <!-- Admin -->
                <div id="admin-page" class="page">
                    <div class="page-title">
                        <h1>Admin Management</h1>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>All Admin (<?php echo count($admin_details); ?>)</h2>
                            <div>
                                <button class="btn" onClick="window.location.reload()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="authors-grid">
                            <?php foreach($admin_details as $list): ?>
                                <div class="author-card">
                                    <div class="author-header">
                                        <div class="author-avatar">
                                            <img src="<?php echo $list->image ;?>" alt="John Smith">
                                        </div>
                                        <h3><?php echo $list->name; ?></h3>
                                        <p>Senior Editor</p>
                                    </div>
                                    <div class="author-body">
                                        <p><i class="fas fa-envelope"></i> Email:  <?php  echo $list->email; ?></p>
                                        <p><i class="fas fa-calendar-alt"></i> Joined: <?php $date = date("d M Y", strtotime($list->created_at));
                                        echo $date; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Update profile -->
                 <div id="update-page" class="page">
                    <div class="card">
                        <div class="form-container">
                            <form class="form" method="post" action="">
                                <div class="form-grid">
                                    <div>
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <div class="input-with-icon">
                                                <input type="text" class="name-input" name="name" class="form-control" placeholder="akt Doe">
                                            </div>
                                            <div class="error-message" id="name-error">Please enter your full name</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <div class="input-with-icon">
                                                <input type="email" id="email" class="email-input" name="email" class="form-control" placeholder="john@example.com">
                                            </div>
                                            <div class="error-message" id="email-error">Please enter a valid email</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">Create New Password</label>
                                            <div class="input-with-icon password-container">
                                                <input type="password" id="password" class="password-input" class="form-control" name="password" placeholder="Create a strong password">
                                                <button type="button" class="toggle-password" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength">
                                                <div class="strength-bar"><span class="strength-1"></span></div>
                                                <div class="strength-bar"><span class="strength-2"></span></div>
                                                <div class="strength-bar"><span class="strength-3"></span></div>
                                                <div class="strength-bar"><span class="strength-4"></span></div>
                                            </div>
                                            <div class="strength-text" id="strength-text">Password strength</div>
                                            <div class="error-message" id="password-error">Password must be at least 8 characters</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="form-group">
                                            <label for="imgUrl">Profile Image URL</label>
                                            <div class="input-with-icon">
                                                <input type="url" id="imgUrl" class="img-url" class="form-control" name="img" placeholder="https://example.com/photo.jpg">
                                            </div>
                                            <div class="error-message" id="image-error">Please enter a valid image URL</div>
                                            
                                            <div class="image-preview-container">
                                                <div class="image-preview">
                                                    <i class="fas fa-user"></i>
                                                    <img src="" alt="Preview" class="preview-img">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-add" name="update_member" >
                                    <i class="fas fa-user-plus"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                 </div>
                <!-- Logout Page -->
                <div id="logout-page" class="page">
                    <div class="logout-container">
                        <div class="logout-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <h1>Confirm Logout</h1>
                        <p>Are you sure you want to log out? Any unsaved changes will be lost.</p>
                        <div>
                            <a href="logout.php"> <button class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                                <i class="fas fa-sign-in-alt"></i> Logout
                            </button></a>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="footer">
                    <p>© 2023 noots Admin Dashboard. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('overlay');
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        closeBtn.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Page navigation
        const menuItems = document.querySelectorAll('.menu-item');
        const pages = document.querySelectorAll('.page');
        
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Update active menu item
                menuItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                // Show the selected page
                const targetPage = this.getAttribute('data-page');
                pages.forEach(page => {
                    page.classList.remove('active');
                    if(page.id === targetPage) {
                        setTimeout(() => {
                            page.classList.add('active');
                        }, 10);
                    }
                });
                
                // Close sidebar on mobile after selection
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
                
                // Update page title
                const pageTitle = this.querySelector('span').textContent;
                document.querySelector('.page-title h1').textContent = pageTitle;
            });
        });

        // Close sidebar when clicking outside on desktop
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 768) return;
            
            if (!sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form');

        forms.forEach(form => {
            const password = form.querySelector('.password-input');
            const togglePassword = form.querySelector('.toggle-password');
            const eyeIcon = togglePassword.querySelector('i');
            const imgUrl = form.querySelector('.img-url');
            const previewImg = form.querySelector('.preview-img');
            const userIcon = form.querySelector('.image-preview i');
            const strengthText = form.querySelector('.strength-text');
            const strengthBars = [
                form.querySelector('.strength-1'),
                form.querySelector('.strength-2'),
                form.querySelector('.strength-3'),
                form.querySelector('.strength-4')
            ];

            // Toggle password visibility
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });

            // Password strength logic
            password.addEventListener('input', function () {
                const value = password.value;
                let strength = 0;

                strengthBars.forEach(bar => {
                    bar.style.width = '0';
                    bar.style.background = '#ef4444';
                });

                if (value.length > 0) strength++;
                if (value.length >= 8) strength++;
                if (/[A-Z]/.test(value)) strength++;
                if (/[0-9]/.test(value)) strength++;
                if (/[^A-Za-z0-9]/.test(value)) strength++;

                if (strength > 0) {
                    for (let i = 0; i < Math.min(strength, 4); i++) {
                        strengthBars[i].style.width = '100%';
                        if (strength >= 4) {
                            strengthBars[i].style.background = '#22c55e';
                            strengthText.textContent = 'Strong password';
                            strengthText.style.color = '#22c55e';
                        } else if (strength >= 3) {
                            strengthBars[i].style.background = '#f59e0b';
                            strengthText.textContent = 'Medium password';
                            strengthText.style.color = '#f59e0b';
                        } else {
                            strengthBars[i].style.background = '#ef4444';
                            strengthText.textContent = 'Weak password';
                            strengthText.style.color = '#ef4444';
                        }
                    }
                } else {
                    strengthText.textContent = 'Password strength';
                    strengthText.style.color = '#6c757d';
                }
            });

            // Image preview logic
            imgUrl.addEventListener('input', function () {
                const url = imgUrl.value.trim();
                if (url) {
                    previewImg.src = url;
                    previewImg.onload = function () {
                        previewImg.style.display = 'block';
                        userIcon.style.display = 'none';
                    };
                    previewImg.onerror = function () {
                        previewImg.style.display = 'none';
                        userIcon.style.display = 'block';
                    };
                } else {
                    previewImg.style.display = 'none';
                    userIcon.style.display = 'block';
                }
            });
        });
    });
</script>


</body>
</html>