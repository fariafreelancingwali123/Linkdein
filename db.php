<?php
$host = 'localhost';
$user = 'ug8dhn6iemfc6';
$password = 'fgbir1e7e6t4';
$db = 'dbdwjgwhoeqrfg';
$conn = mysqli_connect($host, $user, $password, $db);
if(!$conn){
    die('Connection failed: ' . mysqli_connect_error());
}
?>
