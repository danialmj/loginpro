<?php
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $father_name = trim($_POST['father_name']);
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['pas']);
    $confirm_password = trim($_POST['confirm_pas']);
    $captcha_input = trim($_POST['captcha']);

    // بررسی کپچا
    if(!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])) {
        $message = "<h3 style='color:red;'>❌ کد امنیتی اشتباه است</h3>";
    }
    // بررسی تطابق پسورد
    elseif($password !== $confirm_password) {
        $message = "<h3 style='color:red;'>❌ رمز عبور و تکرار آن مطابقت ندارند</h3>";
    } else {
        $conn = new mysqli("localhost", "root", "", "batman");
        if ($conn->connect_error) die("خطا در اتصال به دیتابیس: " . $conn->connect_error);

        // هش کردن پسورد
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO baty (first_name, last_name, father_name, user_name, pas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $father_name, $user_name, $password_hash);

        if($stmt->execute()) {
            $message = "<h3 style='color:green;'>✅ ثبت نام با موفقیت انجام شد. حالا وارد شوید.</h3>";
        } else {
            $message = "<h3 style='color:red;'>❌ خطا: نام کاربری تکراری یا مشکل دیگر</h3>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>ثبت نام</title>
<style>
body { direction: rtl; text-align: center; font-family: tahoma; margin: 100px; }
form { margin-top: 20px; }
img { margin: 10px 0; border: 1px solid #000; }
</style>
</head>
<body>

<?php if($message) echo $message; ?>

<h2>فرم ثبت نام</h2>
<form method="POST" action="">
    <label>نام:</label><br>
    <input type="text" name="first_name" required><br>
    <label>نام خانوادگی:</label><br>
    <input type="text" name="last_name" required><br>
    <label>نام پدر:</label><br>
    <input type="text" name="father_name" required><br>
    <label>یوزرنیم:</label><br>
    <input type="text" name="user_name" required><br>
    <label>پسورد:</label><br>
    <input type="password" name="pas" required><br>
    <label>تکرار پسورد:</label><br>
    <input type="password" name="confirm_pas" required><br>
    <label>کد امنیتی:</label><br>
    <img src="captcha.php?rand=<?php echo rand(); ?>" alt="captcha"><br>
    <input type="text" name="captcha" required><br><br>
    <input type="submit" value="ثبت نام">
</form>
<br>
<a href="login.php">ورود به حساب کاربری</a>

</body>
</html>
