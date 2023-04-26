<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;

class Demand extends Controller
{    
     protected static Demand $obj_demand;
     public static function getDemand(): Object {
         if (!isset(self::$obj_demand)) {
         self::$obj_demand = new Demand();
         }
         return self::$obj_demand;
     }   
     public static function formdisplay($id): void
     {          
          $user = new User();
          $user = $user->model->getByField('username',$_COOKIE['coo_username']);          
          $formation = Formation::getFormation();
          $formation = $formation->model->getByField('id',$id);
          Output::render('demande_formdisplay',$user, $formation->name);
     } 
     public static function getUserByid(){
          $user = new User();
          $user = $user->model->getByField('username',$_COOKIE['coo_username']);
          return $user;
     }  
     public static function getUserByid2($id){
          $user = new User();
          $user = $user->model->getByField("id",$id);
          return $user;
     }
     public static function getFormationName($id){
          $formation = Formation::getFormation();
          $formation = $formation->model->getByField('id',$id);
          return $formation->name;
     } 

     public static function getDemandStatusIfExist($formationid): String
     {          
          $user = self::getUserByid();
          $demand = self::getDemand();          
          $demandStatus = $demand->model->findDemand($formationid,$user);          
          return $demandStatus;

     } 
     public static function getDemandById($demandid)
     {          
          $user = self::getUserByid();
          $demand = self::getDemand();         
          $demandid = $demand->model->getByField("id", $demandid);          
          return $demandid;
     } 
    
     public static function create(): void
     {          
          if (!$_POST) {
               header('HTTP/1.1 405');
           }
           if (!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['formationame']) && !empty($_POST['DemandAction']
           && $_POST['DemandAction'] == "Create" ))
           {
               $demand_data = [ 
               'userid' => self::getUserIdByName($_COOKIE['coo_username']),
               'formationid' => self::getFormationIdByName($_POST['formationame']),               
               'status' => "Pending"  ];                   
               $demand = self::getDemand();               
               $demand = $demand->model->createAny("Demand",$demand_data);
               header('Location: index.php?view=api/formation/formationListforUser');
               
           }
           else if(!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['formationame']) && !empty($_POST['DemandAction']
           && ( $_POST['DemandAction'] == "Inscrit" ) || $_POST['DemandAction'] == "Refuse") )
           {
               $demand_data = [ 
                    'userid' => self::getUserIdByName($_COOKIE['coo_username']),
                    'formationid' => self::getFormationIdByName($_POST['formationame']),               
                    'status' => $_POST['DemandAction']  ];                      
                    $demand = self::getDemand();          
                    //$demandObject = $demand->model->returnDemandid(self::getFormationid($_POST['formationame']),self::getUserid($_COOKIE['coo_username']));
                    $demandObject = $demand->model->getByField("id", $_POST['demandId']);
                    //var_dump($demandObject);
                    if($demandObject == null){
                         $demand_data = [ 
                              'userid' => self::getUserIdByName($_COOKIE['coo_username']),
                              'formationid' => self::getFormationIdByName($_POST['formationame']),               
                              'status' => "Pending"  ];                                  
                              $demand = self::getDemand();
                              //$formationid = $this->$model->create_formation_model($formation_data);
                              $demand = $demand->model->createAny("Demand",$demand_data);
                              header('Location: index.php?view=api/demand/listDemand'); 
                    }     
                    $demandObject->status = $_POST['DemandAction'];
                    $demand->model->update($demandObject);
                    header('Location: index.php?view=api/demand/listDemand');
                    //update role of user to etudiant
                    if ($_POST['DemandAction'] == "Inscrit"){
                         //find if there is a record for this user already in demand table and if not then do following
                         $role = Role::getRole();
                         $role = $role->model->getByField("name","etudiant");
                         var_dump($role);
                         $user = new User();
                         $userid = self::getUserByid();
                         $user->updateRole("4",$role->id );
                    }
           }
           else {
               // Redirection vers le formulaire de signup
               //var_dump("inside else");
               header('Location: index.php?view=user/signup');
               die;
           }  
     }
     protected static function getUserIdByName($username)
     {
          $user = new User();          
          $user = $user->model->getByField("username",$username);          
          return $user->id;
     }
     protected static function getFormationIdByName($formationame)
     {
          $formation = Formation::getFormation();
          $formation = $formation->model->getByField("name",$formationame);
          return $formation->id;          
     }
     public static function listDemand() :void {
          Access::checkLoggedIn();
          $demand = self::getDemand();        
          $demand = $demand->model->getAll(); 
          Output::render('Admin_Listdemande', $demand);

     }     
     public static function listCoursesForUserInProfile() :mixed {
          Access::checkLoggedIn();
          $demand = self::getDemand();        
          $userCourseList = $demand->model->getAllCourses(self::getUserByid()->username);
          return $userCourseList;
          
     }  
}