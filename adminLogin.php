<?php
session_start();
include "DBConn.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM tblAdmin WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["admin"] = $username;
            header("Location: adminDashboard.php");
            exit();
        } else {
            $error = "Incorrect password";
        }

    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" type="image/png" href="images/logo.png">
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

<h2>Admin Login</h2>

<p style="color:red;"><?php echo $error; ?></p>

<form method="POST">

<input type="text" name="username" placeholder="Username" required><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<button class="shop-btn">Login</button>

</form>

</div>

</section>

</body>
</html>