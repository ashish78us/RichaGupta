<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;
use app\Helpers\Bootstrap;
use stdClass;

class Formation extends Controller
{
    protected static Formation $obj_formation;
    public static function getFormation(): Object {
        if (!isset(self::$obj_formation)) {
        self::$obj_formation = new Formation();
        }
        return self::$obj_formation;
    }   
    public static function create_formation(): void {
        if (!$_POST) {
            header('HTTP/1.1 405');
        }
        if (!empty($_POST['name']) && !empty($_POST['niveau_etude']) && !empty($_POST['status']) && !empty($_POST['date_debut'])&& !empty($_POST['date_fin']) ){
            $formation_data = [ 
            'name' => $_POST['name'],
            'niveau_étude' => $_POST['niveau_etude'],
            'status' => $_POST['status'],
            'date_debut' => $_POST['date_debut'],
            'date_fin' => $_POST['date_fin']   ];                        
            $formationid = self::getFormation()->model->create_formation_model($formation_data);
            header('Location: index.php?view=api/formation/formationList');
        }
        else {
            // Redirection vers le formulaire de signup
            header('Location: index.php?view=user/signup');
            die;
        }
        }  
    
    public function formationList(): void
    {       
        $formation = new Formation();      
        $formation = $formation->model->getAll();  
        Output::render('List_formation', $formation);
    }    
    public static function formationListforUser(): void
    {       
        Access::checkLoggedIn();
        if (Access::isAdmin())  {return ;}                     
        $formation = self::getFormation()->model->getByFieldAll("status", "Active")  ;
        Output::render('User_Listformation', $formation);
    }    
    public static function formationListAdmin(): void
    {   
        Access::checkLoggedIn();               
        $formation = self::getFormation()->model->getAll(); 
        Output::render('List_formation', $formation);
    }
    public static function updateDisplay($id):void
    {       
        Access::checkLoggedIn();
        $formationById = self::getFormation()->model->getById($id);
        Output::render('update_formation', $formationById);         
    }
    public static function updateRow($id): void
    {   Access::checkLoggedIn();  
        $formationObjectById = self::getFormation()->model->getById($id);
        $formationObjectById=self::getFormation()->model->setColumnsValueFromView($formationObjectById); 
        if (!empty($_POST['status'])=="checked"){$formationObjectById->status="Active";}
        if (!empty($_POST['status'])=="unchecked"){$formationObjectById->status="Inactive";}         
        $formationUpdateById = self::getFormation()->model->update($formationObjectById);    
        $formation=self::getFormation()->model->getAll();        
        Output::render('List_formation', $formation);       
    }
    public static function delete($id) {
        Access::checkLoggedIn();                
        self::getFormation()->model->Delete_model($id);
        header('Location:index.php?view=api/Formation/formationList'); 
    }
    public static function getFormationByField($field, $id):mixed {
        
        return self::getFormation()->model->getByField($field,$id);
        
    }    

}

