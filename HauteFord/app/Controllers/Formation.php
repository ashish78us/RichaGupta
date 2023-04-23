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

    //public function __construct() {
        //parent::__construct();
   // }
    public  function create_formation(): void {
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
                //var_dump("inside if");
                $formation_model = new \app\Models\Formation();
            //$formationid = $this->$model->create_formation_model($formation_data);
            $formationid = $formation_model->create_formation_model($formation_data);
            header('Location: index.php?view=api/formation/formationList');
            
        }
        else {
            // Redirection vers le formulaire de signup
            header('Location: index.php?view=user/signup');
            die;
        }

        }
    
    


    public function getForFormation(int $id): mixed
    {
        // récupération du record en DB correspondant à l'id fourni
        $formation= $this->get($id);
        return $formation;
    }
    protected function formatForFormation(object $formation): object
    {
        // Clôner l'objet user en un nouvel objet pour l'affichage du profil, afin de différencier de l'objet à mettre à jour
        $formationuser = clone $formation;
        // Formattage des données
        unset($formationuser->id);
        unset($formationuser->name);
        unset($formationuser->niveau_etude);
        unset($formationuser->status);
        if(!$formationuser->status='active') {
            $formationuser->status='Inactive';}
            $formationuser->date_debut = date_format( new \Date($formationuser->date_debut),"d/m/Y"); 
            $formationuser->date_fin = date_format( new \Date($formationuser->date_fin),"d/m/Y");

        return $formationuser;
        
    }
    
    public function formationListforUser(): void
    {       

        $formation = new Formation();
        //var_dump($formation->model);
        $formation = $formation->model->getAll();          

        Output::render('User_Listformation', $formation);
    }


    
    public function formationList(): void
    {       

        $formation = new Formation();
        //var_dump($formation->model);
        $formation = $formation->model->getAll(); 
        //return "<html><body><h2>Hello</h2></body></html>";  
        //$html_content = "<html><body><h2>Hello</h2></body></html>";      

        Output::render('List_formation', $formation);

        //return $html_content;
        
    }
    public function delete($id) {
        $formation_model = new \app\Models\Formation();
        
        $formation_model->Delete_model($id);
        header('Location:index.php?view=api/Formation/formationList');        
       
    }
    public function update($id): void
    {       
        $formationById = $this->model->getById($id);
        //var_dump($formation->name);

        Output::render('update_formation', $formationById);  
       
    }
    public function update_row($id): void
    {       
        $formationObjectById = $this->model->getById($id);
        
       // $formationObjectById->name = $_POST['status'];
        
        
        $formationObjectById=$this->model->setColumnsValueFromView($formationObjectById); 
        if (!empty($_POST['status'])=="checked"){$formationObjectById->status="Active";}
        if (!empty($_POST['status'])=="unchecked"){$formationObjectById->status="Inactive";}  
        //var_dump($_POST);
        //var_dump($formationObjectById->status);    

        //var_dump($formationObjectById->name);
        $formationUpdateById = $this->model->update($formationObjectById);
    
        $formation=$this->model->getAll();
        //Output::render('update_formation', $formationObjectById);  
        Output::render('List_formation', $formation);
       
    }

}

