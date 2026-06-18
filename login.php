<?php
session_start();
include "DBConn.php";

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM tblUser WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {

            if ($row["status"] == "approved") {
                $_SESSION["user"] = $row["fullName"];
                header("Location: index.php");
                exit();
            } else {
                $error = "Account not approved yet.";
            }

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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

<h2>Login</h2>

<p style="color:red;"><?php echo $error; ?></p>

<form method="POST">

<input type="email" name="email" placeholder="Email" required
value="<?php echo $email; ?>"><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<button class="shop-btn">Login</button>

</form>

<br>

<p>Don't have an account? <a href="register.php">Register</a></p>

</div>

</section>

</body>
</html>