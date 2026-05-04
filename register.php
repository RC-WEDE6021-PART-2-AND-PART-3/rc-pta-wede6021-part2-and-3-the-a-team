<?php
// Start session for any messages
session_start();

// Include database connection
include "config/DBConn.php";

// Variable to store messages
$message = "";
$message_type = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation: Check if passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
        $message_type = "error";
    } else {
        
        // Check if username or email already exists
        $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $existing_user = $check_result->fetch_assoc();
            if ($existing_user['username'] == $username) {
                $message = "Username already taken! Please choose another.";
            } else {
                $message = "Email already registered! Please use another email or login.";
            }
            $message_type = "error";
        } else {
            // No duplicate found - hash the password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_sql = "INSERT INTO users (username, email, password, status) VALUES (?, ?, ?, 'pending')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $message = "Registration successful! Your account is pending admin approval.";
                $message_type = "success";
            } else {
                $message = "Registration failed: " . $conn->error;
                $message_type = "error";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Second Hand Fit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Registration Page Specific Styles */
        .register-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .register-card h2 {
            text-align: center;
            color: #ff6b35;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .register-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        
        .register-btn {
            width: 100%;
            padding: 14px;
            background: #ff6b35;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .register-btn:hover {
            background: #e55a2b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
        
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: center;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .login-link a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        /* Sand Timer Animation - Shown while waiting */
        .waiting-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }
        
        .waiting-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 350px;
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .sand-timer {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
            animation: shake 2s infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(5deg); }
        }
        
        .hourglass {
            position: relative;
            width: 80px;
            height: 80px;
            animation: flip 2s infinite;
        }
        
        @keyframes flip {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hourglass:before {
            content: "⏳";
            font-size: 80px;
            position: absolute;
            top: 0;
            left: 0;
            animation: sand 2s infinite;
        }
        
        @keyframes sand {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .waiting-text {
            font-size: 18px;
            color: #333;
            margin: 20px 0;
        }
        
        .waiting-subtext {
            font-size: 14px;
            color: #666;
        }
        
        .dots {
            display: inline-block;
            animation: dotPulse 1.5s infinite;
        }
        
        @keyframes dotPulse {
            0%, 20% { content: "."; }
            40% { content: ".."; }
            60%, 100% { content: "..."; }
        }
        
        .close-waiting {
            margin-top: 20px;
            padding: 8px 20px;
            background: #ff6b35;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            <li><a href="shop.html">Shop</a></li>
            <li><a href="upload.html">Sell</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="cart.html">Cart</a></li>
        </ul>
    </nav>

    <div class="register-container">
        <div class="register-card">
            <h2>Create Account</h2>
            <p class="register-subtitle">Join Second Hand Fit community</p>
            
            <?php if ($message != ""): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                    <?php if($message_type == "success"): ?>
                        <div style="margin-top: 10px; font-size: 14px;">
                            ⏳ You will be notified once admin approves your account.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="showWaiting()">
                <div class="form-group">
                    <label>👤 Username *</label>
                    <input type="text" name="username" required placeholder="Choose a username">
                </div>
                
                <div class="form-group">
                    <label>📧 Email *</label>
                    <input type="email" name="email" required placeholder="your@email.com">
                </div>
                
                <div class="form-group">
                    <label>🔒 Password *</label>
                    <input type="password" name="password" required placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label>🔒 Confirm Password *</label>
                    <input type="password" name="confirm_password" required placeholder="Confirm your password">
                </div>
                
                <button class="register-btn" type="submit">
                    ✨ Register Now
                </button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>
    
    <!-- Waiting Popup (shown after registration) -->
    <div id="waitingPopup" style="display: none;">
        <div class="waiting-overlay">
            <div class="waiting-card">
                <div class="sand-timer">
                    <div class="hourglass"></div>
                </div>
                <div class="waiting-text">
                    Waiting for admin approval<span class="dots">...</span>
                </div>
                <div class="waiting-subtext">
                    Your account has been submitted.<br>
                    An admin will review and approve your account shortly.
                </div>
                <button class="close-waiting" onclick="hideWaiting()">OK, I'll wait</button>
            </div>
        </div>
    </div>
    
    <script>
        function showWaiting() {
            // Only show if form is valid
            var form = document.querySelector('form');
            if(form.checkValidity()) {
                document.getElementById('waitingPopup').style.display = 'block';
            }
        }
        
        function hideWaiting() {
            document.getElementById('waitingPopup').style.display = 'none';
        }
        
        // Auto-hide if there's already a success message
        <?php if($message_type == "success"): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('waitingPopup').style.display = 'block';
        });
        <?php endif; ?>
    </script>
    
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
    </footer>

</body>
</html>