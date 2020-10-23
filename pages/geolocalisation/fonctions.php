<?php

    /**
     * récupère les elements de type "couverture"  qui n'ont pas de localisation dans la table omeka_locations
     * et compte le nombre d'élements retournés
     */
    function getCoveragesWithoutGeo($bdd, $page=null, $nb_results){
        // if ($notfound)
        //     $select = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS id, record_id, text FROM omeka_element_texts  WHERE  record_type="Item" AND element_id="38" AND id  IN (SELECT element_text_id FROM omeka_locations WHERE notfound=1) LIMIT ';
        // else
        $select = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS id, record_id, text FROM omeka_element_texts  WHERE  record_type="Item" AND element_id="38" AND id NOT IN (SELECT element_text_id FROM omeka_locations) LIMIT ';

            
        if (!empty($page)){
            $offset = $nb_results * $page;
            $select .= $offset.','.$nb_results;
        }
        else
            $select .= $nb_results;
        
        $requeteItems = $bdd->query($select);
        $requeteCount = $bdd->query('SELECT found_rows()');
        $result['coverages'] = $requeteItems->fetchAll();
        $result['nb_coverages'] = $requeteCount->fetchColumn();

        return $result;
    }

    function getLocationsNotFound($bdd, $page=null, $nb_results){
         $select = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS id, record_id, text FROM omeka_element_texts  WHERE  record_type="Item" AND element_id="38" AND id  IN (SELECT element_text_id FROM omeka_locations WHERE notfound=1) LIMIT ';
 
        if (!empty($page)){
            $offset = $nb_results * $page;
            $select .= $offset.','.$nb_results;
        }
        else
            $select .= $nb_results;
        
        $requeteItems = $bdd->query($select);
        $requeteCount = $bdd->query('SELECT found_rows()');
        $result['coverages'] = $requeteItems->fetchAll();
        $result['nb_coverages'] = $requeteCount->fetchColumn();

        return $result;
    }


    /**
     * retoure le champ title d'un item
     */
    function getItemTitle($bdd, $item_id){
        $select = 'SELECT record_id,text FROM omeka_element_texts WHERE record_id="'.$item_id.'" AND element_id="50" AND record_type="Item"';
        $requete = $bdd->query($select);
        return $requete->fetchAll();
    }

    /**
     * retourne le champ couverture d'un item
     */
    function getItemCoverages($bdd, $item_id){
        $select = 'SELECT record_id,text FROM omeka_element_texts WHERE record_id="'.$item_id.'" AND element_id="38" AND record_type="Item"';
        $requete = $bdd->query($select);
        return $requete->fetchAll();
    }

    /**
     *  test si la localisation a déjà été enregistrée dans la table omeka_locations
     */
    function testLocationExists($bdd, $coverage){
        $select = 'SELECT element_text_id, latitude, longitude, address, notfound FROM omeka_locations WHERE upper(trim(address))=upper(trim("'.$coverage['text'].'"))';

        $requete = $bdd->query($select);
        return $requete->fetchAll();
    }


    /**
     * ajoute une nouvelle localisation dans la table omeka_locations
     */
    function addLocation($bdd, $location, $coverage, $zoom_level, $map_type){
        $select = 'INSERT INTO omeka_locations (element_text_id, latitude, longitude, zoom_level, map_type, address, notfound) VALUES ("'.$coverage['id'].'", "'.$location['latitude'].'", "'.$location['longitude'].'", "'.$zoom_level.'", "'.$map_type.'", "'.$coverage['text'].'", "'.$location['notfound'].'" )';
         
        $requete = $bdd->query($select);
        return $requete;
    }

    /**
     * fonction qui géolocalise une adresse via l'api open street map
     */
    function findOSMLocation($bdd, $coverage, $nominatim_url, $admin_email){
        //attribution des paramètres de la requête osm
        $coverage_text = $coverage['text'];
        $format = 'json';
        

        //on isole le code postal
        $pattern = '/\(\d{5}\)/';
        $matches = array();
        $pattern_result = preg_match($pattern, $coverage['text'], $matches);
        $postalcode = '';
        if ($pattern_result){
            $postalcode = substr($matches[0], 1, 5);
            
            $split_result = preg_split($pattern, $coverage_text);
            if(isset($split_result[0]))
                $coverage_text = $split_result[0];
            
        }
    
        //construction de la requete osm
        $query = '?q='.urlencode($coverage_text).'&format='.$format.'&email='.$admin_email;

        if(!empty($postalcode)){
            $query .= '&postalcode='.$postalcode;
        }
        
        $nominatim_url .= $query;
        
        $response = null;
        $response_json = file_get_contents($nominatim_url);
        $response['json'] = json_decode($response_json);
        $response['url'] = $nominatim_url;
        return $response;
    }
?>
