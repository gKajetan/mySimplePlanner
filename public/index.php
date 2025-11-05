<?php
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Controllers\TaskController;

// Routing
$request_uri = strtok($_SERVER["REQUEST_URI"], '?');
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();
$pageController = new PageController();

switch ($request_uri) {
    case '/':
        if ($method === 'POST') {
            $authController->handleLogin();
        } else {
            $authController->showLoginForm();
        }
        break;

    case '/register':
        if ($method === 'POST') {
            $authController->handleRegistration();
        } else {
            $authController->showRegistrationForm();
        }
        break;

    case '/main':
        $pageController->main();
        break;

    case '/logout':
        $authController->logout();
        break;

    case '/task/create':
        if ($method === 'POST') {
            (new TaskController())->create();
        } else {
            header("Location: /main"); // Przekieruj jeśli to nie POST
        }
        break;

    case '/task/edit':
        if ($method === 'GET') {
            (new TaskController())->showEditForm();
        } else {
            header("Location: /main");
        }
        break;

    case '/task/update':
        if ($method === 'POST') {
            (new TaskController())->update();
        } else {
            header("Location: /main");
        }
        break;

    case '/task/delete':
        if ($method === 'POST') {
            (new TaskController())->delete();
        } else {
            header("Location: /main");
        }
        break;

    default:
        http_response_code(404);
        echo "404 - Strona nie znaleziona";
        break;
}