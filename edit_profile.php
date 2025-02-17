<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    mysqli_query($conn, "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'");
    header('Location: profile.php');
}
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>
<form method="post">
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    <button type="submit" name="update">Update Profile</button>
</form>
