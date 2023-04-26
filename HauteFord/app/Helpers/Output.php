<?php

namespace app\Helpers;

use JetBrains\PhpStorm\NoReturn;
use app\Helpers\Bootstrap;

class output
{
    /**
     * Basic routing function
     *
     * @param string $view
     * @return void
     * @throws \ReflectionException
     * @throws \Exception
     */
    public static function getContent(string $view): void
    {
        // php array declaration
        $exts = ['html', 'php'];
        // use of SUPER GLOBAL variable ($_SERVER)
        $route = parse_url($_SERVER['REQUEST_URI']);        
        // use of new str_contains php core function (php 8.0+)
        if (str_contains($route['query'], 'view=api/')) {            
            $params = [];
            // useful explode function (split a string by a string)
            $elements = explode('/', rtrim($route['query'], '/'));            
            // foreach control structure (alternative syntax : key | value)
            foreach ($elements as $key => $value) {                
                if ($key == 1) {
                    // php concatenation
                    $class = 'app\Controllers\\' . ucfirst($value);
                } elseif ($key == 2) {
                    $method = $value;
                } elseif ($key == 0) {
                    // goto next loop iteration
                    continue;
                } else {
                    // array push....value of key[3]
                    $params[] = $value;                    
                }                
            }
            if (!empty($class) && !empty($method)) {                
                // use object without "use" keyword
                // Reflection API : https://www.php.net/manual/fr/book.reflection.php
                $r = new \ReflectionMethod($class, $method);
                $nbr = $r->getNumberOfParameters();                
                if ($nbr != count($params)) {
                    // php core Exception
                    throw new \Exception('Parameters count mismatch');
                }
                // class instantiation (class name can be a variable)
                $controller = new $class();
                // check if method exists
                is_callable($method, true, $callable_name);                
                // method call with parameters (specific php syntax)                
                $controller->{$callable_name}(...array_values($params));
            }
        } elseif (!empty($view)) {
            //var_dump("Inside first IF elseif");
            foreach ($exts as $ext) {
                // php concatenation
                $complete_path = $view . '.' . $ext;
                // us of core function file_exists
                if (file_exists($complete_path)) {
                    // php include (include_once avoid multiple includes)
                    include_once $complete_path;
                }
            }
        } else {
            // redirection
            header('Location: index.php?view=view/default');
            // end of execution (alias of exit)
            die;
        }
    }

    /**
     * Fonction appelant dynamiquement la vue associée au template spécifié en paramètre
     * Une méthode du même nom que le template doit exister dans la classe Bootstrap
     *
     * @see Bootstrap
     * @param string $template          le nom de la méthode de la classe Bootstrap
     * @param object|array|string $data l'objet (ou l'array ou la string) contenant les données gérées par la méthode de la classe Bootstrap
     * @param string $class             les éventuelles classes CSS
     * @return void
     */
    public static function render(string $template, object|array|string $data, string $class = 'danger')
    {
        echo Bootstrap::$template($data, $class);
        
    }
    public static function render2(string $template, object|array|string $data, object|array|string $data2)
    {
        echo Bootstrap::$template($data, $data2);
    }
    

    /**
     * Fonction appelant dynamiquement la vue associée au template spécifié en paramètre, mais retournant le contenu au lieu de l'afficher
     * Une méthode du même nom que le template doit exister dans la classe Bootstrap
     *
     * @param string $template
     * @param object|array|string $data
     * @param string $class
     * @return string
     */
    public static function get(string $template, object|array|string $data, string $class = 'danger'): string
    {
        return Bootstrap::$template($data, $class);
    }

    /**
     * Stocke un message d'alerte en session et redirige l'utilisateur
     *
     * @param string $text      le texte à stocker en session
     * @param string $level     la couleur css "Bootstrap" du message [info, warning, danger, success] à stocker en session
     * @param string $redirect  l'url de redirection après l'affichage du message
     * @return void
     */
    #[NoReturn] public static function createAlert(string $text, string $level = 'info', string $redirect = 'index.php?view=view/default'): void
    {
        $_SESSION['alert'] = $text;
        $_SESSION['alert_level'] = $level;
        //var_dump("inside createAlert");
        header('Location: ' . $redirect);
        die;
    }

    /**
     * Affiche l'éventuel message d'alerte existant dans une div utilisant la box d'alerte css Bootstrap
     * Réinitialise le message d'alerte en session après l'affichage
     *
     * @see self::checkAlertLevel()
     * @return void
     */
    public static function manageAlerts(): void
    {
        if (!empty($_SESSION['alert']) && !empty($_SESSION['alert_level'])) {
            echo '<div class="alert alert-' . self::checkAlertLevel() . '">' . $_SESSION['alert'] . '</div>';
            unset($_SESSION['alert']);
            unset($_SESSION['alert_level']);
        }
    }

    /**
     * Vérifie si le level (la classe css Bootstrap) du message d'alerte est utilisée par le Framework CSS
     * Dans le cas contraire, la classe info est utilisée
     *
     * @return string
     */
    public static function checkAlertLevel(): string
    {
        if (!empty($_SESSION['alert_level'])) {
            // Principe de la "white list"
            if (in_array($_SESSION['alert_level'], ['success', 'danger', 'info'])) {
                return $_SESSION['alert_level'];
            } else {
                return 'info';
            }
        }
        return '';
    }
}