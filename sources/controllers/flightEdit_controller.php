<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Flight edit logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/flightDAO.php";
require_once "./sql/groupDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//filter the id from the url
$flightId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
//we filter the user input
$role = filter_input(INPUT_POST, "Cd_Role", FILTER_SANITIZE_STRING);
$flight = filter_input(INPUT_POST, "No_Flight", FILTER_SANITIZE_STRING);
$dateDeparture = filter_input(INPUT_POST, "Dt_Departure", FILTER_SANITIZE_STRING);
$dateArrival = filter_input(INPUT_POST, "Dt_Arrival", FILTER_SANITIZE_STRING);
$timeDeparture = filter_input(INPUT_POST, "Tm_Departure", FILTER_SANITIZE_STRING);
$timeArrival = filter_input(INPUT_POST, "Tm_Arrival", FILTER_SANITIZE_STRING);
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


$validTime = true;


$resetTimeDeparture = "";
$resetTimeArrival = "";
$resetTimeEngineOn = "";
$resetTimeEngineOff = "";
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
$message = "";
$successMessage = "";
//if all data are not empty
if (
    !empty($role) && !empty($flight) && !empty($dateDeparture) && !empty($dateArrival) && !empty($timeDeparture) && !empty($timeArrival) &&
    !empty($typeAircraft) && !empty($registrationPlane) && !empty($icaoDeparture) && !empty($icaoArrival) && !empty($flightType)
    && !empty($flightMode) && !empty($weather) && $passengers != ""
) {
    //we check that the data is valid
    if (strlen($icaoDeparture) == 4 && strlen($icaoArrival) == 4) {
        //check that dates are valid
        if (checkdate(explode("-", $dateDeparture)[1], explode("-", $dateDeparture)[2], explode("-", $dateDeparture)[0]) && $dateDeparture <= date("Y-m-d") && checkdate(explode("-", $dateArrival)[1], explode("-", $dateArrival)[2], explode("-", $dateArrival)[0]) && $dateArrival <= date("Y-m-d") && $dateDeparture <= $dateArrival) {
            //check the validity of the hours of the take off time and landing time
            if (checkTime(explode(":",$timeDeparture)[0] . ":" . explode(":",$timeDeparture)[1]) && checkTime(explode(":",$timeArrival)[0] . ":" . explode(":",$timeArrival)[1])) {
                $validTime = true;
                //check the validity of the hours for the engine off and engine on time
                if (!empty($timeEngineOn) && !empty($timeEngineOff)) {
                    if (checkTime(explode(":",$timeEngineOn)[0] . ":" . explode(":",$timeEngineOn)[1]) && checkTime(explode(":",$timeEngineOff)[0] . ":" . explode(":",$timeEngineOff)[1])) {
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
                                                try {                                                
                                                    FlightDAO::editFlight(
                                                        $flightId,
                                                        $_SESSION['userID'],
                                                        $role,
                                                        strtoupper($flight),
                                                        $dateDeparture,
                                                        $dateArrival,
                                                        $timeDeparture,
                                                        $timeArrival,
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
}

//we get the new data of the pilot of this flight
$pilotOfThisFlight = userDAO::getUserByFlightID($flightId);

/**
 * Function that compute a flight time on with date and hour. Found logic on stackoverflow and adapted it a bit: https://stackoverflow.com/questions/5463549/subtract-time-in-php
 *
 * @param string $Dt_Departure
 * @param string $Dt_Arrival
 * @param string $Tm_Departure
 * @param string $Tm_Arrival
 * @return void
 */
function computeTotalTime($Dt_Departure, $Dt_Arrival, $Tm_Departure, $Tm_Arrival)
{
    $start = strtotime($Dt_Departure . " " . $Tm_Departure);
    $end = strtotime($Dt_Arrival . " " . $Tm_Arrival);

    //If you want it in minutes, you can divide the difference by 60 instead
    $mins = (int)(($end - $start) / 60);
    echo $mins . ' minute(s) ' . '<br>';
}