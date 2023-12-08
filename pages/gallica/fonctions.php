<?php
    function getItemsWithoutThumbs($bdd, $collection_id){
        //$select = 'SELECT id FROM omeka_items WHERE collection_id="'.$collection_id.'" AND id NOT IN (SELECT record_id FROM omeka_element_texts as n INNER JOIN omeka_items as p ON n.record_id=p.id WHERE collection_id="'.$collection_id.'" AND n.element_id=46 AND n.text LIKE "vignette : https://%")';
        $select = 'SELECT item_id as id from item_item_set where item_set_id='.$collection_id.' AND item_id not in (SELECT resource_id FROM value where property_id=13 AND value like "vignette : https://%")';
        // var_dump($select);
        
        $requete = $bdd->query($select);
        return $requete->fetchAll();
    }

    /**
     * test si il y a des élements sans vignettes dans une collection en comparant le nombre d'items retournés dans la table omeka_items et ceux retournés par la table
     * omeka_element_texts
     */
    function testVignettes($bdd, $collection_id){
        //$selectItems = 'SELECT count(id) as nbItems FROM omeka_items WHERE collection_id="'.$collection_id.'"';
        //$selectVignettes = 'SELECT count(record_id) as nbVignettes FROM omeka_element_texts as n INNER JOIN omeka_items as p ON n.record_id=p.id WHERE collection_id="'.$collection_id.'" AND n.element_id=46 AND n.text LIKE "vignette : https://%"';

        $selectItems = 'select count(item_id) as nbItems from item_item_set where item_set_id='.$collection_id;
        $selectVignettes = 'SELECT count(distinct resource_id) as nbVignettes FROM value where property_id=13 AND value like "vignette : https://%" and resource_id in (select item_id from item_item_set where item_set_id='.$collection_id.')';

        $requeteItems = $bdd->query($selectItems)->fetchAll();
        $requeteVignettes = $bdd->query($selectVignettes)->fetchAll();

        $nb_items = $requeteItems[0]['nbItems'] - $requeteVignettes[0]['nbVignettes'];
        
        return $nb_items;
    }

    //ajout des vignettes à une collection
    function ajoutVignettes($bdd, $collection_id, $urlGallica){
        $items = getItemsWithoutThumbs($bdd, $collection_id);
        $sql = 'INSERT INTO `omeka-s-ged`.value (resource_id, property_id, type, value, is_public) VALUES ';
        foreach($items as $key=>$item){
            $requetefile = $bdd->query('SELECT storage_id FROM media WHERE item_id="'.$item['id'].'"');
            $result = $requetefile->fetchAll();
            // $filename = substr($result[0]["storage_id"], 0 ,-4);
            $text = $urlGallica.$result[0]["storage_id"].'.jpg';
            //insertion dans le champ relation de l'url de la vignette
        //    $sql = 'INSERT INTO omeka_element_texts(record_id, record_type, element_id, html, text) VALUES ( "'.$item['id'].'", "Item", "46", 0,"'.$text.'") ';
            $sql .= '( "'.$item['id'].'", "13", "literal", "'.$text.'", 1)';            
            if ($key+1 < count($items))
                $sql .= ',';
        }
        
        $sql .= ';';
        
        
	    //insertion dans la base
	    $output = $bdd->query($sql);
    }
?>
