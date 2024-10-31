<?php
$host = 'localhost'; 
$db = 'qlnhanvien'; 
$user = 'root'; 
$pass = ''; 

$mysqli = new mysqli($host, $user, $pass, $db);

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
