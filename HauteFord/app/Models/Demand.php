<?php

namespace app\Models;

class Demand extends Model

    {  
    /**
     * @param string $orderby
     * @return array
     */
    public static function createDemand(array $data): int
    {
        
        $insert = self::$connect->prepare("INSERT INTO Demand (userid, formationid, status) VALUES (?, ?, ?)");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        return 0;
    }
    public static function findDemand($formationid,$userid)
    {

     $params = [$formationid,$userid->id];
        $sql = "SELECT *
               FROM demand
                WHERE formationid = ? AND userid = ?";
                $sql .= " AND userid = ";
                $sql .= $userid->id;
          //var_dump($sql);
        $request = self::$connect->prepare($sql);
        //$request->bindValue(1,$formationid);
        //$request->bindValue(2,$userid);
        $request->execute($params);
        //var_dump($request->fetchObject()->status);
        if ($request->rowCount()==1){
       return $request->fetchObject()->status;
     }
     else {return "Demand";
     }
}

public static function returnDemandid($formationid,$userid)
    {
//var_dump($formationid);
//var_dump($userid);
     $params = [$formationid,$userid];
        $sql = "SELECT *
               FROM demand
                WHERE formationid = ? AND userid = ?";
                $sql .= " AND userid = ";
                $sql .= $userid;          
        $request = self::$connect->prepare($sql);        
        $request->execute($params);
        //var_dump($request->rowCount())     ;   
        if ($request->rowCount() == 0) {
          return null;
        }
        else {
       return $request->fetchObject();
        }
     
     
}

public static function getAllDemand(string $orderby = ''): array
    {
        $demand= [];
        $sql = 'SELECT d.id,
                d.name,
               d.niveau_etude ,
               d.status,
                d.date_debut,
               d.date_fin,
               d.demande,
               d.request_status
             from demand d
  ORDER BY d.id';

        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $demand[] = $data_tmp;
        }
        //var_dump($formation);
        return $demand;
    }

public static function getAllCourses($username):array {
        $params = [$username];
        $userCourses= [];        
        $sql= 'SELECT f.name as FormationName, c.name as CourseName, u.username as username,
        fc.period as NumberOfPeriods, fc.determinant as Determinants, fc.prepreq as Prepreq, fc.teacher, 
        fc.status as Status,  ur.created as DateDeInscription, r.name as ROLE
        from user_course uc
        LEFT JOIN demand d on d.id = uc.userid
        LEFT JOIN course c on c.id = uc.courseid        
        LEFT JOIN formation_course fc on fc.courseid = uc.courseid
        LEFT JOIN formation f on f.id = fc.formationid
        LEFT JOIN user u on u.id = uc.userid
        LEFT JOIN user_role ur on ur.userid = u.id
        LEFT JOIN role r on r.id = ur.roleid        
        
        WHERE u.username = ?
        ORDER BY f.name';
        $request = self::$connect->prepare($sql);
        $request->execute($params);
        while ($data_tmp = $request->fetchObject()) {
            $userCourses[] = $data_tmp;
        }        
        return $userCourses;

    }         
}
    

