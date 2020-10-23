<?php
     $bodyclass = 'gallica';
     $pageTitle = 'Gallica : ajout des vignettes';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');
     
     require('fonctions.php');
     require(ROOT_DIR.'/fonctions.php');
     require(ROOT_DIR.'/connexion.php');

?>


<div class="content">
               <?php 
                    //traitement de l'action d'ajout des vignettes
                    if (isset($_POST['selectall'])){
                         $collectionsids = explode(',',$_POST['collectionsids']);
                         foreach( $collectionsids as $collection){
                                   ajoutVignettes( $bdd, $collection, $urlGallica );
                         }
                    }
               ?>

<?php
          //affichage du message indiquant si il y a des vignettes à ajouter ou pas
          $collections = getCollections($bdd);
          $collectionsIds = '';
          $messageGallicaOk = "Tout est à jour";
          $messageGallicaFail = '';
          $htmlresult = '';
          foreach ($collections as $one_collection){
               $nb_items = testVignettes($bdd, $one_collection['record_id']);
               if ($nb_items > 0){
                    $messageGallicaFail = 'Il y a des vignettes à ajouter';
               }
               $htmlresult .= '
                         <tr>
                              <td>'. $one_collection['text']. '</td>'.
                              '<td>';
               if ($nb_items==0)
                    $htmlresult .=  'Vignettes ok pour Gallica';
               else{
                    $htmlresult .= '<span class="alert">Il manque '. $nb_items . ' vignettes dans cette collection.</span>';
                    $collectionsIds .= ',' . $one_collection['record_id'];

               }
               $htmlresult .= '</td>
                         </tr>';
               

          }
          ?>
               
          <?php if (!empty($messageGallicaFail)):?>
               <div class="messageFail">
               <img src="<?= WEB_DIR ?>/style/images/error.png"/> <?= $messageGallicaFail ?>
          <?php else: ?>
          <div class="messageOk">
               <img src="<?= WEB_DIR ?>/style/images/check.png"/> <?= $messageGallicaOk ?>
          <?php endif; ?>
          </div>

          <form id="collection-form" action="." method="post">
               <?php if (!empty($messageGallicaFail)):?>
                    <div class="center">   
                                        <input type="checkbox" id="selectall" name="selectall">
                                        <label>Ajouter les vignettes manquantes</label>
                                        <input type="submit" value="Ajouter"/>

                    </div>
                <?php endif; ?>
               <table>
                    <?= $htmlresult; ?>

                    
               </table>
               <input type="hidden" name="collectionsids" value="<?= $collectionsIds; ?>"/>
          </form>
</div>




<?php
     require('../../views/common/footer.php');
?>
