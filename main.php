
 

<?php
// Инициализируем сессию
session_start();
require 'vendor/autoload.php';
// Функция проверки, авторизован ли пользователь
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_email'];
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Homepage</title>
</head>
<body>
    <article class="container">
        <h1>Homepage</h1>

        <!-- Отображаем различные кнопки, в зависимости от того, авторизован ли пользователь -->
        <?php if (isLoggedIn()) { ?>
            <a href="logout.php" class="btn btn-secondary">Выйти</a>
        <?php } else { ?>
            <a href="reg.php" class="btn btn-primary">Регистрация</a>
            <a href="login.php" class="btn btn-primary">Авторизация</a>
        <?php }  ?>
    </article>
</body>
</html>