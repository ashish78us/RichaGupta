<?php

namespace app\Helpers;

use PDO;
use PDOException;

/**
 * DB connect [Singleton]
 */
class DB
{
    protected static PDO|null $dbh = null;

    /**
     * @return PDO|null
     */
    public static function connect(): PDO|null
    {
        if (!self::$dbh) {

            try {
                // Utilisation d'un fichier de configuration contenant les paramètres de connexion à la DB (sous forme de constantes)
                require_once __DIR__ . '/../../config.php';
                self::$dbh = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8', DB_USER, DB_PASSWORD);
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                // En cas d'erreur de connexion, arrêter le script et afficher le message d'erreur
                die ('Error : ' . $exception->getMessage());
            }
        }
        return self::$dbh;
    }
}