<?php
//récupère toutes les collections de la ged
    function getCollections($bdd){
        // $select = 'SELECT record_id, text FROM omeka_element_texts					
					// WHERE record_type="Collection" AND element_id="50"';
        $select = 'SELECT ores.id, ores.title FROM item_set as ois inner join resource as ores on ois.id=ores.id' ;
		$requete = $bdd->query($select);
        
        $collections = $requete->fetchAll();
        return $collections;
    }
    
?>
