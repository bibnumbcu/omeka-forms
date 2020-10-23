<?php
/**
 * Gestion des géolocalisations
 * ce script est exécuté une fois par jour
 * il effectue les actions suivantes :
 * - cherche les items d'omeka qui ont un champ couverture mais qui n'a pas été encore géolocalisé
 * - vérifie que la localisation cherchée n'existe pas dans la base (système de cache)
 * - vérifie que la localisation cherchée n'existe pas dans la table des localisations qui n'ont pas eu de réponse
 * 
 * 
 */



require('../../config.php');
require('fonctions.php');
require(ROOT_DIR.'/fonctions.php');
require(ROOT_DIR.'/connexion.php');


//message de sortie
$coverage_output = array('output_message' => '',
                            'nb_coverages_in_database' => 0,
                            'nb_coverages_osm_found' => 0,
                            'nb_coverages_osm_notfound' => 0
);

//test
$cpt_coverages = 0;
$nb_coverages =0;



do {
    $results = getCoveragesWithoutGeo($bdd, 0, $nb_results);
    $coverages = $results['coverages'];

    $nb_coverages = $results['nb_coverages'];
    
    $previousTitle = '';
    
    foreach($coverages as $one_coverage){
        $cpt_coverages++;
        
        //echo "nb_coverages:".$nb_coverages." cpt:".$cpt_coverages."\n";

        if ($cpt_coverages > $max_cpt)
        continue;
        

        $title = getItemTitle($bdd, $one_coverage['record_id']);
        if ($previousTitle != $title)       
            $coverage_output['output_message'] .= "\n\n".$title[0]['text']." \n";

        $coverage_output['output_message'] .= 'Couverture : '.$one_coverage['text'];

        //on test si la couverture n'a pas déjà été cherchée et enregistrée dans la table item_locations
        $testLocation = testLocationExists($bdd, $one_coverage);
        
        //on test si la couverture a déjà été cherchée et n'a pas été trouvée dans open street map
        //$testLocationNotFound = testLocationNotFound($bdd, $one_coverage);
        
        /**
         * on test si la localisation existe dans omeka_locations
         */
        if ($testLocation){
            //si cette localisation a déjà été rencontrée, on peut l'ajouter dans la base pour cette couverture
            addLocation($bdd, $testLocation[0], $one_coverage, $zoom_level, $map_type);
            
            $coverage_output['output_message'] .= " : cette localisation existe déjà dans la table omeka_locations, ajout de ".$one_coverage['text']." pour l'élément ".$one_coverage['id'];
            $coverage_output['nb_coverages_in_database']++;
            
        }
        else{
            //on fait appel à open street map pour toute localisation que l'on ne connait pas encore
            $osm_response = findOSMLocation($bdd, $one_coverage, $nominatim_url, $admin_email);
            
            $coverage_output['output_message'] .= "\n-> Url OSM : ". $osm_response['url'];
            $osm_json = $osm_response['json'];
            
            //donnes de test
            /*
            $object = $page=new stdClass();
            $object->lat = '45';
            $object->lon = '2';
            
            if (rand(0,1))
            $osm_response = array(0 => $object);
            else $osm_response=null;
            var_dump($osm_response);
            */

            //si la réponse d'osm est vide, ça veut dire que l'adresse n'a pas été trouvée alors on l'enregistre dans la base avec notfound=1
            if (empty($osm_json)){
                $location_osm['latitude']= 0;
                $location_osm['longitude']= 0;
                $location_osm['notfound']= 1;
                
                $coverage_output['output_message'] .= "\n-> cette localisation a été demandée à osm et n'a pas été trouvée";
                $coverage_output['nb_coverages_osm_notfound']++;
            }
            else{
                $location_osm['latitude'] = $osm_json[0]->lat;
                $location_osm['longitude'] = $osm_json[0]->lon;
                $location_osm['notfound']= 0;
                
                $coverage_output['output_message'] .= "\n-> cette localisation a été demandée à osm et a été trouvée. Ajout de ".$one_coverage['text']." pour l'élément ".$one_coverage['id'];
                $coverage_output['nb_coverages_osm_found']++;
            }
            
            //on ajoute la localisation dans la base
            addLocation($bdd, $location_osm, $one_coverage, $zoom_level, $map_type);
            
            //temps d'attente pour ne pas se faire jeter de nominatim
            sleep(1.5);
        }

        $coverage_output['output_message'] .= "\n";
        $previousTitle = $title;
        
    }
} while ($nb_coverages > 0 && $cpt_coverages < $max_cpt);


/**
 * affichage des messages de logs et envoi par mail
 */
$log = 'Script de gestion des localisations dans omeka : exécution le '.date('d-m-Y : H:i');
$log .= "\n";
$log .= 'Il y a eu '.$cpt_coverages." localisations qui ont été traitées\n";
$log .= 'dont '.$coverage_output['nb_coverages_in_database']." localisations qui ont été trouvées dans la base.\n";
$log .= 'et '.$coverage_output['nb_coverages_osm_found']." localisations qui ont été demandées et trouvées dans open street map\n ";
$log .= 'et '.$coverage_output['nb_coverages_osm_notfound']." localisations qui ont été demandées et non trouvées dans open street map\n ";
$log .= "details : \n";
$log .= $coverage_output['output_message'];

//envoi par mail
if ($cpt_coverages!=0){
    $subject = 'Mail de la ged : ajout des localisations';
    $headers = "Content-Type: text/plain; charset=UTF-8";
    
    mail($admin_email, $subject, $log, $headers);
}
?>