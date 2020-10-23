<?php
    /** url of omeka site */
    define('GED_URL', '');

    /** root file dir of this script */
    define('ROOT_DIR', '');

    /** web directory after host name, if exists */
    define('WEB_DIR', '');

    $mainTitle = '';

    //mail admin pour osm et l'envoi des logs
    $admin_email = '';

    /** config test fichiers */
    //upload dir of csv file to test
    $uploadDir = 'file';
    //files to import directory on the server
    $filesToImportDir = '';

    /** config gallica */
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
