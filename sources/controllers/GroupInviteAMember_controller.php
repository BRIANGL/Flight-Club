<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * Invite a member to a group logic here
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
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);


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
//we initialize the error and success messages
$errorMessage = "";
$successMessage = "";
//we check that the email field is not empty
if (!empty($email)) {
    //we get all the user data and try to invite him
    $userData = UserDAO::getUserByEmail($email);
    try {
        if (!empty($userData)) {//if the user is existing we invite him and show a success message
            GroupDAO::CreateInvite($idGroup, $userData['Id_User']);
            $successMessage = "Utilisateur invité!";
        } else {//if the user doesn't exists, we show an error message
            $errorMessage = "Oops, une erreur est survenue. Vérifiez l'adresse du destinataire";
        }
    } catch (\Throwable $th) {//if the user is already in the group or is already invited, we show an error message
        $errorMessage = "Oops, une erreur est survenue. Vérifiez l'adresse du destinataire";
    }
}


/**
 * echo the name of the group
 *
 * @param array[mixed] $groupData
 * @return string
 */
function groupName($groupData)
{
    echo $groupData['Nm_Group'];
}
