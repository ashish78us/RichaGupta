<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;
class Course extends Controller
{
    /**
     * @return void
     */
    public function list(): void
    {
        Access::checkLoggedIn();

        // Appel à la méthode du Model afin de récupérer le résultat sous forme d'un tableau d'objets
        $courses = $this->model->getAll();
        // Appel à la méthode statique render de la classe Output afin d'afficher la vue "courses" en y intégrant le tableau d'objets provenant du Model
        Output::render('courses', $courses);
    }

    /**
     * @param int $courseid
     * @param int $userid
     * @return int
     */
    public function getEnrol(int $courseid, int $userid): int
    {
        return $this->model->getEnrol($courseid, $userid);
    }

    /**
     * @param int $userid
     * @return array
     */
    public function getByUserEnrol(int $userid): array
    {
        return $this->model->getByUserEnrol($userid);
    }

    /**
     * @param int $courseid
     * @param int $userid
     * @return void
     */
    public function enrol(int $courseid, int $userid): void
    {
        if (self::getEnrol($courseid, $userid)) {
            Output::createAlert('Utilisateur déjà inscrit à ce cours', 'danger', 'index.php?view=api/course/list');
        } else {
            // Vérification des prérequis
            $prereq = $this->model->getPreprequisite($courseid);
            if ($prereq && !self::getEnrol($prereq, $userid)) {
                Output::createAlert('L\'inscription a échoué, les prérequis ne sont pas satisfaits', 'danger', 'index.php?view=api/course/list');
            }
            // Inscription
            if ($this->model->enrol($courseid, $userid)) {
                Output::createAlert('Utilisateur inscrit avec succès', 'success', 'index.php?view=api/course/list');
            } else {
                Output::createAlert('L\'inscription a échoué', 'danger', 'index.php?view=api/course/list');
            }
        }
    }

    /**
     * @param int $formationid
     * @return void
     */
    public function getByFormationForForm(int $formationid): void
    {
        $data = $this->model->getByFormationForForm($formationid);
        Output::render('getFormOptions', $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function getForForm(int $id): void
    {
        $data = $this->model->getByFormation($id);
        Output::render('getFormOptions', $data);
    }

    /**
     * @return void
     */
    public function createCourse(): void
    {
        //Access::checkAdmin();

        $formation = new Formation();
        $data_formation = $formation->model->getAllForForm('name');
        $course = new Course();
        $data_course = $course->model->getAllForForm('name');

        Output::render2('createCourse', $data_formation,$data_course);
    }
    public function createNewCourse() : void {       
         $data=1;

         Output::render('createNewCourse',$data);
        
     //}
        }
        public function  cr_new_course() : void{            
            if (!$_POST) {
                header('HTTP/1.1 405');
            }
            if (!empty($_POST['name']) && !empty($_POST['code']) && !empty($_POST['status']) ){
                $course_data = [ 
                'name' => $_POST['name'],
                'code' => $_POST['code'],
                'status' => $_POST['status'],
                 ];            
                
                 $courseid = $this->model->createNewCourse($course_data);
               //  Output::render('createNewCourse',$course_data);
        
    }
}
}
