<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $data = file_get_contents('db.json');
        $users = json_decode($data, true);

        $user = null;

        foreach ($users as $userData) {
            if ($userData['login'] === $login && $userData['password'] === $password) {
                $user = $userData;
                break;
            }
        }

        if ($user) {
            session_start();

            $_SESSION['login'] = $user['login'];
            setcookie('login', $user['login'], time() + 45);
            setcookie('password', $user['password'], time() + 30);
            setcookie('session_id', "test", time() + 30, '/');
            echo '==== <br>';
            echo 'Cookie username: ' . $_SESSION['login'];
            header('Location: index.php');
            exit();
        } else {
            echo "Неверный логин или пароль";
            echo '<br>';
            echo '<a href="index.php">Попробовать еще раз</a>';
        }
    } else {
        http_response_code(403); 
        echo "ошибка. это не Ajax-запрос";
        exit();
    }
}