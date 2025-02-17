<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
if(isset($_POST['post'])){
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')");
    header('Location: index.php');
}
?>
<form method="post">
    <textarea name="content" placeholder="What's on your mind?" required></textarea><br>
    <button type="submit" name="post">Post</button>
</form>
