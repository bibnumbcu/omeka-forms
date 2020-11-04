<?php
    /** url of omeka site */
    define('GED_URL', '');

    /** root file dir of this script */
    define('ROOT_DIR', dirname(__FILE__));

    /** web directory after host name, if exists */
    $dirParts = explode('/', dirname($_SERVER['PHP_SELF']) );
    define('WEB_DIR', '/'.($dirParts[1]??''));

    $mainTitle = '';

    //mail admin pour osm et l'envoi des logs
    $admin_email = '';

    /** config test fichiers */
    //upload dir of csv file to test
    $uploadDir = 'files';
    //directory on the server with files to import
    $filesToImportDir = '';

    /** config gallica */
    //prefix to add to thumbnail image name in the relation field
    $urlGallica = "";

    /**  config geolocalisation */
    //nb results per view
    $nb_results = '30';

    //zoom level for view in omeka
    $zoom_level = '14';

    $map_type = 'Leaflet';
    
    //open street map api url
    $nominatim_url = 'https://nominatim.openstreetmap.org/search';

    //max requests in osm for each script execution
    $max_cpt = 500; 

?>
