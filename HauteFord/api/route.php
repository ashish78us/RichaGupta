<?php
var_dump("inside route.php");
$api_path = 'api/route/';

$route = parse_url($_SERVER['REQUEST_URI']);
if  (str_contains($route['path'], $api_path)) {
    
    // auto load classes
    spl_autoload_register(function ($class) {
        //var_dump(__DIR__ .  strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php'); die;
        //var_dump(DIRECTORY_SEPARATOR);
        //var_dump("inside spl");
        //var_dump($class);
       
        require __DIR__  . '\\' .  strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
    });
    $params = [];
    $route = substr($route['path'], strpos($route['path'], strlen($api_path)), strlen($route['path']));
   // var_dump($route);
    $elements = explode('/', rtrim($route, '/'));
    foreach ($elements as $key => $value) {
        if ($key == 0) {
            $class = 'app\Controllers\\' . ucfirst($value);
        } elseif ($key == 1) {
            $method = $value;
        } else {
            $params[] = $value;
        }        
    }
    if (!empty($class) && !empty($method)) {
        $r = new \ReflectionMethod($class, $method);
        $nbr = $r->getNumberOfParameters();
        if ($nbr != count($params)) {
            throw new \Exception('Parameters count mismatch');
        }
        $controller = new $class();
        is_callable($method, true, $callable_name);
        $controller->{$callable_name}(...array_values($params));
    }
}
