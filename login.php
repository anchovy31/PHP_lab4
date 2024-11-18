<?php
$config = require_once 'config.php';
require_once 'notification.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    if (empty($email)) {
        $errors[] = 'Введите email';
    }

    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    if (empty($password)) {
        $errors[] = 'Введите пароль';
    }

    if (empty($errors)) {
        try {
            $connection = mysqli_connect($config['dbHost'], $config['dbUser'], $config['dbPassword'], $config['dbName']);
            // Пробуем извлечь пользователя из базы с предоставленным email
            $sql = "SELECT data_users.id FROM data_users WHERE data_users.email = '$email' and data_users.password = '$password'";
            $result = mysqli_query($connection,$sql);
            $count=mysqli_num_rows($result);
            // Если пользователь найден, сверяем пароли
            if ($count!=0) {
                // Если пароли совпадают, сохраняем данные пользователя в сессию и редиректим на главную страницу
                $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
                $_SESSION['user_id'] = $row[0];
                $_SESSION['user_email'] = $email;

                header('location: main.php');
                exit;

                // В случае несовпадения паролей выводим сообщение, что нет пользователя с такой комбинацией
                // Не стоит выводить сообщение о том, что только пароль неверный - это усиливает уязвимость сайта к взлому
                notify('Пользователя с такими данными не существует');
            } else {
                // Такое же сообщение выведем, если email неверный
                notify('Пользователя с такими данными не существует');
            }
        } catch (mysqli_sql_exception $e) {
            notify('Произошла ошибка при авторизации');
            error_log($e->__toString());
        } finally {
            if (isset($connection)) {
                $connection->close();
            }
        }
    } else {
        notify(implode('<br>', $errors));
    }
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
    <title>Login</title>
</head>
<body>

<section class="container w-25">
    <h2>Авторизация</h2>
    <form method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>

    <?php notify(); ?>
</section>

</body>
</html>