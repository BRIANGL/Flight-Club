<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * switching page logic here
 */

//we start a session
session_start();

require_once("./sql/userDAO.php");

use FlightClub\sql\UserDAO;

//we initialize a session user if it does not exists
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = "";
}
// variable that will get the page to show
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);

//if empty we show the default home page
if ($page == null) {
    header('location: index.php?page=homepage');
}

//we initialize a session of connexion if it doesn't exists
if (!isset($_SESSION['connected'])) {
    $_SESSION['connected'] = false;
}

//we check that we have a page to show
if (isset($page)) {
    //we show the desired page
    switch ($page) {
        case 'register':
            require("./pages/registration.php");
            break;
        case 'login':
            require("./pages/login.php");
            break;
        case 'logout':
            require("./pages/Logout.php");
            break;
        case 'homepage':
            require("./pages/index.php");
            break;
        case 'product':
            require("./pages/products.php");
            break;
        case 'aboutProduct':
            require("./pages/aboutProduct.php");
            break;
        case 'cart':
            require("./pages/cart.php");
            break;
        case 'account':
            require("./pages/utilisateur.php");
            break;
        case 'user':
            require("./pages/utilisateurChoix.php");
            break;
        case 'userOrder':
            require("./pages/userOrder.php");
            break;
        case 'admin':
            require("./pages/adminMenu.php");
            break;
        case 'addproduct':
            require("./pages/AdminAddProduct.php");
            break;
        case 'confirm':
            require("./pages/confirmation.php");
            break;
        case 'addAdresses':
            require("./pages/AjoutAdresse.php");
            break;
        case 'about':
            require("./pages/about.php");
            break;
        case 'forgetPassword':
            require("./pages/forgetPassword.php");
            break;
        case 'modifyPassword':
            require("./pages/modifyPassword.php");
            break;
        case '404':
            require("./pages/404.php");
            break;
        case 'adminAdd':
            require("./pages/Admin.php");
            break;
        case 'adminOrder':
            require("./pages/adminOrder.php");
            break;
        case 'adminOrderModify':
            require("./pages/adminOrderModify.php");
            break;
        case 'conditionUtilisation':
            require("./pages/CG.php");
            break;
        default:
            require("./pages/404.php");
    }
} else {
    require("./pages/homepage.php");
}
