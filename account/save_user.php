<?php
session_start();

$flag = true;
$id = 0;


if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }

//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    echo ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    $flag = false;
}
//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
$user ='';
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);

//удаляем лишние пробелы
$login = trim($login);
$password = trim($password);


$res = file_get_contents('../bd/bd2.json');
$data = json_decode($res, true);


if($flag == true) {
    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]['login'] == $login) {
            $id = $i;
            break;
        }
    }
    if ($data[$id]['password'] == md5("соль" . $password)) {
        $user = $login;
        $_SESSION['USER_ID'] = $data[$id];
        $_SESSION['USER_LOGIN'] = $data[$id]['login'];
        $_SESSION['USER_NAME'] = $data[$id]['name'];
        $_SESSION['USER_PASSWORD'] = $data[$id]['password'];
        $_SESSION['USER_EMAIL'] = $data[$id]['email'];
        $_SESSION['USER_LOGIN_IN'] = 1;
        $_SERVER['HTTP_REFERER'] = '/';
        setcookie('user', $_POST['login'], strtotime('+30 days'), '/');
        echo 'yes';
        } else {
            echo 'Неправильно введен пароль или логин';
        }
}
