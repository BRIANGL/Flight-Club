<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/05)
 * User logbook add flight page here
 */
require_once("./controllers/userAddFlight_controller.php");
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
                        <h2 class="text-info">Ajouter un vol</h2>
                        <p>Ajoutez un vol a votre carnet de vol</p>
                    </div>
                    <form method="POST" action="" enctype="multipart/form-data">
                    <?php if ($message != "") { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erreur !</strong> <?= $message ?>
                            </div>
                        <?php } ?>
                        <?php if ($successMessage != "") { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Succès !</strong> <?= $successMessage ?>
                            </div>
                        <?php } ?>
                        <div class="mb-3"><label class="form-label" for="Cd_Role">Role</label><select class="form-select" name="Cd_Role" id="Cd_Role">
                        <option value="Pilot Flying">PF (Pilot Flying, Pilote qui pilote)</option>
                        <option value="Pilot not flying">PNF (Pilot Not Flying, Pilote qui ne pilote pas)</option>
                        <option value="Passenger">PAX (Passager)</option>
                        </select></div>
                        <div class="mb-3"><label class="form-label" for="No_Flight">Vol</label><input class="form-control item" type="text" id="No_Flight" name="No_Flight" required style="text-transform:uppercase" placeholder="VFR, IFR, ..." value="<?=$flight?>"></div>
                        <div class="mb-3"><label class="form-label" for="Dttm_Departure">Date du décollage</label><input class="form-control item" type="datetime-local" id="Dttm_Departure" name="Dttm_Departure" required placeholder="jj-mm-YYYY hh:mm" value="<?=$dateDeparture?>"></div>
                        <div class="mb-3"><label class="form-label" for="Dttm_Arrival">Date de l'atterissage</label><input class="form-control item" type="datetime-local" id="Dttm_Arrival" name="Dttm_Arrival" required placeholder="jj-mm-YYYY hh:mm" value="<?=$dateArrival?>"></div>
                        <div class="mb-3"><label class="form-label" for="Tm_Engine_On">Heure de démmarage moteur</label><input class="form-control item" type="time" id="Tm_Engine_On" name="Tm_Engine_On" value="<?=$timeEngineOn?>"></div>
                        <div class="mb-3"><label class="form-label" for="Tm_Engine_Off">Heure d'arret moteur</label><input class="form-control item" type="time" id="Tm_Engine_Off" name="Tm_Engine_Off" value="<?=$timeEngineOff?>"></div>
                        <div class="mb-3"><label class="form-label" for="Nm_Plane">Type d'aéronef</label><input class="form-control item" type="text" id="Nm_Plane" name="Nm_Plane" required placeholder="Ex: Tecnam P2002" maxlength="45" value="<?=$typeAircraft?>"></div>
                        <div class="mb-3"><label class="form-label" for="No_Plane">Immatriculation</label><input class="form-control item" type="text" id="No_Plane" name="No_Plane" required placeholder="Ex: F-XXXX" style="text-transform:uppercase" maxlength="45" value="<?=$registrationPlane?>"></div>
                        <div class="mb-3"><label class="form-label" for="Cd_ICAO_Departure">ICAO de départ</label><input class="form-control item" type="text" id="Cd_ICAO_Departure" name="Cd_ICAO_Departure" style="text-transform:uppercase" required placeholder="Ex: LFHN" minlength="4" maxlength="4" value="<?=$icaoDeparture?>"></div>
                        <div class="mb-3"><label class="form-label" for="Cd_ICAO_Arrival">ICAO d'arrivée</label><input class="form-control item" type="text" id="Cd_ICAO_Arrival" name="Cd_ICAO_Arrival" style="text-transform:uppercase" required placeholder="Ex: LSMP" minlength="4" maxlength="4" value="<?=$icaoArrival?>"></div>
                        <div class="mb-3"><label class="form-label" for="Cd_Flight_Type">Type de vol</label><input class="form-control item" type="text" id="Cd_Flight_Type" name="Cd_Flight_Type" required placeholder="Ex: Navigation" maxlength="45" value="<?=$flightType?>"></div>
                        <div class="mb-3"><label class="form-label" for="Cd_Flight_Mode">Mode de vol</label><input class="form-control item" type="text" id="Cd_Flight_Mode" name="Cd_Flight_Mode" required placeholder="Ex: Solo" maxlength="45" value="<?=$flightMode?>"></div>
                        <div class="mb-3"><label class="form-label" for="Txt_Meteo">Météo</label><textarea class="form-control" id="Txt_Meteo" rows="3" name="Txt_Meteo" required placeholder="Ex: LSMP 060520Z AUTO VRB01KT 9999NDV BKN087 07/02 Q1012 RMK" maxlength="512"><?=$weather?></textarea></div>
                        <div class="mb-3"><label class="form-label" for="Nb_Passengers">Nombre de passagers</label><input class="form-control item" type="number" id="Nb_Passengers" name="Nb_Passengers" required min="0" max="99999999999" value="<?=$passengers?>"></div>
                        <div class="mb-3"><label class="form-label" for="Txt_Note">Notes</label><textarea class="form-control" id="Txt_Note" rows="3" name="Txt_Note" maxlength="1024"><?=$notes?></textarea></div>
                        <div class="mb-3"><input type="file" files name="media[]" multiple accept=".png, .gif, .jpg, .jpeg">(.gif,.png,.jpeg,.jpg seulement)</div>
                        <button class="btn btn-primary" type="submit" name="AddFlight" value="submit">Enregistrer le vol</button>
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