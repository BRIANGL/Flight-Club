<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * Detailed flight page here
 */

require_once("./controllers/flightDetail_controller.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Détails du vol - Flight Club</title>
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
        <!-- Start: Contact Us Form -->
        <main class="page login-page">
            <section class="clean-block clean-form dark">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">Détails du vol</h2>
                        <p>Votre vol en détail</p>
                    </div>
                    <form style="max-width: 100%;">
                        <div class="table-responsive">
                        <h2 class="text-info">Aéronef</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Immatriculation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Nm_Plane']?></td>
                                        <td><?=$userFlight['No_Plane']?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <h2 class="text-info">Emplacement</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Cd_ICAO_Departure']?></td>
                                        <td><?=$userFlight['Cd_ICAO_Arrival']?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="text-info">Durée</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Dttm_Departure']?></td>
                                        <td><?=$userFlight['Dttm_Arrival']?></td>
                                        <td><?=computeTotalTime($userFlight['Dttm_Departure'],$userFlight['Dttm_Arrival'])?></td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th>Moteur allumé</th>
                                        <th>Moteur eteint</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Tm_Engine_On']?></td>
                                        <td><?=$userFlight['Tm_Engine_Off']?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="text-info">Météo</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Rapport du vol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Txt_Meteo']?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="text-info">Informations complémentaires</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Passagers</th>
                                        <th>Type de vol</th>
                                        <th>Mode de vol</th>
                                        <th>Vol</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Nb_Passengers']?></td>
                                        <td><?=$userFlight['Cd_Flight_Type']?></td>
                                        <td><?=$userFlight['Cd_Flight_Mode']?></td>
                                        <td><?=$userFlight['No_Flight']?></td>
                                        <td><?=$pilotOfThisFlight['Cd_Role']?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="text-info">Notes</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Notes du vol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$userFlight['Txt_Note']?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h2 class="text-info">Images</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Images du vol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?=showAllPicturesFromTheFlight($flightId)?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </section>
        </main><!-- End: Contact Us Form -->
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