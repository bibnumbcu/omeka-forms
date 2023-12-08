<?php
     $bodyclass = 'tests';
     $pageTitle = 'Tests des fichiers d\'import';
     require('../../config.php');
     require(ROOT_DIR.'/views/common/header.php');
?>


<div class="col-md-4 d-flex mx-auto">
    
    <form class="col-md-9" enctype="multipart/form-data" action="." method="post">
            <fieldset>
                <input type="hidden" name="MAX_FILE_SIZE" value="2000000000" />
                <label class="form-label" for="fichiercsv" rel="tooltip" title="Fichier CSV">Téléchargez un fichier CSV</label>
                <div class="input-group mb-3">
                    <input class="form-control" name="fichiercsv" type="file" accept=".csv"/>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input " type="checkbox" name="files_exists" id="files_exists" checked >
                    <label class="form-check-label" for="files_exists">
                        Tester la présence du fichier sur le serveur
                    </label>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input " type="checkbox" name="url_format" id="url_format" checked>
                    <label class="form-check-label" for="url_format">
                        Tester le format de l'url
                    </label>
                </div>
            </fieldset>
            <input type="submit" class="btn btn-info" value="Envoyer" />
        </form>
        
        <p class="m-3 col-md-3">
            Ce formulaire prend un fichier csv et teste les urls si une colonne est nommée "url"
        </p>
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
   

        $files_exists_tests = true;
        if (!isset($_POST['files_exists'])){
                $files_exists_tests = false;
        }

        $url_format_test = true;
        if (!isset($_POST['url_format'])){
                $url_format_test = false;
        }
?>
<div class="col-md-9 mx-auto">
    <?php 
        require ('fonctions.php');

        // /** on teste si les noms de fichiers correspondent */
        $resultats = '<h2>Résultats</h2><table class="table table-hover table-striped"><tr><th>Nom du fichier</th><th>Erreurs</th></tr>';
        $errors_found = false;

        foreach($urls as $one_url){
            $errors = false;
            $message = '<ul>';
            $parts = explode('/',$one_url);
            $filename = end($parts);
            if (empty($filename)){
                $errors = true;
                $message .= '<li class="alert-empty">le champ est vide</li>';
            }
            if (testespace($filename)){
                $errors = true;
                $message .= '<li class="alert-space">Il y a un espace dans le nom de fichier</li>';
            }
            if (testaccent($filename)){
                $errors = true;
                $message .= '<li class="alert-accent">Il y a un accent dans le nom de fichier</li>';
            }
            if (!$errors){
                if ($files_exists_tests && !file_exists($filesToImportDir.'/'.$filename)){
                    $errors = true;
                    $message .= '<li class="alert-not-exist-file">Le fichier n\'existe pas sur le serveur.</li>';
                }
                else if($url_format_test && !preg_match($filesUrlPattern, $one_url)){
                    $errors = true;
                    $message .= '<li class="alert-incorrect-url">L\'url du fichier est incorrecte</li>';
                }
            }

             $message .='</ul>';
            if ($errors){
                $resultats .= '<tr>';
                $resultats .= '<td>'.$filename.'</td>';
                $resultats .= '<td>'.$message.'</td>';
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
