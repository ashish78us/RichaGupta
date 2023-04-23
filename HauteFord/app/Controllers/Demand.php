<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;

class Demand extends Controller
{
    
     public static function formdisplay($id): void
     {
          //var_dump($_COOKIE['coo_userid']);
          $user = new User();
          $user = $user->model->getByField('username',$_COOKIE['coo_username']);
          //var_dump($user);
          $formation = new Formation();
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

          $formation = new Formation();
          $formation = $formation->model->getByField('id',$id);
          return $formation->name;

     } 

     public static function getDemandStatusIfExist($formationid): String
     {          
          $user = self::getUserByid();
          $demand = new Demand();          
          $demandStatus = $demand->model->findDemand($formationid,$user);
          //var_dump($demandStatus);
          return $demandStatus;

     } 
    
     public static function create(): void
     {
          //var_dump($_POST);
          if (!$_POST) {
               header('HTTP/1.1 405');
           }
           if (!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['formationame']) && !empty($_POST['DemandAction']
           && $_POST['DemandAction'] == "Create" )){

               $demand_data = [ 
               'userid' => self::getUserid($_COOKIE['coo_username']),
               'formationid' => self::getFormationid($_POST['formationame']),               
               'status' => "Pending"  ];
                   //var_dump("inside if");
                   $demand = new Demand();
               //$formationid = $this->$model->create_formation_model($formation_data);
               $demand = $demand->model->createAny("Demand",$demand_data);
               header('Location: index.php?view=api/formation/formationListforUser');
               
           }
           else if(!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['formationame']) && !empty($_POST['DemandAction']
           && ( $_POST['DemandAction'] == "Inscrit" ) || $_POST['DemandAction'] == "Refuse") ){

               $demand_data = [ 
                    'userid' => self::getUserid($_COOKIE['coo_username']),
                    'formationid' => self::getFormationid($_POST['formationame']),               
                    'status' => $_POST['DemandAction']  ];
                        //var_dump("inside if");
                        $demand = new Demand();
                    //$formationid = $this->$model->create_formation_model($formation_data);
                    $demand = new Demand();          
                    $demandObject = $demand->model->returnDemandid(self::getFormationid($_POST['formationame']),self::getUserid($_COOKIE['coo_username']));
                    if($demandObject == null){
                         $demand_data = [ 
                              'userid' => self::getUserid($_COOKIE['coo_username']),
                              'formationid' => self::getFormationid($_POST['formationame']),               
                              'status' => "Pending"  ];
                                  //var_dump("inside if");
                                  $demand = new Demand();
                              //$formationid = $this->$model->create_formation_model($formation_data);
                              $demand = $demand->model->createAny("Demand",$demand_data);
                              header('Location: index.php?view=api/formation/formationListforUser');
                              

                    }
                    
                    
                    $demandObject->status = $_POST['DemandAction'];
                    $demand->model->update($demandObject);
                    header('Location: index.php?view=api/formation/formationListforUser');
           }
           else {
               // Redirection vers le formulaire de signup
               header('Location: index.php?view=user/signup');
               die;
           }      
          

     }
     protected static function getUserid($username)
     {
          $user = new User();
          
          $user = $user->model->getByField("username",$username);
          
          return $user->id;
     }
     protected static function getFormationid($formationame)
     {
          $formation = new Formation();
          $formation = $formation->model->getByField("name",$formationame);
          return $formation->id;
          
     }

     public static function listDemand() :void {
          $demand = new Demand();
        //var_dump($formation->model);
        $demand = $demand->model->getAll();          
        
        Output::render('Admin_Listdemande', $demand);

     }
        
    
}