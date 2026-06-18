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
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="style.css">

    <style>
    body {
        background: #f5f5f5;
    }

    .upload-section {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    .upload-card {
        background: white;
        width: 100%;
        max-width: 600px;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }

    .upload-card h2 {
        text-align: center;
        color: #ff4081;
        margin-bottom: 10px;
    }

    .upload-text {
        text-align: center;
        color: #555;
        margin-bottom: 25px;
        line-height: 1.5;
    }

    .upload-card form {
        display: flex;
        flex-direction: column;
    }

    .upload-card label {
        font-weight: bold;
        margin-top: 12px;
        margin-bottom: 5px;
    }

    .upload-card input,
    .upload-card textarea {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
    }

    .upload-card textarea {
        min-height: 100px;
        resize: vertical;
    }

    .upload-card input:focus,
    .upload-card textarea:focus {
        outline: none;
        border-color: #ff4081;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
    }

    .help-text {
        text-align: center;
        margin-top: 20px;
    }

    .help-text a {
        color: #ff4081;
        font-weight: bold;
        text-decoration: none;
    }

    .help-text a:hover {
        text-decoration: underline;
    }
</style>
</head>

<body>

    <nav class="navbar">

        <div class="logo">
            <a href="index.php">Second Hand Fit</a>
        </div>

        <ul class="navlist">

            <li><a href="shop.php">Shop</a></li>
            <li><a href="cart.php">Cart</a></li>

        </ul>

    </nav>

    <section class="upload-section">

    <div class="upload-card">

        <h2>Sell Your Clothing</h2>
        <p class="upload-text">
            Send your clothing item details to the admin. Once reviewed, the admin can approve or reject your request.
        </p>

        <?php
            if($message != ""){
                echo "<p class='success-message'>$message</p>";
            }
        ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Item Name</label>
            <input type="text" placeholder="Example: Nike Hoodie">

            <label>Price</label>
            <input type="text" placeholder="Example: 250">

            <label>Size</label>
            <input type="text" placeholder="Example: Medium">

            <label>Brand</label>
            <input type="text" name="brand" placeholder="Example: Nike" required>

            <label>Condition</label>
            <input type="text" placeholder="Example: Good condition">

            <label>Location</label>
            <input type="text" placeholder="Example: Pretoria">

            <label>Description</label>
            <textarea name="description" placeholder="Describe the item clearly..." required></textarea>

            <label>Upload Image</label>
            <input type="file" name="image" required>

            <button class="shop-btn" type="submit">
                Submit Seller Request
            </button>

        </form>

        <p class="help-text">
            Need help? <a href="messages.php">Contact Admin</a>
        </p>

    </div>

</section>

</body>

</html>