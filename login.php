<?php
session_start();
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = trim($_POST['username']);
    $inputPassword = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($inputPassword, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            session_regenerate_id(true); 
            header("Location: welcome.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/web.css">
    <title>Đăng Nhập</title>
</head>
<body>
    <div class="container">
        <h2>Đăng Nhập</h2>
        <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
        <form method="POST">
            <label for="username">Tài Khoản:</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Mật Khẩu:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Đăng Nhập">
        </form>
        <p>
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay!</a>
        </p>
    </div>
</body>
</html>
