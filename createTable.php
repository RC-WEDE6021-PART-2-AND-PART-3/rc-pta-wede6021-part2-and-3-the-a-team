<?php
include "DBConn.php";

// 1. Drop table if it exists
$sql = "DROP TABLE IF EXISTS tblUser";
$conn->query($sql);

// 2. Create table
$sql = "CREATE TABLE tblUser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending'
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// 3. Read from userData.txt
$file = fopen("userData.txt", "r");

if ($file) {
    while (($line = fgets($file)) !== false) {

        // Remove spaces/new lines
        $line = trim($line);

        // Split by comma
        $data = explode(",", $line);

        $name = $data[0];
        $email = $data[1];
        $password = $data[2];

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO tblUser (fullName, email, password)
                VALUES ('$name', '$email', '$hashedPassword')";

        $conn->query($sql);
    }

    fclose($file);
    echo "Data loaded successfully!";
} else {
    echo "Error opening file.";
}

$conn->close();
?>