<?php
session_start();
include("inc/connection.php");
include("inc/funcstuffs.php");

ini_set('max_execution_time', 6000);
ini_set('memory_limit', '-1');

$id = $_GET["id"];
$tabname = "data";
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d");
$tabname = "production_1";
$tabmachine = "machine";
$taboperator = "operator";
$tabpro3 = "production_3";
$title = "Manage Operator Report";
$first_day = date('01/m/Y'); // hard-coded '01' for first day
$last_day = date('t/m/Y');

$pageUrl = $sitepath . "manageoperatorreport.php";

if ($_SESSION["msdate"]) {
	$msdate = $_SESSION["msdate"];
}

if ($_SESSION["medate"]) {
	$medate = $_SESSION["medate"];
}

if ($msdate == "") {
	$msdate = $first_day;
}
if ($medate == "") {
	$medate = $last_day;
}

//include("Excel/reader.php");
/// Check Login Session
if ($_SESSION["sadmin_username"] != "") {

	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	ini_set('display_startup_errors', true);

	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	date_default_timezone_set('Asia/kolkata');
	/** Error reporting */
	error_reporting(E_ALL);

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/Classes/Phpspreadsheet/vendor/autoload.php';

// Create new PHPExcel object
	$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	$title = "Operator Report - " . $today;

// Set document properties
	//echo date('H:i:s') , " Set document properties" , EOL;
	$objPHPExcel->getProperties()->setCreator("DCM Industries")
		->setLastModifiedBy("DCM Industries")
		->setTitle($title)
		->setSubject($title)
		->setDescription("")
		->setKeywords("")
		->setCategory("");



	// Create a first sheet, representing sales data
	$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load("sample/sampleoperator.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);

	$k = 3;
	$j = 1;
	$i = 0;
	$objPHPExcel->getActiveSheet()->getStyle('A2:Z5000')->getAlignment()->setWrapText(true);
	$styleArray = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ];


	$wheresql = "";

	if ($msdate != "" && $medate != "") {
		$date = DateTime::createFromFormat('d/m/Y', $msdate);
		$msdate = $date->format('Y-m-d');
		$date = DateTime::createFromFormat('d/m/Y', $medate);
		$medate = $date->format('Y-m-d');

		$wheresql = " where $tabname.productiondate >= '$msdate' and $tabname.productiondate <= '$medate'";

	}

	$ddate = "From :- " . $msdate . " To :- " . $medate;
	$k = 8;
	$percentage = 0;
	$rework_nos = 0;
	$req_qty = 0;
	$prod_qty = 0;
	$turning_rejection_nos = 0;
	$variation_nos = 0;

	$sql = "SELECT $taboperator.operator, round(avg(production_3.production_per),2) as avgproduction,sum($tabpro3.expected_q) as req_qty,sum($tabpro3.total_q_after_rejection) as prod_qty,sum($tabpro3.turning_rejection_nos) as turning_rejection_nos,sum($tabpro3.variation_nos) as variation_nos,sum($tabpro3.rework_nos) as rework_nos FROM $tabname LEFT JOIN $taboperator ON $taboperator.id=$tabname.operator LEFT JOIN $tabpro3 ON $tabpro3.production_1=$tabname.id ".$wheresql." group by $tabname.operator";

	$rs = $db->query($sql) or die("cannot Select Customers" . $db->error);
	while ($row = $rs->fetch_assoc()) {

		$k++;
		$i++;
		$operator = $row["operator"];
		//$percentage += $row["avgproduction"];
		$req_qty += $row["req_qty"];
		$prod_qty += $row["prod_qty"];
		$turning_rejection_nos += $row["turning_rejection_nos"]; 
		$variation_nos += $row["variation_nos"];
		$rework_nos += $row["rework_nos"];

		$perval = 0;
		if($prod_qty > 0){
			$perval = sprintf("%0.02f",($row["prod_qty"] * 100)/$row["req_qty"]);
		}

		$percentage += $perval;
		

		$objPHPExcel->getActiveSheet()->setCellValue('A' . $k, $row["operator"]);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $k, $row["req_qty"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $k, $row["prod_qty"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $k, $row["turning_rejection_nos"]);
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $k, $row["variation_nos"]);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $k, $row["rework_nos"]);
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $k, $perval);

	}

	$percentage = sprintf("%0.2f",$percentage/$i);
	$k++;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $k, 'Total');
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $k, $req_qty);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $k, $prod_qty);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $k, $turning_rejection_nos);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $k, $variation_nos);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $k, $rework_nos);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $k, $percentage);


	$objPHPExcel->getActiveSheet()->getStyle("A9:G" . $k)->applyFromArray($styleArray);

	// Setting Design
	$objPHPExcel->getActiveSheet()->getStyle("A9:G" . $k)->getFont()->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle("A9:G" . $k)->getFont()->setBold(false);
	$objPHPExcel->getActiveSheet()->getStyle("A9:G" . $k)->getFont()->setName('Calibri');
	$objPHPExcel->getActiveSheet()->getStyle("A9:G" . $k)->getAlignment()->setWrapText(true);

	
// Set page orientation and size
	//echo date('H:i:s') , " Set page orientation and size" , EOL;
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

// Rename first worksheet
//    echo date('H:i:s') , " Rename first worksheet" , EOL;
	//   $objPHPExcel->getActiveSheet()->setTitle('FRESH');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	/** Include PHPExcel_IOFactory */
	require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';

	// Save Excel 2007 file
//date('H:i:s') , " Write to Excel2007 format" , EOL;
	$callStartTime = microtime(true);
	$filename = "export_operator_report_from_" . $msdate . "_to_" . $medate . ".xlsx";
	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
	$objWriter->save("export/" . $filename);
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;

	$dir = $sitepath . "export/";

	header("Location: " . $dir . $filename . "");

	print "<META http-equiv='refresh' content=0; URL=" . $pageUrl . ">";
	exit;

} else {
	print "<META http-equiv='refresh' content=0;URL=index.php>";
}
?>