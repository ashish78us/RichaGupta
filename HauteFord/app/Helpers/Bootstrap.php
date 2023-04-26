<?php

namespace app\Helpers;
use app\Controllers\Course;
use app\Controllers\User;
use app\Controllers\Formation;
use app\Controllers\Demand;
use stdClass;

class Bootstrap
{
    /**
     * Affichage d'un tableau HTML sur base d'un objet ou d'un array de données
     *
     * @param array|object $data    les données à afficher
     * @param array|object $cols    l'entête des colonnes
     * @param string $title         l'éventuel titre du tableau
     * @param string $class         les éventuelles classes css du tableau
     * @return string
     */
    public static function table(array|object $data, array|object $cols, string $title = '', string $class = ''): string
    {
        $thead = '';
        $tbody = '';
        if (is_array($cols) || is_object($cols)) {
            foreach ($cols as $th) {
                $thead .= '<th>' . $th . '</th>';
            }
        } else {
            $thead = '<th>' . $cols . '</th>';
        }
        if (is_array($data) || is_object($data)) {
            foreach ($data as $sub) {
                $tbody .= '<tr>';
                if (is_array($sub) || is_object($sub)) {
                    foreach ($sub as $value) {
                        $tbody .= '<td>' . $value . '</td>';
                    }
                } else {
                    $tbody .= '<td>' . $sub . '</td>';
                }
                $tbody .= '</tr>';
            }
        } else {
            $tbody .= '<tr><td>' . $data . '</td></tr>';
        }

        return '<h2>' . $title . '</h2><table class="table table-striped ' . $class . '"><thead><tr>' . $thead . '</tr></thead><tbody>' . $tbody . '</tbody></table>';
    }

    /**
     * Récupère les options d'un select de formulaire HTML sur base d'un objet
     *
     * @param object $options
     * @param string $selected
     * @return string
     */
    public static function getFormOptions(object|array $options, string $selected = ''): string
    {
        $output = '';
        foreach ($options as $key => $value) {
            if ($key == $selected) {
                $attr = 'selected';
            } else {
                $attr = '';
            }
            $output .= '<option value="' . $key . '" ' . $attr . '>' . $value . '</option>';
        }
        return $output;
    }

    /**
     * Générateur de la vue profile
     *
     * Appelé par la méthode profile de la classe Bootstrap, qui est elle-même appelée par la méthode render de la classe Output
     *
     * @see Bootstrap::profile()
     * @see Output::render()
     * @param object $data      l'objet contenant les données à afficher dans le tableau
     * @param string $class     l'éventuelle classe css du tableau
     * @return string
     */
  
    protected static function createTableTwoColumn(String $key, String $value){
        return '<tr><th>' . Text::getStringFromKey($key) . '</th><td>' . $value . '</td></tr>';
    }
    
     public static function profile(object $data, string $class = ''): string
    {
        if ($data->admin==1)
        {$data->admin="Yes";}
        else {$data->admin="No";}
        $tbody = '';
        foreach ($data as $key => $value) {
            if ($key == 'image') {
                if (!empty($value)) {
                    $value = '<div class="row">
                            <div class="col-6">
                                <img src="' . $value . '?' . time() . '" alt="photo" class="d-block img-fluid" id="profile-photo">
                                <br><a href="api/route/user/exportImage/' . $_SESSION['userid'] . '">Exporter l\'image</a>
                            </div>
                          </div>';
                }
            }
            $tbody .= '<tr><th>' . Text::getStringFromKey($key) . '</th><td>' . $value . '</td></tr>';
        }
        //var_dump(Demand::getDemand()->listCoursesForUserInProfile());
        return '<h2>' . Text::getStringFromKey('profile') . '</h2>
                <a href="index.php?view=api/user/exportProfile/' . $_SESSION['userid'] . '" class="btn btn-sm btn-primary">Exporter</a>
                <table class="table ' . $class . '">' . $tbody . '</table>';
    }
    

    public static function exportProfile(object $data): void
    {
       // var_dump("inside bootstrap exportProfiile");
        $filename = $data->username . '_' . time() . '.' . $data->format;
        // Envoi des headers HTTP au browser pour le téléchargement du fichier.
        //header('Content-type: application/json');
       // header('Content-disposition: attachment; filename="' . $filename . '"');
        // output du contenu au format json
        if ($data->format == 'json') {
            unset($data->format);
            echo json_encode($data);
        } else {
            unset($data->format);
            foreach ($data as $key => $value) {
                echo $key .' : '. $value . "\n";
            }
        }
    }


    /**
     * @param object $data
     * @return void
     */
    
    
    public static function exportImage(object $data): void
    {
        $path = ROOT_PATH . DIRECTORY_SEPARATOR . $data->image;

        header('Content-type: image');
        header('Content-disposition: attachment; filename="' . $data->image . '"');
        echo file_get_contents($path);

    }
    
