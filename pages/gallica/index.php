<?php
     $bodyclass = 'gallica';
     $pageTitle = 'Gallica : ajout des vignettes';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');
     
     require('fonctions.php');
     require(ROOT_DIR.'/fonctions.php');
     require(ROOT_DIR.'/connexion.php');

?>


<div class="col-md-7 mx-auto">
               <?php 
                    //traitement de l'action d'ajout des vignettes
                    if (isset($_POST['addthumbnails'])){
                         $collectionsIds = explode(',',$_POST['collectionsIds']);
                         foreach( $collectionsIds as $collection){
                                   ajoutVignettes( $bdd, $collection, $urlGallica );
                         }
                    }
               ?>

<?php
          //affichage du message indiquant si il y a des vignettes à ajouter ou pas
          $collections = getCollections($bdd);
          $collectionsIds = [];
          $messageGallicaOk = "Tout est à jour";
          $messageGallicaFail = '';
          $htmlresult = '';
          foreach ($collections as $one_collection){
               
               
               $nb_items = testVignettes($bdd, $one_collection['id']);

               if ($nb_items > 0){
                    $messageGallicaFail = 'Il y a des vignettes à ajouter';
               }
               $htmlresult .= '
               <tr>
               <td>'. $one_collection['title']. '</td>'.
               '<td>';
               if ($nb_items>0){
                    $htmlresult .=  '<img width=15" height="15" src="' . WEB_DIR . '/style/images/error.png" /> ';
                    $htmlresult .= '<span class="alert-accent">Il manque '. $nb_items . ' vignettes dans cette collection.</span>';
                    
                    $collectionsIds[] =  $one_collection['id'];
               }
               else{
                    $htmlresult .=  '<img width=15" height="15" src="' . WEB_DIR . '/style/images/check.png" />';
               }
               $htmlresult .= '</td>
               </tr>';
          
          
          }
          ?>
               
          <?php if (!empty($messageGallicaFail)):?>
               <div class="alert alert-danger text-center">
               <img src="<?= WEB_DIR ?>/style/images/error.png"/> <?= $messageGallicaFail ?>
               <form id="collection-form" action="." method="post" onsubmit="return confirm('Confirmez-vous l\'ajout des vignettes ?');">
                    <div class="d-flex justify-content-end m-3">   
                         <input type="submit"  class="btn btn-sm btn-info" value="Ajouter les vignettes manquantes"/>
                         <input type="hidden" name="collectionsIds" value="<?php echo implode(',', $collectionsIds); ?>"/>
                         <input type="hidden" name="addthumbnails" value="1"/>
                    </div>
               </form>
          <?php else: ?>
               <div class="alert alert-success text-center">
               <img src="<?= WEB_DIR ?>/style/images/check.png"/> <?= $messageGallicaOk ?>
          <?php endif; ?>
          </div>

                    
               <table class="table table-hover table-striped">
                    <th>Collections</th>
                    <th>Etat</th>
               
                    <?= $htmlresult; ?>

                    
               </table>
               
          
</div>




<?php
     require('../../views/common/footer.php');
?>
