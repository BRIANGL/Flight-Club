<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * login logic here
 */
require_once "./sql/userDAO.php";

use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}
//we filter the user input
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

$message = "";
//we check that the email and password are filled
if (!empty($email) && !empty($password)) {

    //we hash the user password
    $hashed = hash('sha512', $password);
    $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
    $hashed = hash('sha512', $salted);

    //we compare the hashed password of the user with the one in the database
    if (password_verify($hashed, userDAO::getUserByEmail($email)['Txt_Password_Hash'])) {
        //we connect the user in session
        $_SESSION['connected'] = true;
        $_SESSION['userID'] = userDAO::getUserByEmail($email)['Id_User'];
        $message = "Succesfully logged";
        //we redirect the user to the homepage
        header("Location: ./index.php?page=homepage");
        exit();
    } else {
        $message = "Mauvais mot de passe ou email !";
    }
}
