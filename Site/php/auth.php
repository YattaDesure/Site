<?php
session_start();
include 'database.php';

if ($_POST['login'] && $_POST['password']) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    // Простая проверка (в реальном проекте нужно хэширование!)
    $sql = "SELECT UserID, FullName, Role FROM Users WHERE Login = ? AND Password = ?";
    $user = getData($sql, array($login, $password));
    
    if (!empty($user)) {
        $_SESSION['user_id'] = $user[0]['UserID'];
        $_SESSION['user_name'] = $user[0]['FullName'];
        $_SESSION['user_role'] = $user[0]['Role'];
        header('Location: index.html');
        exit();
    } else {
        echo "<script>alert('Неверный логин или пароль!'); window.location.href='login.html';</script>";
    }
}
?>