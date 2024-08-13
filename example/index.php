<?php

include __DIR__ . './vendor/autoload.php';

use App\Models\UserModel;

// Show errors
error_reporting(-1);
ini_set('display_errors', 1);

// Start session
session_start();

// Get URI
switch ($_SERVER["REQUEST_URI"]) {
    case '/': // root
        require 'app/views/index.php';
        break;

    case '/login': // login
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $user->id;

            header("Location: /home");
        } else {
            $_SESSION['message'] = "Bad credentials";

            header("Location: /");
        }

        break;

    case '/home': // home
        if (!$_SESSION['login']) {
            header("Location: /");
        }

        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['id']);

        require 'app/views/home.php';
        break;

    case '/logout': // logout
        unset($_SESSION['login']);
        unset($_SESSION['id']);
        session_destroy();

        header("Location: /");
        break;

    default: // Error 404
        echo "error 404";
        break;
}
