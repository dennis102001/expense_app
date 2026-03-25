<?php

namespace core;

use models\UserModel;

class Router {
    private static $protectedRoutes = [];
    public static $routes = [];

    public static function get($path, $action){
        self::$routes['GET'][$path] = $action;
    }

    public static function post($path, $action){
        self::$routes['POST'][$path] = $action;
    }

    public static function run(){
        $method = $_SERVER['REQUEST_METHOD'];

        $base = '/Personal_Expense_Tracker/public';
        $uri = str_replace($base, '', strtok($_SERVER['REQUEST_URI'], '?'));

        $userModel = new UserModel();
        if(!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])){
            
            if(!$userModel->hasRememberToken($_COOKIE['remember_token'])){
                setcookie("remember_token", "", time() - 3600, "/");
            }
        }

        if(isset($_SESSION['user_id']) && !isset($_COOKIE['remember_token'])){
            $userModel->deleteRememberToken($_SESSION['user_id']);
        }

        if(!in_array($uri, ['/login', '/signup', '/forgot_password', '/reset_password', '/verify_email', '/resend_verification']) && !isset($_SESSION['user_id'])){
            header('Location: login');
            exit;
        }

        $action = self::$routes[$method][$uri] ?? null;

        if(!$action){
            die("404");
        }

        list($controller, $controllerMethod) = explode('@', $action);

        $controllerClass = "controllers\\$controller";
        $controllerObj = new $controllerClass;

        call_user_func([$controllerObj, $controllerMethod]);

    }

}