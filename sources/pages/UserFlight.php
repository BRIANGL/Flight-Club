<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/05)
 * User logbook page here
 */

require_once("./controllers/userLogBook_controller.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TPI</title>
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
        <!-- Start: User Log Book Form -->
        <main class="page login-page">
            <section class="clean-block clean-form dark">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">Vos vols</h2>
                        <p>Total des heures: <?=computeTotal($userFlight)?></p>
                    </div>
                    <form style="max-width: 100%;">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Vol</th>
                                        <th>Date du décollage</th>
                                        <th>Date de l'atterrissage</th>
                                        <th>Heure de démmarage moteur</th>
                                        <th>Heure de moteur coupé</th>
                                        <th>Modèle d'avion</th>
                                        <th>Immatriculation</th>
                                        <th>Aérodrome de départ</th>
                                        <th>Aérodrome d'arrivée</th>
                                        <th>Type de vol</th>
                                        <th>Mode de vol</th>
                                        <th>Role</th>
                                        <th>Détails</th>
                                        <th>Éditer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    showFlight($userFlight);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </section><!-- End: User Log Book Form -->
        </main>
    </div>
    <!-- Start: Footer Dark -->
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