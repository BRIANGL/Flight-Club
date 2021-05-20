<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * group menu logic here
 */
require_once "./sql/userDAO.php";

use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}