<?php
include "DBConn.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO tblUser (fullName, email, password, status)
            VALUES ('$name', '$email', '$hashedPassword', 'pending')";

    if ($conn->query($sql)) {
        $message = "Registration successful! Wait for admin approval.";
    } else {
        $message = "Error occurred.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">
    <div class="logo">
        <a href="index.php">Second Hand Fit</a>
    </div>
</nav>

<section class="hero">

<div class="hero-text">

<h2>Register</h2>

<p style="color:green;"><?php echo $message; ?></p>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required><br><br>

<input type="email" name="email" placeholder="Email" required><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<button class="shop-btn">Register</button>

</form>

</div>

</section>

</body>
</html>