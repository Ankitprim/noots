<?php
    require __DIR__ . '/../config/config.php';

    if (isset($_POST['submit'])) {
        try {
            $name  = $_POST['name'];
            $email = $_POST['email'];
            $pass  = $_POST['password'];
            $bio   = $_POST['bio'];
            $expert =  $_POST['category'];
            $img   = $_POST['img']; // For simplicity; see notes below.

            // Secure password hashing
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO author (email, username, password, bio, expertise , img) 
                                    VALUES (:email, :username, :password, :bio, :expertise, :img)");
            $stmt->execute([
                ':email'    => $email,
                ':username' => $name,
                ':password' => $hashedPass,
                ':bio'      => $bio,
                ':expertise'   => $expert ,
                ':img'      => $img
            ]);

            header("location: login.php");

        } catch (Exception $ex) {
            $error[] =  $ex->getMessage();
        }
    }
        // for category 
    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();
    $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);

// testimonial
    $stmt = $conn->query("SELECT username, img, joined, expertise,testimonial FROM author LIMIT 3");
    $stmt->execute();
    $alldetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($alldetails);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>noots - Become an Author</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            
            --primary-dark: #1a365d;
            --accent: #c53030;
            --gray: #94a3b8;
            --border: #cbd5e1;
            --success: #38a169;
            --primary: #1a365d;
            --secondary: #c53030;
            --dark: #1a202c;
        }


        .hero {
            text-align: center;
            padding: 4rem 2rem 3rem;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%232c5282" opacity="0.05"/></svg>');
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            background: linear-gradient(90deg, var(--primary-dark) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 1.5rem;
            color: var(--dark);
        }

        .benefits {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .benefit-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            width: 180px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
        }

        .benefit-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .benefit-card h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 1rem auto 3rem;
            padding: 0 2rem;
            gap: 3rem;
            width: 100%;
        }

        .form-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            width: 60%;
        }

        .sidebar {
            width: 40%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--gray);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.15);
        }

        textarea.form-control {
            padding: 1rem;
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* image prev */

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
            margin-bottom: 1rem;
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

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .checkbox-group input {
            margin-top: 0.3rem;
            accent-color: var(--primary);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 18px;
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
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
        
        .btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.4);
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
 
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .testimonial-card h3 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
            text-align: center;
        }

        .author {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }



        .author-img img{
             width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .author-info h4 {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .author-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .footer {
            background-color: var(--primary-dark);
            color: white;
            text-align: center;
            padding: 2rem;
            font-size: 0.95rem;
            margin-top: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1.2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            margin-top: 1.5rem;
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            
            .form-container, .sidebar {
                width: 100%;
            }
            
            .benefits {
                gap: 1.2rem;
            }
            
            .benefit-card {
                width: 160px;
                padding: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: row;
                gap: 10;
                text-align: center;
            }
            
            .nav-links {
                margin-top: 0.5rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 1rem;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {

            p{
                font-size:13px;
            }
            .form-container {
                padding: 1.8rem;
            }
            
            .hero {
                padding: 3rem 1.5rem;
            }
            
            .benefit-card {
                width: 100%;
                max-width: 250px;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            #authorForm {
                padding: 15px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group,
            .form-row .form-group {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .input-with-icon {
                font-size: 14px;
            }
            
            .input-with-icon i {
                padding: 10px 12px;
            }
            
            input.form-control,
            textarea.form-control,
            select.form-control {
                padding: 10px 10px 10px 40px;
                font-size: 14px;
            }
            
            .image-preview-container {
                flex-direction: column;
            }
            
            .image-preview {
                margin-top: 10px;
                margin-left: 0;
                align-self: center;
            }
            
            .checkbox-group {
                font-size: 13px;
                line-height: 1.4;
            }
            
            .checkbox-group label {
                display: inline;
            }
            
            .btn {
                width: 100%;
                padding: 12px;
                font-size: 16px;
            }
            }

    </style>
</head>
<body>

<?php  require 'head.php';?>
    <!-- <section class="hero">
        <h1>Become a Featured Author</h1>
        <p>Join our community of writers and share your insights with millions of readers worldwide. Get published, build your audience, and earn competitive compensation.</p>
        
        <div class="benefits">
            <div class="benefit-card">
                <i class="fas fa-coins"></i>
                <h3>Competitive Pay</h3>
                <p>Earn based on readership</p>
            </div>
            <div class="benefit-card">
                <i class="fas fa-chart-line"></i>
                <h3>Build Audience</h3>
                <p>Reach millions of readers</p>
            </div>
            <div class="benefit-card">
                <i class="fas fa-headset"></i>
                <h3>Editor Support</h3>
                <p>Professional editing team</p>
            </div>
            <div class="benefit-card">
                <i class="fas fa-gem"></i>
                <h3>Exclusive Access</h3>
                <p>Premium writer resources</p>
            </div>
        </div>
    </section> -->

    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Author Application</h2>
                <p>Complete the form below to join our writing team</p>
                <?php 
                        if(isset($error)){
                         foreach($error as $error){
                                echo '<span class="error-msg">'.$error.'</span>';
                            };
                         };
                    ?>
            </div>

            <form id="authorForm" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" id="firstName" class="form-control" placeholder="Ankit Kushwha" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Create a password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm your password" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Professional Bio</label>
                    <textarea id="bio" name="bio" class="form-control" placeholder="Tell us about your background, expertise, and writing experience..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Areas of Expertise</label>
                    <div class="checkbox-group">
                            <select id="article-category" class="form-control" name="category" required>
                                <option value="">Select a Expertise</option>
                                <?php foreach($allCategories as $categorie) : ?>
                                    <option value="<?php echo $categorie->Name; ?>"><?php echo $categorie->Name; ?></option>

                                <?php endforeach; ?>
                            </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="imgUrl">Profile Image URL</label>
                    <div class="image-preview-container">
                        <input type="url" id="imageUrlInput" class="form-control" name="img"   placeholder="https://example.com/photo.jpg" required>
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-user"></i>
                            <img src="" alt="Preview" id="previewImg">
                        </div>
                    </div>    
                </div>        
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#" style="color: var(--primary);">Terms of Service</a> and <a href="#" style="color: var(--primary);">Author Agreement</a></label>
                </div>

                <button type="submit" name="submit" class="btn">Join Now</button>
            </form>
        </div>

        <div class="sidebar">
            <div class="testimonial-card">
                <h3>Success Stories</h3>
                <?php foreach($alldetails as $details): ?>
                    <div class="testimonial">
                        <p>"<?php echo $details['testimonial']; ?>"</p>
                        <div class="author">
                            <div class="author-img"><img src="<?php echo $details['img']; ?>" alt=""></div>
                            <div class="author-info">
                                <h4><?php echo $details['username']; ?></h4>
                                <p> Expertise in <?php echo $details['expertise']; ?> , <?php
                                $formattred = date("F, Y", strtotime($details['joined']) );
                                
                                echo $formattred; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>

            <div class="stats">
                <div  class="stat-card">
                    <i class="fas fa-users"></i>
                    <!-- <div class="number">850+</div>
                    <p>Active Authors</p> -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-globe-americas"></i>
                    <!-- <div class="number">4.2M</div>
                    <p>Monthly Readers</p> -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <!-- <div class="number">$1.8M</div>
                    <p>Paid to Authors</p> -->
                </div>
                <div class="stat-card">
                    <i class="fas fa-trophy"></i>
                    <!-- <div class="number">27</div>
                    <p>Award Winners</p> -->
                </div>
            </div>
        </div> 
    </div>

<?php require 'foot.php' ;  ?>


<script>
    const input = document.getElementById('imageUrlInput');
    const previewImg = document.getElementById('previewImg');
    const icon = document.querySelector('.image-preview i');

    input.addEventListener('input', function () {
        const url = input.value.trim();

        if (!url) {
            previewImg.style.display = 'none';
            icon.style.display = 'block';
            return;
        }

        previewImg.src = url;

        previewImg.onload = function () {
            previewImg.style.display = 'block';
            icon.style.display = 'none';
        };

        previewImg.onerror = function () {
            previewImg.style.display = 'none';
            icon.style.display = 'block';
        };
    });
</script>
</body>
</html>