    public static function exportCourseList(array $data): void
    {
        //php trick : convertir un tableau d'objets en tableau associatif (attention, c'est relativement lent comme process)
        $data = json_decode(json_encode($data), true);
        $filename = 'courses_list_' . time() . '.csv';
        // ouverture du flux (en mode écriture)
//        $ressource = fopen($filename, 'w');
//        foreach ($data as $course) {
//            // ajout d'une ligne au format csv
//            fputcsv($ressource, $course);
//        }
//        // fermeture du flux
//        fclose($ressource);

        // Envoi des headers HTTP au browser pour le téléchargement du fichier.
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        // output du contenu du fichier créé
        // echo file_get_contents($filename);
        // suppression du fichier créé sur le serveur
        // unlink($filename);

        // alternative sans écriture sur le disque
        header('Cache-Control: no-store');
        $buffer = fopen('php://output', 'r+');
        foreach ($data as $course) {
            fputcsv($buffer, $course);
        }
        fclose($buffer);

    }




    /**
     * @param object $data
     * @return string
     */
    public static function profileUpdate(object $data): string
    {
        $pays = new stdClass();
        $pays->Belge = 'Belge';
        $pays->Candian = 'Candian';
        $pays->Indian = 'Indian';
        $pays->Russian = 'Russian';
        $pays->Italian = 'Italian';
        $pays->German = 'German';

        return '<hr>
                 
                 
                    <form action="index.php?view=api/user/update" method="post" enctype="multipart/form-data">
                    <div class="container">
                    <h1 class ="form-title"> Update_Profile</h1> 
                   <div class="main-user-info">
                        <input type="hidden" id="uu-userid" name="id" value="' . $data->id . '">
                        <div class ="formation-input-box">
                        <label for="uu-password">' . Text::getStringFromKey('password') . '</label>
                        <input type="password" id="uu-password" name="password" class="form-control" placeholder="Pour changer votre mot de passe, cliquez ici">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-nom">' . Text::getStringFromKey('nom') . '</label>
                        <input type="text" id="uu-nom" name="nom" class="form-control" value="' . $data->nom . '">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-prenom">' . Text::getStringFromKey('prenom') . '</label>
                        <input type="prenom" id="uu-prenom" name="prenom" class="form-control" value="' . $data->prenom . '">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-email">' . Text::getStringFromKey('email') . '</label>
                        <input type="email" id="uu-email" name="email" class="form-control" value="' . $data->email . '">
                        </div>
                        <div class ="formation-select-box">
                        <label for="uu-pays">' . Text::getStringFromKey('pays') . '</label>
                        <select name="pays" id="uu-pays" class="form-control">
                            ' . self::getFormOptions($pays, $data->pays) . '
                        </select>
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-birthdate">' . Text::getStringFromKey('birthdate') . '</label>
                        <input type="date" id="uu-birthdate" name="birthdate" class="form-control" value="' . $data->birthdate . '">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-phone">' . Text::getStringFromKey('phone') . '</label>
                        <input type="tel" id="uu-phone" name="phone" class="form-control" value="' . $data->phone . '">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-address">' . Text::getStringFromKey('address') . '</label>
                        <input type="text" id="uu-address" name="address" class="form-control" value="' . $data->address . '">
                        </div>
                        <div class ="formation-input-box">
                        <label for="uu-photo">' . Text::getStringFromKey('photo') . '</label>
                        <p><input type="file" id="uu-photo" name="photo"></p>
                        </div>
                        <div class ="form-submit-btn">
                        <input type="submit" class="btn btn-primary" value="' . Text::getStringFromKey('submit') . '">
                        </div>
                    
                    </div>
                    </form>';
    }
    public static function profileCourses(array $courses): string
    {
        $body = '';
        if (!empty($_SESSION['userid'])) {
            $exportlink = '<a href="api/route/user/exportCourseList/' . $_SESSION['userid'] . '" class="btn btn-sm btn-primary">Exporter</a>';
        } else {
            $exportlink = '';
        }
        foreach ($courses as $row) {
            $body .= '<tr>';
            foreach ($row as $key => $value) {               
                if ($key == 'det') {
                    $value = Text::yesOrNo($value);
                } elseif ($key == 'courseid') {
                    continue;
                }
                elseif ($key == 'username') {
                    continue;
                }               

                $body .= '<td>' . $value . '</td>';
            }
            $body .= '</tr>';
        }
        return '<hr><h2><a href="#profile-courses-list" data-bs-toggle="collapse" role="button" class="text-decoration-none">' . Text::getStringFromKey('courses') . '</a></h2>
                <table class="table table-striped collapse" id="profile-courses-list">
                    <thead>
                        <tr>
                            <th>' . Text::getString(['formation name', 'formation name']) . '</th>
                            <th>' . Text::getString(['course name', 'course name']) . '</th> 
                            <th>' . Text::getString(['periods', 'périodes']) . '</th>
                            <th>' . Text::getString(['determining', 'déterminant']) . '</th>
                            <th>' . Text::getString(['prerequisite', 'prérequis']) . '</th>
                            <th>' . Text::getString(['teacher', 'professeur']) . '</th> 
                            <th>' . Text::getString(['status', 'status']) . '</th> 
                            <th>' . Text::getString(['Date de Inscription', 'Date de Inscription']) . '</th> 
                            <th>' . Text::getString(['Role', 'Role']) . '</th>                                                     
                        </tr>
                    </thead>
                    <tbody>
                        ' . $body . '
                    </tbody>
                </table>' . $exportlink;
    }
    
    
    public static function filter_courses(){
        return '<h1>Course</h1>
<form action="" method="post">
    <div class="container">
       <div class="cour-group">
                <label for="formation">Formation</label>
                <input type="text" id="formation" name="formation" class="cour-group" value="">
                </div>
            </div>
            <div class="cour-group">
                <label for="course">Course</label>
                <input type="text" id="course" name="course" class="cour-group" value="">
                </div>
            </div>
            <div class="form-check">
         <input class="form-check-input" name="status" type="radio"  id="active" class="form-control" value="active"> 
    <label class="form-check-label" for="active">Active</label>
</div>
<div class="form-check">
         <input class="form-check-input" name="status" type="radio" id="inactive" class="form-control" value="inactive"> 
    <label class="form-check-label" for="inactive">Inactive</label>
</div>
           <button type="submit" class="btn btn-primary">Create</button>
        </form>';
   
    }


