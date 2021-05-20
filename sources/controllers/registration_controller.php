<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
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
    //we check the length and initialize the default value for the return
    $password_length = 8;
    $returnVal = True;

    //we check that the password is bigger than the password length
    if (strlen($password) < $password_length) {
        $returnVal = False;
    }

    //we check that there is a number in the password
    if (!preg_match("#[0-9]+#", $password)) {
        $returnVal = False;
    }

    //we check that there is at least one smaller character 
    if (!preg_match("#[a-z]+#", $password)) {
        $returnVal = False;
    }

    //we check that there is at least one bigger character 
    if (!preg_match("#[A-Z]+#", $password)) {
        $returnVal = False;
    }

    //we check that there is at least one special character 
    if (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password)) {
        $returnVal = False;
    }

    //we return the true if the password is ok or false if ko
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
    if (strlen($email) < 100) {
        //we check that the email is not already in our database
        if (userDAO::readEmail($email) == null) {
            //we check booth of the password are the same and the strength of the password
            if ($password === $password2) {
                if (password_strength($password)) {
                    //we hash the password
                    $hashed = hash('sha512', $password);
                    $salted = substr($hashed, 0, 20) . $hashed . substr($hashed, 50, 70);
                    $hashed = hash('sha512', $salted);
                    //we hash again with password hash
                    $hashed = password_hash($hashed, PASSWORD_BCRYPT);
                    //we add it to the database
                    userDAO::AddUsers($email, $hashed);
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
    }else {
        $message = "L'email est trop long";
    }
}
