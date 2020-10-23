<?php
    /**
     * script d'ajout des vignettes manquantes
    *  à executer une fois par jour dans une tâche cron.
    */
    require('../../config.php');
    
    require('fonctions.php');
    require(ROOT_DIR.'/fonctions.php');
    require(ROOT_DIR.'/connexion.php');

    $collections = getCollections($bdd);
    $output = '';

    foreach($collections as $one_collection){
        $nb_items = testVignettes($bdd, $one_collection['record_id']);
        if ($nb_items > 0){
            ajoutVignettes( $bdd, $one_collection['record_id'], $urlGallica );
            $output .= 'Collection '.$one_collection['text'].' : '.$nb_items.' vignettes ajoutées';
            $output .= "\n";
        }
    }

    /**
     * affichage des messages de logs et envoi par mail
     */
    if (!empty($output)){
        $log = 'Script d\'ajout des vignettes dans la ged : exécution le '.date('d-m-Y : H:i');
        $log .= "\n".$output;
        //envoi par mail
        $subject = 'Mail de la ged : ajout des vignettes';
        $headers = "Content-Type: text/plain; charset=UTF-8";

        mail($admin_email, $subject, $log, $headers);
    }

   

?>