    public static function User_Listformation(array $data):string{           

    $modal1 = self::viewModal('modal-formation-list', Text::getString(['formation detail', 'détail formation']), '' , '', 'lg');
    $modal2 = "";    
    $userObj = new User();
    $user = Demand::getUserByid();
    $userType = $userObj->isAdmin($user->id);
    $body = '<tr>';
        foreach ($data as $row) {
             foreach ($row as $key => $value) { 
                if ($key == 'id') {
                    $id=$value;      
                }           
                if ($value) {
                    $body .= '<td>' . $value . '</td>';                     
                 } 
            }           
            $createDemandBody = self::demande_formdisplay($user,Demand::getFormationName($id),$userType,"ListFormation");
            $demandStatus =Demand::getDemandStatusIfExist($id);            
            $modalid = str_replace(' ', '', Demand::getFormationName($id));
            if (($demandStatus == "Pending" || $demandStatus == "Inscrit" || $demandStatus == "Refuse") && !($userType)) {$modalid = "#"; }
            
            $modal2 .= self::viewModal($modalid, Text::getString(['Demand Request', 'Demand Request']), $createDemandBody , '', 'lg');
            $buttonClass="button";
            if($demandStatus == "Refuse"){$buttonClass="buttonred";}
            $demandModal = self::linkModalButton($modalid, $demandStatus ,$buttonClass); 
            $body .='<td>'. $demandModal .'</td>';            
            $body .= '</tr>';
           
        }   
         return '<h2>' . Text::getStringFromKey('formation') . '</h2>
        <table class="table table-hover table-dark table-striped table-responsive table-borderless table-dt" id="formation-list">
            <thead>
                <tr>
                    <th>id</th>
                    <th>' . Text::getStringFromKey('name') . '</th>
                    <th>' . Text::getString(['niveau_etude', 'niveau_etude']) . '</th>
                    <th>' . Text::getString(['status', 'status']) . '</th>
                    <th>' . Text::getString(['date_debut', 'date_debut']) . '</th>
                    <th>' . Text::getString(['date_fin', 'date_fin']) . '</th>
                    <th>' . Text::getString(['demande', 'demande']) . '</th>  
                </tr>
            </thead>
            <tbody>    
                ' . $body . '
            </tbody>
        </table>' . $modal1 . $modal2 ; 
    }

    public static function demande_formdisplay($data,$formationame,$admin,$dmdid = ""){
        if ($admin && $dmdid!="ListFormation"){
            $heading = "<h4>Action on Demande</h4>";
            $Button ="<button type=\"submit\" class=\"button\" name=\"DemandAction\" value=\"Inscrit\">Inscrit</button>";
            $Button .="<button type=\"submit\" class=\"buttonred\" name=\"DemandAction\" value=\"Refuse\">Refuse</button></div>";
            $demandidtext = '<div class="demande-group">
            <label for="demandId">Demand Id</label>
            <input type="text" id="demandId" name="demandId" class="demande-group" value="' . $dmdid . '">
            </div>
        ';

        }
        else {
            $demandidtext="";
            $heading = "<h4>Create Demande</h4>";
            $Button ="<button type=\"submit\" class=\"btn btn-primary\" name=\"DemandAction\" value=\"Create\">Create Request</button></div>";
        }


        return  $heading . '
    <form action="index.php?view=api/Demand/create/" method="post">
    '.$demandidtext. '
            <div class="container">       
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="demande-group" value="' . $data->username . '">
                <label for="prenom">Prenom</label>
                <input type="text" id="prenom" name="prenom" class="demande-group" value="' . $data->prenom . '">
                </div>
            <div class="demande-group">
            <label for="formationame">Formation Name</label>
            <input type="text" id="formationame" name="formationame" size="50" class="demande-group" value="' . $formationame . '">
            </div>
            <div class="demande-group">
            
           '.$Button.'
        </form>';
   
    }

