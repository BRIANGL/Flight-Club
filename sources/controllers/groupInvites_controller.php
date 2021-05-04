<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * group invites logic here
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

//check if the user clicked on any of these buttons
$accept = filter_input(INPUT_POST, "accept");
$deny = filter_input(INPUT_POST, "delete");

//if he accepted an invite, we update the database
if ($accept) {
    GroupDAO::acceptPendingInvite($accept, $_SESSION['userID']);
}
//if he deny an invite, we remove it from the database
if ($deny) {
    GroupDAO::deletePendingInvite($deny, $_SESSION['userID']);
}

/**
 * function that show all invites for the current user
 *
 * @return string
 */
function showInvites()
{
    //we get all invites in a variable
    $pendingInvites = GroupDAO::getAllPendingInvites();
    
    //we initialize a counter
    $countUserInvite = 0;
    //we check every invite  of the database
    foreach ($pendingInvites as $key => $value) {
        //if an invite is for our user, we show it to him
        if ($value['Id_User'] == $_SESSION['userID']) {
            //we increment our counter
            $countUserInvite++;
            echo "<tr><td>" . GroupDAO::getGroupById($value['Id_Group'])['Nm_Group'] . " vous a invité a rejoindre son groupe!</td><td><button class=\"btn btn-success\" type=\"submit\" value=\" " . $value['Id_Group'] . "\" name=\"accept\">✓</button></td><td><button class=\"btn btn-danger\" type=\"submit\" value=\" " . $value['Id_Group'] . "\" name=\"delete\">X</button></td></tr>";
        }
    }
    //if our user have 0 invites, we show him a message telling him to comme back later to check if he got a new invite
    if ($countUserInvite == 0) {
        echo "<tr><td>Aucune invitation pour le moment... </td></tr><tr><td>Revenez plus tard</td></tr>";
    }
}
