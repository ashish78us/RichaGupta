<?php

namespace app\Controllers;

class Role extends Controller
{
    const ADMIN = 1;
    const ETUDIANT = 2;
    const BANNI = 3;
    const INVITE = 4;
    protected static Role $obj_role;
    public static function getRole(): Object {
        if (!isset(self::$obj_role)) {
        self::$obj_role = new Role();
        }
        return self::$obj_role;
    } 

}
