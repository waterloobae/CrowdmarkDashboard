<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Crowdmark;
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use setasign\Fpdi\Fpdi;

//$crowdmark = new Crowdmark('courses');
$assessment_ids = [];
$assessments = [];
$crowdmark = new Crowdmark();

// ==============================
// This script list Booklets whose response Count is off.
// It hardly happens, but it is good to check.
// ==============================

echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");

$crowdmark->downloadCoverPages(['csmc-french-c70d7']);

/*** 
foreach($crowdmark->getCourseIds() as $course_id) {

    $course = new Course($course_id);
    if ($course->getCourseName() == "CSMC 2024 G") {
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
}

foreach($assessment_ids as $assessment_id) {
    $temp = new Assessment($assessment_id);
    $temp->setCoverPages($temp->getBooklets());
    $assessments[] = $temp;
}   

$pageUrls = [];
foreach($assessments as $assessment) {
    foreach($assessment->getBooklets() as $booklet) {
        foreach($booklet->getPages() as $page) {
                    if($page->getPageNumber() == "1"){
                        $pageUrls[] = $page->getPageUrl();
                    }
                }
            }
}

$pdf = new Fpdi();
foreach ($pageUrls as $url) {
    $image = file_get_contents($url);
    $imagePath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
    file_put_contents($imagePath, $image);

    list($width, $height) = getimagesize($imagePath);
    $pdf->AddPage('P', [$width, $height]);
    $pdf->Image($imagePath, 0, 0, $width, $height);
    unlink($imagePath);
}


$dateTime = date("Ymd_His");
$pdfOutputPath = __DIR__ . "/cover_pages_$dateTime.pdf";
$pdf->Output($pdfOutputPath, 'F');
echo "PDF created at: " . $pdfOutputPath . "<br>";
***/


echo("<pre>");
var_dump($pageUrls);
echo("</pre>");
echo("End Time:" . date("Y-m-d H:i:s") . "<br>");
