<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * registration logic here
 */

//require all the users functions
require_once("./sql/userDAO.php");

use FlightClub\sql\userDAO;

//check if the user is not already connected
if (isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

/**
 * Check the password strength with REGEX
 *
 * @param string $password
 * @return bool
 */
function password_strength($password)
{
    $password_length = 8;
    $returnVal = True;

    if (strlen($password) < $password_length) {
        $returnVal = False;
    }

    if (!preg_match("#[0-9]+#", $password)) {
        $returnVal = False;
    }

    if (!preg_match("#[a-z]+#", $password)) {
        $returnVal = False;
    }

    if (!preg_match("#[A-Z]+#", $password)) {
        $returnVal = False;
    }

    if (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password)) {
        $returnVal = False;
    }

    return $returnVal;
}

//we filter the user input
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
$password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_STRING);
//we reset the message
$message = "";
//if all data are not empty
if (!empty($email) && !empty($password) && !empty($password2)) {
    //we check that the email is not already in our database
    if (userDAO::readEmail($email) == null) {
        //we check booth of the password are the same and the strength of the password
        if ($password === $password2) {
            if (password_strength($password)) {
                //we hash the password
                $hashed = hash('sha512', $password);
                $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
                $hashed = hash('sha512', $salted);
                //we add a php generated salt
                $salt = password_hash($hashed, PASSWORD_BCRYPT);
                //we add it to the database
                userDAO::AddUsers($email, $hashed, $salt);
                $_SESSION['user'] = userDAO::readUsersByEmail($email);
                //we redirect him to the login page
                header("Location: ./index.php?page=login");
                exit();
            } else {
                $message = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial et faire 8 caractère ou plus!";
            }
        } else {
            $message = "Les mots de passes ne sont pas identiques !";
        }
    } else {
        $message = "L'email existe déjà";
    }
}