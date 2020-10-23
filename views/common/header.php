<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?= $mainTitle; ?> | <?= $pageTitle; ?></title>
	<link href="<?= WEB_DIR; ?>/style/css/style.css" media="screen" rel="stylesheet" type="text/css" >

</head>

<body class="<?= $bodyclass; ?>">
    <header>
        <a href="<?= WEB_DIR; ?>"><img src="<?= WEB_DIR; ?>/style/images/logo.png"></a>
        <div id="title"><?= $mainTitle; ?></div>
        <nav id="mainmenu">
            <a href="<?= WEB_DIR ?>/index.php" <?php if ($bodyclass=='home') echo 'class="active"'; ?>>Accueil</a>
            <a href="<?= WEB_DIR ?>/pages/testsfichiers/index.php" <?php if ($bodyclass=='tests') echo 'class="active"'; ?>>Test des fichiers</a>
            <a href="<?= WEB_DIR ?>/pages/gallica/index.php" <?php if ($bodyclass=='gallica') echo 'class="active"'; ?>>Gallica</a>
            <a href="<?= WEB_DIR ?>/pages/geolocalisation/index.php" <?php if ($bodyclass=='geolocalisation') echo 'class="active"'; ?>>Géolocalisation</a>
        </nav>
    </header>


<h1><?= $pageTitle; ?></h1>
<div id="container">
