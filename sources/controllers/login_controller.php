<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * login logic here
 */
require_once "./sql/userDAO.php";

use FlightClub\sql\userDAO;

if (isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

$message = "";
if (!empty($email) && !empty($password)) {

    $hashed = hash('sha512', $password);
    $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
    $hashed = hash('sha512', $salted);

    if (password_verify($hashed, userDAO::getUserByEmail($email)['Txt_Password_Salt'])) {
        $_SESSION['connected'] = true;
        $_SESSION['userID'] = userDAO::getUserByEmail($email)['Id_User'];
        $message = "Succesfully logged";
        header("Location: ./index.php?page=homepage");
        exit();
    } else {
        $message = "Mauvais mot de passe ou email !";
    }
}
