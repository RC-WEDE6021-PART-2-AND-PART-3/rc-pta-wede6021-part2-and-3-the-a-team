<?php
session_start();
include "DBConn.php";

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

if (!isset($_SESSION["admin"])) {
    header("Location: adminLogin.php");
    exit();
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

// GET USERS
$result = $conn->query("SELECT * FROM tblUser");
?>

<!DOCTYPE html>
<html>
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

</body>
</html>