<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Add flight logic here
 */

//require all the flight functions
require_once("./sql/flightDAO.php");
//require all the media functions
require_once("./sql/mediaDAO.php");
//require all the database functions
require_once("./sql/dbConnection.php");

use FlightClub\sql\FlightDAO;
use FlightClub\sql\MediaDAO;
use FlightClub\sql\DBConnection;
use M152\sql\MediaDAO as SqlMediaDAO;

//check if the user is not already connected
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//we filter the user input
$role = filter_input(INPUT_POST, "Cd_Role", FILTER_SANITIZE_STRING);
$flight = filter_input(INPUT_POST, "No_Flight", FILTER_SANITIZE_STRING);
$dateDeparture = filter_input(INPUT_POST, "Dttm_Departure", FILTER_SANITIZE_STRING);
$dateArrival = filter_input(INPUT_POST, "Dttm_Arrival", FILTER_SANITIZE_STRING);
$timeEngineOn = filter_input(INPUT_POST, "Tm_Engine_On", FILTER_SANITIZE_STRING);
$timeEngineOff = filter_input(INPUT_POST, "Tm_Engine_Off", FILTER_SANITIZE_STRING);
$typeAircraft = filter_input(INPUT_POST, "Nm_Plane", FILTER_SANITIZE_STRING);
$registrationPlane = filter_input(INPUT_POST, "No_Plane", FILTER_SANITIZE_STRING);
$icaoDeparture = filter_input(INPUT_POST, "Cd_ICAO_Departure", FILTER_SANITIZE_STRING);
$icaoArrival = filter_input(INPUT_POST, "Cd_ICAO_Arrival", FILTER_SANITIZE_STRING);
$flightType = filter_input(INPUT_POST, "Cd_Flight_Type", FILTER_SANITIZE_STRING);
$flightMode = filter_input(INPUT_POST, "Cd_Flight_Mode", FILTER_SANITIZE_STRING);
$weather = filter_input(INPUT_POST, "Txt_Meteo", FILTER_SANITIZE_STRING);
$passengers = filter_input(INPUT_POST, "Nb_Passengers", FILTER_SANITIZE_NUMBER_INT);
$notes = filter_input(INPUT_POST, "Txt_Note", FILTER_SANITIZE_STRING);
$btn = filter_input(INPUT_POST, 'AddFlight', FILTER_SANITIZE_STRING);
$allflight = "";
$validTime = true;
$idFlight = 0;
/**
 * Function found on Stack overflow to check if a time is valid. Found at: https://stackoverflow.com/questions/3964972/validate-that-input-is-in-this-time-format-hhmm
 *
 * @param string $time
 * @return bool
 */
function checkTime($time)
{
    //check that the first char is either 0, a 1 or a 2, then the second character between 0 and 9. Then it looks for the ":" character. Then it check that the first minute number is between 0 and 5, finnaly, it check that the last number is between 0 and 9
    if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time)) {
        return true;
    } else {
        return false;
    }
}

/**
 * function that validate the date
 *
 * @param string $date
 * @return bool
 */
function validateDate($date)
{
    $newDate = explode("T", $date);
    return checkdate(explode("-", $newDate[0])[1], explode("-", $newDate[0])[2], explode("-", $newDate[0])[0]);
}

