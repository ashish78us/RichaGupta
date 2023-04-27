<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;
class Formation_course extends Controller
{
     protected static Formation_course $obj_formation_course;
     protected static function getformation_course(): Object {
         if (!isset(self::$obj_formation_course)) {
         self::$obj_formation_course = new Formation_course();
         }
         return self::$obj_formation_course;
     } 
     public function create(): void
     {
          $course_formation_data = [ 
               'formationid' => $_POST['cc-formation'],
               'courseid' => $_POST['cc-course'],
               'period' => "120",
               'determinant' => "12",
               'prepreq'=>"18",
               'teacher'=>"1",               
               'status' => "Active"  
          ];                   
               $course_formation = self::getformation_course();               
               $course_formation = $course_formation->model->createAny("formation_course",$course_formation_data);
               header('Location: index.php?view=api/course/list');
     }   
    
    
}
