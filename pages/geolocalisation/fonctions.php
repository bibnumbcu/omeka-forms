<?php

    /**
     * récupère les elements de type "couverture"  qui n'ont pas de localisation dans la table bu_maps_locations
     * et compte le nombre d'élements retournés
     */
    function getCoveragesWithoutGeo($bdd, $page=null, $nb_results){
        $select = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS r.id as resource_id, r.title, v.value as address FROM resource as r
                    inner join value as v on v.resource_id = r.id
                    WHERE v.property_id="14" AND r.id NOT IN (SELECT resource_id FROM bu_maps_resources_locations) LIMIT ';

            
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
        $select = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS r.id as resource_id, r.title, bo.address as address FROM resource as r
                    inner join bu_maps_resources_locations as brl on brl.resource_id = r.id
                    inner join bu_maps_locations as bo on brl.location_id = bo.id
                    where  bo.notfound=1 LIMIT ';

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
    // function getItemTitle($bdd, $item_id){
    //     $select = 'SELECT record_id,text FROM omeka_element_texts WHERE record_id="'.$item_id.'" AND element_id="50" AND record_type="Item"';
    //     $requete = $bdd->query($select);
    //     return $requete->fetchAll();
    // }

    /**
     * retourne le champ couverture d'un item
     */
    // function getItemCoverages($bdd, $item_id){
    //     $select = 'SELECT record_id,text FROM omeka_element_texts WHERE record_id="'.$item_id.'" AND element_id="38" AND record_type="Item"';
    //     $requete = $bdd->query($select);
    //     return $requete->fetchAll();
    // }

    /**
     *  test si la localisation a déjà été enregistrée dans la table omeka_locations
     */
    function testLocationExists($bdd, $coverage){
        $select = 'SELECT * FROM bu_maps_locations WHERE upper(trim(address))=upper(trim("'.$coverage['address'].'"))';

        $requete = $bdd->query($select);
        return $requete->fetchAll();
    }


    /**
     * ajoute une nouvelle localisation dans la table bu_maps_locations
     */
    function addLocation($bdd, $location, $coverage){
        $select = 'INSERT INTO bu_maps_locations (latitude, longitude, address, notfound) VALUES ("'.$location['latitude'].'", "'.$location['longitude'].'", "'.$coverage['address'].'", "'.$location['notfound'].'" )';
         
        $requete = $bdd->query($select);
        return $requete;
    }

    /**
     * ajoute un lien entre une resource et une localisation dans la table bu_maps_resources_locations
     */
    function addResourceLocation($bdd, $location, $coverage){
        $select = 'INSERT INTO bu_maps_resources_locations (resource_id, location_id) VALUES ("'.$coverage['resource_id'].'", "'.$location['id'].'" )';
         
        $requete = $bdd->query($select);
        return $requete;
    }

    /**
     * fonction qui géolocalise une adresse via l'api open street map
     */
    function findOSMLocation($bdd, $coverage, $nominatim_url, $admin_email){
        //attribution des paramètres de la requête osm
        $coverage_text = $coverage['address'];
        $format = 'json';
        

        //on isole le code postal
        $pattern = '/\(\d{5}\)/';
        $matches = array();
        $pattern_result = preg_match($pattern, $coverage['address'], $matches);
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
