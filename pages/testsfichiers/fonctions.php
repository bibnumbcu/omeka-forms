<?php
//renvoie true si il trouve un espace dans le nom de fichier, false sinon
function testespace($name){
    if (strrpos($name, " ")===false)
      return false;
    else return true;
  }

//renvoie true si il trouve un accent dans le nom de fichier et false sinon
function testaccent($name){
    $pattern = "(á|â|à|å|ä|ð|é|ê|è|ë|í|î|ì|ï|ó|ô|ò|ø|õ|ö|ú|û|ù|ü|æ|ç|ß)";
    if (preg_match($pattern, $name)==1)
    return true;
    else
    return false;
}

//renvoie true si il trouve un espace dans le nom de fichier, false sinon
function testurl($url){
    $headers=get_headers($url);
    //var_dump($headers);
    if (stripos($headers[0],"200 OK"))
    return true;
    else
    return false;
}


?>