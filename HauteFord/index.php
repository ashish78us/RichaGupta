<?php
/**
 * main page
 */

// Attention, la fonction native Php session_start() doit être appelée dans chaque script où la session sera utilisée
// Il est conseillé de nommer la session et de la limiter dans le temps
session_name('HAUTEFORD' . date('Ymd'));
session_start(['cookie_lifetime' => 3600]);
spl_autoload_register(function ($class) {
   //var_dump($class);
   //var_dump(__DIR__);
   $ashish=__DIR__ . '\\' .   strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
    //var_dump($ashish);
    require_once __DIR__  . '\\' .   strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
});





$connect = \app\Helpers\DB::connect();

// output
require_once __DIR__ . '/view/header.html';
require_once __DIR__ . '/view/menu.php';
require_once __DIR__ . '/view/default.php';
require_once __DIR__ . '/view/footer.html';

?>