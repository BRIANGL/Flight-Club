<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * group logbook logic here
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

//we filter the url to get the id of the group
$idGroup = filter_input(INPUT_GET, "idGroup", FILTER_VALIDATE_INT);


//we get the group with the user id
$groupCheck = GroupDAO::getMyGroupById($idGroup, $_SESSION['userID']);

//we check if the user is in the group, if not we redirect him to the menu page of the groupes
if (empty($groupCheck)) {
    header("Location: ./index.php?page=myGroupes");
    exit();
}

//we get the group data from the id
$groupDATA = GroupDAO::getGroupById($idGroup);

//we get all the users in the group
$groupUser = GroupDAO::getAllOfGroupUser($idGroup);


/**
 * Show all flight from all group users
 *
 * @param array[mixed] $users
 * @return void
 */
function showFlight($users)
{
    $compteur = 0;
    foreach ($users as $key => $value) {

        //we get the group flight
        $flight = FlightDAO::getAllUserFlightByUserId($value['Id_User']);

        //for each flight of the group we write it in a table
        foreach ($flight as $key => $value) {
            $compteur++;
            echo "<tr><td>" . $value['No_Flight'] . "</td><td>" . $value['Dttm_Departure'] . "</td><td>" . $value['Dttm_Arrival'] . "</td><td>" .
                $value['Tm_Engine_On'] . "</td><td>" . $value['Tm_Engine_Off'] . "</td><td>" . $value['Nm_Plane'] . "</td><td>" . $value['No_Plane'] . "</td><td>" .
                $value['Cd_ICAO_Departure'] . "</td><td>" . $value['Cd_ICAO_Arrival'] . "</td><td>" . $value['Cd_Flight_Type'] . "</td><td>" . $value['Cd_Flight_Mode'] .
                "</td><td>" . $value['Cd_Role'] . "</td><td><a href='?page=logbookDetail&id=" . $value['Id_Flight'] . "'>Détails</a></td></tr>";
        }
    }
    //if there is no flight, we simply fill all cell with a "/"
    if ($compteur == 0) {
        echo "<tr><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td></tr>";
    }
}

/**
 * Function that take the time of each flight and put it in a total time
 *
 * @param array[mixed] $flight
 * @return void
 */
function computeTotal($users)
{
    $totalMinutes = 0;
    $totalHour = 0;
    //we get the group flight
    foreach ($users as $key => $value) {
        $flight = FlightDAO::getAllUserFlightByUserId($value['Id_User']);

        //we get the total minute time
        foreach ($flight as $key => $value) {

            $totalMinutes += computeTotalTime($value['Dttm_Departure'], $value['Dttm_Arrival']);
        }
    }
    //we get the hour from the minutes
    $totalHour = floor($totalMinutes / 60);

    //we substract 60 minutes for each hour
    $totalMinutes = ($totalMinutes - (60 * $totalHour));

    //we output the hour and minutes
    echo $totalHour . ":" . $totalMinutes;
}

/**
 * Write the group name
 *
 * @param array[mixed] $groupData
 * @return void
 */
function groupName($groupData)
{
    echo $groupData['Nm_Group'];
}

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
    return $mins;
}
