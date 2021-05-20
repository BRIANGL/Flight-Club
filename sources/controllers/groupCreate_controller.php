<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * Create group logic here
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

//filter the user input
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);

//reset the success and error message
$message = "";
$errorMessage = "";

//we check that the name is not empty and not bigger than the maximum size in the database
if (!empty($name) && strlen($name) <= 100) {
    
    //we create the group and get his id
    GroupDAO::createGroup($name);
    $lastID =GroupDAO::getLastGroupId();
    
    //we make the user join his newly created group
    GroupDAO::joinCreatedGroup($lastID[0]['Id_Group'], $_SESSION['userID']);

    //we show him a success message
    $message = "Groupe créer avec succès!";

}elseif(strlen($name) > 100){
    //we show an error message
    $errorMessage = "Nom de groupe trop long";
}