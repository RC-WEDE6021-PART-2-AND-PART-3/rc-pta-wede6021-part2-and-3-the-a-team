<?php
session_start();
include "DBConn.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $brand = $_POST['brand'];
    $description = $_POST['description'];

    $imageName = $_FILES['image']['name'];

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "images/" . $imageName
    );

    $sql = "INSERT INTO tblSellerRequest
            (brand, description, image, status)
            VALUES
            ('$brand', '$description', '$imageName', 'pending')";

    if ($conn->query($sql) === TRUE) {
        $message = "Seller request submitted successfully.";
    } else {
        $message = "Error submitting request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Upload Item</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            <a href="index.html">Second Hand Fit</a>
        </div>

        <ul class="navlist">

            <li><a href="shop.html">Shop</a></li>
            <li><a href="cart.html">Cart</a></li>

        </ul>

    </nav>

    <section class="join">

        <h2>Upload Clothing Item</h2>
        
        <?php
            if($message != ""){
                echo "<p style='color:green; text-align:center;'>$message</p>";
            }
        ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Item Name</label>
            <input type="text">

            <label>Price</label>
            <input type="text">

            <label>Size</label>
            <input type="text">

            <label>Brand</label>
            <input type="text" name="brand" required>

            <label>Condition</label>
            <input type="text">

            <label>Location</label>
            <input type="text">

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Upload Image</label>
            <input type="file" name="image" required>

            <button class="shop-btn">
                Upload Item
            </button>

        </form>

    </section>

</body>

</html>