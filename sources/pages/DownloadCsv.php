<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/07/04)
 * CSV Generation page here
 */

use FlightClub\sql\DBConnection;
use FlightClub\sql\UserDAO;
use FlightClub\sql\FlightDAO;

require_once './sql/dbConnection.php';
require_once './sql/userDAO.php';
require_once './sql/flightDAO.php';


//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

//initialize the name of the column in the csv
$nameColumn = "";

//we get all flight from the current user
$userFlight = FlightDAO::getAllUserFlightByUserId($_SESSION['userID']);

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}


function cleanData($str)
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

/**
 * Function that take the time of each flight and put it in a total time
 *
 * @param array[mixed] $flight
 * @return void
 */
function computeTotal($flight)
{
    $totalMinutes = 0;
    $totalHour = 0;
    foreach ($flight as $key => $value) {

        $totalMinutes += computeTotalTime($value['Dttm_Departure'], $value['Dttm_Arrival']);
    }

    $totalHour = floor($totalMinutes / 60);

    $totalMinutes = ($totalMinutes - (60 * $totalHour));


    $hour_padded = sprintf("%02d", $totalHour);
    $minutes_padded = sprintf("%02d", $totalMinutes);

    return $hour_padded . ":" . $minutes_padded;
}


/**
 * Function that compute a flight time on with date and hour. Found logic on stackoverflow and adapted it a bit: https://stackoverflow.com/questions/5463549/subtract-time-in-php
 *
 * @param string $Dt_Departure
 * @param string $Dt_Arrival
 * @return void
 */
function computeTotalTime($Dt_Departure, $Dt_Arrival)
{
    $start = strtotime($Dt_Departure);
    $end = strtotime($Dt_Arrival);

    //If you want it in minutes, you can divide the difference by 60 instead
    $mins = (int)(($end - $start) / 60);
    return $mins;
}


// filename of the download
$filename = "flight_club" . date('Ymd') . ".csv";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/csv");

$out = fopen("php://output", 'w');

$flag = false;

$nameColumn = array("Role" => '', "Type de vol" => '', "Date de départ" => '', "Date d'arrivée" => '', "Démmarage moteur" => '', "Moteur coupé" => '', "Modèle d'avion" => '', "Immatriculation" => '', "ICAO de départ" => '', "ICAO d'arrivée" => '', "Catégorie de vol" => '', "Mode de vol" => '', "Nombre de passagers" => '');

$db = DBConnection::getConnection();
$result = "SELECT `Cd_Role`, `No_Flight`, `Dttm_Departure`, `Dttm_Arrival`, `Tm_Engine_On`, `Tm_Engine_Off`, `Nm_Plane`, `No_Plane`, `Cd_ICAO_Departure`, `Cd_ICAO_Arrival`, `Cd_Flight_Type`, `Cd_Flight_Mode`, `Nb_Passengers` FROM `tbl_flight` join `tbl_user-flight` on `tbl_flight`.`Id_Flight` = `tbl_user-flight`.`Id_Flight` WHERE `Id_User` = :idUser";

$query =  $db->prepare($result);

$query->execute([
    ':idUser' => $_SESSION['userID']
]);



while (false !== ($row = $query->fetch(PDO::FETCH_ASSOC))) {
    if (!$flag) {
        // display field/column names as first row
        fputcsv($out, array_keys($nameColumn), ';', '"');
        $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    fputcsv($out, array_values($row), ';', '"');
}
fputcsv($out, array("Carnet de vol de " . UserDAO::getUserByID($_SESSION['userID'])['Txt_Email'], "Temps de vol total : ", computeTotal($userFlight), date("d-m-Y H:i:s")), ';', '"');

fclose($out);
exit;
