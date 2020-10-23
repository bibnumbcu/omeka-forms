<?php
     $bodyclass = 'home';
     $pageTitle = '';
     require('config.php');
     require('views/common/header.php');


     require(ROOT_DIR.'/pages/gallica/fonctions.php');
     require(ROOT_DIR.'/pages/geolocalisation/fonctions.php');
     require(ROOT_DIR.'/fonctions.php');

     require(ROOT_DIR.'/connexion.php');

?>

<nav id="menuhome" class="colonnegauche">
<ul>
     <li><a href="/formulaires/pages/testsfichiers/index.php"><h2>Tests des fichiers</h2><div class="description">Pour tester le nom et l'url des fichiers présents dans un fichier csv</div></a></li>
     <li><a href="/formulaires/pages/geolocalisation/index.php"><h2>Géolocalisation</h2><div class="description">Pour ajouter une carte aux élements disposant d'un champ couverture</div></a></li>
     <li><a href="/formulaires/pages/gallica/index.php"><h2>Gallica</h2><div class="description">Pour ajouter une vignette à chaque élément qui s'affichera dans Gallica</div></a></li>
</ul>
</nav>

<div class="content">

     <?php
          /*
          * affichage du message indiquant si il y a des vignettes à ajouter ou pas
          */
          $collections = getCollections($bdd);

          $messageGallicaOk = "Tout est à jour";
          $messageGallicaFail = '';

          foreach ($collections as $one_collection){
               $nb_items = testVignettes($bdd, $one_collection['record_id']);
               if ($nb_items > 0){
                   $messageGallicaFail = 'Il y a des vignettes à ajouter';
                   break;
               }

          }
         
     

          //message concernant la géolocalisation
          $messageGeoOk = "Tout est à jour";
          $messageGeoFail = '';
          $results = getCoveragesWithoutGeo($bdd, 0, 1);
          $nb_items = $results['nb_coverages'];
          if ($nb_items > 0){
               $messageGeoFail = 'Il y a des localisations à ajouter';
          }


     ?>


     <h3>Gallica</h3>

          <?php if (!empty($messageGallicaFail)):?>
               <div class="messageFail">
                   <img src="style/images/error.png"/> <?= $messageGallicaFail ?>
          <?php else: ?>
          <div class="messageOk">
                   <img src="style/images/check.png"/> <?= $messageGallicaOk ?>
          <?php endif; ?>
          </div>

     <h3>Géolocalisations</h3>

     <?php if (!empty($messageGeoFail)):?>
          <div class="messageFail">
              <img src="style/images/error.png"/> <?= $messageGeoFail ?>
     <?php else: ?>
     <div class="messageOk">
              <img src="style/images/check.png"/> <?= $messageGeoOk ?>
     <?php endif; ?>
     </div>




</div>


<?php
     require('views/common/footer.php');
?>
