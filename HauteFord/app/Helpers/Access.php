<?php

namespace app\Helpers;

use app\Controllers\Role;
use app\Controllers\User;

class Access
{
    /**
     * Fonction retournant l'état booléen de l'élément userid de la variable de session
     *
     * @return bool
     */
    public static function isLoggedIn() : bool
    {
        if (!empty($_SESSION['userid'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Si l'utilisateur n'est pas connecté (si l'élément userid de la variable de session est vide),
     * il est redirigé vers la page de login et l'exécution du script s'arrête
     *
     * @return void
     */
    public static function checkLoggedIn() : void
    {
        if (empty($_SESSION['userid'])) {
            header('Location: index.php?view=view/user/login');
            die;
        }
    }
    public static function checkProfile(int $id) : void
    {
        if (empty($_SESSION['userid']) || $_SESSION['userid'] != $id) {
            header('Location: index.php?view=view/user/login');
            die;
        }
    }

/**
     * Si l'identifiant de session (élément userid de la variable de session) est vide ou différent de l'id passé en paramètre,
     * l'utilisateur est redirigé vers la page de login et l'exécution du script s'arrête
     *
     * @param int $id   l'id de l'utilisateur (provenant idéalement de la DB)
     * @return void
     */
   /* public static function checkFormation(int $id) : void
    {
        if (empty($_SESSION['formationid'])  || $_SESSION['formationid'] != $id) {
            header('Location: index.php?view=view/admin/menu');
            die;
    }
}*/


    public static function checkAdmin() : void
    {
        if (!self::isAdmin()) {
            self::redirect();
        }
    }

    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        $user = new User();
        return $user->isAdmin($_SESSION['userid']);
    }

    /**
     * @param int $roleid
     * @return void
     */
    public static function checkAccess(int $roleid): void
    {
        $user = new User();
        if (!$user->hasRole($_SESSION['userid'], $roleid)) {
            self::redirect();
        }
    }

    /**
     * Wrapper de la fonction native PHP de redirection
     * Redirection vers la page index.php par défaut
     * Arrête du script après la redirection
     *
     * @param string $location  url de destination
     * @return void
     */
    public static function redirect(string $location = 'index.php') : void
    {
        header('Location: ' . $location);
        die;
    }
}