    public static function Admin_Listdemande(array $data){
        //$modal1 = self::viewModal('modal-demand-adminlist', Text::getString(['demand details', 'demand detail']), '' , '', 'lg');
    $modal2 = "";
    $userObj = new User();
    //$user = $user->model->getByField('username',$_COOKIE['coo_username']);
            //var_dump($user);
            $user = Demand::getUserByid();
            $userType = $userObj->isAdmin($user->id);
       $body = '<tr>';

        foreach ($data as $row) {
            
            $formationName="";            
            foreach ($row as $key => $value) {                
                
                if ($key == 'id') {
                    $id=$value;                                     
                    
                } 
                if ($key == 'userid') {
                    $userid=$value;
                    $user = new Demand(); 
                    $user = $user->getUserByid2($userid) ;                                  
                    $value = $user->username;
                } 

                if ($key == 'formationid') {
                    $formationid=$value;                                                      
                    $formation = Formation::getFormationByField("id", $formationid);
                    $value = $formation->name;
                    $formationName = $value;
                    $value .= '<td>' . $formation->niveau_etude . '</td>';
                    $value .= '<td>' . $formation->status . '</td>';
                    $value .= '<td>' . $formation->date_debut . '</td>';
                    $value .= '<td>' . $formation->date_fin . '</td>';
                } 
                if ($key == 'status') {
                    
                //$value='<button onclick="index.php?view=api/formation/formationListforUser" class="button">' .$value. '</a>';
                $createDemandBody = self::demande_formdisplay($user,$formationName,$userType,$id);
                $demandStatus =(Demand::getDemandById($id))->status; 
                $buttonClass = "button";  
                if ($demandStatus == "Refuse") {$buttonClass = "buttonred";}            
                $modalid = str_replace(' ', '', $formationName);
                $modalid.= $user->username;
                
                $modal2 .= self::viewModal($modalid, Text::getString(['Demand Request', 'Demand Request']), $createDemandBody , '', 'lg');
                $value = self::linkModalButton($modalid, $demandStatus ,$buttonClass);  
                }                 

                if ($value) {
                    $body .= '<td>' . $value . '</td>';                     
                 }  

            }
            
            $body .= '</tr>';
        }        
       
        
    //include_once ROOT_PATH . '/view/admin/Formation.html';
    return '<h2>' . Text::getStringFromKey('demand list') . '</h2>
        <table class="table table-hover table-dark table-striped table-responsive table-borderless table-dt" id="demand-list">
            <thead>
                <tr>
                    <th>id</th>                    
                    <th>' . Text::getStringFromKey('User') . '</th>
                    <th>' . Text::getString(['Formation Name', 'Formation Name']) . '</th>
                    <th>' . Text::getString(['niveau_etude', 'niveau_etude']) . '</th>
                    <th>' . Text::getString(['status', 'status']) . '</th>
                    <th>' . Text::getString(['date_debut', 'date_debut']) . '</th>
                    <th>' . Text::getString(['date_fin', 'date_fin']) . '</th>
                    <th>' . Text::getString(['demande status', 'demande status']) . '</th>
                </tr>
            </thead>
            <tbody>    
                '  . $body . '
            </tbody>
        </table>'  . $modal2 ;       

    }


    

    

       
     
  


    
    
   
    public static function courses(array $data): string
    {
        // déclaration de la variable, comme une string vide, destinée à contenir le body du tableau HTML
        $body = '';
        $modal = '';
        // boucles foreach imbriquées
        // la première parcourt le tableau d'objets $data afin d'en extraire chaque objet, correspondant à une ligne du tableau HTML ($row)
        foreach ($data as $row) {
            $course = new Course();
            // concaténation de la balise HTML <tr> dans la variable $body, représentant le début d'une ligne dans le tableau HTML
            $body .= '<tr>';
            // seconde boucle pacourant chaque objet du tableau d'objets $data
            foreach ($row as $key => $value) {
                // concaténation de chaque élément (propriété) de l'objet au sein d'une balise HTML <td> représentant une cellule du tableau HTML
                if ($key == 'det') {
                    $value = Text::yesOrNo($value);
                } elseif ($key == 'courseid') {
                    continue;
                }
                $body .= '<td>' . $value . '</td>';
            }
            if (!$course->getEnrol($row->courseid, $_SESSION['userid'])) {
                $body .= '<td><a href="index.php?view=api/course/enrol/' . $row->courseid . '/' . $_SESSION['userid'] . '" class="btn btn-sm btn-success">+</a></td>';
            } else {
                $body .= '<td>Déjà inscrit</td>';
            }
            $body .= '</tr>';
            // concaténation de la balise fermante HTML <tr> dans la variable $body, représentant la fin d'une ligne dans le tableau HTML
        }
        // la fonction retourne le code HTML complet (représentant la vue)
        // le tableau HTML reçoit diverses classes du Framework CSS Bootstrap, ainsi qu'une classe CSS custom "table-dt" associée à la variable de session "lang", formant ainsi 3 nouvelles classes CSS : table-dt, table-dten et table-dtfr
        // ces nouvelles classes CSS doivent être définies dans le projet, elles le sont dans le fichier js/main.js
        // la balise <table> reçoit un attribut id (unique) permettant au Javascript et au CSS de pouvoir le cibler facilement dans le DOM
        return '<h2>' . Text::getStringFromKey('courses') . '</h2>
        <form action="index.php?view=api/course/list_filtered" method="post">
                <div class="container">
                
                <div class="cour-group">
                <label for="formation">Search</label>
               <input type="text" id="formation" name="Search" class="cour-group" value="">
                </div>
                    
                 </div>
                 <button type="submit" class="btn btn-primary">Submit</button>
                 </form>
                 <div class="cour-list">
                <table class="table table-striped table-dt' . $_SESSION['pays'] . '" id="courses-list">                
                    <thead>
                        <tr>
                            <th>' . Text::getString(['formation', 'formation']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['course', 'cours']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['periods', 'périodes']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['determining', 'déterminant']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['prerequisite', 'prérequis']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['teacher', 'professeur']) . '<span class="icon-arrow">&UpArrow;</span></th>
                            <th>' . Text::getString(['enrol', 'inscrire']) . '<span class="icon-arrow">&UpArrow;</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $body . '
                    </tbody>
                </table></div>' . $modal;

        // Alternative utilisant la méthode générique de la classe Bootstrap. Plus court à écrire, mais moins flexible et ne gérant pas les traductions.
        // return self::table($data, array_keys(get_object_vars($data[0])), 'Courses');
    }

