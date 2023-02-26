<?php

namespace app\Models;

class Course extends Model

    {  
    /**
     * @param string $orderby
     * @return array
     */
    public static function createCourse(array $data): int
    {
        
        $insert = self::$connect->prepare("INSERT INTO Course (name, code, status) VALUES (?, ?, ?)");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        return 0;
    }
    public static function getAll(string $orderby = ''): array
    {
        $courses = [];
        $sql = 'SELECT c.id as courseid, 
                f.name as formation_name, 
                c.name as course_name, 
                fc.period as periods, 
                fc.determinant as det, 
                c2.name as course_prereq, 
                fc.teacher as teacher
               FROM formation_course fc
               JOIN formation f ON f.id = fc.formationid
               JOIN course c ON c.id = fc.courseid
               LEFT JOIN formation_course fc2 ON fc2.id = fc.prepreq
               LEFT JOIN course c2 ON c2.id = fc2.courseid
               ORDER BY fc.formationid';

        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $courses[] = $data_tmp;
        }
        return $courses;
    }

    /**
     * @param int $formationid
     * @return array
     */
    public static function getByFormation(int $formationid): array
    {
        $courses = [];
        $sql = 'SELECT c.id as courseid, 
                f.name as formation_name, 
                c.name as course_name, 
                fc.period as periods, 
                fc.determinant as det, 
                c2.name as course_prereq, 
                fc.teacher as teacher
               FROM formation_course fc
               JOIN formation f ON f.id = fc.formationid
               JOIN course c ON c.id = fc.courseid
               LEFT JOIN formation_course fc2 ON fc2.id = fc.prepreq
               LEFT JOIN course c2 ON c2.id = fc2.courseid
               WHERE fc.formationid = ?
               ORDER BY fc.formationid';

        $request = self::$connect->prepare($sql);
        $request->execute([$formationid]);
        while ($data_tmp = $request->fetchObject()) {
            $courses[] = $data_tmp;
        }
        return $courses;
    }

    /**
     * @param int $formationid
     * @return array
     */
    public static function getByFormationForForm(int $formationid): array
    {
        $courses = [];
        $sql = 'SELECT c.id as courseid, 
                c.name as coursename
               FROM formation_course fc
               JOIN formation f ON f.id = fc.formationid
               JOIN course c ON c.id = fc.courseid
               WHERE fc.formationid = ?
               ORDER BY c.name';

        $request = self::$connect->prepare($sql);
        $request->execute([$formationid]);
        while ($data_tmp = $request->fetchObject()) {
            $courses[$data_tmp->courseid] = $data_tmp->coursename;
        }
        return $courses;
    }

    /**
     * @param int $courseid
     * @param int $userid
     * @return bool
     */
    public static function enrol(int $courseid, int $userid): bool
    {
        $request = self::$connect->prepare("INSERT INTO user_course (userid, courseid, created) VALUES (?, ?, NOW())");
        $request->execute([$userid, $courseid]);
        if ($request->rowCount()) {
            return true;
        }
        return false;
    }

    /**
     * @param int $courseid
     * @param int $userid
     * @return int
     */
    public function getEnrol(int $courseid, int $userid): int
    {
        $request = self::$connect->prepare("SELECT COUNT(*) FROM user_course WHERE userid = ? AND courseid = ?");
        $request->execute([$userid, $courseid]);
        return $request->fetchColumn();
    }

    /**
     * @param int $userid
     * @return array
     */
    public function getByUserEnrol(int $userid): array
    {
        $courses = [];
        $sql = 'SELECT c.id as courseid, 
                f.name as formation_name, 
                c.name as course_name, 
                fc.period as periods, 
                fc.determinant as det, 
                c2.name as course_prereq, 
                fc.teacher as teacher
               FROM formation_course fc
               JOIN formation f ON f.id = fc.formationid
               JOIN course c ON c.id = fc.courseid
               JOIN user_course uc ON uc.courseid = c.id AND uc.userid = ?
               LEFT JOIN formation_course fc2 ON fc2.id = fc.prepreq
               LEFT JOIN course c2 ON c2.id = fc2.courseid
               ORDER BY f.name, c.name';

        $request = self::$connect->prepare($sql);
        $request->execute([$userid]);
        while ($data_tmp = $request->fetchObject()) {
            $courses[] = $data_tmp;
        }
        return $courses;
    }

    /**
     * @param int $courseid
     * @return mixed
     */
    public function getPreprequisite(int $courseid): mixed
    {
        $sql = "SELECT prepreq FROM formation_course WHERE courseid = ?";
        $request = self::$connect->prepare($sql);
        $request->execute([$courseid]);
        return $request->fetchObject()->prepreq;
    }
}
