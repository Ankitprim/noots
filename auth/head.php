<?php 

define("APPURL", "http://localhost/noots");


?>
  <style>

    
        :root {
            --primary: #1a365d;
            --accent: #c53030;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #94a3b8;
            --border: #cbd5e1;
        }

        body {
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            
            background-color: #f1f5f9;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--primary);
            color: white;
            padding: 10px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .error-msg{
            color:var(--accent);
        }
               .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            color: var(--accent);
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            margin-left: 1.5rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: white;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

  </style>
  <header class="header">
        <div class="logo">
            <!-- <i class="fas fa-newspaper"></i> -->
            <span>noots</span>
        </div>
        <div class="nav-links">
            <a href="<?php echo APPURL; ?>/index.php"><i class="fas fa-home"></i> Home</a>
        </div>
    </header>

