<?php
session_start();

// Show success message if item was added
$success_message = "";
if (isset($_SESSION['cart_message'])) {
    $success_message = $_SESSION['cart_message'];
    unset($_SESSION['cart_message']);
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['clothingID'];
    $product_name = $_POST['clothingName'];
    $product_price = $_POST['price'];
    $product_image = $_POST['image'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'location' => 'South Africa',
            'image' => $product_image,
            'quantity' => 1
        ];
    }

    $_SESSION['cart_message'] = "✅ " . $product_name . " has been added to your cart!";
    header("Location: cart.php");
    exit();
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    $_SESSION['cart_message'] = "❌ Item removed from cart!";
    header("Location: cart.php");
    exit();
}

// Handle Update Quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    $_SESSION['cart_message'] = "🔄 Cart updated successfully!";
    header("Location: cart.php");
    exit();
}

// Clear Cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    $_SESSION['cart_message'] = "🗑️ Cart cleared!";
    header("Location: cart.php");
    exit();
}

// Get cart items and calculate total
$cart_items = $_SESSION['cart'];
$total = 0;
$item_count = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
    $item_count += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Second Hand Fit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .cart-title {
            text-align: center;
            color: #ff4081;
            margin-bottom: 30px;
        }
        
        .success-message {
            background: #4caf50;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .cart-table th {
            background: black;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-table tr:hover {
            background: #f9f9f9;
        }
        
        .cart-product-img {
            width: 80px;
            height: 80px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cart-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .quantity-input {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .remove-link {
            color: #ff4081;
            text-decoration: none;
            font-weight: bold;
        }
        
        .remove-link:hover {
            text-decoration: underline;
        }
        
        .cart-summary {
            background: black;
            color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            text-align: right;
        }
        
        .cart-summary h3 {
            font-size: 24px;
        }
        
        .cart-summary span {
            color: #ff4081;
            font-size: 28px;
        }
        
        .checkout-btn {
            background: #ff4081;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .checkout-btn:hover {
            background: #e91e63;
        }
        
        .clear-btn {
            background: #666;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
        }
        
        .clear-btn:hover {
            background: #444;
        }
        
        .update-btn {
            background: #ff4081;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 10px;
        }
        
        .empty-cart a {
            color: #ff4081;
            text-decoration: none;
        }
        
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .continue-shopping {
            display: inline-block;
            margin-top: 20px;
            color: #ff4081;
            text-decoration: none;
        }
        
        .continue-shopping:hover {
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
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="upload.php">Sell</a></li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><span style="color: #ff4081;">Welcome, <?php echo $_SESSION['username']; ?>!</span></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
            <li><a href="cart.php">Cart <?php if($item_count > 0) echo "($item_count)"; ?></a></li>
        </ul>
    </nav>

    <div class="cart-container">
        <h2 class="cart-title">🛒 Your Shopping Cart</h2>
        
        <?php if($success_message != ""): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(empty($cart_items)): ?>
            <div class="empty-cart">
                <h3>Your cart is empty!</h3>
                <p>Looks like you haven't added any items yet.</p>
                <a href="shop.php">← Continue Shopping</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $id => $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product-img">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                            </td>
                            <td><strong><?php echo $item['name']; ?></strong></td>
                            <td><?php echo $item['location']; ?></td>
                            <td>R<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="0" class="quantity-input">
                            </td>
                            <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="?remove=<?php echo $id; ?>" class="remove-link" onclick="return confirm('Remove this item?')">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="button-group">
                    <div>
                        <a href="?clear=1" class="clear-btn" onclick="return confirm('Clear entire cart?')">Clear Cart</a>
                        <button type="submit" name="update_cart" class="update-btn">Update Cart</button>
                    </div>
                </div>
            </form>
            
            <div class="cart-summary">
                <h3>Total Amount: <span>R<?php echo number_format($total, 2); ?></span></h3>
                <a href="checkout.php">
                    <button class="checkout-btn">Proceed to Checkout →</button>
                </a>
            </div>
            
            <div style="text-align: center;">
                <a href="shop.php" class="continue-shopping">← Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

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