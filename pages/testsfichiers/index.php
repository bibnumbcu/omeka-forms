<?php
     $bodyclass = 'tests';
     $pageTitle = 'Tests des fichiers d\'import';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');
?>


<div class="colonnegauche">
        <form enctype="multipart/form-data" action="." method="post">
            <fieldset>
                <input type="hidden" name="MAX_FILE_SIZE" value="2000000000" />
                    <label for="fichiercsv">Téléchargez un fichier csv</label>
                    <input name="fichiercsv" type="file" accept=".csv"/>
            </fieldset>
            <input type="submit" value="Envoyer le fichier" />
        </form>
    </div>

<?php

    if (isset($_FILES['fichiercsv'])):
        $uploadfile =  ROOT_DIR.'/'.$uploadDir.'/'.$_FILES['fichiercsv']['name'];
        
        if (!move_uploaded_file($_FILES['fichiercsv']['tmp_name'], $uploadfile ))
        exit("Erreur de téléchargement du fichier.");

        $file = fopen($uploadfile, 'r') or exit("unable to open file ($uploadfile)");
     
        $first_line = true;
        $cle = 0;
        $urls = array();
        //on récupère la colonne url du fichier csv
        while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
        if ($first_line==true){
            foreach ($data as $key=>$one_line){
                if (strtolower($one_line)=='url')
                    $cle = $key;
            }
            $first_line=false;
            continue;
        }
        $urls[] = $data[$cle];
        }
       
        fclose($file);
   
?>
<div class="content">
    <?php 
        require ('fonctions.php');

        // /** on teste si les noms de fichiers correspondent */
        $resultats = '<h2>Résultats</h2><table><tr><th>Nom du fichier</th><th>Présent sur le serveur</th></tr>';
        $errors_found = false;
        foreach($urls as $one_url){
            $errors = false;
            $message = '<ul>';
            $parts = explode('/',$one_url);
            $filename = end($parts);
            if (empty($filename)){
                $errors = true;
                $message .= '<li>le champ est vide</li>';
            }
            if (testespace($filename)){
                $errors = true;
                $message .= '<li>Il y a un espace dans le nom de fichier</li>';
            }
            if (testaccent($filename)){
                $errors = true;
                $message .= '<li>Il y a un accent dans le nom de fichier</li>';
            }
            if (!$errors){
                
                if (!file_exists($filesToImportDir.'/'.$filename)){
                    $errors = true;
                    $message .= '<li>Le fichier n\'existe pas sur le serveur.</li>';
                }
                else if(!testUrlOk($one_url)){
                    $errors = true;
                    $message .= '<li>L\'url du fichier est incorrecte</li>';
                }
            }

             $message .='</ul>';
            if ($errors){
                $resultats .= '<tr>';
                $resultats .= '<td>'.$filename.'</td>';
                $resultats .= '<td class="alert">'.$message.'</td>';
                $resultats .= '</tr>';
            $errors_found = true;
            }
        }
        $resultats .= '</table>';
        if (!$errors_found)
            $resultats = '<p>Aucune erreur n\'a été trouvée</p>';
        echo $resultats;
    ?>

</div>

<?php  endif; ?>


<?php
     require('../../views/common/footer.php');
?>
