<?php 
session_start();
include 'database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra tên người dùng đã tồn tại chưa
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Tên người dùng đã tồn tại. Vui lòng chọn tên khác.";
        } else {
            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Câu lệnh SQL để thêm người dùng
            $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $username, $hashedPassword);

                if ($stmt->execute()) {
                    $message = "Người dùng đã được tạo thành công!";
                } else {
                    $message = "Lỗi: " . $stmt->error;
                }
            } else {
                $message = "Lỗi khi chuẩn bị câu lệnh: " . $mysqli->error;
            }
        }
        $stmt->close();
    } else {
        $message = "Lỗi khi chuẩn bị câu lệnh: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/web.css">
    <title>Đăng Ký</title>
</head>
<body>
    <div class="container">
        <h2>Đăng Ký Người Dùng Mới</h2>
        
        <?php if ($message): ?>
            <div class="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Tài Khoản:</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Mật Khẩu:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Đăng Ký">
        </form>
        <p>
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay!</a>
        </p>
    </div>
</body>
</html>
