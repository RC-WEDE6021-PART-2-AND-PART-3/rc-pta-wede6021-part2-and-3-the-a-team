<?php
// Start session to remember logged-in user
session_start();

// Include database connection
include "config/DBConn.php";

// Variable to show error messages
$error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // First, check if this email exists in database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Email found! Get the user's data
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct!
            
            // Check if admin approved this user
            if ($user['status'] == 'approved') {
                // ALL GOOD! Let them login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                
                // Send to home page
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Your account is pending admin approval.";
            }
        } else {
            $error_message = "Wrong password!";
        }
    } else {
        $error_message = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index.php">Second Hand Fit</a>
        </div>
    </nav>

    <section class="join">
        <h2>Login</h2>
        
        <?php if($error_message != ""): ?>
            <div class="error-msg"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Email</label>
            <input type="email" name="email" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button class="shop-btn" type="submit">
                Login
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </section>

</body>

</html>