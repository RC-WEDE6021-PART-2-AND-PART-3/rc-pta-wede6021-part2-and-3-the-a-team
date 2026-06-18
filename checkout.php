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
    $stockError = false;

    foreach ($_SESSION['cart'] as $item) {
        $clothingID = $item['id'];
        $cartQuantity = $item['quantity'];

        $stockCheck = $conn->query("SELECT quantity, clothingName FROM tblClothing WHERE clothingID = '$clothingID'");
        $stockRow = $stockCheck->fetch_assoc();

    if ($cartQuantity > $stockRow['quantity']) {
        $message = "Not enough stock for " . $stockRow['clothingName'] . ". Available stock: " . $stockRow['quantity'];
        $stockError = true;
        break;
    }

    $total += $item['price'] * $item['quantity'];
}

if ($stockError == true) {
    // stop checkout
} else {

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
    <link rel="stylesheet" href="style.css">
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

<section class="join">
    <h2>Checkout</h2>

    <?php if ($message != ""): ?>
        <p style="color:#ff4081; font-weight:bold;">
            <?php echo $message; ?>
        </p>

        <p>
            <a href="shop.php">Continue Shopping</a>
        </p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['cart']) || !empty($_SESSION['cart'])): ?>
        <form method="POST" action="checkout.php">

            <label>Full Name</label>
            <input type="text" name="fullName" required>

            <label>Delivery Address</label>
            <input type="text" name="address" required>

            <label>Phone Number</label>
            <input type="text" name="phone" required>

            <button class="shop-btn" type="submit">
                Confirm Order
            </button>

        </form>
    <?php endif; ?>
</section>

</body>
</html>