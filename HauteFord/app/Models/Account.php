<?php

namespace app\Models;

class Account extends Model
{

    /**
     * Création d'un compte (account) utilisateur en DB
     * Le compte (account) est obligatoirement lié à l'id d'un utilisateur
     *
     * Bien que dans le cas de cette fonction seul l'userid soit nécessaire,
     * le paramètre doit être un array car la fonction doit respecter la définition de son parent
     *
     * @see Model::create()
     * @param array $data   objet contenant les données (dans ce cas, uniquement userid)
     * @return int
     */
    public static function create(array $data): int
    {
        $insert = self::$connect->prepare("INSERT INTO account (userid, amount, created) VALUES (?, 0.00, NOW())");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        return 0;
    }
}
