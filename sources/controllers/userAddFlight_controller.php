<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
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

//check if the user is not already connected
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//we hide error to the user to only show our custom errors
error_reporting(0);

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
$engineTime = true;
$idFlight = 0;
$error = "";

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
    $result = "";
    $newDate = explode("T", $date);
    try {
        $result = checkdate(explode("-", $newDate[0])[1], explode("-", $newDate[0])[2], explode("-", $newDate[0])[0]);
    } catch (\Throwable $th) {
        $result = null;
    }
    return $result;
}

//we reset the message
$message = array();
$successMessage = "";
//if all data are not empty
if (!empty($btn)) {

    if (
        !empty($role) && !empty($flight) && !empty($dateDeparture) && !empty($dateArrival) &&
        !empty($typeAircraft) && !empty($registrationPlane) && !empty($icaoDeparture) && !empty($icaoArrival) && !empty($flightType)
        && !empty($flightMode) && !empty($weather) && $passengers != ""
    ) {
    } else {
        $message[] = "Entrez tout les champs";
    }
    //we check that the data is valid
    if (strlen($icaoDeparture) == 4 && strlen($icaoArrival) == 4) {
    } else {
        $message[] = "Vérifiez les codes ICAO";
    }
    //check that dates are valid
    try {
        if (validateDate($dateDeparture) && validateDate($dateArrival)) {
        } else {
            $message[] = "La date n'est pas valide";
        }
    } catch (\Throwable $th) {
        $message[] = "La date n'est pas valide";
    }

    //check the validity of the hours of the take off time and landing time
    try {
        if (checkTime(explode("T", $dateDeparture)[1]) && checkTime(explode("T", $dateArrival)[1])) {
            $validTime = true;
            //check the validity of the hours for the engine off and engine on time
            if (!empty($timeEngineOn) && !empty($timeEngineOff)) {
                $engineTime = true;
                if (checkTime($timeEngineOn) && checkTime($timeEngineOff)) {
                    $validTime = true;
                } else {
                    $validTime = false;
                }
            } else {
                $timeEngineOff = null;
                $timeEngineOn = null;
            }
        } else {
            $message[] = "La date et l'heure saisie ne sont pas valides";
        }
    } catch (\Throwable $th) {
        $message[] = "L'heure saisie n'est pas valide";
    }

    if ($validTime && $engineTime) {
        //check that the type of aircraft doesn't blow the database
    } else {
        $message[] = "L'heure saisie n'est pas valide";
    }
    if (strlen($typeAircraft) <= 45) {
        //check that the registration of the aircraft doesn't blow the database
    } else {
        $message[] = "Le type d'avion est trop long";
    }
    if (strlen($registrationPlane) <= 45) {
        //check that the flight type doesn't blow the database
    } else {
        $message[] = "L'immatriculation de l'avion est trop longue";
    }
    if (strlen($flightType) <= 45) {
        //check that the flight mode doesn't blow the database
    } else {
        $message[] = "Le type de vol est trop long";
    }
    if (strlen($flightMode) <= 45) {
        //check that the weather doesn't blow the database
    } else {
        $message[] = "Le mode de vol est trop long";
    }
    if (strlen($weather) <= 512) {
        //check that the passengers number doesn't blow the database
    } else {
        $message[] = "La météo est trop longue";
    }
    if ($passengers <= 99999999999 && $passengers >= 0) {
        //check that the notes doesn't blow the database
    } else {
        $message[] = "Le nombre de passagers est invalide";
    }
    if (strlen($notes) <= 1024) {
        //every data is correct
    } else {
        $message[] = "Les notes sonts trop longues";
    }

    //we check that every data is ok
    if (empty($message)) {

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

            //we add the flight
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
            //we show a success message
            $successMessage = "Vol enregistrer avec succès!";
        } catch (\Throwable $th) {//we show an error message
            $message = "Une erreure est survenue. Merci de réessayer";
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
            if ($_FILES['media']['name'][0] != "") {
                DBConnection::startTransaction();
                //we check the size of the image
                foreach ($_FILES['media']['size'] as $key => $value) {
                    if ($value > $MAX_FILE_SIZE) {
                        $error = 'File too heavy.';
                        DBConnection::rollback();
                    } else {
                        $size_total += $value;
                    }
                }
                //if there is images we check each media size
                if (isset($_FILES['media'])) {
                    
                    for ($i = 0; $i < $nb_files; $i++) {
                        $errorimg = $_FILES['media']["error"][$i];
                        if ($error == 'File too heavy.' || $size_total > $MAX_POST_SIZE) {
                            $error = "Fichier trop volumineux!";
                            DBConnection::rollback();
                        } else {
                            //we check the mime type
                            $separator = '/';
                            $extension = explode($separator, mime_content_type($_FILES['media']['tmp_name'][$i]))[1];
                            $type = explode($separator, mime_content_type($_FILES['media']['tmp_name'][$i]))[0];

                            if (!in_array($type, $types)) {
                                $error = "erreur dans le type de fichier";
                                DBConnection::rollback();
                            } else {
                                if ($error != "erreur dans le type de fichier") {
                                    if ($errorimg[0] == 0) { //if there is no error, we add the file
                                        $tmp_name = $_FILES['media']["name"][$i];
                                        $name = explode(".", $tmp_name);
                                        $name = $name[0] . uniqid() . "." . $name[1];

                                        if (move_uploaded_file($_FILES['media']["tmp_name"][$i], $default_dir . $type . "/" . $name)) {
                                            //add the filename in the database
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
if ($error != "") {
    $message .= " " . $error;
}
