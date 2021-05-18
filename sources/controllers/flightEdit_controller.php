<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Flight edit logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/flightDAO.php";
require_once "./sql/dbConnection.php";
require_once "./sql/mediaDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\DBConnection;
use FlightClub\sql\MediaDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//we hide error to the user to only show our custom errors
error_reporting(0);

//filter the id from the url
$flightId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
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
$btn = filter_input(INPUT_POST, 'btnModify', FILTER_SANITIZE_STRING);
$imgToDelete = filter_input(INPUT_POST, 'deletedImg', FILTER_SANITIZE_STRING);

$validTime = true;
$engineTime = true;

$goodDateTimeDepartureFormat = "";
$goodDateTimeArrivalFormat = "";
$resetTimeDeparture = "";
$resetTimeArrival = "";
$resetTimeEngineOn = "";
$resetTimeEngineOff = "";
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
    $newDate = explode("T", $date);
    return checkdate(explode("-", $newDate[0])[1], explode("-", $newDate[0])[2], explode("-", $newDate[0])[0]);
}

//redirect the user if the id is wrong
if (empty($flightId)) {
    header("Location: ./index.php?page=404");
    exit();
}

//we initialize the variable that allow the user to see this page
$isAllowedToSeeThisFlight = false;

//we get the data of the pilot of this flight
$pilotOfThisFlight = userDAO::getUserByFlightID($flightId);

//if the pilot is the current user, we allow him to see the page
if ($pilotOfThisFlight['Id_User'] == $_SESSION['userID']) {
    $isAllowedToSeeThisFlight = true;
}

//if the user is not allowed to see the page, we show him a 404
if (!$isAllowedToSeeThisFlight) {
    header("Location: ./index.php?page=404");
    exit();
}

//we get all flight from the current user
$userFlight = FlightDAO::getFlightById($flightId);

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
    if (validateDate($dateDeparture) && validateDate($dateArrival)) {
    } else {
        $message[] = "La date n'est pas valide";
    }
    //check the validity of the hours of the take off time and landing time
    if (checkTime(explode(":", (explode("T", $dateDeparture)[1]))[0] . ":" . explode(":", (explode("T", $dateDeparture)[1]))[1]) && checkTime(explode(":", (explode("T", $dateArrival)[1]))[0] . ":" . explode(":", (explode("T", $dateArrival)[1]))[1])) {
        $validTime = true;
        //check the validity of the hours for the engine off and engine on time
        if (!empty($timeEngineOn) && !empty($timeEngineOff)) {
            $engineTime = true;
            if (checkTime(explode(":", $timeEngineOn)[0] . ":" . explode(":", $timeEngineOn)[1]) && checkTime(explode(":", $timeEngineOff)[0] . ":" . explode(":", $timeEngineOff)[1])) {
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

    if (empty($message)) {

        //we check with is the latest id in the database
        $allflight = FlightDAO::getAllFlightOrderById();
        try {

            //converting departure datetime to a good datetime
            $dateDeparture = explode("T", $dateDeparture);
            $dateDeparture = $dateDeparture[0] . " " . $dateDeparture[1];

            //converting arrival datetime to a good datetime
            $dateArrival = explode("T", $dateArrival);
            $dateArrival = $dateArrival[0] . " " . $dateArrival[1];

            //we edit the flight
            FlightDAO::editFlight(
                $flightId,
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
            $successMessage = "Vol modifié avec succès!";

            //we get the new data to show to the user
            $userFlight = FlightDAO::getFlightById($flightId);
        } catch (\Throwable $th) {
            $message = "Une erreure est survenue. merci de réessayer";
        }

        //we check if there is some images to delete
        if (!empty($imgToDelete)) {

            $isAllowedToDeletePicture = false;

            


            DBConnection::startTransaction();
            $imgToDelete = explode(",", $imgToDelete);
            unset($imgToDelete[count($imgToDelete) - 1]);
            $pictureData = "";
            // we try to remove each image
            try {
                foreach ($imgToDelete as $key => $value) {
                    $pictureData = MediaDAO::read_media_by_id($value);

                    if (userDAO::getUserByFlightID($pictureData['Id_Flight'])['Id_User'] == $_SESSION['userID']) {
                        $isAllowedToDeletePicture = true;
                    }else {
                        $isAllowedToDeletePicture = false;
                    }

                    if ($isAllowedToDeletePicture) {
                        MediaDAO::del_mediaByIdMedia($value);
                    unlink($pictureData['Txt_File_Path']);
                    }else {
                        $message[] = "Vous n'avez pas la permission de supprimer l'image!";
                    }
                    
                }
                DBConnection::commit();
            } catch (\Throwable $th) {//if there was an error, we rollback
                DBConnection::rollback();
                $message[] = "Une erreur est survenue lors de la suppression du media";
            }
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

        // we check that the user have clicked on the button
        if ($btn != 'null') {
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
                                    try { //we add the media in the database
                                        MediaDAO::addmedia($lienimg, $flightId);

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
}

if ($error != "") {
    $message .= " " . $error;
}



//we get the new data of the pilot of this flight
$pilotOfThisFlight = userDAO::getUserByFlightID($flightId);

/**
 * Function that compute a flight time on with date and hour. Found logic on stackoverflow and adapted it a bit: https://stackoverflow.com/questions/5463549/subtract-time-in-php
 *
 * @param string $Dt_Departure
 * @param string $Dt_Arrival
 * @return void
 */
function computeTotalTime($Dt_Departure, $Dt_Arrival)
{
    $start = strtotime($Dt_Departure);
    $end = strtotime($Dt_Arrival);

    //If you want it in minutes, you can divide the difference by 60 instead
    $mins = (int)(($end - $start) / 60);
    echo $mins . ' minute(s) ' . '<br>';
}

/**
 * Function that get all pictures from a flight with the id of the flight
 *
 * @param int $idFlight
 * @return void
 */
function showAllPicturesFromTheFlight($idFlight)
{
    $picture = MediaDAO::readMediaByIdFlight($idFlight);
    foreach ($picture as $key => $value) {
        echo "<tr id='" . $value['Id_Picture'] . "'><td><img class='img-thumbnail' src='" . $value['Txt_File_Path'] . "'></td><td><button type='button' class='btn btn-danger' name='delete' value='" . $value["Id_Picture"] . "' onclick='deleteJS(" . $value["Id_Picture"] . ", " . $idFlight . ")'>X</button></td></td></tr>";
    }
    if (empty($picture)) {
        echo " <tr><td>Aucune image pour ce vol</td></tr>";
    }
}

//change the format of the datetime to be a valid sticky datetime format
$goodDateTimeDepartureFormat = explode(" ", $userFlight['Dttm_Departure']);
$goodDateTimeDepartureFormat = $goodDateTimeDepartureFormat[0] . "T" . $goodDateTimeDepartureFormat[1];

$goodDateTimeArrivalFormat = explode(" ", $userFlight['Dttm_Arrival']);
$goodDateTimeArrivalFormat = $goodDateTimeArrivalFormat[0] . "T" . $goodDateTimeArrivalFormat[1];
