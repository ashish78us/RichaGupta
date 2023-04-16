<?php

namespace app\Models;

use PDO;
use app\Controllers\Role;

class User extends Model
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $nom;
    protected string $prenom;
    protected string $pays;
    protected string $birthdate;
    protected string $phone;
    protected string $address;
    protected string $created;
    protected string $lastlogin;
    protected bool $admin;

    // generate getters and setters

    public function __construct() {
        parent::__construct();
    }

    /**
     * Création d'un utilisateur en DB
     *
     * @param array $data   objet contenant les données à passer dans la méthode execute
     * @return int
     */
    public static function create(array $data): int
    {
        
        $insert = self::$connect->prepare("INSERT INTO user (username, password, nom, prenom, email, pays,birthdate, phone,address, created, lastlogin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,NOW(), NOW())");
        $insert->execute(array_values($data));
        if ($insert->rowCount()) {
            // retourne l'id du champ créé en DB par l'INSERT
            return self::$connect->LastInsertId();
        }
        return 0;
    }

    /**
     * Retourne le nombre d'utilisateurs ayant le login ou l'email fournis
     *
     * @param string $login
     * @param string $email
     * @return int
     */
    public static function getByUsernameOrEmail(string $login, string $email): int
    {
        $result = self::$connect->prepare("SELECT COUNT(*) FROM user WHERE username = ? OR email = ?");
        $result->execute([$login, $email]);
        return $result->fetchColumn();
    }
    public static function getAll(string $orderby = ''): array
    {
        $users = [];
        $sql = 'SELECT u.id,
                u.username,
                u.nom,
                u.prenom,
                u.email,
                u.pays,
                u.birthdate,
                u.phone,
                u.address,
                u.created,
                u.lastlogin,
               u.image,
             r.name
               FROM user u
               JOIN user_role ur ON ur.userid = u.id
               JOIN role r ON r.id = ur.roleid
  ORDER BY u.id';

        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $users[] = $data_tmp;
        }
        return $users;
    }
    public static function isAdmin(int $id): mixed
    {
        //var_dump("inside model isAdmin");
        $result = self::$connect->prepare("SELECT COUNT(*) FROM user_role WHERE roleid = " . Role::ADMIN . " AND userid = ?");
        $result->execute([$id]);
        return $result->fetchColumn();
    }
    public static function isAdmin2(int $id): mixed
    {
        //var_dump("inside model isAdmin");
        $result = self::$connect->prepare("SELECT roleid FROM user_role WHERE  userid = ?");
        $result->execute([$id]);
        $roleid=$result->fetchColumn();
        $result2 = self::$connect->prepare("SELECT name FROM role WHERE  id = ?");
        $result2->execute([$roleid]);

        //var_dump($result2->fetchColumn());
        return $result2->fetchColumn();
    }
    

    public static function hasRole(int $id, int $role): mixed
    {
        $result = self::$connect->prepare("SELECT COUNT(*) FROM user_role WHERE userid = ? AND roleid = ?");
        $result->execute([$id, $role]);
        return $result->fetchColumn();
    }

    /**
     * @param int $id
     * @param string $role
     * @return mixed
     */
    public static function hasRoleByName(int $id, string $role): mixed
    {
        $result = self::$connect->prepare("SELECT COUNT(*) FROM user_role ur, role r WHERE ur.roleid = r.id AND ur.userid = ? AND r.name = ?");
        $result->execute([$id, $role]);
        return $result->fetchColumn();
    }
    public static function hasAnyRole(int $id): mixed
    {
        $result = self::$connect->prepare("SELECT COUNT(*) FROM user_role WHERE userid = ?");
        $result->execute([$id]);
        return $result->fetchColumn();
    }

    /**
     * @param int $id
     * @param int $roleid
     * @return bool
     */
    public static function addRole(int $id, int $roleid): bool
    {
        $request = self::$connect->prepare("INSERT INTO user_role (userid, roleid, created) VALUES (?, ?, NOW())");
        $request->execute([$id, $roleid]);
        if ($request->rowCount()) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @param int $roleid
     * @return bool
     */
    public static function updateRole(int $id, int $roleid): bool
    {
        $request = self::$connect->prepare("INSERT INTO user_role (userid, roleid, created) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE roleid = ?");
        $request->execute([$id, $roleid, $roleid]);
        if ($request->rowCount()) {
            return true;
        }
        return false;
    }
    public function getUserListIfAdmin(int $userid): array
    {
        $UserList = [];
        $sql = 'SELECT username ,email, created, pays, lastlogin FROM USER';

        $request = self::$connect->prepare($sql);
        $request->execute();
        while ($data_tmp = $request->fetchObject()) {
            $UserList [] = $data_tmp;
        }
        return $UserList;
    }




}
