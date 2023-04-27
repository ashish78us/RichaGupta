<?php

namespace app\Controllers;

use app\Controllers\Account;
use app\Helpers\Helper;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;

class User extends Controller
{
    protected static array $images = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];
    public function getBearer(int $id, int $delay = 3600, bool $return = true): mixed
    {
        $now = time();
        $payload = [
            'iss' => 'http://localhost',    //issuer
            'aud' => 'https://aubedesaigles.net',    //audience
            'jti' => 'web-userid',          //unique id
            'exp' => $now + $delay,         //expiration time
            'sub' => 'user',                //subject
            'iat' => $now,                  //issued at (created)
            'nbf' => $now                   //not before
        ];
        $jwt = JWT::encode($payload, JWT_KEY, 'HS256');
        if ($return) {
            return $jwt;
        } else {
            echo $jwt;
        }
        return 0;
    }



    /**
     * @return void
     */
    public function hello(): void
    {
        Output::render('messageBox', 'Hello World !', 'info');
    }
    public function list(): void {
        Access::checkLoggedIn();
        Access::checkAdmin();

        $users = $this->model->getAll();

        // insert all roles of each user
        foreach ($users as $user) {
            $user->roles = $this->model::getRoles($user->id);
            $course = new Course();
            $user->courses = $course->getByUserEnrol($user->id);

            unset($user->password);
            unset($user->admin);
            unset($user->colorid);
            unset($user->updated);
        }

        Output::render('users', $users);
    }

    /**
     * Crée un utilisateur sur base des données envoyées par une requête HTTP en method POST
     * Le formulaire doit contenir les champs suivants :
     * - login
     * - password
     * - email
     *
     * @see \app\Models\User::getByUsernameOrEmail()
     * @see \app\Models\User::create()
     * @see Account::create()
     * @return void
     * @throws \Exception
     */
    public function create(): void
    {
        // Si l'appel à cette méthode ne provient pas d'une requête HTTP utilisant la méthode POST, un message d'erreur est affiché
        if (!$_POST) {
            header('HTTP/1.1 405');
        }


        // Filtrage des données de la requête HTTP
        if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['nom']) && !empty($_POST['prenom'])&& !empty($_POST['pays']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['birthdate']) && !empty($_POST['phone']) && !empty($_POST['address'])) {

            if (!in_array($_POST['pays'], ['Belge', 'Candian','Indian', 'Russian', 'Italian','German'])) {
                Output::createAlert('pays mismatch', 'danger', 'index.php?view=user/register');
            }

            $user_data = [
                'login' => trim($_POST['login']),
                // Tout mot de passe doit être crypté. Php possède une fonction native password_hash
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'pays' => $_POST['pays'],
                'birthdate' => $_POST['birthdate'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                

            ];

            // Appel à la method du Model vérifiant si un utilisateur possède déjà le login ou l'email envoyés par la requête HTTP
            if (!$this->model->getByUsernameOrEmail($user_data['login'], $user_data['email'])) {
                // Appel à la method du Model créant l'utilisateur en DB
                // le résultat de la requête (id de l'utilisateur créé) est stocké dans la variable $userid
                $userid = $this->model->create($user_data);
                if ($userid) {
                    // Création de l'image, appel à la method associée
                    $photo_message = false;
                    if (!empty($_FILES['photo']['name'])) {
                        $user = $this->get($userid);
                        if (!$this->manageUploadedFile($user, true)) {
                            $photo_message = 'L\'import de la photo a échoué';
                        }
                    }
                    // si la création de l'utilisateur est effective, on instancie un objet Account (du Controller du même nom)
                    $account = new Account();
                    // Appel à la method create du Controller Account
                    if ($account->create($userid)) {
                        Output::render('messageBox', 'Utilisateur ' . $_POST['login'] . ' avec l\'adresse email ' . $_POST['email'] . ' créé avec succès', 'success');
                        if (!empty($photo_message)) {
                            Output::render('messageBox', $photo_message);
                        }
                    } else {
                        Output::render('messageBox', 'La création du compte utilisateur a partiellement échoué');
                    }
                } else {
                    Output::render('messageBox', 'La création du compte utilisateur a échoué');
                }
            } else {
                Output::render('messageBox', 'Un utilisateur avec cet identifiant ou cette adresse email existe déjà !');
            }
        } else {
            // Redirection vers le formulaire de signup
            header('Location: index.php?view=user/signup');
            die;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function update(): void
    {if (!$_POST) {
        header('HTTP/1.1 405');
    }
;
        Access::checkProfile($_POST['id']);
        $this->addDefaultRoleIfNone($_SESSION['userid']);

        $update = false;
        $paysupdate = false;

        $user = $this->get($_POST['id']);

        if (!empty($_POST['password']) && $_POST['password'] != $user->password) {
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $update = true;
        }
        if (!empty($_POST['nom']) && $_POST['nom'] != $user->nom) {
            $user->nom = $_POST['nom'];
          $update = true;
        
        }
        if (!empty($_POST['prenom']) && $_POST['prenom'] != $user->phone) {
            $user->prenom = $_POST['prenom'];
          $update = true;
      }
      if (!empty($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) && $_POST['email'] != $user->email) {
        $email = trim($_POST['email']);
        if (!$this->model->getByUsernameOrEmail('', $email)) {
            $user->email = $email;
            $update = true;
        } else {
            Output::createAlert('Cette adresse email existe déjà!', 'danger', 'index.php?view=api/user/profile/' . $user->id);
        }
    }
        if (!empty($_POST['pays']) && in_array($_POST['pays'], ['Belge', 'Candian','Indian', 'Russian', 'Italian','German']) && $_POST['pays'] != $user->pays) {
            $user->pays = $_POST['pays'];
            $update = true;
            $paysupdate = true;
           }
        
        if (!empty($_POST['birthdate']) && $_POST['birthdate'] != $user->birthdate) {
            $user->birthdate = $_POST['birthdate'];
            $update = true;
          
            
        }
        if (!empty($_POST['phone']) && $_POST['phone'] != $user->phone) {
           
            $user->phone = $_POST['phone'];
           
           $update = true;
        
        }
        if (!empty($_POST['address']) && $_POST['address'] != $user->address) {
            $user->address = $_POST['address'];
           
          $update = true;
           
        }
        if (!empty($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
        try {
            $user->image = $this->manageUploadedFile($user);
        } catch (\Exception $e) {
            Output::createAlert($e->getMessage(), 'danger', 'index.php?view=api/user/profile/' . $user->id);
        }
        $update = true;
    }
        if ($update) {
            $now = new \DateTime();
            $user->updated = $now->setTimezone(new \DateTimeZone('Europe/Paris'))->format('Y-m-d H:i:s');
        }

        if ($update && $this->model->update($user)) {
            if ($paysupdate) {
                $_SESSION['pays'] = $_POST['pays'];
            }
            Output::createAlert('La mise à jour du compte utilisateur a été effectuée avec succès', 'success', 'index.php?view=api/user/profile/' . $user->id);
        } else {
            Output::createAlert('La mise à jour du compte utilisateur a échoué', 'danger', 'index.php?view=api/user/profile/' . $user->id);
        }
    }

    /**
     * Connexion de l'utilisateur sur base de données envoyées par une requête HTTP en method POST
     *
     * @see \app\Models\User::getByField()
     * @see \app\Models\User::updateFieldById()
     * @return void
     * @throws \Exception
     */
    public function login(): void
    {
        if (!$_POST) {
            header('HTTP/1.1 405');
        }

        

        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            // Appel à la method du Model récupérant, sous forme d'objet, l'utilisateur dont le username correspond au login (purgé des éventuels espaces)
            $user = $this->model->getByField('username', trim($_POST['login']));
            
            if (!$user) {
                Output::createAlert('Cet utilisateur n\'existe pas!', 'danger', 'index.php?view=view/user/login');
            }
            // Utilisation de la fonction native PHP password_verify pour valider un mot de passe et sa valeur de hashage stockée en DB par la fonction native password_hash
            if (password_verify($_POST['password'], $user->password)) {
                // Si le mot de passe est vérifié, on met à jour le champ "lastlogin" dans la table user en DB, via l'appel à la method du Model
                if ($this->model->updateFieldById('lastlogin', 'NOW()', $user->id)) {
                    // Protection contre le vol de session : Attribuer un nouvel ID de session lors du login et limiter la durée de vie du cookie
                    session_destroy();
                    session_name('HAUTEFORD' . date('Ymd'));
                    session_id(bin2hex(openssl_random_pseudo_bytes(32)));
                    session_start(['cookie_lifetime' => 3600]);
                    // L'id de l'utilisateur est stocké en session
                    // Attention, la fonction native Php session_start() doit être appelée dans chaque script où la session sera utilisée
                    $_SESSION['userid'] = $user->id;
                    $_SESSION['pays'] = $user->pays;
                    // Appel à la method de création d'alerte et redirection vers la vue dynamique (method profile du Controller user)
                    //var_dump("inside Login");
                    $bool=self::isAdmin($user->id);
                    
                    $this->addRole($user->id, Role::INVITE);
                    //if($bool=self::isAdmin($user->id)){
                        if(Access::isAdmin($user->id)){
                        $view="index.php?view=view/admin/index/";
                    }
                    else {
                        $view="index.php?view=api/user/profile/";
                    }
                    $cookie_username = "coo_username";
                    $cookie_userid = $user->username;
                    setcookie($cookie_username, $cookie_userid, 0, "/");                    
                    Output::createAlert('Bienvenue ' . $user->username, 'success', $view . $user->id);
                    
                }
            } else {
                Output::render('messageBox', 'Paramètres invalides!');
            }
        }
    }
    public static function getUserByid(){
        $user = new User();
        $user = $user->model->getByField('username',$_COOKIE['coo_username']);
        return $user;
   }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_write_close();
        header('Location: index.php');
        die;
    }

    /**
     * Affichage de la vue dynamique du profil, sur base de l'id de l'utilisateur
     *
     * @see Access::checkProfile()
     * @see Output::render()
     * @param int $id               id de l'utilisateur
     * @return void
     * @throws \Exception
     */
    public function profile(int $id): void
    {
        /// Vérification de la correspondance entre l'id fourni et l'id de session
        Access::checkProfile($id);

        //récupération du record en DB correspondant à l'id fourni
        //$user = $this->getForProfile($id);
        $user = $this->get($id);

        // formattage des données pour l'affichage du profil
        $userForProfile = self::formatForProfile($user);

        // Affichage de l'utilisateur dans la vue dynamique générée par le renderer
        Output::render('profile', $userForProfile);

        // Affichage du formulaire de mise à jour de l'utilisateur dans la vue dynamique générée par le renderer
        Output::render('profileUpdate', $user);
        $userCourseList = Demand::listCoursesForUserInProfile();
        Output::render('profileCourses', $userCourseList);
     
        //if($this->model->isAdmin($id)){            
            //$listUser = $this->model->getUserListIfAdmin($id);
            //Output::render('profileUserListIfAdmin', $listUser);
       //}

    }
    public function admin(): void
    {
        Access::checkAdmin();

        $user = new User();
        $users = $user->model->getAll();

        Output::render('admin', $users);
    }

    public function usersList(): void
    {
        Access::checkAdmin();

        $user = new User();
        $users = $user->model->getAll();

        Output::render('users', $users);
    }
    public function isAdmin(int $id) : bool
    {        
        $role=$this->model->isAdmin($id);
        if ($role==1){
            return true;
        }
        else return false; 
    }

    /**
     * @param int $id
     * @param int $roleid
     * @return bool
     */
    public function hasRole(int $id, int $roleid): bool
    {
        return $this->model->hasRole($id, $roleid);
    }

    /**
     * @param int $id
     * @param int $roleid
     * @return void
     */
    public static function updateRole(int $id, int $roleid): void
    {
        $this->model->updateRole($id, $roleid);
    }

    /**
     * @param int $id
     * @param int $roleid
     * @return void
     */
    public function addRole(int $id, int $roleid): void
    {
        if (!$this->model->hasAnyRole($id)) {
            $this->model->addRole($id, $roleid);
        }
    }

    public function addDefaultRoleIfNone(int $iduser) : void{
        /*if($this->model->Checkifrole($iduser) < 1){
            $this->model->Insertrole($iduser,Role::INVITE);
        }
        */
    }



    public function exportProfile(int $id): void
    {        
        //var_dump("inside User exportProfiile fun");
        $user = $this->getForProfile($id);
        $userForProfile = self::formatForProfile($user);
        $userForProfile->format = 'json';

        //if ($render) {
            Output::render('exportProfile', $userForProfile);
        //} else {
           // echo json_encode($userForProfile);
        //}

    }


    /**
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function exportImage(int $id): void
    {
        $user = $this->getForProfile($id);
        $userForProfile = self::formatForProfile($user);

        Output::render('exportImage', $userForProfile);
    }


    protected function getForProfile(int $id): mixed
    {
        // récupération du record en DB correspondant à l'id fourni
        $user = $this->get($id);
        //var_dump($this);
        // Ajout de l'information sur le compte.
        // Pour ce faire, on instancie le Controller associé et on récupère les données depuis le Model
        // Afin de n'affecter que le montant, on extrait la propriété de l'objet du résultat via le chaînage de méthodes (method chaining) ou dans ce cas de propriété de classe
        $account = new Account();
        //$user->account = $account->model->getByField('userid', $user->id)->amount;
        //var_dump($account->model->getByField('userid', $user->id)->amount);//null value returned
        //var_dump($account->model->getByField('userid', $user->id));///returned false...this is the problem

       /* if ($user->colorid) {
            $color = new Color();
            $user->color = $color->model->getByField('idC', $user->colorid)->nameC;
        }*/
        return $user;
    }

