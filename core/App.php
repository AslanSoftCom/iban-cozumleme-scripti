<?php

class App {
    
    protected $controller = 'MainController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        $this->handleSpecialCases($url);
        $this->loadController($url);
        $this->loadMethod($url);
        $this->loadParams($url);
        $this->callControllerMethod();
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    protected function handleSpecialCases(&$url) {
        if (empty($url)) {
            $url[0] = 'main';
        } else {
            if ($url[0] === 'main' || (isset($url[1]) && $url[1] === 'main')) {
                $this->sendNotFoundResponse();
            }
        }
    }

    protected function loadController(&$url) {
        $controllerPath = 'app/controllers/';
        if (isset($url[0]) && is_dir($controllerPath . $url[0])) {
            $controllerPath .= $url[0] . '/';
            array_shift($url);
        }
        
        if (empty($url)) {
            $this->controller = 'MainController';
        } elseif (file_exists($controllerPath . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else {
            $this->sendNotFoundResponse();
        }

        require_once $controllerPath . $this->controller . '.php';
        $this->controller = new $this->controller;
    }

    protected function loadMethod(&$url) {
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->sendNotFoundResponse();
            }
        }
    }

    protected function loadParams($url) {
        $this->params = $url ? array_values($url) : [];
    }

    protected function callControllerMethod() {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function sendNotFoundResponse() {
        http_response_code(404);
        require_once 'app/views/errors/404.php';
        exit();
    }
}
