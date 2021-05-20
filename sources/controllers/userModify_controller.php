<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * modify user data logic here
 */
require_once "./sql/userDAO.php";

use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!$_SESSION['connected']) {
    header("Location: ./index.php?page=homepage");
    exit();
}
//we filter the user input
$newPassword = filter_input(INPUT_POST, "newpassword", FILTER_SANITIZE_STRING);
$repeatNewPassword = filter_input(INPUT_POST, "repeatnewpassword", FILTER_SANITIZE_STRING);
$oldPassword = filter_input(INPUT_POST, "oldpassword", FILTER_SANITIZE_STRING);

$messageErreur = "";
$messageSucces = "";
//we check that the email and password are filled
if (!empty($newPassword) && !empty($repeatNewPassword) && !empty($oldPassword)) {

    //we hash the user password
    $hashed = hash('sha512', $oldPassword);
    $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
    $hashed = hash('sha512', $salted);

    //we compare the hashed password of the user with the one in the database
    if (password_verify($hashed, userDAO::getUserByEmail(UserDAO::getUserByID($_SESSION['userID'])['Txt_Email'])['Txt_Password_Hash'])) {
        //we check that booth of the new password are the same
        if ($newPassword == $repeatNewPassword) {
            //we hash the new password to make it work with the login
            $hashed = hash('sha512', $newPassword);
            $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
            $hashed = hash('sha512', $salted);

            //we hash again with password hash
            $hashed = password_hash($hashed, PASSWORD_BCRYPT);

            //we update the password in the database
            UserDAO::changePassword($_SESSION['userID'], $hashed);
            //we display a success message to the user
            $messageSucces = "Mot de passe changé avec succès";
        }else {
                //we display an error message to the user
        $messageErreur = "Les 2 mot de passe saisis ne sont pas identiques!";
        }
    } else {
        //we display an error message to the user
        $messageErreur = "Mauvais mot de passe!";
    }
}
