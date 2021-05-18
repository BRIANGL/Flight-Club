<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Log-out page
 */
//start a session
session_start();
$_SESSION = array();
//remove all cookies and destroy the session
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', 0);
}
session_destroy();
//redirect the user to the homepage
header("Location: ./index.php?page=homepage");
exit;
