<?php
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['pas']);
    $captcha_input = trim($_POST['captcha']);

    // بررسی کپچا
    if(!isset($_SESSION['captcha']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha'])) {
        $message = "<h3 style='color:red;'>❌ کد امنیتی اشتباه است</h3>";
    } else {
        $conn = new mysqli("localhost", "root", "", "batman");
        if ($conn->connect_error) die("خطا در اتصال به دیتابیس: " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM baty WHERE user_name = ?");
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['pas'])) {
                $_SESSION['user'] = $user;
                $_SESSION['message'] = "✅ ورود با موفقیت انجام شد!";
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "<h3 style='color:red;'>❌ رمز عبور اشتباه است</h3>";
            }
        } else {
            $message = "<h3 style='color:red;'>❌ نام کاربری یافت نشد</h3>";
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
<title>ورود</title>
<style>
body { direction: rtl; text-align: center; font-family: tahoma; margin: 100px; }
form { margin-top: 20px; }
img { margin: 10px 0; border: 1px solid #000; }
</style>
</head>
<body>

<?php if($message) echo $message; ?>

<h2>فرم ورود</h2>
<form method="POST" action="">
    <label>یوزرنیم:</label><br>
    <input type="text" name="user_name" required><br>
    <label>پسورد:</label><br>
    <input type="password" name="pas" required><br>
    <label>کد امنیتی:</label><br>
    <img src="captcha.php?rand=<?php echo rand(); ?>" alt="captcha"><br>
    <input type="text" name="captcha" required><br><br>
    <input type="submit" value="ورود">
</form>
<br>
<a href="register.php">ثبت نام</a>

</body>
</html>
