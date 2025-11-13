<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>داشبورد</title>
<style>
body { direction: rtl; text-align: center; font-family: tahoma; margin: 100px; }
.dashboard { margin-top: 30px; }
</style>
</head>
<body>
<div class="dashboard">
    <?php
    if(isset($_SESSION['message'])) {
        echo "<h3 style='color:green;'>".$_SESSION['message']."</h3>";
        unset($_SESSION['message']);
    }
    ?>

    <h2>خوش آمدید <?php echo htmlspecialchars($_SESSION['user']['user_name']); ?>!</h2>
    <p>نام: <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?></p>
    <p>نام خانوادگی: <?php echo htmlspecialchars($_SESSION['user']['last_name']); ?></p>
    <p>نام پدر: <?php echo htmlspecialchars($_SESSION['user']['father_name']); ?></p>
    <p>یوزرنیم: <?php echo htmlspecialchars($_SESSION['user']['user_name']); ?></p>

    <br>
    <a href="logout.php">خروج</a>
</div>
</body>
</html>
