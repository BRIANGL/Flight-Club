<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * About group logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/groupDAO.php";

use FlightClub\sql\GroupDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}
//filter the url to get the id of the group
$idGroup = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
//get the group to check if the user is in this group
$groupCheck = GroupDAO::getMyGroupById($idGroup, $_SESSION['userID']);

//if the user is not in the group, we redirect him to the group menu page
if (empty($groupCheck)) {
    header("Location: ./index.php?page=myGroupes");
    exit();
}

//we get the data of the group using the group id
$groupDATA = GroupDAO::getGroupById($idGroup);

//if the data is empty or the id, we redirect the user to the group menu page
if (empty($idGroup) || empty($groupDATA)) {
    header("Location: ./index.php?page=myGroupes");
    exit();
}


/**
 * echo the name of the group
 *
 * @param array[mixed] $groupData
 * @return string
 */
function groupName($groupData){
    echo $groupData['Nm_Group'];
}