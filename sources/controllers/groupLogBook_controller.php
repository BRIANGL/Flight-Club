<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * group menu logic here
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
            echo "<tr><td>" . $value['No_Flight'] . "</td><td>" . $value['Dt_Departure'] . "</td><td>" . $value['Dt_Arrival'] . "</td><td>" . $value['Tm_Departure'] . "</td><td>" . $value['Tm_Arrival'] . "</td><td>" .
                $value['Tm_Engine_On'] . "</td><td>" . $value['Tm_Engine_Off'] . "</td><td>" . $value['Nm_Plane'] . "</td><td>" . $value['No_Plane'] . "</td><td>" .
                $value['Cd_ICAO_Departure'] . "</td><td>" . $value['Cd_ICAO_Arrival'] . "</td><td>" . $value['Cd_Flight_Type'] . "</td><td>" . $value['Cd_Flight_Mode'] .
                "</td><td>" . $value['Cd_Role'] . "</td><td><a href='?page=logbookDetail&id=" . $value['Id_Flight'] . "'>Détails</a></td></tr>";
        }
    }
    //if there is no flight, we simply fill all cell with a "/"
    if ($compteur == 0) {
        echo "<tr><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td><td>/</td></tr>";
    }
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
