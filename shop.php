<?php
session_start();
include "DBConn.php";

$sql = "SELECT * FROM tblClothing WHERE status = 'available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Second Hand Fit</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="style.css">

    <style>
        .shop-section {
            padding: 40px;
            text-align: center;
        }

        .style-grid {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .card {
            background: black;
            padding: 15px;
            width: 250px;
            border-radius: 10px;
            color: white;
        }

        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }

        .price {
            color: #ff4081;
            font-weight: bold;
            font-size: 18px;
        }

        .card button {
            background: #ff4081;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="logo">
        <a href="index.php">Second Hand Fit</a>
    </div>

    <ul class="navlist">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="sellerRequest.php">Sell</a></li>
        <li><a href="cart.php">Cart</a></li>

        <?php if(isset($_SESSION['username'])): ?>
            <li><span style="color:#ff4081;">Welcome, <?php echo $_SESSION['username']; ?></span></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<section class="shop-section">
    <h2>SHOP PRODUCTS</h2>

    <div class="style-grid">

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>

        <div class="card">
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['clothingName']; ?>">

            <h3><?php echo $row['clothingName']; ?></h3>

            <p><strong>Brand:</strong> <?php echo $row['brand']; ?></p>

            <p><?php echo $row['description']; ?></p>

            <p class="price">R<?php echo $row['price']; ?></p>

            <p>Available: <?php echo $row['quantity']; ?></p>

            <form method="POST" action="cart.php">
                <input type="hidden" name="clothingID" value="<?php echo $row['clothingID']; ?>">
                <input type="hidden" name="clothingName" value="<?php echo $row['clothingName']; ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <input type="hidden" name="image" value="<?php echo $row['image']; ?>">

                <button type="submit" name="add_to_cart">Add To Cart</button>
            </form>
        </div>

        <?php
            }
        } else {
            echo "<p>No clothing items available.</p>";
        }
        ?>

    </div>
</section>

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