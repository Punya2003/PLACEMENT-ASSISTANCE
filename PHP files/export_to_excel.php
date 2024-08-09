<?php
include 'C:/Xamppp/htdocs/Placement_Assistance/Database Connect Files/db_connect.php';
require '/Xamppp/htdocs/Placement_Assistance/Classes/PHPExcel.php';

// Fetch job applications grouped by company name
$applications_query = "
    SELECT ja.job_id, ja.student_id, jr.job_title, sr.name, sr.usn, jr.company_name
    FROM job_applications ja
    JOIN job_listings jr ON ja.job_id = jr.id
    JOIN student_registration sr ON ja.student_id = sr.id
    ORDER BY jr.company_name, jr.job_title
";
$applications_result = $conn->query($applications_query);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle('Job Applications');

// Add headers
$sheet->setCellValue('A1', 'Company Name');
$sheet->setCellValue('B1', 'Job Title');
$sheet->setCellValue('C1', 'Student Name');
$sheet->setCellValue('D1', 'Student USN');

// Populate data
$rowNumber = 2;
$current_company = '';
while ($application = $applications_result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $application['company_name']);
    $sheet->setCellValue('B' . $rowNumber, $application['job_title']);
    $sheet->setCellValue('C' . $rowNumber, $application['name']);
    $sheet->setCellValue('D' . $rowNumber, $application['usn']);
    $rowNumber++;
}

// Set headers to download file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="job_applications.xlsx"');
header('Cache-Control: max-age=0');

// Write file to output
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2013');
$objWriter->save('php://output');

exit;
?>
