<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?= $mainTitle; ?> | <?= $pageTitle; ?></title>
	
    <link href="<?= WEB_DIR; ?>/style/css/style.css" media="screen" rel="stylesheet" type="text/css" >

    <link href="https://cdn.uca.fr/v2/css/uca.min.css" media="screen" rel="stylesheet" type="text/css" >
</head>

<body class="<?= $bodyclass; ?>">
    <header>
        <!--
        <a href="<?= WEB_DIR; ?>"><img src="<?= WEB_DIR; ?>/style/images/logo.png"></a>
        <div id="title"><?= $mainTitle; ?></div>
        <nav id="mainmenu">
            <a href="<?= WEB_DIR ?>/index.php" <?php if ($bodyclass=='home') echo 'class="active"'; ?>>Accueil</a>
            <a href="<?= WEB_DIR ?>/pages/testsfichiers/index.php" <?php if ($bodyclass=='tests') echo 'class="active"'; ?>>Test des fichiers</a>
            <a href="<?= WEB_DIR ?>/pages/gallica/index.php" <?php if ($bodyclass=='gallica') echo 'class="active"'; ?>>Gallica</a>
            <a href="<?= WEB_DIR ?>/pages/geolocalisation/index.php" <?php if ($bodyclass=='geolocalisation') echo 'class="active"'; ?>>Géolocalisation</a>
        </nav>
        -->

        <nav id="main-menu" class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= WEB_DIR ?>/index.php"><img width="40" height="40" src="<?= WEB_DIR; ?>/style/images/logo.png"> Formulaires de la GED</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($bodyclass=='home') echo ' active '; ?>"  href="<?= WEB_DIR ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link <?php if ($bodyclass=='tests') echo ' active '; ?>" href="<?= WEB_DIR ?>/pages/testsfichiers/index.php" >Test des fichiers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($bodyclass=='gallica') echo ' active '; ?>" href="<?= WEB_DIR ?>/pages/gallica/index.php" >Gallica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($bodyclass=='geolocalisation') echo ' active '; ?>" href="<?= WEB_DIR ?>/pages/geolocalisation/index.php" >Géolocalisation</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
    </header>


<h1 class="text-center"><?= $pageTitle; ?></h1>
<div class="container-fluid">
