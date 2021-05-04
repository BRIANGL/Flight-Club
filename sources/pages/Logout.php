<?php
//supprime la session
session_start();
$_SESSION = array();
//supprime les cookies
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', 0);
}
session_destroy();
//redirige vers login
if (filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) == "profile") {
    header("Location: ./index.php?page=homepage");
} else {
    header("Location: ./index.php?page=homepage");
}
exit;