    public static function course_list(array $data, String $message = ''): string
    {
        //var_dump("Inside Boot and List_formation");
       $modal = self::viewModal('modal-course-list', Text::getString(['course detail', 'détail course']), '', '', 'lg');
       $body = '<tr>';
        foreach ($data as $row) {
            
            //$formation = new Formation();            
            foreach ($row as $key => $value) {
                
                if ($key == 'name') {
                    $value = self::linkModal('modal-course-list', $value, 'formation-course-link'); 
                    //var_dump($value);                  
                    
                }  
                if ($key == 'id') {
                    $id=$value; 
                    //var_dump($value);
                    //var_dump($id);                  
                    
                }           
                if ($value) {
                    $body .= '<td>' . $value . '</td>';                     
                 }  

            }
            $hyper_link ="<a class=\"btn btn-primary\" role=\"button\" href=\"index.php?view=api/Course/update/";
            $hyper_link .= $id;
            //var_dump("row=".$row);
            $hyper_link .="\">UPDATE</a>";
            //var_dump($hyper_link);

            $body .= '<td>' . $hyper_link .'</td>'; 
            //$hyper_link = '<a href=\"index.php?view=api/Formation/delete/" onclick="(confirmDelete('.$id.'})" class="btn btn-primary btn-sm" role="button">Delete</a>';
            $hyper_link = "<a class=\"btn btn-primary\" role=\"button\" onclick=\"confirmDeleteCourse(";
            $hyper_link .= $id;
            $hyper_link .=")\">Delete</a>";
            $body .='<td>'. $hyper_link .'</td>'; 
            
            
										
          $body .= '</tr>';
            //var_dump($body);
        }        
        
        //var_dump($body);
    //include_once ROOT_PATH . '/view/admin/menu.html';
    return 
    
    '<h2>' . Text::getStringFromKey('course') . '</h2>
        <table class="table table-striped table-dt" id="course-list">
            <thead>
                <tr>
                    <th>id</th>
                    <th>' . Text::getStringFromKey('name') . '</th>
                    <th>' . Text::getString(['code', 'code']) . '</th>
                    <th>' . Text::getString(['status', 'status']) . '</th>
                    
                    
                </tr>
            </thead>
            <tbody>    
                ' . $body . '
            </tbody>
        </table>' . $modal;       

    }
    public static function users(array $data): string
    {
        $body = '';
        $modal_footer = '<div class="d-none">
                             <span id="m-uid">' . $_SESSION['userid'] . '</span>
                             <span id="m-tokenu">' . Access::generateToken('modal-user-token', $_SESSION['userid']) . '</span>
                         </div>';
        $modal = self::viewModal('modal-user-list', Text::getString(['user detail', 'détail utilisateur']), '', $modal_footer, 'lg');
        foreach ($data as $row) {
            $user = new User();
            $body .= '<tr>';
            foreach ($row as $key => $value) {
                if ($key == 'username') {
                    $value = self::linkModal('modal-user-list', $value, 'user-modal-link');
                } elseif ($key == 'image') {
                    $value = '';
                }
                if ($value) {
                    $body .= '<td>' . $value . '</td>';
                }
            }
            $body .= '</tr>';
        }
        include_once ROOT_PATH . '/view/admin/menu.html';
        return '<h2>' . Text::getStringFromKey('user', true, 2, 's') . '</h2>
                <table class="table table-striped table-dt' . $_SESSION['pays'] . '" id="users-list">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>' . Text::getStringFromKey('username') . '</th>
                            <th>' . Text::getString(['nom', 'nom']) . '</th>
                            <th>' . Text::getString(['prenom', 'prenom']) . '</th>
                            <th>' . Text::getString(['email', 'email']) . '</th>
                            <th>' . Text::getStringFromKey('pays') . '</th>
                            <th>' . Text::getString(['birthdate', 'birthdate']) . '</th>
                            <th>' . Text::getString(['phone', 'phone']) . '</th>
                            <th>' . Text::getString(['address', 'address']) . '</th>
                            <th>' . Text::getStringFromKey('created') . '</th>
                            <th>' . Text::getStringFromKey('lastlogin') . '</th>
                            
                            <th>' . Text::getString(['role', 'role']) . '</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $body . '
                    </tbody>
                </table>' . $modal;
    }
    