//we reset the message
$message = "";
$successMessage = "";
//if all data are not empty
if (!empty($btn)) {

    if (
        !empty($role) && !empty($flight) && !empty($dateDeparture) && !empty($dateArrival) &&
        !empty($typeAircraft) && !empty($registrationPlane) && !empty($icaoDeparture) && !empty($icaoArrival) && !empty($flightType)
        && !empty($flightMode) && !empty($weather) && $passengers != ""
    ) {
        //we check that the data is valid
        if (strlen($icaoDeparture) == 4 && strlen($icaoArrival) == 4) {
            //check that dates are valid
            if (validateDate($dateDeparture) && validateDate($dateArrival)) {
                //check the validity of the hours of the take off time and landing time
                if (checkTime(explode("T", $dateDeparture)[1]) && checkTime(explode("T", $dateArrival)[1])) {
                    $validTime = true;
                    //check the validity of the hours for the engine off and engine on time
                    if (!empty($timeEngineOn) && !empty($timeEngineOff)) {
                        if (checkTime($timeEngineOn) && checkTime($timeEngineOff)) {
                            $validTime = true;
                        } else {
                            $validTime = false;
                        }
                    } else {
                        $timeEngineOff = null;
                        $timeEngineOn = null;
                    }
                    if ($validTime) {
                        //check that the type of aircraft doesn't blow the database
                        if (strlen($typeAircraft) <= 45) {
                            //check that the registration of the aircraft doesn't blow the database
                            if (strlen($registrationPlane) <= 45) {
                                //check that the flight type doesn't blow the database
                                if (strlen($flightType) <= 45) {
                                    //check that the flight mode doesn't blow the database
                                    if (strlen($flightMode) <= 45) {
                                        //check that the weather doesn't blow the database
                                        if (strlen($weather) <= 512) {
                                            //check that the passengers number doesn't blow the database
                                            if ($passengers <= 99999999999 && $passengers >= 0) {
                                                //check that the notes doesn't blow the database
                                                if (strlen($notes) <= 1024) {
                                                    //every data is correct

                                                    //we check with is the latest id in the database
                                                    $allflight = FlightDAO::getAllFlightOrderById();
                                                    $idFlight = 0;
                                                    if (!empty($allflight)) {
                                                        $idFlight = $allflight[0]['Id_Flight'] + 1;
                                                    } else {
                                                        $idFlight = 0;
                                                    }
                                                    try {

                                                        //converting departure datetime to a good datetime
                                                        $dateDeparture = explode("T", $dateDeparture);
                                                        $dateDeparture = $dateDeparture[0] . " " . $dateDeparture[1];

                                                        //converting arrival datetime to a good datetime
                                                        $dateArrival = explode("T", $dateArrival);
                                                        $dateArrival = $dateArrival[0] . " " . $dateArrival[1];

                                                        FlightDAO::addFlight(
                                                            $idFlight,
                                                            $_SESSION['userID'],
                                                            $role,
                                                            strtoupper($flight),
                                                            $dateDeparture,
                                                            $dateArrival,
                                                            $timeEngineOn,
                                                            $timeEngineOff,
                                                            $typeAircraft,
                                                            strtoupper($registrationPlane),
                                                            strtoupper($icaoDeparture),
                                                            strtoupper($icaoArrival),
                                                            $flightType,
                                                            $flightMode,
                                                            $weather,
                                                            $passengers,
                                                            $notes
                                                        );
                                                        $successMessage = "Vol enregistrer avec succès!";
                                                    } catch (\Throwable $th) {
                                                        $message = "Une erreure est survenue. merci de réessayer";
                                                    }
                                                } else {
                                                    $message = "Les notes sonts trop longues";
                                                }
                                            } else {
                                                $message = "Le nombre de passagers est invalide";
                                            }
                                        } else {
                                            $message = "La météo est trop longue";
                                        }
                                    } else {
                                        $message = "Le mode de vol est trop long";
                                    }
                                } else {
                                    $message = "Le type de vol est trop long";
                                }
                            } else {
                                $message = "L'immatriculation de l'avion est trop longue";
                            }
                        } else {
                            $message = "Le type d'avion est trop long";
                        }
                    } else {
                        $message = "L'heure saisie n'est pas valide";
                    }
                } else {
                    $message = "L'heure saisie n'est pas valide";
                }
            } else {
                $message = "La date n'est pas valide";
            }
        } else {
            $message = "Vérifiez les codes ICAO";
        }
    } else {
        $message = "Entrez tout les champs";
    }
    ////FILE UPLOAD

    $name = "";
    $lienimg = "";
    $default_dir = "./media/";
    $errorimg = "";
    $size_total = 0;
    $error = "";
    $type = '';
    $extension = '';
    $nb_files = count($_FILES['media']['name']);
    $MAX_FILE_SIZE = 3145728;    // 3MB in bytes
    $MAX_POST_SIZE = 73400320;  // 70MB in bytes

    $extensions = array(
        "image" => array('.png', '.gif', '.jpg', '.jpeg'),
    );
    // available types of file
    $types = array('image');

    // si on souhaite envoyer quelque chose...
    if ($btn != 'null') {
        if ($_FILES['media']['name'][0] != "") {
            DBConnection::startTransaction();
            foreach ($_FILES['media']['size'] as $key => $value) {
                if ($value > $MAX_FILE_SIZE) {
                    $error = 'File too heavy.';
                    DBConnection::rollback();
                } else {
                    $size_total += $value;
                }
            }

            if (isset($_FILES['media'])) {
                for ($i = 0; $i < $nb_files; $i++) {
                    $errorimg = $_FILES['media']["error"][$i];
                    if ($error == 'File too heavy.' || $size_total > $MAX_POST_SIZE) {
                        $error = "Fichier trop volumineux!";
                        DBConnection::rollback();
                    } else {
                        $separator = '/';
                        $extension = explode($separator, mime_content_type($_FILES['media']['tmp_name'][$i]))[1];
                        $type = explode($separator, mime_content_type($_FILES['media']['tmp_name'][$i]))[0];

                        if (!in_array($type, $types)) {
                            $error = "erreur dans le type de fichier";
                            DBConnection::rollback();
                        } else {
                            if ($error != "erreur dans le type de fichier") {
                                if ($errorimg[0] == 0) {
                                    //echo "upload reussi";
                                    $tmp_name = $_FILES['media']["name"][$i];
                                    $name = explode(".", $tmp_name);
                                    $name = $name[0] . uniqid() . "." . $name[1];

                                    if (move_uploaded_file($_FILES['media']["tmp_name"][$i], $default_dir . $type . "/" . $name)) {
                                        //ajout du nom du fichier dans la bd
                                        MediaDAO::changePath($name, $tmp_name[$i]);
                                        $lienimg = $default_dir . $type . "/" . $name;
                                    } else {
                                        DBConnection::rollback();
                                    }
                                }
                                try {
                                    MediaDAO::addmedia($lienimg, $idFlight);

                                    DBConnection::commit();
                                } catch (\Throwable $th) {
                                    $error = $th;
                                    DBConnection::rollback();
                                }
                            } else {
                                DBConnection::rollback();
                            }
                        }
                    }
                }
            }
        }
    }
}
$message = $error;