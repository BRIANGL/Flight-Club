<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Flight Detail logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/flightDAO.php";
require_once "./sql/groupDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\GroupDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//filter the id from the url
$flightId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//redirect the user if the id is wrong
if (empty($flightId)) {
    header("Location: ./index.php?page=404");
    exit();
}

//initialize a variable to allow or not the user to see this flight (for group purposes)
$isAllowedToSeeThisFlight = false;

//we get the pilot that did the flight that this page is currently showing
$pilotOfThisFlight = userDAO::getUserByFlightID($flightId);
//we get the pilot group of that flight
$groupsOfPilot = GroupDAO::getAllOfMyGroup($pilotOfThisFlight['Id_User']);
//we get the current user data
$groupsOfCurrentUser = GroupDAO::getAllOfMyGroup($_SESSION['userID']);

//we compare the pilot and the current user to check if the current user is by any way related to the pilot by groups. If not, we do not allow the user to see this page and show him a 404
foreach ($groupsOfPilot as $key => $gPValue) {
    foreach ($groupsOfCurrentUser as $key => $gUValue) {
        if ($gPValue['Id_Group'] == $gUValue['Id_Group']) {
            $isAllowedToSeeThisFlight = true;
        }
    }
}

//showing the user a 404
if (!$isAllowedToSeeThisFlight) {
    header("Location: ./index.php?page=404");
    exit();
}

//we get all flight from the current user
$userFlight = FlightDAO::getFlightById($flightId);

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
