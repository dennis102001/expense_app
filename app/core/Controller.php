<?php

namespace core;

class Controller {

    public function view($name, $data = []){
        extract($data);
        $appUrl = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? '');

        require_once "../app/views/$name.php";
    }
}