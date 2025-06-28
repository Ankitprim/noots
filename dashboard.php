<?php
    define("APPURL", "http://localhost/noots");
    require './config/config.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ./auth/login.php");
        exit;
    }
   $id = $_SESSION['user_id'];

   try{
        $stmt = $conn->prepare("SELECT * FROM author WHERE id = $id");
        $stmt-> execute();

        $details = $stmt->fetch(PDO::FETCH_OBJ);

   }catch (PDOException $e) {
         echo 'unable to fetch'.$e->getMessage();
    }

    try {
        $stmt2 = $conn->prepare("SELECT * FROM article WHERE author_id = :id");
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        $article_details = $stmt2->fetchAll(PDO::FETCH_OBJ); // Fetch all rows as objects

    } catch (PDOException $e) {
        echo 'Unable to fetch: ' . $e->getMessage();
    }


 // views count

    $stmt = $conn->prepare("SELECT SUM(views) AS total_views FROM article WHERE author_id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_views = $result['total_views'] ?? 0;


 // delete article



    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $deleteId = (int) $_POST['delete_id'];

        $stmt = $conn->prepare("DELETE FROM article WHERE id = :id");
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





    // publish article



    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['publish'])) {
        try {
            // Collect the form data
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $description = $_POST['description'];
            $content = $_POST['content'];
            $category = $_POST['category'];
            $tags = $_POST['tags'];
            $img = $_POST['img'];
            $author = $details->username;
            $author_id = $id;

            // Insert into DB
            $query = $conn->prepare("INSERT INTO article (title, sub_title, category, description, image, content, author, author_id, tags)
                                    VALUES(:title, :sub_title, :category, :description, :image, :content, :author, :author_id, :tags)");

            $query->execute([
                ':title' => $title,
                ':sub_title' => $subtitle,
                ':category' => $category,
                ':description' => $description,
                ':image' => $img,
                ':content' => $content,
                ':author' => $author,
                ':author_id' => $author_id,
                ':tags' => $tags
            ]);

            header("Location: " . $_SERVER['PHP_SELF'] . "?submitted=1");
            exit;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }



    // for category 
    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();
    $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);

    // update profile
    if(isset($_POST['update'])){
         try {
            $id = $_SESSION['user_id'];
            $name  = $_POST['name'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $bio   = $_POST['bio'];
            $expert =  $_POST['category'];
            $testimonial = $_POST['testimonial'];
            $img   = $_POST['img']; // For simplicity; see notes below.

            // Secure password hashing
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            $stmt1 = $conn->prepare("UPDATE author SET email= :email, password = :password ,username= :username ,bio= :bio ,expertise= :expertise, testimonial= :testimonial, img= :img  WHERE id = :id");
            $stmt1->execute([
                ':email'    => $email,
                ':password'   => $hashedPass,
                ':username' => $name,
                ':bio'      => $bio,
                ':expertise'   => $expert ,
                ':testimonial' => $testimonial,
                ':img'      => $img,
                ':id'      =>$id
            ]);

            $message[] = "<center><font color = green>Update Successfull!";

        } catch (Exception $ex) {
            $message[] =  $ex->getMessage();
        }
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/dash.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-pen-nib"></i> <span>noots Author</span></h2>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-page="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </div>
            <!-- <div class="menu-item" data-page="manage-articles">
                <i class="fas fa-newspaper"></i>
                <span class="menu-text">Manage Articles</span>
            </div> -->
            <div class="menu-item" data-page="create-article">
                <i class="fas fa-edit"></i>
                <span class="menu-text">Create Article</span>
            </div>
            <div class="menu-item" data-page="profile">
                <i class="fas fa-user"></i>
                <span class="menu-text">Profile</span>
            </div>
            <div class="menu-item" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span class="menu-text">Log Out</span>
            </div>
        </div>
    </div>
    

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
           <button class="nav-toggle" id="navToggle"> 
                <i class=" fas fa-bars" id="navIcon"></i>
            </button>
            <div class="overlay" style="display: none;"></div>
           <h3>Hello, <?php echo $details->username; ?> !</h3>
            <div class="user-actions">
                <div class="notification">
                   <a href="<?php echo APPURL; ?>"><i class="fas fa-home"></i></a> 
                </div>
                <div class="user-profile">
                
                    <img  src="<?php echo $details->img;?>" alt="User Profile">
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content">
            <!-- Dashboard Page -->
            <div class="page dashboard-page">
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <div class="actions">
                        <button class="btn btn-primary"><div class="menu-itm" data-page="create-article"><i class="fas fa-plus"></i> <span class="menu-text"> Create Article</span>
                      </div></button>
                    </div>
                </div>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php 
                            if($article_details){
                                echo count($article_details);
                            }else{
                                echo '0';
                            }
                            ?></h3>
                            <p>Total Articles</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-green">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $total_views; ?></h3>
                            <p>Total Views</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-purple">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-info">
                            <!-- <h3>1.2K</h3> -->
                            <p>Likes Received</p>
                        </div>
                    </div>
                </div>

                <div class="page-title">
                    <h2>Recent Articles</h2>
                    <div class="actions">
                        <button class="btn btn-outline">View All</button>
                    </div>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Published Date</th>
                                <!-- <th>Views</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                               <?php if($article_details): ?>
                                    <?php foreach($article_details as $det): ?>  
                                        <tr>
                                            <td><a style="text-decoration:none; color:#212529" href="./article.php?id=<?php echo $det->id ;?>"><?php echo $det->title; ?></a></td>
                                            <td><?php echo $det->category; ?></td>
                                            <td><span class="status status-published">Published</span></td>
                                            <td>
                                            <?php   
                                                $formattedDate = date("d/m/y", strtotime($det->created_at));
                                                echo $formattedDate; 
                                            ?></td>
                                            <td>
                                                 <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $det->id; ?>">
                                                    <button class="action-btn" type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                        
                                            </td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                        <?php if (isset($_SESSION['flash_message'])): ?>
                                            <div class="alert success">
                                                <?php
                                                    echo $_SESSION['flash_message'];
                                                    unset($_SESSION['flash_message']); // show only once
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                 <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center;">No article found</td>
                                        </tr>
                                 <?php endif; ?>

                                 
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Article Page (Hidden by default) -->
            <div class="page create-article-page" style="display:none;" id="create-article">
                <div class="page-title">
                    <h1>Create New Article</h1>
                </div>

                <div class="form-container">
                        <form id="article-form" method="post" action="">
                            <!-- Title -->
                            <div class="form-group">
                                <label for="article-title">Article Title</label>
                                <input type="text" name="title" id="article-title" class="form-control" placeholder="Enter article title">
                            </div>

                            <!-- sub-title -->
                            <div class="form-group">
                                <label for="article-subtitle">sub Title</label>
                                <input type="text" name="subtitle" id="sub-title" class="form-control" placeholder="Enter article subtitle">
                            </div>

                            <!-- description -->
                            <div class="form-group">
                                <label for="article-content">Description</label>
                                <textarea name="description" id="decription" class="form-control" placeholder="Write your description..."></textarea>
                            </div>

                            <!-- Content -->
                            <div class="form-group">
                                <label for="article-content">Content</label>
                                <textarea name="content" id="article-content" class="form-control" placeholder="Write your content..."></textarea>
                            </div>

                            <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
                            <script>
                            ClassicEditor
                                .create(document.querySelector('#article-content'))
                                .catch(error => {
                                    console.error(error);
                                });
                            </script>


                            <!-- Category -->
                            <div class="form-group">
                                <label for="article-category">Category</label>
                                <select name="category" id="article-category" class="form-control">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach($allCategories as $categorie): ?>
                                        <option value="<?= htmlspecialchars($categorie->Name) ?>">
                                            <?= htmlspecialchars($categorie->Name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tags -->
                            <div class="form-group">
                                <label for="article-tags">Tags</label>
                                <input type="text" name="tags" id="article-tags" class="form-control" placeholder="e.g. news, tech">
                            </div>

                            <!-- Image -->
                            <div class="form-group">
                                <label for="article-image">Featured Image URL</label>
                                <input type="text" name="img" id="article-image" class="form-control" placeholder="https://example.com/image.jpg">
                            </div>

                            <!-- Buttons -->
                            <div style="margin-top: 20px; text-align: center;">
                                <button type="submit" name="publish" value="1" class="btn btn-primary">Publish Article</button>
                                <button type="submit" name="save_draft" value="1" class="btn btn-outline-secondary" style="margin-left: 15px;">Save as Draft</button>
                            </div>

                        </form>
                </div>
            </div>
            <div class="page profile-page" style="display:none;">

            <!-- Profile Page (Hidden by default) -->
                <div class="page-title">
                    <h1>My Profile</h1>
                </div>

                <div class="profile-container">
                    <div class="profile-card">
                        <div class="profile-header">
                            <img src="<?php echo $details->img; ?>" alt="Profile" class="profile-img">
                            <h3 class="profile-name"><?php echo $details->username;  ?></h3>
                            <!-- <p class="profile-role">Senior Content Writer</p> -->
                        </div>

                        <!-- <div class="profile-stats">
                            <div>
                                <div class="stat-value">24</div>
                                <div class="stat-label">Articles</div>
                            </div>
                            <div>
                                <div class="stat-value">3.2K</div>
                                <div class="stat-label">Followers</div>
                            </div>
                            <div>
                                <div class="stat-value">42.8K</div>
                                <div class="stat-label">Views</div>
                            </div>
                        </div> -->

                        <div class="form-container">
                            <form id="profile-form" method="POST">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="nmae">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $details->username;  ?>">
                                    </div>
                                  
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $details->email;  ?>">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="pass" name="pass" class="form-control" placeholder="set new password">
                                </div>
                                <div class="form-group">
                                    <label for="email">Expertise</label>
                                        <div class="checkbox-group">
                                            <select id="article-category" class="form-control"    name="category">
                                                    <option value=""><?php echo $details->expertise;  ?></option>
                                                    <?php foreach($allCategories as $categorie) : ?>
                                                        <option value="<?php echo $categorie->Name; ?>"><?php echo $categorie->Name; ?></option>

                                                    <?php endforeach; ?>
                                             </select>
                                        </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Image</label>
                                    <input type="url" id="img" name="img" class="form-control" value="<?php echo $details->img;  ?>">
                                </div>

                                <div class="form-group">
                                    <label for="bio">Bio</label>
                                    <textarea id="bio" name="bio" class="form-control" rows="4"><?php echo $details->bio;  ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="testimonial">testimonial</label>
                                    <textarea id="testimonial" name="testimonial" class="form-control" rows=""><?php echo $details->testimonial;  ?></textarea>
                                </div>


                                

                                <div style="text-align: center; margin-top: 20px;">
                                    <button type="update" name="update" class="btn btn-primary" style="padding: 12px 30px;">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                </div>
                                <?php 
                                    if(isset($message)){
                                    foreach($message as $msg){
                                            echo '<span class="error-msg">'.$msg.'</span>';
                                        };
                                    };
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <!-- Logout Modal -->
    </div>

    <div class="modal" id="logout-modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="modal-title">Confirm Logout</h3>
            <p class="modal-text">Are you sure you want to log out? Any unsaved changes will be lost.</p>
            <div class="modal-actions">
                <button class="btn btn-cancel" id="cancel-logout">Cancel</button>
                <a href="logout.php"><button class="btn btn-logout"><div class="btn">Log out</div></button></a>
                
            </div>
        </div>
    </div>
  
 
<script>
  const navToggle = document.getElementById("navToggle");
  const navIcon = document.getElementById("navIcon");
  const navMenu = document.getElementById("sidebar");

  navToggle.addEventListener("click", () => {
    // Toggle nav menu visibility
    navMenu.classList.toggle("active");

    // Add spin animation
    navIcon.classList.add("spin");

    // Swap icons after spin animation
    setTimeout(() => {
      if (navIcon.classList.contains("fa-bars")) {
        navIcon.classList.remove("fa-bars");
        navIcon.classList.add("fa-xmark");
      } else {
        navIcon.classList.remove("fa-xmark");
        navIcon.classList.add("fa-bars");
      }

      navIcon.classList.remove("spin");
    }, 300); // duration of animation
  });
</script>

  
    <script>
    


        const menuItms = document.querySelectorAll('.menu-itm');
        const menuItems = document.querySelectorAll('.menu-item');
        const pages = document.querySelectorAll('.page');
      
         menuItms.forEach(item => {
            if (item.id !== 'logout-btn') {
                item.addEventListener('click', function() {
                    const targetPage = this.getAttribute('data-page');
                    
                    // Remove active class from all menu items
                    menuItms.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to current menu item
                    this.classList.add('active');
                    
                    // Hide all pages
                    pages.forEach(page => page.style.display = 'none');
                    
                    // Show target page
                    document.querySelector(`.${targetPage}-page`).style.display = 'block';
                });
            }
        });

        menuItems.forEach(item => {
            if (item.id !== 'logout-btn') {
                item.addEventListener('click', function() {
                    const targetPage = this.getAttribute('data-page');
                    
                    // Remove active class from all menu items
                    menuItems.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to current menu item
                    this.classList.add('active');
                    
                    // Hide all pages
                    pages.forEach(page => page.style.display = 'none');
                    
                    // Show target page
                    document.querySelector(`.${targetPage}-page`).style.display = 'block';
                });
            }
        });

        // Logout functionality
        document.getElementById('logout-btn').addEventListener('click', function() {
            
            document.getElementById('logout-modal').style.display = 'flex';
        });

        document.getElementById('cancel-logout').addEventListener('click', function() {
            document.getElementById('logout-modal').style.display = 'none';
        });

        // Form submission handling
        // document.getElementById('article-form').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     alert('Article published successfully!');
        // });

        // document.getElementById('profile-form').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     alert('Profile updated successfully!');
        // });
    </script>



</body>
</html>