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




</div>

<div class=" flex-wrap col-md-10 mx-auto d-flex flex-row">
     <div class="card mx-5 mt-4 col-md-3" >
          <img src="style/images/fichier.jpg" class="card-img-top" alt="...">
          <div class="d-flex flex-column justify-content-between card-body">
               <div>
                    <h5 class="card-title">Test des fichiers</h5>
                    <p class="card-text">Pour tester le nom et l'url des fichiers présents dans un fichier csv</p>
               </div>
               <a href="/formulaires/pages/testsfichiers/index.php" class="btn btn-info">Go !</a>
          </div>
     </div>

     <div class="card mx-5 mt-4 col-md-3" >
          <img src="style/images/mappemonde2.avif" class="card-img-top" alt="...">
          <div class="d-flex flex-column justify-content-between card-body">
               <div class="mb-3" >
                    <h5 class="card-title">Géolocalisation</h5>
                    <p class="card-text">Pour ajouter une carte aux élements disposant d'un champ couverture</p>
                    <?php if (!empty($messageGeoFail)):?>
                         <p class="text-center card-text messageFail">
                         <img src="style/images/error.png"/> <?= $messageGeoFail ?>
                    <?php else: ?>
                         <p class="text-center card-text messageOk">
                         <img src="style/images/check.png"/> <?= $messageGeoOk ?>
                    <?php endif; ?>
                    </p>
               </div>
               <a href="/formulaires/pages/geolocalisation/index.php" class="btn btn-info">Go !</a>
          </div>
     </div>

     <div class="card mx-5 mt-4 col-md-3" >
          <img src="style/images/livres.jpg" class="card-img-top" alt="...">
          <div class="d-flex flex-column justify-content-between card-body">
               <div class="mb-3">
                    <h5 class="card-title">Gallica</h5>
                    <p class="card-text">Pour ajouter une vignette à chaque élément qui s'affichera dans Gallica</p>
                         <?php if (!empty($messageGallicaFail)):?>
                              <p class="card-text text-center messageFail" >
                                   <img src="style/images/error.png"/> <?= $messageGallicaFail ?>
                         <?php else: ?>
                              <p class="card-text text-center messageOk" >
                                   <img src="style/images/check.png"/> <?= $messageGallicaOk ?>
                         <?php endif; ?>
                              
                    </p>
               </div>
               <a href="/formulaires/pages/gallica/index.php" class="btn btn-info">Go !</a>
          </div>
     </div>
</div>


<?php
     require('views/common/footer.php');
?>
