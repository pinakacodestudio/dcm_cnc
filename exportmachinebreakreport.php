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
$tabpro = "production_machine_breakdown";
$title = "Manage Machine Report";
$first_day = date('01/m/Y'); // hard-coded '01' for first day
$last_day = date('t/m/Y');

$pageUrl = $sitepath . "managemachinebreakreport.php";

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

	$title = "Machine Report - " . $today;

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
	$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load("sample/samplemachinebreak.xlsx");
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

	if ($msdate != "" && $medate != "") {
		$date = DateTime::createFromFormat('d/m/Y', $msdate);
		$msdate = $date->format('Y-m-d');
		$date = DateTime::createFromFormat('d/m/Y', $medate);
		$medate = $date->format('Y-m-d');

		$sql = " where $tabname.productiondate >= '$msdate' and $tabname.productiondate <= '$medate'";

	}

	$ddate = "From :- " . $msdate . " To :- " . $medate;
	$k = 8;
	$setting_hour = 0;
	$machine_fault_hour = 0;
	$recess_hour = 0;
	$maintanance_hour = 0;
	$no_operator_hour = 0;
	$no_load_hour = 0;
	$power_fail_hour = 0;
	$rework = 0;
	$other = 0;
	$total_breakdown_hours = 0;

	$sql = "SELECT $tabname.id,$tabname.productiondate,$tabmachine.machine, sum($tabpro.setting_hour) as setting_hour, sum($tabpro.machine_fault_hour) as machine_fault_hour,sum($tabpro.recess_hour) as recess_hour,sum($tabpro.maintanance_hour) as maintanance_hour,sum($tabpro.no_operator_hour) as no_operator_hour,sum($tabpro.no_load_hour) as no_load_hour,sum($tabpro.power_fail_hour) as power_fail_hour,sum($tabpro.other) as other,sum($tabpro.total_breakdown_hours) as total_breakdown_hours,sum($tabpro.rework) as rework FROM $tabname LEFT JOIN $tabmachine ON $tabmachine.id=$tabname.machine LEFT JOIN $tabpro ON $tabpro.production_1=$tabname.id " . $sql . " group by $tabname.machine";
	$rs = $db->query($sql) or die("cannot Select Customers" . $db->error);
	while ($row = $rs->fetch_assoc()) {

		$k++;
		$i++;

		$setting_hour += $row["setting_hour"];
		$machine_fault_hour += $row["machine_fault_hour"];
		$recess_hour += $row["recess_hour"];
		$maintanance_hour += $row["maintanance_hour"];
		$no_operator_hour += $row["no_operator_hour"];
		$no_load_hour += $row["no_load_hour"];
		$power_fail_hour += $row["power_fail_hour"];
		$rework += $row["rework"];
		$other += $row["other"];
		$total_breakdown_hours += $row["total_breakdown_hours"];

		$objPHPExcel->getActiveSheet()->setCellValue('A' . $k, $row["machine"]);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $k, $row["setting_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $k, $row["machine_fault_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $k, $row["recess_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $k, $row["maintanance_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $k, $row["no_operator_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $k, $row["no_load_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('H' . $k, $row["power_fail_hour"]);
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $k, $row["rework"]);
		$objPHPExcel->getActiveSheet()->setCellValue('J' . $k, $row["other"]);
		$objPHPExcel->getActiveSheet()->setCellValue('K' . $k, $row["total_breakdown_hours"]);

	}
	$k++;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $k, 'Total');
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $k, $setting_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $k, $machine_fault_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $k, $recess_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $k, $maintanance_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $k, $no_operator_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $k, $no_load_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $k, $power_fail_hour);
	$objPHPExcel->getActiveSheet()->setCellValue('I' . $k, $rework);
	$objPHPExcel->getActiveSheet()->setCellValue('J' . $k, $other);
	$objPHPExcel->getActiveSheet()->setCellValue('K' . $k, $total_breakdown_hours);


	$objPHPExcel->getActiveSheet()->getStyle("A9:K" . $k)->applyFromArray($styleArray);

	// Setting Design
	$objPHPExcel->getActiveSheet()->getStyle("A9:K" . $k)->getFont()->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle("A9:K" . $k)->getFont()->setBold(false);
	$objPHPExcel->getActiveSheet()->getStyle("A9:K" . $k)->getFont()->setName('Calibri');
	$objPHPExcel->getActiveSheet()->getStyle("A9:K" . $k)->getAlignment()->setWrapText(true);

// Set page orientation and size
    //echo date('H:i:s') , " Set page orientation and size" , EOL;
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

	$objPHPExcel->getActiveSheet()->setCellValue('G6', $ddate);
	

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
	$filename = "export_machine_report_from_" . $msdate . "_to_" . $medate . ".xlsx";
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