<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/05)
 * Ajax-used functions page here
 */

//require all the flight functions
require_once("./sql/flightDAO.php");
//require all the media functions
require_once("./sql/mediaDAO.php");
//require all the database functions
require_once("./sql/dbConnection.php");
//require all the user functions
require_once("./sql/userDAO.php");

use FlightClub\sql\FlightDAO;
use FlightClub\sql\MediaDAO;
use FlightClub\sql\DBConnection;
use FlightClub\sql\UserDAO;

//we get the image id from the post request and check if it is empty
$imageId = filter_input(INPUT_POST, "IdImg", FILTER_SANITIZE_NUMBER_INT);
$flightId = filter_input(INPUT_POST, "IdFlight", FILTER_SANITIZE_NUMBER_INT);
if (empty($imageId)) {
    http_response_code(404);
    header('location: index.php?page=404');
    exit();
}

//we initialize the variable that allow the user to see this page
$isAllowedToSeeThisFlight = false;

//we get the data of the pilot of this flight
$pilotOfThisFlight = userDAO::getUserByFlightID($flightId);

//if the pilot is the current user, we allow him to see the page
if ($pilotOfThisFlight['Id_User'] == $_SESSION['userID']) {
    $isAllowedToSeeThisFlight = true;
}

//if the user is not allowed to see the page, we show him a 404
if (!$isAllowedToSeeThisFlight) {
    http_response_code(404);
    header("Location: ./index.php?page=404");
    exit();
}


//if there was a deletion request we start transaction and start the process to remove the image
if (!empty($imageId)) {
    DBConnection::startTransaction();
    try {
        $linkMediaToDelete = mediaDAO::read_media_by_id($imageId)[0]['Txt_File_Path'];
        mediaDAO::del_mediaByIdMedia($imageId);
        unlink($linkMediaToDelete);
        DBConnection::commit(); //if everything went good, we commit the changes
    } catch (\Throwable $th) {
        DBConnection::rollback(); //if there was an error, we rollback
    }
}



/**
 * Function that get all pictures from a flight with the id of the flight
 *
 * @param int $idFlight
 * @return string
 */
function showAllPicturesFromTheFlight($idFlight){
    $picture = MediaDAO::readMediaByIdFlight($idFlight);
    $output = "";
    foreach ($picture as $key => $value) {
        $output .= "<tr><td><img class='img-thumbnail' src='" . $value['Txt_File_Path'] . "'></td><td><button type='button' class='btn btn-danger' name='delete' value='" . $value["Id_Picture"] . "' onclick='deleteJS(" . $value["Id_Picture"] . ", " . $idFlight . ")'>X</button></td></td></tr>";
    }
    if (empty($picture)) {
        $output = " <tr><td>Aucune image pour ce vol</td></tr>";
    }
    return $output;
}
echo showAllPicturesFromTheFlight($flightId);
