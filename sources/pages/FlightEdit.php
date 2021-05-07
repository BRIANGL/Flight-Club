<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/05)
 * Detailed flight page here
 */

require_once("./controllers/flightEdit_controller.php");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Éditer un vol - Flight Club</title>
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
                        <h2 class="text-info">Éditer un vol</h2>
                        <p>Modifiez votre vol en détail</p>
                    </div>
                    <form style="max-width: 100%;" method="POST" action="">
                        <?php if ($message != "") { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erreur !</strong> <?= $message ?>
                            </div>
                        <?php } ?>
                        <?php if ($successMessage != "") { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Erreur !</strong> <?= $successMessage ?>
                            </div>
                        <?php } ?>
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
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="Nm_Plane" name="Nm_Plane" placeholder="Ex: Tecnam P2002" maxlength="45" value="<?= $userFlight['Nm_Plane'] ?>" required></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="No_Plane" name="No_Plane" required placeholder="Ex: F-XXXX" style="text-transform:uppercase" maxlength="45" value="<?= $userFlight['No_Plane'] ?>"></div>
                                        </td>
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
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="Cd_ICAO_Departure" name="Cd_ICAO_Departure" style="text-transform:uppercase" required placeholder="Ex: LFHN" minlength="4" maxlength="4" value="<?= $userFlight['Cd_ICAO_Departure'] ?>"></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="Cd_ICAO_Arrival" name="Cd_ICAO_Arrival" style="text-transform:uppercase" required placeholder="Ex: LSMP" minlength="4" maxlength="4" value="<?= $userFlight['Cd_ICAO_Arrival'] ?>"></div>
                                        </td>
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
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="date" id="Dt_Departure" name="Dt_Departure" value="<?= $userFlight['Dt_Departure'] ?>" required></div>
                                            <div class="mb-3"><input class="form-control item" type="time" id="Tm_Departure" name="Tm_Departure" value="<?= $userFlight['Tm_Departure'] ?>" required></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="date" id="Dt_Arrival" name="Dt_Arrival" value="<?= $userFlight['Dt_Arrival'] ?>" required></div>
                                            <div class="mb-3"><input class="form-control item" type="time" id="Tm_Arrival" name="Tm_Arrival" value="<?= $userFlight['Tm_Arrival'] ?>" required></div>
                                        </td>
                                        <td><?= computeTotalTime($userFlight['Dt_Departure'], $userFlight['Dt_Arrival'], $userFlight['Tm_Departure'], $userFlight['Tm_Arrival']) ?></td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th>Moteur allumé</th>
                                        <th>Moteur eteint</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="time" id="Tm_Engine_On" name="Tm_Engine_On" value="<?= $userFlight['Tm_Engine_On'] ?>"></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="time" id="Tm_Engine_Off" name="Tm_Engine_Off" value="<?= $userFlight['Tm_Engine_Off'] ?>"></div>
                                        </td>
                                        <td><?= computeTotalTime($userFlight['Dt_Departure'], $userFlight['Dt_Arrival'], $userFlight['Tm_Engine_On'], $userFlight['Tm_Engine_Off']) ?></td>
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
                                        <td>
                                            <div class="mb-3"><textarea class="form-control" id="Txt_Meteo" rows="3" name="Txt_Meteo" required placeholder="Ex: LSMP 060520Z AUTO VRB01KT 9999NDV BKN087 07/02 Q1012 RMK" maxlength="512"><?= $userFlight['Txt_Meteo'] ?></textarea></div>
                                        </td>
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
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="number" id="Nb_Passengers" name="Nb_Passengers" required min="0" max="99999999999" value="<?= $userFlight['Nb_Passengers'] ?>"></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="Cd_Flight_Type" name="Cd_Flight_Type" required placeholder="Ex: Navigation" maxlength="45" value="<?= $userFlight['Cd_Flight_Type'] ?>"></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="Cd_Flight_Mode" name="Cd_Flight_Mode" required placeholder="Ex: Solo" maxlength="45" value="<?= $userFlight['Cd_Flight_Mode'] ?>"></div>
                                        </td>
                                        <td>
                                            <div class="mb-3"><input class="form-control item" type="text" id="No_Flight" name="No_Flight" required style="text-transform:uppercase" placeholder="VFR, IFR, ..." value="<?= $userFlight['No_Flight'] ?>" required></div>
                                        </td>
                                        <td>
                                            <div class="mb-3">
                                                <div class="mb-3"><select class="form-select" name="Cd_Role" id="Cd_Role">
                                                        <?php
                                                        if ($pilotOfThisFlight['Cd_Role'] == "Pilot Flying") {
                                                        ?>
                                                            <option value="Pilot Flying" selected>PF (Pilot Flying, Pilote qui pilote)</option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option value="Pilot Flying">PF (Pilot Flying, Pilote qui pilote)</option>
                                                        <?php
                                                        }
                                                        if ($pilotOfThisFlight['Cd_Role'] == "Pilot not flying") {
                                                        ?>
                                                            <option value="Pilot not flying" selected>PNF (Pilot Not Flying, Pilote qui ne pilote pas)</option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option value="Pilot not flying">PNF (Pilot Not Flying, Pilote qui ne pilote pas)</option>
                                                        <?php
                                                        }
                                                        if ($pilotOfThisFlight['Cd_Role'] == "Passenger") {
                                                        ?>
                                                            <option value="Passenger" selected>PAX (Passager)</option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option value="Passenger">PAX (Passager)</option>
                                                        <?php
                                                        }
                                                        ?>

                                                    </select></div>
                                        </td>
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
                                        <td>
                                            <div class="mb-3"><textarea class="form-control" id="Txt_Note" rows="3" name="Txt_Note" maxlength="1024"><?= $userFlight['Txt_Note'] ?></textarea></div>
                                        </td>
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
                                    <tr>
                                        <td>Cell 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary" type="submit">Modifier le vol</button>
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