<?php
require_once('tcpdf_config_alt.php');

// Include the main TCPDF library (search the library on the following directories).
$tcpdf_include_dirs = array(
	//realpath('tcpdf/tcpdf.php'),
	'./assets/tcpdf/tcpdf.php'
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
	error_log("TCPDF not found");
}