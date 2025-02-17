<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
echo "<h1>{$user['name']}'s Profile</h1>";
echo "<p>Email: {$user['email']}</p>";
echo "<a href='edit_profile.php'>Edit Profile</a>";
?>