    public static function List_formation(array $data): string
    {        
       $modal = self::viewModal('modal-formation-list', Text::getString(['formation detail', 'détail formation']), '', '', 'lg');
       $body = '<tr>';
        foreach ($data as $row) {             
            foreach ($row as $key => $value) { 
                if ($key == 'id') {
                    $id=$value;        
                }           
                if ($value) {
                    $body .= '<td>' . $value . '</td>';                     
                 } 

            }
            $hyper_link ="<a class=\"btn btn-primary\" role=\"button\" href=\"index.php?view=api/Formation/updateDisplay/";
            $hyper_link .= $id;            
            $hyper_link .="\">Update</a>";           

            $body .= '<td>' . $hyper_link .'</td>'; 
            //$hyper_link = '<a href=\"index.php?view=api/Formation/delete/" onclick="(confirmDelete('.$id.'})" class="btn btn-primary btn-sm" role="button">Delete</a>';
            $classname = ",Formation";
            $functionname = ",delete";
            //var_dump($classname.$id);            
            $hyper_link = "<a class=\"btn btn-primary\" role=\"button\" onclick=\"confirmDelete(";
            $hyper_link .= $id;
            //$hyper_link .= $classname;
            //$hyper_link .= $functionname;
            $hyper_link .=")\">Delete</a>";
            $body .='<td>'. $hyper_link .'</td>';            
            $body .= '</tr>';
            //var_dump($body);
        }        
        
        //var_dump(ROOT_PATH);
    //include_once ROOT_PATH . '/view/admin/Formation.html';
    return '<h2>' . Text::getStringFromKey('formation list') . '</h2>
        <table class="table table-striped table-dt" id="formation-list">
            <thead>
                <tr>
                    <th>id</th>
                    <th>' . Text::getStringFromKey('name') . '</th>
                    <th>' . Text::getString(['niveau_etude', 'niveau_etude']) . '</th>
                    <th>' . Text::getString(['status', 'status']) . '</th>
                    <th>' . Text::getString(['date_debut', 'date_debut']) . '</th>
                 <th>' . Text::getString(['date_fin', 'date_fin']) . '</th>
                    
                </tr>
            </thead>
            <tbody>    
                ' . $body . '
            </tbody>
        </table>' . $modal;       

    }
    
    public static function profileUserListIfAdmin(array $userList): string
    {
        $body = '';
        foreach ($userList as $row) {
            $body .= '<tr>';
            foreach ($row as $key => $value) {
                if ($key == 'det') {
                    $value = Text::yesOrNo($value);
                } elseif ($key == 'courseid') {
                    continue;
                }
                $body .= '<td>' . $value . '</td>';
            }
            $body .= '</tr>';
        }
        return '<hr><h2><a href="#profile-courses-list" data-bs-toggle="collapse" role="button" class="text-decoration-none">' . Text::getStringFromKey('User List') . '</a></h2>
                <table class="table table-striped collapse" id="profile-courses-list">
                    <thead>
                        <tr>
                            <th>' . Text::getString(['Name', 'identifiant']) . '</th>
                            <th>' . Text::getString(['mail', 'adresse-email']) . '</th>
                            <th>' . Text::getString(['inscription date', 'date inscription']) . '</th>
                            <th>' . Text::getString(['Language', 'langue']) . '</th>
                            <th>' . Text::getString(['Last connexion', 'Dernière connexion']) . '</th>

                        </tr>
                    </thead>
                    <tbody>
                        ' . $body . '
                    </tbody>
                </table>
                <a href="api/route/user/exportCourseList/' . $_SESSION['userid'] . '" class="btn btn-sm btn-primary">Exporter</a>';
    }


