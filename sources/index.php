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

//we initialize a session of connection if it doesn't exists
if (!isset($_SESSION['connected'])) {
    $_SESSION['connected'] = false;
}

//we check that we have a page to show
if (isset($page)) {
    //we show the desired page
    switch ($page) {
        case 'register':
            require("./pages/Registration.php");
            break;
        case 'userModify':
            require("./pages/UserModify.php");
            break;
        case 'login':
            require("./pages/Login.php");
            break;
        case 'logout':
            require("./pages/Logout.php");
            break;
        case 'homepage':
            require("./pages/Index.php");
            break;
        case 'GroupMenu':
            require("./pages/GroupMenu.php");
            break;
        case 'invite':
            require("./pages/GroupInvites.php");
            break;
        case 'myGroupes':
            require("./pages/myGroup.php");
            break;
        case 'CreateGroups':
            require("./pages/GroupCreate.php");
            break;
        case 'aboutGroup':
            require("./pages/aboutGroup.php");
            break;
        case 'InviteAMember':
            require("./pages/GroupInviteAMember.php");
            break;
        case 'MemberList':
            require("./pages/GroupMemberList.php");
            break;
        case 'logbook':
            require("./pages/UserLogBookMenu.php");
            break;
        case 'myFlights':
            require("./pages/UserFlight.php");
            break;
        case 'addFlight':
            require("./pages/UserAddFlight.php");
            break;
        case 'GroupLogBook':
            require("./pages/GroupLogBook.php");
            break;
        case 'logbookDetail':
            require("./pages/FlightDetail.php");
            break;
        case 'logbookEdit':
            require("./pages/FlightEdit.php");
            break;
        case 'downloadPdf':
            require("./pages/DownloadPdf.php");
            break;
        case 'downloadCsv':
            require("./pages/DownloadCsv.php");
            break;
        case 'ajax':
            require("./pages/ajax.php");
            break;
        case '404':
            require("./pages/404.php");
            break;
        default:
            require("./pages/404.php");
    }
} else {
    require("./pages/Index.php");
}
