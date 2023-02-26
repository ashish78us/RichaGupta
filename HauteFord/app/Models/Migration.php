<?php

namespace app\Models;

class Migration extends Model
{

    /**
     * @return mixed
     */
    public static function addNew(): mixed
    {
        return self::$connect->query("INSERT INTO migration (id, lasttime) VALUES (1, UNIX_TIMESTAMP(NOW())) ON DUPLICATE KEY UPDATE lasttime = UNIX_TIMESTAMP(NOW())");
    }

    /**
     * @return mixed
     */
    public static function getLastMigrationDate(): mixed
    {
        $result = self::$connect->prepare("SELECT lasttime FROM migration WHERE id = 1");
        $result->execute();
        return $result->fetchObject()->lasttime;
    }

}