/*
     * Formattage des données pour la vue profile
     *
     * @param object $user
     * @return object
     * @throws \Exception
     */
    protected function formatForProfile(object $user): object
    {
        // Clôner l'objet user en un nouvel objet pour l'affichage du profil, afin de différencier de l'objet à mettre à jour
        $userForProfile = clone $user;
        // Formattage des données
        unset($userForProfile->id);
        unset($userForProfile->password);
        
        if (!$userForProfile->admin) {
            $userForProfile->admin = 'Non';
        }
        if ($userForProfile->pays == 'Belge') {
            $userForProfile->created = date_format( new \DateTime($userForProfile->created),"d/m/Y H\hi");
            $userForProfile->lastlogin = date_format( new \DateTime($userForProfile->lastlogin),"d/m/Y H\hi");
        }
        // Redéfinition d'une nouvelle propriété de classe sur base d'une existante, et suppression de l'ancienne
        //$userForProfile->amount = $user->account . '€';
        unset($userForProfile->account);

        return $userForProfile;
    }

    /**
     * Gestion de la photo importée depuis le formulaire de création
     *
     * @param int $userid
     * @return mixed
     */
    protected function manageUploadedFile(int|object $user, bool $update = false): mixed
    {
        {
            if (!is_object($user)) {
                $user = $this->model->get($user);
            }
            // Si tmp_name est vide, vérifiez que la taille du fichier ne dépasse pas la valeur du paramètre définie sur le serveur
            
            if (!empty($_FILES['photo']['tmp_name']) &&
                !empty($_FILES['photo']['name']) &&
                is_uploaded_file($_FILES['photo']['tmp_name']) &&
                in_array($_FILES['photo']['type'], self::$images)
            ) {
                $photopath = 'image' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR;
                // Sous Windows, il est nécessaire de remplacer \ par /. Inutile sous les systèmes Unix
                $imagepath = getcwd() . DIRECTORY_SEPARATOR . $photopath;
                // Crée le path s'il n'existe pas
                if (!is_dir($imagepath)) {
                    mkdir($imagepath, 0755, true);
                }
           // Construction du path définitif du fichier
          
                $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $dest = $imagepath . $user->id . '.' . $ext;
                $url = $photopath . $user->id . '.' . $ext;
                // Déplacement du fichier temporaire vers son emplacement définitive
                $move = move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
                if ($move) {
                    // Dans le cas où l'url existe déjà et où l'extension ne correspond à l'url existante, on supprime l'image d'origine
                    if (!empty($user->image) && $url != $user->image && pathinfo($user->image, PATHINFO_EXTENSION) != $ext) {
                        $existing_file_image = ROOT_PATH . DIRECTORY_SEPARATOR . $user->image;
                        if (file_exists($existing_file_image)) {
                            unlink($existing_file_image);
                        }
                    }
                    $user->image = $url;
                    if ($update) {
                        return $this->model->update($user);
                    }
                } else {
                    throw new \Exception(Text::getString(['Upload file error', 'Erreur de téléchargement du fichier']));
                }
            } else {
                $output_txt = [
                    'File error.',
                    'Le fichier a été refusé.',
                ];
                if (!empty($_FILES['photo']['type']) && !in_array($_FILES['photo']['type'], self::$images)) {
                    $output_txt[0] .= ' File extension must be ' . implode(' ou ', self::$images) . '.';
                    $output_txt[1] .= ' Le format de fichier doit être ' . implode(' ou ', self::$images) . '.';
                }
                if ($_FILES['photo']['error']) {
                    switch ($_FILES['photo']['error']) {
                        case UPLOAD_ERR_OK:
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $output_txt[0] .= ' No file sent.';
                            $output_txt[1] .= ' Aucun fichier envoyé.';
                            break;
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $output_txt[0] .= ' File size must be lower than ' . Helper::getMaxFileSizeHumanReadable() . '.';
                            $output_txt[1] .= ' La taille du fichier doit être inférieure à ' . Helper::getMaxFileSizeHumanReadable() . '.';
                            break;
                        default:
                            $output_txt[0] .= ' Unknown error.';
                            $output_txt[1] .= ' Erreur inconnue.';
                    }
                }
                throw new \Exception(Text::getString($output_txt));
            }
            return $user->image;
        }
    }    
}
