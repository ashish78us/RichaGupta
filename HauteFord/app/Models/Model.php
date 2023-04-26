<?php

namespace app\Models;

use PDO;
use app\Helpers\DB;

abstract class Model
{
    protected static PDO $connect;
    

    public function __construct()
    {
       // var_dump("inside model super");
        global $connect;

        if (!$connect) {
            $connect = DB::connect();
        }
        self::$connect = $connect;
    }



   public static function get(int $id): bool
    {
        return false;
    }
    
    
    /**
     * @param string $orderby
     * @return array
     */
    public static function getAll(string $orderby = ''): array
    {
        $datas = [];
        $sql = "SELECT * FROM " . self::getClassName();
        //var_dump($sql);
        if ($orderby && in_array($orderby, self::getColumns())) {
            $sql .= " ORDER BY " . $orderby;
        }
        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $datas[] = $data_tmp;
        }
        return $datas;
    }
    

    /**
     * @param string $orderby
     * @param string $key
     * @param string $caption
     * @return array
     */
    public static function getAllForForm(string $orderby = '', string $key = 'id', string $caption = 'name'): array
    {
        $datas = [];
        $sql = "SELECT * FROM " . self::getClassName();
        if ($orderby && in_array($orderby, self::getColumns())) {
            $sql .= " ORDER BY " . $orderby;
        }
        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $datas[$data_tmp->$key] = $data_tmp->$caption;
        }
        return $datas;
    }


    /**
     * Retourne un objet de données de la DB selon la valeur d'un champ (colonne)
     * La table interrogée en DB sera toujours celle dont le nom équivaut à celui de la Classe appelante
     *
     * @param string $field     le champ
     * @param string $value     la valeur
     * @return mixed
     */
    public static function getByField(string $field, string $value): mixed
    {
        $request = self::$connect->prepare("SELECT * FROM " . self::getClassName() . " WHERE $field = ?");        
        $request->execute([$value]);        
        return $request->fetchObject();
    }

    public static function getByFieldAll(string $field, string $value): mixed
    {
        $request = self::$connect->prepare("SELECT * FROM " . self::getClassName() . " WHERE $field = ?");
        $request->execute([$value]);
        //var_dump($request->rowCount());
        //var_dump($request->fetchObject());
        while ($data_tmp = $request->fetchObject()) {
            //$datas[$data_tmp->$key] = $data_tmp->$caption;
            $datas[] = $data_tmp;
        }
        return $datas;
    }

    /**
     * Met à jour un champ en DB sur base de l'id
     * La table interrogée en DB sera toujours celle dont le nom équivaut à celui de la Classe appelante
     *
     * @param string $field     le champ à mettre à jour
     * @param string $value     la nouvelle valeur
     * @param int $id           l'id (clé primaire) du record à mettre à jour
     * @return mixed
     */
    public static function updateFieldById(string $field, string $value, int $id): mixed
    {

        // Si le champ ne fait pas partie de la liste des champs de la table en DB, la fonction retourne false
        if (!in_array($field, self::getColumns())) {
            return false;
        }

        // Cas spécial : dans le cas où NOW() est utilisé, il est inséré tel quel
        if ($value != 'NOW()') {
            $val = '?';
            $params = [$value, $id];
        } else {
            $val = $value;
            $params = [$id];
        }

        $request = self::$connect->prepare("UPDATE " . self::getClassName() . " SET $field = $val WHERE id = ?");
        $request->execute($params);
        // Retourne le nombre de records (lignes, rows) modifiés par la requête
        return $request->rowCount();
    }

    public static function create(array $data): int
    {
        return 0;
    }
    public static function createAny($tablename,$data): int
    {
        //var_dump($tablename);
       
       $count = 0;
       $arraycount = count($data);
        $columns = self::getColumns();
        $fields = "";
        foreach($data as $key => $val){
           if ($count == $arraycount-1){
            $fields .= $key;
            break;
           }
           else{$fields .= $key . ",";}            
            $count= $count +1;
        }        
        $insert = self::$connect->prepare("INSERT INTO $tablename ($fields) VALUES (?, ?, ?)");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        //return 0;       
        
    }
   

    /**
     * @param object $object
     * @return int
     */
    public static function update(object $object): int
    {
        //var_dump("inside Model superclass::update and if");
        //var_dump($object);
        if (empty($object->id)) {
            var_dump($object);
            return false;
        }
        $params = array_values(get_object_vars($object));        
        $params[] = $object->id;       
        
        $setFields = self::getSelectFields($object, ',', ' = ?');
        //var_dump($setFields);
        $query = "UPDATE " . self::getClassName() . " SET $setFields WHERE id = ?";
        //var_dump("query=".$query);
        $request = self::$connect->prepare($query);
        $request->execute($params);
        return $request->rowCount();
    }
    

    public static function raw(string $sql, array $params = [], string $fetch = 'RESULT'): mixed
    {
        $result = self::$connect->prepare($sql);
        $result->execute($params);
        if ($fetch == 'OBJECT') {
            $data = $result->fetchObject();
        } elseif ($fetch == 'UPDATE') {
            $data = $result->rowCount();
        } elseif ($fetch == 'COUNT') {
            $data = $result->fetchColumn();
        } elseif ($fetch == 'ALL') {
            $data = $result->fetchAll();
        } elseif ($fetch == 'MULTI') {
            $data = [];
            while ($data_tmp = $result->fetchObject()) {
                $data[] = $data_tmp;
            }
        } elseif ($fetch == 'INDEX') {
            $data = [];
            while ($data_tmp = $result->fetchObject()) {
                $data[$data_tmp->id] = $data_tmp;
            }
        } elseif ($fetch == 'IMPLODE') {
            $data_implode = [];
            while ($data_tmp = $result->fetchObject()) {
                $data_implode[] = $data_tmp->ID;
            }
            $data = implode(',', $data_implode);
        } else {
            $data = $result;
        }
        return $data;
    }

    /**
     * Fonction interne récupérant le nom de la classe appelante,
     * afin de la lier avec le nom de la table en DB
     *
     * @return string
     */
    protected static function getClassName(): string
    {
        $class = get_called_class();
        //var_dump($class);  die;
        $data = explode('\\', $class);
        //var_dump($data); die ;
        //var_dump(end($data)); die;
        return strtolower(end($data));
    }

    /**
     * Récupère, sous forme de tableau, la liste des champs de la table en DB liée au Model
     *
     * @return array
     */
    protected static function getColumns()
    {
        $columns = [];
        $cols = self::$connect->query("DESCRIBE " . self::getClassName(), PDO::FETCH_OBJ);
        foreach ($cols as $col) {
            $columns[] = $col->Field;
        }
        return $columns;
    }

    /**
     * Générateur de champs de requête SELECT ou WHERE
     *
     * @param object $fields
     * @param string $concat [, dans le cas d'un select ou d'un update; AND, OR, etc... dans le cas d'un WHERE]
     * @param string $addtxt [suffixe pour chaque élément de la requête, par exemple = ? pour chaque champs du where dans une requête préparée]
     * @return string
     */
    protected static function getSelectFields(object $fields, string $concat, string $addtxt = ''): string
    {
        $return = '';
        $nbr = 0;
        foreach ($fields as $key => $value) {
            if ($nbr > 0) {
                if ($value == 'NOW()') {
                    $return .= $concat . $key . '=NOW()';
                } else {
                    $return .= $concat . $key . $addtxt;
                }
            } else {
                $return .= $key . $addtxt;
            }
            $nbr++;
        }
        return $return;
    }

    static function setColumnsValueFromView($id): Object
    {
        $columns = [];
        $cols = self::$connect->query("DESCRIBE " . self::getClassName(), PDO::FETCH_OBJ);
        foreach ($cols as $col) {
            $columns[] = $col->Field;
        }
        //var_dump($_POST);
        foreach ($columns as $col => $value) {            
            if(!empty($_POST[$value]))
            $id->$value = $_POST[$value];
           // var_dump( $value."=". $_POST[$value]);
        }
        return $id;
    }

}
