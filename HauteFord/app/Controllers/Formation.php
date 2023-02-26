<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;

class Formation extends Controller
{
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

            $formationid = $this->model->create_formation($formation_data);
           
        

    
        }
        else {
            // Redirection vers le formulaire de signup
            header('Location: index.php?view=user/signup');
            die;
        }

    
    }

    
    public function update($id)
    
    {
       
       $update=true;       
        $formation = $this->get($id);      
    
         //  var_dump($update);
          if($update){
        Output::render('update_formation', $formation);
        }    

    }

    public function update_row(array $formation)
    
    {
        //var_dump("id=".$id);
       //var_dump("inside update_row");
       //var_dump("sessionID=".$_SESSION['formationid']);

       $this->model->update($formation);

       
        
        //Output::createAlert('Update Success', 'success', 'index.php?view=api/formation/formationList/' . $user->id);

       

    }


    protected function getForFormation(int $id): mixed
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
    


    
    public function formationList(): void
    {       

        $formation = new Formation();
        //var_dump($formation->model);
        $formation = $formation->model->getAll();          

        Output::render('List_formation', $formation);
    }
    public function delete($id) {
        
        $this->model->delete($id);
        header('Location:index.php?view=api/Formation/List_formation');
        
       
    }
}

