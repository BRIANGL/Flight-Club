<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Accueil - Flight Club</title>
    <meta name="description" content="TPI GOLAY Brian">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=2fc55c83fda89d85c10ec2813155ab55">
    <link rel="manifest" href="manifest.json?h=44264fee9c6d05944711595970c43d33">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="assets/fonts/typicons.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="assets/css/vanilla-zoom.min.css?h=e737f76df021b46fa7180f48799a5d20">
</head>

<body style="display: flex;flex-direction: column;height: 100vh;">
    <div style="flex: 1 0 auto;">
        <?php
        require_once("./assets/php/nav.php");
        ?>
        <main class="page landing-page">
        <section class="clean-block clean-hero" style="background-image: url(&quot;assets/img/HeadImg.png&quot;);color: rgba(9, 162, 255, 0.85);">
                <div class="text">
                    <h2>Flight Club</h2>
                    <?php
                    if ($_SESSION['connected']) { ?>
                        <p>Un carnet de vol disponible partout dans le monde</p><a class="btn btn-outline-light btn-lg" href="?page=logbook">Enregistrer un vol</a>
                    <?php
                    } else { ?>
                        <p>Un carnet de vol disponible partout dans le monde</p><a class="btn btn-outline-light btn-lg" href="?page=login">Enregistrer un vol</a>
                    <?php }
                    ?>

                </div>
            </section>
            <section class="clean-block clean-info dark">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">Flight Club</h2>
                        <p>Vous en avez marre de noter vos heures de vol dans votre carnet qui reste à l'aéroclub? Ne cherchez pas plus loin, Flight Club est le carnet de vol disponible partout en 1 clique</p>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6"><img class="img-thumbnail" src="assets/img/DualTakeOff.png?h=5a16d46fccd884924ce66752802d76c5"></div>
                        <div class="col-md-6">
                            <h3>En équipe</h3>
                            <div class="getting-started-info">
                                <p>Rejoignez un groupe de passionnés et volez sans retenue. Les heures de vol montent plus vite à plusieurs</p>
                            </div>
                            <?php
                    if ($_SESSION['connected']) { ?>
                        <a class="btn btn-outline-primary btn-lg" href="?page=GroupMenu">Rejoindre un groupe</a>
                    <?php
                    } else { ?>
                        <a class="btn btn-outline-primary btn-lg" href="?page=login">Rejoindre un groupe</a>
                    <?php }
                    ?>
                        </div>
                    </div>
                </div>
            </section>
            <section class="clean-block features">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">Fonctionnalités</h2>
                        <p>Un carnet de vol simple et accessible en tout temps</p>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-5 feature-box"><i class="far fa-paper-plane icon"></i>
                            <h4>Rapide</h4>
                            <p>Le site est rapide et léger</p>
                        </div>
                        <div class="col-md-5 feature-box"><i class="icon-pencil icon"></i>
                            <h4>Simple</h4>
                            <p>Enregistrez vos vol est super-facile</p>
                        </div>
                        <div class="col-md-5 feature-box"><i class="typcn typcn-weather-partly-sunny icon"></i>
                            <h4>Complet</h4>
                            <p>Enregistrez les données de votre vol comme jamais auparavant avec des détails importants&nbsp;</p>
                        </div>
                        <div class="col-md-5 feature-box"><i class="icon-plane icon"></i>
                            <h4>Accessible dans le monde entier</h4>
                            <p>Accédez à vos données dans le monde entier</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div><!-- Start: Footer Dark -->
    <?php
    require_once("./assets/php/footer.php");
    ?>
    <!-- End: Footer Dark -->
    <script src="assets/bootstrap/js/bootstrap.min.js?h=1eb47230ed13e88113270f63f470e160"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/vanilla-zoom.js?h=6a37ea9c996b05f763161c73127d66bc"></script>
    <script src="assets/js/theme.js?h=a083aeb15550c5e1266c666e8a5846d9"></script>
</body>

</html>