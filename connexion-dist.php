<?php
//infos de connexions à la base omeka
$host = '';
$dbname = '';
$dbuser = '';
$password = '';

try {
	$bdd = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8', $dbuser, $password);
}
catch(Exception $e) {
	die('Erreur : '.$e->getMessage());
}

?>
