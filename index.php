<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Second Hand Fit</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar">

        <div class="logo">
            <a href="index.php">Second Hand Fit</a>
        </div>

        <ul class="navlist">

    <li><a href="index.php">Home</a></li>

    <li><a href="shop.php">Shop</a></li>

    <li><a href="upload.php">Sell</a></li>

  

    <?php if(isset($_SESSION['user'])): ?>
    <li><span style="color: #ff4081;">Welcome, <?php echo $_SESSION['user']; ?>!</span></li>
    <li><a href="logout.php">Logout</a></li>
<?php else: ?>
    <li><a href="login.php">Login</a></li>
    <li><a href="register.php">Register</a></li>
    <li><a href="adminLogin.php">Admin</a></li>
<?php endif; ?>

</ul>

    </nav>

    <!-- HERO SECTION -->

    <section class="hero">



        <div class="placeholder-img"></div>

        <div class="hero-text">

        <?php if(isset($_SESSION['user'])): ?>
        <p style="color:#ff4081; font-weight:bold;">
        User <?php echo $_SESSION['user']; ?> is logged in
        </p>
        <?php endif; ?>

            <h1>SECOND HAND STYLE</h1>

            <p>
                Affordable fashion. Sustainable living.<br> Buy and sell pre-loved clothing easily.
            </p>

            <a href="shop.php">

                <button class="shop-btn">
                    Shop Now
                </button>

            </a>

        </div>

    </section>

    <!-- SHOP BY CATEGORY -->

    <section class="style">

        <h2>SHOP BY CATEGORY</h2>

        <div class="style-grid">

        <a href="men.html">>

                <div class="card">

                    <img src="https://placehold.co/300x300">

                    <h3>Men</h3>

                    <p>EXPLORE...</p>

                </div>

            </a>

            <a href="accessories.html">

                <div class="card">

                    <img src="https://placehold.co/300x300">

                    <h3>Women</h3>

                    <p>EXPLORE...</p>

                </div>

            </a>

            <a href="accessories.html">

                <div class="card">

                    <img src="https://placehold.co/300x300">

                    <h3>Accessories</h3>

                    <p>EXPLORE...</p>

                </div>

            </a>

        </div>

    </section>

    <!-- ABOUT SECTION -->

    <section class="born">

        <h2>SECOND HAND FASHION</h2>

        <h4>SECOND HAND FIT</h4>

        <p>

            Second Hand Fit helps people buy and sell pre-loved clothing easily.
            <br><br> Our platform supports sustainable fashion by giving clothes a second life.

        </p>

        <img src="https://placehold.co/400x400">

        <button class="ourstory">
            Our Story
        </button>

    </section>

    <!-- JOIN SECTION -->

    <section class="join">

        <h2>JOIN SECOND HAND FIT</h2>

        <button class="sub-btn">
            Subscribe
        </button>

        <input type="text" placeholder="Enter your email">

    </section>

    <!-- FOOTER -->

    <footer>

        <h5>Second Hand Fit</h5>

        <ul class="list1">

            <li class="Top">Shop</li>

            <li>New Arrivals</li>

            <li>Men</li>

            <li>Women</li>

        </ul>

        <ul class="list2">

            <li class="Top">Company</li>

            <li>About Us</li>

            <li>Contact Us</li>

        </ul>

        <ul class="list3">

            <li class="Top">Help</li>

            <li>Shipping</li>

            <li>Returns</li>

            <li>Size Guide</li>

        </ul>

        <ul class="list4">
            <li class="Top">Support</li>
            <li><a href="messages.php">Contact Admin</a></li>
        </ul>
    </footer>

</body>

</html>