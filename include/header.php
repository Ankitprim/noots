<?php
    require "./config/config.php";
    session_start();
    define("APPURL", "http://localhost/noots");

    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $allCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Noots</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="./css/header.css" />
  <style>
    .container {
      max-width: 1200px;
      padding: 0 1rem;
      margin: 0 auto;
    }
    .login-link {
      white-space: nowrap;
      font-size: var(--font-sm);
    }
  </style>
</head>
<body>

<header>
  <div class="container">
    <div class="header-top">
        <button class="nav-toggle" id="navToggle">
        <i class=" fas fa-bars" id="navIcon"></i>
        </button>


      <div class="logo">n<span>oo</span>ts</div>

      <div class="date-display">
        <i class="fas fa-calendar-alt"></i>
        <span id="current-date"><?php 
        date_default_timezone_set("Asia/Kolkata");
        echo date("l h:m A"); ?></span>
      </div>

      <div class="login-link">
        <a href="./auth/login.php" style="size:15px"><i class="fa-solid fas fa-arrow-right-to-bracket"></i></a>
      </div>
    </div>

    <nav>
      <ul class="nav-menu" id="navMenu">
        <li><a href="./index.php">Home</a></li>

        <?php if (!empty($allCategories)) : ?>
          <?php foreach ($allCategories as $categorie) : ?>
            <li>
              <a href="<?php echo APPURL; ?>/categories.php?name=<?php echo urlencode($categorie->Name); ?>">
                <?php echo htmlspecialchars($categorie->Name); ?>
              </a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li><em>No categories found</em></li>
        <?php endif; ?>

        <li>
          <div class="header-actions">
            <div class="search-bar">
              <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search..." />
                <button type="submit"><i class="fas fa-search"></i></button>
              </form>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>



<script>
  const navToggle = document.getElementById("navToggle");
  const navIcon = document.getElementById("navIcon");
  const navMenu = document.getElementById("navMenu");

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
  // Date Display
  document.getElementById('current-date').textContent = 
    new Date().toLocaleDateString('en-US', { 
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
    });

  // Toggle Nav Menu
  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');

  navToggle.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    document.body.classList.toggle('menu-open');
  });

  document.addEventListener('click', (e) => {
    if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
      navMenu.classList.remove('active');
      document.body.classList.remove('menu-open');
    }
  });
</script>
<script>
  let lastScrollY = window.scrollY;
  const nav = document.querySelector("header");

  window.addEventListener("scroll", () => {
    const currentScrollY = window.scrollY;

    if (window.innerWidth > 480) {
      // Ignore if menu is open on mobile
      if (document.querySelector(".nav-menu").classList.contains("active")) return;

      if (currentScrollY > lastScrollY && currentScrollY > 100) {
        // Scrolling down - hide nav
        nav.style.transform = "translateY(-50%)";
      } else {
        // Scrolling up - show nav
        nav.style.transform = "translateY(0)";
      }
    }

    lastScrollY = currentScrollY;
  });
</script>

</body>
</html>
