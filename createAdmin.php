<?php
include "DBConn.php";

$username = "admin";
$password = "123456";

// Hash password properly
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Clear old admin (optional but safe)
$conn->query("DELETE FROM tblAdmin");

// Insert new admin
$sql = "INSERT INTO tblAdmin (username, password)
        VALUES ('$username', '$hashedPassword')";

if ($conn->query($sql)) {
    echo "Admin created successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>