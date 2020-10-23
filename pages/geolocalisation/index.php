<?php
     $bodyclass = 'geolocalisation';
     $pageTitle = 'Géolocalisation';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');

     require('fonctions.php');
     require(ROOT_DIR.'/fonctions.php');
     require(ROOT_DIR.'/connexion.php');
?>

<div id="content">
<?php
          
          /**
           * chargement de la bonne page et des items
           */
     
          $messageGeoOk = "Tout est à jour";
          $messageGeoFail = '';
          
          $page = 1;
          if (isset($_GET['page']))
               $page = $_GET['page'];
               

          $nav = 'addlocations';
          if (isset($_GET['nav']))
               $nav = $_GET['nav'];
               

          if ($nav=='addlocations')
               $results = getCoveragesWithoutGeo($bdd, $page - 1, $nb_results);
          else if ($nav=='notfound')
               $results = getLocationsNotFound($bdd, $page - 1, $nb_results);


          $nb_items = $results['nb_coverages'];
          $items = $results['coverages'];

          $nb_pages = ceil($nb_items / $nb_results);

          if ($nb_items > 0){
               $messageGeoFail = 'Il y a '.$nb_items.' localisations à ajouter';
          }

          ?>

          <?php
               /**
                * navigation et message d'infos
                */
          ?>
          <nav id="navgeo">
               <span>Pages : </span> 
               <a href="./?nav=addlocations"  <?php if ($nav=='addlocations') echo "class=\"active\""; ?>>Localisations à ajouter</a>
               &nbsp;|&nbsp;
               <a href="./?nav=notfound" <?php if ($nav=='notfound') echo "class=\"active\""; ?>>Localisations en erreur</a>
          </nav>

          <?php if ($nav=='addlocations'): ?>
               <?php if (!empty($messageGeoFail)):?>
                    <div class="messageFail">
                    <img src="<?= WEB_DIR ?>/style/images/error.png"/> <?= $messageGeoFail ?>
               <?php else: ?>
               <div class="messageOk">
                    <img src="<?= WEB_DIR ?>/style/images/check.png"/> <?= $messageGeoOk ?>
               <?php endif; ?>
               </div>
          <?php endif; ?>
          
          <?php if ($nav=='addlocations'): ?>
               <h3>Localisations à ajouter</h3>
          <?php else: ?>
               <h3>Localisations non trouvées dans open street map</h3>
          <?php endif; ?>
         

          
          <?php
               /**
                * affichage des items avec pagination ou message d'info
                */
          ?>

          <?php if (!empty($items)): ?>
          <div id="items">
               <ul id="coverages">
                    <?php $previousId = ''; ?>
                    <?php foreach($items as $key => $one_item): ?>
                         <?php $item_data = getItemTitle($bdd, $one_item['record_id']); ?>
                         <?php if ($previousId != $item_data[0]['record_id']): ?>
                         
                              <li>
                                   <a href="<?= GED_URL ?>/item/<?= $item_data[0]['record_id'] ?>"><?= $item_data[0]['text']; ?></a>
                                   <ul class="coverage-list">
                         <?php endif; ?>         

                            
                              <li><?= $one_item['text'] ?></li>
                        
                         <?php if (isset($items[$key+1])):?>
                              <?php if ($items[$key+1]['record_id']!=$item_data[0]['record_id']):?>
                                        </ul>         
                                   </li>
                              <?php endif; ?>
                         <?php endif; ?>

                         <?php $previousId = $one_item['record_id']; ?>
                         
                    <?php endforeach; ?>
               </ul>

           
               
          </div>
          <nav id="previousnext">
                    <?php if ($page > 1) : ?>
                         <a href="./?page=<?= $page -1 ?>&amp;nav=<?= $nav ?>">Page précédente</a>
                    <?php endif; ?>


                    <?php for ($i=1 ; $i<=$nb_pages; $i++): ?>
                         <a href="./?page=<?= $i ?>&amp;nav=<?= $nav ?>"  class="pagination <?php if ($i==$page) echo "active"; ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $nb_pages) : ?>
                         <a href="./?page=<?= $page +1 ?>&amp;nav=<?= $nav ?>">Page suivante</a>
                    <?php endif; ?>
          </nav>
          <?php else: ?>
               <div class="messageInfo">
                    Aucun élement n'a été trouvé
               </div>
          <?php endif;?>
          
</div>


<?php
     require('../../views/common/footer.php');
?>
