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
require_once "./sql/groupDAO.php";

use FlightClub\sql\FlightDAO;
use FlightClub\sql\GroupDAO;
use FlightClub\sql\userDAO;


//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

$pilot = userDAO::getUserByID($_SESSION['userID']);



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
        $w = array(20, 20, 25, 25, 25, 25, 30, 25, 45, 30, 20, 20, 30,30, 20);
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
            $this->Cell($w[13], 6, $row[13], 'LR', 0, 'C', $fill);
            $this->Cell($w[14], 6, $row[14], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
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
$pdf->SetHeaderData("", 0, 'Carnet de vol de ' . $pilot['Txt_Email'], PDF_HEADER_STRING);

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
$header = array('Role', 'Type de vol', 'Date de départ', 'Date d\'arrivée', 'Heure de départ', 'Heure d\'arrivée', 'Allumage moteur', 'Moteur coupé', 'Type d\'aéronef', 'Immatriculation', 'Départ', 'Arrivée', 'Catégorie de vol', 'Mode de vol', 'Passager');

// data loading
$data = FlightDAO::getAllUserFlightByUserIdForExportPDF($_SESSION['userID']);

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('FlightClub.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+