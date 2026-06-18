<?php
session_start();
include "DBConn.php";

$message = "";

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $message = "Your cart is empty.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['cart'])) {

    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $orderRef = "ORD" . rand(1000, 9999) . date("His");

    $userID = 0;

    $sqlOrder = "INSERT INTO tblOrder (userID, orderRef, totalAmount)
                 VALUES ('$userID', '$orderRef', '$total')";

    if ($conn->query($sqlOrder) === TRUE) {

        $orderID = $conn->insert_id;

        foreach ($_SESSION['cart'] as $item) {
            $clothingID = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $sqlLine = "INSERT INTO tblOrderLine (orderID, clothingID, quantity, price)
                        VALUES ('$orderID', '$clothingID', '$quantity', '$price')";
            $conn->query($sqlLine);

            $sqlUpdate = "UPDATE tblClothing 
                          SET quantity = quantity - $quantity 
                          WHERE clothingID = '$clothingID'";
            $conn->query($sqlUpdate);
        }

        $_SESSION['cart'] = [];

        $message = "Order placed successfully. Your reference number is: " . $orderRef;
    } else {
        $message = "Order could not be saved.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="icon" type="image/png" href="images/logo.png">

    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .checkout-card {
            background: white;
            max-width: 550px;
            margin: 50px auto;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .checkout-card h2 {
            text-align: center;
            color: #ff4081;
            margin-bottom: 10px;
        }

        .checkout-card h3 {
            margin-top: 25px;
            color: #222;
            border-bottom: 2px solid #ff4081;
            padding-bottom: 8px;
        }

        .checkout-card form {
            display: flex;
            flex-direction: column;
        }

        .checkout-card label {
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        .checkout-card input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }   

        .checkout-message {
            background: #ffe6f0;
            color: #333;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
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
        <li><a href="cart.php">Cart</a></li>
    </ul>
</nav>

<section class="checkout-card">
    <h2>Checkout</h2>

    <?php if ($message != ""): ?>
        <p class="checkout-message">
            <?php echo $message; ?>
        </p>

        <p style="text-align:center;">
            <a href="shop.php">Continue Shopping</a>
        </p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['cart']) || !empty($_SESSION['cart'])): ?>
        <form method="POST" action="checkout.php">

            <h3>Delivery Details</h3>

            <label>Full Name</label>
            <input type="text" name="fullName" required>

            <label>Delivery Address</label>
            <input type="text" name="address" required>

            <label>Phone Number</label>
            <input type="text" name="phone" required>

            <h3>Payment Details</h3>

            <label>Card Number</label>
            <input type="text" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>

            <label>Card Holder Name</label>
            <input type="text" name="cardHolder" required>

            <label>Expiry Date</label>
            <input type="text" name="expiryDate" placeholder="MM/YY" required>

            <label>CVV</label>
            <input type="password" name="cvv" maxlength="3" required>

            <p style="font-size:13px; color:#777;">
                Demo checkout only. No real payment is processed.
            </p>

            <button class="shop-btn" type="submit">
                Place Order
            </button>

        </form>
    <?php endif; ?>
</section>

</body>
</html> 