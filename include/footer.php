<?php

    require "./config/config.php";

    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();

    $allcategories = $categories->fetchall(PDO::FETCH_OBJ);

    // var_dump($allcategories);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<body>
     <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">n<span>oo</span>ts</div>
                    <p>Delivering accurate, timely news with integrity. Our commitment to journalistic excellence remains unchanged in the digital age.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-links-section">
                    <h4 class="footer-heading">Categories</h4>
                    <ul class="footer-links">

                    <?php   foreach($allcategories as $category): ?>
                            <li><a href="<?php echo APPURL;?>/category.php/name=<?php echo $category->Name; ?> "><?php echo $category->Name; ?></a></li>
                    <?php endforeach; ?>    
                        
                    </ul>
                </div>

                <div class="footer-links-section">
                    <h4 class="footer-heading">Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Advertise</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>

                <div class="footer-links-section">
                    <h4 class="footer-heading">Contact Us</h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Uttar pradesh, India</li>
                        <li><i class="fas fa-phone"></i> +91 xxxxx xxxxx</li>
                        <li><i class="fas fa-envelope"></i> ankitprime3.14@gmail.com</li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                &copy; 2025 noots. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>