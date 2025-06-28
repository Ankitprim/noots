<?php  
require __DIR__ . '/../config/config.php';

session_start();

if (isset($_POST['submit'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass = $_POST['password'];

    try {
        // Prepare the query
        $stmt = $conn->prepare("SELECT * FROM author WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        // Check if user exists and password matches
        if ($user && password_verify($pass, $user->password)) {
            $_SESSION['user_id'] = $user->id; // or similar

            header("Location: /noots/dashboard.php"); // redirect after login
            exit();
        } else {
            $error[] =  "Invalid email or password.";
        }

    } catch (PDOException $e) {
            $error[] =  $e->getMessage();
    }

}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>noots | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1a365d;
            --accent: #c53030;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #94a3b8;
            --border: #cbd5e1;
        }

        .tagline {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.3rem;
        }

        .container {
            display: flex;
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            width: 100%;
        }

        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            margin: auto;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            font-family: 'Merriweather', serif;
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--gray);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
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
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input {
            accent-color: var(--primary);
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--primary);
        }

        .divider {
            text-align: center;
            margin: 1.8rem 0;
            position: relative;
            color: var(--gray);
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background-color: var(--border);
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background-color: white;
            color: var(--dark);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .social-btn:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
        }

        .social-btn.google:hover {
            background-color: #f8f0f0;
            color: #db4437;
        }

        .social-btn.facebook:hover {
            background-color: #f0f4f8;
            color: #4267B2;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .footer {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 2rem 1.5rem;
            }
            
            .logo {
                font-size: 1.8rem;
            }
            
            .options {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }
            
            .forgot-password {
                margin-left: auto;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .form-control {
                padding: 0.8rem 0.8rem 0.8rem 2.8rem;
            }
            
            .social-login {
                gap: 0.8rem;
            }
            
            .social-btn {
                width: 45px;
                height: 45px;
            }
            
            .divider::before,
            .divider::after {
                width: 38%;
            }
        }
    </style>
</head>
<body>
<?php  require 'head.php';?>

    <div class="container">

        <div class="login-container">
            <div class="login-header">

                <h2>Login to Your Account</h2>
                    <?php 
                        if(isset($error)){
                         foreach($error as $error){
                                echo '<span class="error-msg">'.$error.'</span>';
                            };
                         };
                    ?>
                <!-- <p>Access premium articles and personalized content</p> -->
            </div>

            <form id="loginForm" action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <!-- <a href="#" class="forgot-password">Forgot password?</a> -->
                </div>

                <button type="submit" name="submit" class="btn">Sign In</button>
            </form>

            <!-- <div class="divider">or continue with</div> -->

            <!-- <div class="social-login">
                <button class="social-btn google">
                    <i class="fab fa-google"></i>
                </button>
                <button class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="social-btn twitter">
                    <i class="fab fa-twitter"></i>
                </button>
            </div> -->

            <div class="signup-link">
                Don't have an account? <a href="join.php">Join now</a>
            </div>
        </div>
    </div>


<?php require 'foot.php' ;  ?>

    <!-- <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember').checked;
            
            // Basic validation
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            // Simulate login process
            console.log('Login attempt with:', { email, password, rememberMe });
            
            // Show loading state
            const btn = this.querySelector('button');
            const originalText = btn.textContent;
            btn.textContent = 'Signing in...';
            btn.disabled = true;
            
            // Simulate API call
        
        });
    </script> -->
</body>
</html>