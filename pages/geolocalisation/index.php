<?php
     $bodyclass = 'geolocalisation';
     $pageTitle = 'Géolocalisation';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');

     require('fonctions.php');
     require(ROOT_DIR.'/fonctions.php');
     require(ROOT_DIR.'/connexion.php');
?>

<div class="col-md-10 d-flex flex-column mx-auto">
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
               if ($nav=='addlocations')
                    $messageGeoFail = 'Il y a '.$nb_items.' localisations à ajouter';
               else if ($nav=='notfound')
                    $messageGeoFail = 'Il y a '.$nb_items.' localisations qui n\'ont pas été trouvées dans open street map';
          }

          ?>

          <?php
               /**
                * navigation et message d'infos
                */
          ?>
          <ul class="nav nav-tabs">
               <li class="nav-item">
                    <a href="./?nav=addlocations" class="nav-link <?php if ($nav=='addlocations') echo " active "; ?>" >Localisations à ajouter</a>
               </li>
               <li class="nav-item">
                    <a href="./?nav=notfound" class="nav-link <?php if ($nav=='notfound') echo " active"; ?>" >Localisations en erreur</a>
               </li>
          </li>
          </ul>
          
          <?php if ($nav=='addlocations'): ?>
               <h3>Localisations à ajouter</h3>
          <?php else: ?>
               <h3>Localisations non trouvées dans open street map</h3>
          <?php endif; ?>

          <?php if (!empty($messageGeoFail)):?>
               <div class="alert alert-danger m-2">
               <img src="<?= WEB_DIR ?>/style/images/error.png"/> <?= $messageGeoFail ?>
          <?php else: ?>
          <div class="alert alert-success m-2">
               <img src="<?= WEB_DIR ?>/style/images/check.png"/> <?= $messageGeoOk ?>
          <?php endif; ?>
          </div>

               <nav aria-label="Page navigation example" >
               <ul class="pagination flex-wrap justify-content-center col-md-10 mx-auto">
                    <?php if ($page > 1) : ?>
                         <li class="page-item mb-2">
                              <a class="page-link" aria-label="Previous" href="./?page=<?= $page -1 ?>&amp;nav=<?= $nav ?>">
                                   <span aria-hidden="true">&laquo;</span>
                              </a>
                         </li>
                    <?php endif; ?>

                    <?php for ($i=1 ; $i<=$nb_pages; $i++): ?>
                         <li class="page-item mb-2">
                              <a class="page-link <?php if ($i==$page) echo " active"; ?>" href="./?page=<?= $i ?>&amp;nav=<?= $nav ?>">
                                   <?= $i ?>
                              </a>
                         </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $nb_pages) : ?>
                         <li class="page-item mb-2">
                              <a class="page-link" href="./?page=<?= $page +1 ?>&amp;nav=<?= $nav ?>">
                                   <span aria-hidden="true">&raquo;</span>
                              </a>
                         </li>
                    <?php endif; ?>
               </ul>
          </nav>
          
         

          
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
                         <?php //$item_data = getItemTitle($bdd, $one_item['resource_id']); ?>
                         <?php if ($previousId != $one_item['resource_id']): ?>
                         
                              <li>
                                   <a href="<?= GED_URL ?>item/<?= $one_item['resource_id'] ?>"><?= $one_item['title']; ?></a>
                                   <ul class="coverage-list">
                         <?php endif; ?>         

                            
                              <li><?= $one_item['address'] ?></li>
                        
                         <?php if (isset($items[$key+1])):?>
                              <?php if ($items[$key+1]['resource_id']!=$one_item['resource_id']):?>
                                        </ul>         
                                   </li>
                              <?php endif; ?>
                         <?php endif; ?>

                         <?php $previousId = $one_item['resource_id']; ?>
                         
                    <?php endforeach; ?>
               </ul>

           
               
          </div>

          


          
          <?php endif;?>
          
</div>


<?php
     require('../../views/common/footer.php');
?>
