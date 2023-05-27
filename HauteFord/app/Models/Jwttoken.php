<?php

namespace app\Models;

use PDO;
//use app\Controllers\Role;

class Jwttoken extends Model
{
     public static function insertToken(array $data): int
     {
         
         $insert = self::$connect->prepare("INSERT INTO jwttoken (username,token, expiry, used,comment) VALUES (?,?, ?, ?,?)");
         $insert->execute(array_values($data));
         if ($insert->rowCount()) {
             // retourne l'id du champ créé en DB par l'INSERT
             return self::$connect->LastInsertId();
         }
         return 0;
     }
     public static function getRecordByToken($username,$jwttoken): Object
     {
          $params = [$username,$jwttoken];
          //var_dump($username."  ".$jwttoken);
          $request = self::$connect->prepare("SELECT * FROM jwttoken WHERE username = ? AND token = ?");        
          $request->execute($params);        
          return $request->fetchObject();         
     }

     public static function getRecordByUsername($username)
     {
          $params = [$username];
          //var_dump($username);
          $request = self::$connect->prepare("SELECT * FROM jwttoken WHERE username = ? ");        
          $request->execute($params);        
          return $request->fetchObject();         
     }
     public static function deleteByID($id)
     {
          $params = [$id];
          //var_dump($username);
          $request = self::$connect->prepare("DELETE FROM jwttoken WHERE id = ?");        
          $request->execute($params);        
          return $request->fetchObject();         
     }


}