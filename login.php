<?php
include 'db.php';
session_start();
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);
    if(password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
    } else {
        echo "Invalid credentials!";
    }
}
?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login">Login</button>
</form>
