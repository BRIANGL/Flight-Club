<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Group member list logic here
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
//if the id or the data is empty, we redirect the user to the menu page of the groupes
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


/**
 * function that show all group members
 *
 * @return string
 */
function showGroupMembers($idGroup)
{
    //we get all users of the selected group in a variable
    $groupMember = GroupDAO::getAllOfGroupUser($idGroup);

    //we write every user of the selected group
    foreach ($groupMember as $key => $value) {
        echo "<tr><td>" . $value['Txt_Email'] . "</td><td>" . $value['Dttm_Membership'] . "</td></tr>";
    }
}
