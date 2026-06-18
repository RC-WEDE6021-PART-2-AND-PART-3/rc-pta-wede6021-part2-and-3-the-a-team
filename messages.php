<?php
session_start();
include "DBConn.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senderName = $_POST["senderName"];
    $senderEmail = $_POST["senderEmail"];
    $userMessage = $_POST["message"];

    $sql = "INSERT INTO tblMessage (senderName, senderEmail, message)
            VALUES ('$senderName', '$senderEmail', '$userMessage')";

    if ($conn->query($sql) === TRUE) {
        $message = "Message sent successfully.";
    } else {
        $message = "Message could not be sent.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages - Second Hand Fit</title>
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
        <li><a href="upload.php">Sell</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="messages.php">Messages</a></li>
    </ul>
</nav>

<section class="join">
    <h2>Contact Admin</h2>

    <?php
    if ($message != "") {
        echo "<p style='color:green;'>$message</p>";
    }
    ?>

    <form method="POST">
        <label>Your Name</label>
        <input type="text" name="senderName" required>

        <label>Your Email</label>
        <input type="email" name="senderEmail" required>

        <label>Message</label>
        <textarea name="message" required></textarea>

        <button class="shop-btn" type="submit">Send Message</button>
    </form>
</section>

</body>
</html>