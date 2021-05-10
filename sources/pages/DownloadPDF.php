<?php
//============================================================+
// File name   : DownloadPDF.php
// Begin       : 07-05-2021
// Last Update : 07-05-2021
//
// Description : 
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/07/04)
 * PDF Generation page here
 */

// Include the main TCPDF library (search for installation path).
require_once('./controllers/tcpdf_include.php');

require_once "./sql/userDAO.php";
require_once "./sql/flightDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\userDAO;


//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

$pilot = userDAO::getUserByID($_SESSION['userID']);
$flight = FlightDAO::getAllUserFlightByUserIdForExport($_SESSION['userID']);


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
        
        $totalMinutes += computeTotalTime($value['Dttm_Departure'],$value['Dttm_Arrival']);
    }

    $totalHour = floor($totalMinutes/60);

    $totalMinutes = ($totalMinutes - (60* $totalHour));

    return $totalHour . ":" . $totalMinutes;
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




// extend TCPF with custom functions
class MYPDF extends TCPDF
{

    // Colored table
    public function ColoredTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(59, 153, 224);
        $this->SetTextColor(255);
        $this->SetDrawColor(59, 133, 224);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(20, 20, 35, 35, 25, 25, 45, 25, 20, 20, 30, 30, 20);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 'LR', 0, 'C', $fill);
            $this->Cell($w[5], 6, $row[5], 'LR', 0, 'C', $fill);
            $this->Cell($w[6], 6, $row[6], 'LR', 0, 'C', $fill);
            $this->Cell($w[7], 6, $row[7], 'LR', 0, 'C', $fill);
            $this->Cell($w[8], 6, $row[8], 'LR', 0, 'C', $fill);
            $this->Cell($w[9], 6, $row[9], 'LR', 0, 'C', $fill);
            $this->Cell($w[10], 6, $row[10], 'LR', 0, 'C', $fill);
            $this->Cell($w[11], 6, $row[11], 'LR', 0, 'C', $fill);
            $this->Cell($w[12], 6, $row[12], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date("d-m-Y H:i:s"), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Brian GOLAY');
$pdf->SetTitle('Flight Club');
$pdf->SetSubject('Flight Club logbook');
$pdf->SetKeywords('Logbook, PDF, carnet de vol, vol, aviation');

// set default header data
$pdf->SetHeaderData("", 0, 'Carnet de vol de ' . $pilot['Txt_Email'] . "| Total des heures: " . computeTotal($flight), PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// column titles
$header = array('Role', 'Type de vol', 'Départ', 'Arrivée', 'Allumage moteur', 'Moteur coupé', 'Type d\'aéronef', 'Immatriculation', 'Départ', 'Arrivée', 'Catégorie de vol', 'Mode de vol', 'Passager');

// data loading
$data = $flight;

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('FlightClub.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+