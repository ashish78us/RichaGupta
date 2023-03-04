<main role="main" class="container-fluid">
<?php

use app\Helpers\Output;
use app\Helpers\Text;

// Affichage des éventuels messages d'alertes
Output::manageAlerts();
// Vérifie la présence d'un paramètre view dans l'URL
if (!empty($_GET['view']) && $_GET['view'] != 'view/default') {
    // Routing
  // try {
    //var_dump($_GET['view']);
    //var_dump("Inside default.php");
        Output::getContent($_GET['view']);
 /*   } catch (Exception $e) {
        Output::render('messageBox', Text::getString(['Veuillez utiliser l\'interface du site', 'Please use site interface']));
    }*/
} else {
    Output::render('messageBox', 'Welcome to our site!', 'info');
}
?>
  
</main>