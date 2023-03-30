<?php

namespace app\Controllers;
use app\Helpers\Helper;
use app\Helpers\Bootstrap;
use app\Helpers\Output;
use app\Helpers\Access;
use app\Helpers\Text;
class Course_formation extends Controller
{
    
     public function create(): void
     {

          var_dump("inside course_formation");
          var_dump($_POST['cc-formation']);
          var_dump($_POST['cc-course']);
          print_r($_SESSION);
     }   
    
    
}
