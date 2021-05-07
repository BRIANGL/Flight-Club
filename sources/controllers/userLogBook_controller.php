<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * group menu logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/flightDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//we get all flight from the current user
$userFlight = FlightDAO::getAllUserFlightByUserId($_SESSION['userID']);

/**
 * Show all flight of the current user
 *
 * @param array[mixed] $flight
 * @return void
 */
function showFlight($flight)
{
    //if there is at least 1 flight, we show it
    if (!empty($flight)) {
        foreach ($flight as $key => $value) {
            echo "<tr><td>" . $value['No_Flight'] . "</td><td>" . $value['Dt_Departure'] . "</td><td>" . $value['Dt_Arrival'] . "</td><td>" . $value['Tm_Departure'] . "</td><td>" . $value['Tm_Arrival'] . "</td><td>" .
                $value['Tm_Engine_On'] . "</td><td>" . $value['Tm_Engine_Off'] . "</td><td>" . $value['Nm_Plane'] . "</td><td>" . $value['No_Plane'] . "</td><td>" .
                $value['Cd_ICAO_Departure'] . "</td><td>" . $value['Cd_ICAO_Arrival'] . "</td><td>" . $value['Cd_Flight_Type'] . "</td><td>" . $value['Cd_Flight_Mode'] .
                "</td><td>" . $value['Cd_Role'] . "</td><td><a href='?page=logbookDetail&id=" . $value['Id_Flight'] . "'>Détails</a></td><td><a href='?page=logbookEdit&id=" . $value['Id_Flight'] . "'>Editer le vol</a></td></tr>";
        }
    } else { //else we show "/"
        echo "<tr><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td></tr>";
    }
}


/**
 * Function that take the time of each flight and put it in a total time
 *
 * @param array[mixed] $flight
 * @return void
 */
function computeTotal($flight)
{
    $totalMinutes = 0;
    $totalHour = 0;
    foreach ($flight as $key => $value) {
        
        $totalMinutes += computeTotalTime($value['Dt_Departure'],$value['Dt_Arrival'],$value['Tm_Departure'],$value['Tm_Arrival']);
    }

    $totalHour = floor($totalMinutes/60);

    $totalMinutes = ($totalMinutes - (60* $totalHour));

    echo $totalHour . ":" . $totalMinutes;
}


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
    return $mins;
}
