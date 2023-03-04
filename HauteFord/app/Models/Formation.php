<?php

namespace app\Models;
use PDO;

class Formation extends Model
{   protected int $id;
    protected string $name;
    protected string $niveau_etude;
    protected string $status;
    protected string $date_debut;
    protected string $date_fin;
   
    public function __construct() {
        parent::__construct();
    }
    public static function create_formation(array $data): int
    {
        
        $insert = self::$connect->prepare("INSERT INTO formation (name, niveau_etude, status, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        return 0;
    }
    public static function getAll(string $orderby = ''): array
    {
        $formation = [];
        $sql = 'SELECT f.id,
                f.name,
               f.niveau_etude ,
               f.status,
                f.date_debut,
               f.date_fin
             from formation f
  ORDER BY f.id';

        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $formation[] = $data_tmp;
        }
        //var_dump($formation);
        return $formation;
    }
    public static function Delete($id){
        if (!is_numeric($id)) {
            return false;
        }
        // Construct and execute the DELETE query
        $this->db->query("DELETE FROM formation WHERE id = :id");
        $this->db->bind(':id', $id);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    public static function getById($id)
    {
        //var_dump($id);
        //return "Inside Formation Model getById";
        
        $sql = 'SELECT f.id,
        f.name,
       f.niveau_etude ,
       f.status,
        f.date_debut,
       f.date_fin
        from formation f where f.id=? ORDER BY f.id';

        $request = self::$connect->prepare($sql);
        $request->execute([$id]);
        $formationResultById=$request->fetchObject();
        
        return $formationResultById;
    }

    }

