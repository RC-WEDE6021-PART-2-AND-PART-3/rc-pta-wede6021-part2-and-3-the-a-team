<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>

<h2>
    <?php
    echo "User " . $_SESSION["user"] . " is logged in";
    ?>
</h2>

</body>
</html>