    /**
     * @param object $data
     */
    public static function usermodal(object $data): void
    {
        $output = '';
        if (!empty($data->user->image)) {
            $output .= '<div class="row">
                            <div class="col-6">
                                <img src="' . $data->user->image . '?' . time() . '" alt="photo" class="d-block img-fluid">
                            </div>
                          </div>';
        }
        if (!empty($data->courseslist)) {
            $output .= $data->courseslist;
        }
        echo $output;
    }
    public static function createCourse(array $data_formation, array $data_course)
    {
        include_once ROOT_PATH . '/view/admin/menu.html';
        return '<h2>Création d\'un nouveau cours</h2>
                <form action="index.php?view=api/Course_formation/create/" method="post">
                    <label for="cc-formation">Formation</label>
                    <select name="cc-formation" id="cc-formation" class="form-control">
                    ' . self::getFormOptions($data_formation) . '
                    </select>
                    <div class ="course-input-box">
                    <label for="cc-course">Course</label>
                    <select name="cc-course" id="cc-course" class="form-control">
                    ' . self::getFormOptions($data_course) . '
                    </select>                    
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>';
    }

    public static function createNewCourse()
    {
        include_once ROOT_PATH . '/view/admin/menu.html';
        return '<h1>Create Course</h1>
<form action="index.php?view=api/course/cr_new_course/" method="post">
    <div class="container">
       <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-group" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" id="code" name="code" class="form-group" value="">
                </div>
            </div>
            <div class="form-check">
         <input class="form-check-input" name="status" type="radio"  id="active" class="form-control" value="active"> 
    <label class="form-check-label" for="active">Active</label>
</div>
<div class="form-check">
         <input class="form-check-input" name="status" type="radio" id="inactive" class="form-control" value="inactive"> 
    <label class="form-check-label" for="inactive">Inactive</label>
</div>
           <button type="submit" class="btn btn-primary">Create</button>
        </form>';
   
    }


    public static function update_formation($data) {     

       $formationid=$data; 
       if ($data->status == 'Active'){
       $active="checked"; 
       $inactive="unchecked"; 
       }    
       else  
       {
        $active="unchecked"; 
        $inactive="checked"; 
    }

       return '<hr>
       
       <form action="index.php?view=api/formation/updateRow/' . $data->id  . '" method="post" enctype="multipart/form-data">
       
       <div class="cantainer">
       <h1 class ="form-title">Update_formation</h1> 
       <div class="main-formation-info">
     <input type="hidden" id="uu-formationid" name="id" value="' . $data->id . '">
     <div class ="formation-input-box">
        <label for="uu-name">' . Text::getStringFromKey('name') . '</label>
       <input type="name" id="uu-name" name="name" class="form-control" value="' . $data->name . '">
       </div>
       <div class ="formation-input-box">
       <label for="uu-niveau_etude">' . Text::getStringFromKey('niveau_etude') . '</label>
     <input type="text" id="uu-niveau_etude" name="niveau_etude" class="form-control" value="' . $data->niveau_etude . '">
               
     </div>
     <div class ="formation-input-box">
               <label for="uu-status">' . Text::getStringFromKey('status info') . '</label>
<div class="form-check">
         <input class="form-check-input" name="status" type="radio"  id="active" class="form-control" value="'.$active.'"' . $active . '> 
    <label class="form-check-label" for="active">Active</label>
</div>
<div class="form-check">
         <input class="form-check-input" name="status" type="radio" id="inactive" class="form-control" value="'.$inactive.'"'. $inactive.'> 
    <label class="form-check-label" for="inactive">Inactive</label>
</div>
</div>
<div class ="formation-input-box">
<label for="uu-date_debut">' . Text::getStringFromKey('date_debut') . '</label>
  <input type="date" id="uu-date_debut" name="date_debut" class="form-control" value="' . $data->date_debut . '">
  </div>
  
  <div class ="formation-input-box">
  <label for="uu-date_fin">' . Text::getStringFromKey('date_fin') . '</label>
  <input type="date" id="uu-date_fin" name="date_fin" class="form-control" value="' . $data->date_fin . '">
               </div>
               <div class ="form-submit-btn">
               <input type="submit" class="btn btn-primary" value="Valider">
               </div>
               
                    </form>
                    </div>';

    } 
    
