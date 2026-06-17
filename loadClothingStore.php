<?php
include "DBConn.php";

/*
Name: Relebogile Moralo
Student Number: ST10399732
Declaration: This code is my own work.
*/

// Drop tables first
$conn->query("DROP TABLE IF EXISTS tblOrderLine");
$conn->query("DROP TABLE IF EXISTS tblOrder");
$conn->query("DROP TABLE IF EXISTS tblMessage");
$conn->query("DROP TABLE IF EXISTS tblSellerRequest");
$conn->query("DROP TABLE IF EXISTS tblClothing");
$conn->query("DROP TABLE IF EXISTS tblUser");
$conn->query("DROP TABLE IF EXISTS tblAdmin");

// User table
$conn->query("CREATE TABLE tblUser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    status VARCHAR(20) DEFAULT 'pending'
)");

// Admin table
$conn->query("CREATE TABLE tblAdmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)");

// Clothing table
$conn->query("CREATE TABLE tblClothing (
    clothingID INT AUTO_INCREMENT PRIMARY KEY,
    clothingName VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'available'
)");

// Seller request table
$conn->query("CREATE TABLE tblSellerRequest (
    requestID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    brand VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    requestDate DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Message table
$conn->query("CREATE TABLE tblMessage (
    messageID INT AUTO_INCREMENT PRIMARY KEY,
    senderName VARCHAR(100) NOT NULL,
    senderEmail VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    reply TEXT,
    messageDate DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Order table
$conn->query("CREATE TABLE tblOrder (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    orderRef VARCHAR(50) NOT NULL,
    totalAmount DECIMAL(10,2) NOT NULL,
    orderDate DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Order line table
$conn->query("CREATE TABLE tblOrderLine (
    orderLineID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    clothingID INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
)");

// Insert admin
$adminPassword = password_hash("12345678", PASSWORD_DEFAULT);
$conn->query("INSERT INTO tblAdmin (username, password)
VALUES ('admin', '$adminPassword')");

// Insert sample users
$userPassword = password_hash("12345678", PASSWORD_DEFAULT);

$conn->query("INSERT INTO tblUser (fullName, email, password, address, status) VALUES
('John Doe', 'john@gmail.com', '$userPassword', '10 Main Road', 'approved'),
('Mary Jane', 'mary@gmail.com', '$userPassword', '15 Church Street', 'approved'),
('Peter Smith', 'peter@gmail.com', '$userPassword', '22 Long Street', 'pending')");

// Insert sample clothing
$conn->query("INSERT INTO tblClothing (clothingName, brand, description, price, quantity, image, status) VALUES
('Blue Denim Jacket', 'Levis', 'Second-hand denim jacket in good condition.', 350.00, 5, 'images/denim.jpg', 'available'),
('Black Nike Hoodie', 'Nike', 'Warm black hoodie, lightly used.', 280.00, 4, 'images/hoodie.jpg', 'available'),
('Adidas Sneakers', 'Adidas', 'White sneakers in good condition.', 500.00, 3, 'images/sneakers.jpg', 'available'),
('Zara Dress', 'Zara', 'Red dress suitable for casual wear.', 220.00, 2, 'images/dress.jpg', 'available'),
('Puma T-Shirt', 'Puma', 'Comfortable branded t-shirt.', 150.00, 6, 'images/tshirt.jpg', 'available')");

echo "<h2>ClothingStore database loaded successfully.</h2>";
echo "<p>Admin username: admin</p>";
echo "<p>Admin password: 12345678</p>";
echo "<p>Sample user password: 12345678</p>";

$conn->close();
?>