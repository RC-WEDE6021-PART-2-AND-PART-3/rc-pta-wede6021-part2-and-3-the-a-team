<?php
session_start();
include "DBConn.php";

if (!isset($_SESSION["admin"])) {
    header("Location: adminLogin.php");
    exit();
}

// ADD USER
if (isset($_POST["addUser"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $conn->query("INSERT INTO tblUser (fullName, email, password, status)
                  VALUES ('$name', '$email', '$hashedPassword', 'approved')");
}

// UPDATE USER
if (isset($_POST["updateUser"])) {

    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];

    $conn->query("UPDATE tblUser
                  SET fullName='$name', email='$email'
                  WHERE id=$id");
}

// ADD CLOTHING
if (isset($_POST["addClothing"])) {

    $clothingName = $_POST["clothingName"];
    $brand = $_POST["brand"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $imageName = $_FILES["image"]["name"];
    move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $imageName);

    $imagePath = "images/" . $imageName;

    $conn->query("INSERT INTO tblClothing 
        (clothingName, brand, description, price, quantity, image, status)
        VALUES 
        ('$clothingName', '$brand', '$description', '$price', '$quantity', '$imagePath', 'available')");
}

// UPDATE CLOTHING
if (isset($_POST["updateClothing"])) {

    $clothingID = $_POST["clothingID"];
    $clothingName = $_POST["clothingName"];
    $brand = $_POST["brand"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $conn->query("UPDATE tblClothing 
                  SET clothingName='$clothingName',
                      brand='$brand',
                      description='$description',
                      price='$price',
                      quantity='$quantity'
                  WHERE clothingID=$clothingID");
}
// REPLY TO MESSAGE
if (isset($_POST["replyMessage"])) {
    $messageID = $_POST["messageID"];
    $reply = $_POST["reply"];

    $conn->query("UPDATE tblMessage 
                  SET reply='$reply' 
                  WHERE messageID=$messageID");
}

// APPROVE USER
if (isset($_GET["approve"])) {
    $id = $_GET["approve"];
    $conn->query("UPDATE tblUser SET status='approved' WHERE id=$id");
}

// DELETE USER
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM tblUser WHERE id=$id");
}
// DELETE CLOTHING
if (isset($_GET["deleteClothing"])) {
    $clothingID = $_GET["deleteClothing"];
    $conn->query("DELETE FROM tblClothing WHERE clothingID=$clothingID");
}
// APPROVE SELLER REQUEST
if (isset($_GET["approveRequest"])) {
    $requestID = $_GET["approveRequest"];
    $conn->query("UPDATE tblSellerRequest SET status='approved' WHERE requestID=$requestID");
}

// REJECT SELLER REQUEST
if (isset($_GET["rejectRequest"])) {
    $requestID = $_GET["rejectRequest"];
    $conn->query("UPDATE tblSellerRequest SET status='rejected' WHERE requestID=$requestID");
}

// GET USERS
$result = $conn->query("SELECT * FROM tblUser");
$clothingResult = $conn->query("SELECT * FROM tblClothing");
$messageResult = $conn->query("SELECT * FROM tblMessage ORDER BY messageDate DESC");
$sellerRequestResult = $conn->query("SELECT * FROM tblSellerRequest ORDER BY requestDate DESC");

?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            background: black;
            color: white;
            padding: 15px;
            border-radius: 8px;
        }

        h3 {
            color: #ff4081;
            margin-top: 25px;
        }

        form {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #ff4081;
            color: white;
            border: none;
            padding: 9px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #e91e63;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: black;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:hover {
            background: #f9f9f9;
        }

        a {
            color: #ff4081;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            border-radius: 6px;
        }

        hr {
            margin: 40px 0;
        }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>

<h3>Add New User</h3>

<form method="POST">
    Name: <input type="text" name="name" required>
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <button type="submit" name="addUser">Add User</button>
</form>
<br>
<?php
// LOAD USER FOR EDIT
if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $editUser = $conn->query("SELECT * FROM tblUser WHERE id=$id")->fetch_assoc();
}
?>

<?php if (isset($editUser)) { ?>

<h3>Edit User</h3>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editUser["id"]; ?>">

    Name:
    <input type="text" name="name"
        value="<?php echo $editUser["fullName"]; ?>" required>

    Email:
    <input type="email" name="email"
        value="<?php echo $editUser["email"]; ?>" required>

    <button type="submit" name="updateUser">Update</button>
</form>

<br>

<?php } ?>

<table border="1">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo $row["fullName"]; ?></td>
    <td><?php echo $row["email"]; ?></td>
    <td><?php echo $row["status"]; ?></td>
    <td>
        <a href="?approve=<?php echo $row["id"]; ?>">Approve</a> |
        <a href="?edit=<?php echo $row["id"]; ?>">Edit</a> |
        <a href="?delete=<?php echo $row["id"]; ?>">Delete</a>
    </td>
</tr>
<?php } ?>

</table>
<hr>

<h2>Manage Clothing</h2>

<h3>Add New Clothing</h3>

<form method="POST" enctype="multipart/form-data">
    Item Name:
    <input type="text" name="clothingName" required>

    Brand:
    <input type="text" name="brand" required>

    Description:
    <input type="text" name="description" required>

    Price:
    <input type="number" name="price" step="0.01" required>

    Quantity:
    <input type="number" name="quantity" required>

    Image:
    <input type="file" name="image" required>

    <button type="submit" name="addClothing">Add Clothing</button>
</form>

<br>

<?php
if (isset($_GET["editClothing"])) {
    $clothingID = $_GET["editClothing"];
    $editClothing = $conn->query("SELECT * FROM tblClothing WHERE clothingID=$clothingID")->fetch_assoc();
}
?>

<?php if (isset($editClothing)) { ?>

<h3>Edit Clothing</h3>

<form method="POST">
    <input type="hidden" name="clothingID" value="<?php echo $editClothing["clothingID"]; ?>">

    Item Name:
    <input type="text" name="clothingName" value="<?php echo $editClothing["clothingName"]; ?>" required>

    Brand:
    <input type="text" name="brand" value="<?php echo $editClothing["brand"]; ?>" required>

    Description:
    <input type="text" name="description" value="<?php echo $editClothing["description"]; ?>" required>

    Price:
    <input type="number" step="0.01" name="price" value="<?php echo $editClothing["price"]; ?>" required>

    Quantity:
    <input type="number" name="quantity" value="<?php echo $editClothing["quantity"]; ?>" required>

    <button type="submit" name="updateClothing">Update Clothing</button>
</form>

<br>

<?php } ?>

<table border="1">
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Brand</th>
    <th>Description</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Action</th>
</tr>

<?php while($clothing = $clothingResult->fetch_assoc()) { ?>
<tr>
    <td><?php echo $clothing["clothingID"]; ?></td>
    <td><img src="<?php echo $clothing["image"]; ?>" width="60"></td>
    <td><?php echo $clothing["clothingName"]; ?></td>
    <td><?php echo $clothing["brand"]; ?></td>
    <td><?php echo $clothing["description"]; ?></td>
    <td>R<?php echo $clothing["price"]; ?></td>
    <td><?php echo $clothing["quantity"]; ?></td>
    <td>
        <a href="?editClothing=<?php echo $clothing["clothingID"]; ?>">Edit</a> |
        <a href="?deleteClothing=<?php echo $clothing["clothingID"]; ?>" onclick="return confirm('Delete this clothing item?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>


<hr>

<h2>Seller Requests</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Brand</th>
    <th>Description</th>
    <th>Image</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while($request = $sellerRequestResult->fetch_assoc()) { ?>
<tr>
    <td><?php echo $request["requestID"]; ?></td>
    <td><?php echo $request["brand"]; ?></td>
    <td><?php echo $request["description"]; ?></td>
    <td>
        <img src="images/<?php echo $request["image"]; ?>" width="80">
    </td>
    <td><?php echo $request["status"]; ?></td>
    <td><?php echo $request["requestDate"]; ?></td>
    <td>
        <a href="?approveRequest=<?php echo $request["requestID"]; ?>">Approve</a> |
        <a href="?rejectRequest=<?php echo $request["requestID"]; ?>">Reject</a>
    </td>
</tr>
<?php } ?>
</table>
<hr>

<h2>Customer and Seller Messages</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Reply</th>
    <th>Action</th>
</tr>

<?php while($msg = $messageResult->fetch_assoc()) { ?>
<tr>
    <td><?php echo $msg["messageID"]; ?></td>
    <td><?php echo $msg["senderName"]; ?></td>
    <td><?php echo $msg["senderEmail"]; ?></td>
    <td><?php echo $msg["message"]; ?></td>
    <td><?php echo $msg["reply"]; ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="messageID" value="<?php echo $msg["messageID"]; ?>">
            <input type="text" name="reply" required>
            <button type="submit" name="replyMessage">Reply</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>
</body>
</html>