    public static function update_course($data) {     

        $formationid=$data; 
        if ($data->status == 'Active'){
        $active="checked"; 
        $inactive="unchecked"; 
        }    
        else  
        {
         $active="unchecked"; 
         $inactive="checked"; 
     }
 
        return '<hr>
        
        <form action="index.php?view=api/course/update_row/' . $data->id  . '" method="post" enctype="multipart/form-data">
        
        <div class="cantainer">
        <h1 class ="form-title">Update_course</h1> 
        <div class="main-formation-info">
      <input type="hidden" id="uu-formationid" name="id" value="' . $data->id . '">
      <div class ="formation-input-box">
         <label for="cc-name">' . Text::getStringFromKey('name') . '</label>
        <input type="name" id="cc-name" name="cc-name" class="form-control" value="' . $data->name . '">
        </div>
        <div class ="formation-input-box">
        <label for="cc-code">' . Text::getStringFromKey('code') . '</label>
      <input type="text" id="cc-code" name="cc-code" class="form-control" value="' . $data->code . '">
                
      </div>
      <div class ="formation-input-box">
                <label for="cc-status">' . Text::getStringFromKey('status info') . '</label>
 <div class="form-check">
          <input class="form-check-input" name="status" type="radio"  id="active" class="form-control" value="'.$active.'"' . $active . '> 
     <label class="form-check-label" for="active">Active</label>
 </div>
 <div class="form-check">
          <input class="form-check-input" name="status" type="radio" id="inactive" class="form-control" value="'.$inactive.'"'. $inactive.'> 
     <label class="form-check-label" for="inactive">Inactive</label>
 </div>
 </div>   
   
                <div class ="form-submit-btn">
                <input type="submit" class="btn btn-primary" value="Valider">
                </div>
                
                     </form>
                     </div>';
 
     } 



    

    /**
     * Vue générique pour les messages dans une div utilisant la classe d'alerte bootstrap
     *
     * @see Output::render()
     * @param string $message
     * @param string $class     Débute par la classe Bootstrap associée à la couleur de l'alerte (danger, info, success, warning)
     * @return string
     */
    public static function messageBox(string $message, string $class = 'danger'): string
    {
        return '<div class="alert alert-' . $class . '">' . $message . '</div>';
    }
    /**
     * @param string $content
     * @param string $type
     * @param bool $dismiss
     * @return string
     */
    public static function alert(string $content, string $type = 'success', bool $dismiss = false): string
    {
        if ($dismiss) {
            return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              ' . $content . '
            </div>';
        } else {
            return '<div class="alert alert-' . $type . '">' . $content . '</div>';
        }
    }


    /**
     * @param string $link
     * @param string $caption
     * @param string $class
     * @return string
     */
    public static function linkBtn(string $link, string $caption, string $class = 'default'): string
    {
        return '<a href="' . $link . '" class="btn btn-' . $class . '">' . $caption . '</a>';
    }

    /**
     * @param string $link
     * @param string $caption
     * @param string $target
     * @return string
     */
    public static function link(string $link, string $caption, string $target = 'default'): string
    {
        return '<a href="' . $link . '" class="lien" target="' . $target . '">' . $caption . '</a>';
    }

    /**
     * Bouton Modal bootstrap
     *
     * @param string $target_id
     * @param string $btn_text
     * @param string $btn_class
     * @return string
     */
    public static function btnModal(string $target_id, string $btn_text, string $btn_class = 'primary'): string
    {
        return '<button type="button" class="btn btn-' . $btn_class . '" data-bs-toggle="modal" data-bs-target="#' . $target_id . '">' . $btn_text . '</button>';
    }

    /**
     * Link Modal bootstrap
     *
     * @param string $target_id
     * @param string $caption
     * @param string $class
     * @return string
     */
    public static function linkModal(string $target_id, string $caption, string $class = ''): string
    {
        return '<a href="#" class="link ' . $class . '" data-bs-toggle="modal" data-bs-target="#' . $target_id . '">' . $caption . '</a>';
    }
    public static function linkModalButton(string $target_id, string $caption, string $buttonClass): string
    {
        return '<a href="#" class="'.$buttonClass.'" data-bs-toggle="modal" data-bs-target="#' . $target_id . '">' . $caption . '</a>';
    }
    
    /**
     * Générateur de Modal bootstrap
     *
     * @param string $modal_id
     * @param string $modal_title
     * @param string $modal_body
     * @param string $modal_footer
     * @param string $modal_size
     * @return string
     */
    public static function viewModal(string $modal_id, string $modal_title, string $modal_body, string $modal_footer = '', string $modal_size = ''): string
    {
        if ($modal_size == 'lg') {
            $modal_size = ' modal-lg';
        } elseif ($modal_size == 'hg') {
            $modal_size = ' modal-hg';
        }
        return '<div class="modal fade" tabindex="-1" id="' . $modal_id . '" aria-hidden="true">
          <div class="modal-dialog' . $modal_size . '">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">' . $modal_title . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="' . $modal_id . '-body">
                ' . $modal_body . '
              </div>
              <div class="modal-footer">
                ' . $modal_footer . '
              </div>
            </div>
          </div>
        </div>';
    }

    /**
     * @param string $title
     * @param string $text
     * @param string $footer
     * @param string $style
     * @param string $class
     * @param string $id
     * @return string
     */
    public static function showCard(string $title, string $text, string $footer = '', string $style = '', string $class = '', string $id = ''): string
    {
        if ($footer) {
            $footer = '<div class="card-footer">' . $footer . '</div>';
        }
        return '<div class="card card-war ' . $class . '" style="' . $style . '" id="' . $id . '">
                <div class="card-header">' . $title . '</div>
                <div class="card-body">' . $text . '</div>
                ' . $footer . '
            </div>';
    }

}
