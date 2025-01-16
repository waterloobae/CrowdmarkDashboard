<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Crowdmark;

// ==============================
// This script lists uploaded and matched counts 
// for all assessments in selected courses
// ==============================


//$crowdmark = new Crowdmark('courses');
$assessment_ids = [];
$assessments = [];
$crowdmark = new Crowdmark();

echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");

foreach($crowdmark->getCourseIds() as $course_id) {

    $course = new Course($course_id);
    if (strpos($course->getCourseName(), '2024') !== false && 
        (strpos($course->getCourseName(), 'CSMC') !== false || strpos($course->getCourseName(), 'CIMC') !== false)) {
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
    //$assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
}

foreach($assessment_ids as $assessment_id) {
    $assessments[] = new Assessment($assessment_id);
}   

$totalUploaded = 0;
$totalMatched = 0;

echo("<table>");
echo("<tr> <td>Assessment ID</td>,<td>Uploaded</td>,<td>Matched</td></tr>");
foreach($assessments as $assessment) {
    $assessment->setUploadedAndMatchedCounts();
    $totalUploaded += $assessment->getUploadedCount();
    $totalMatched += $assessment->getMatchedCount();
    echo("<tr>");
    echo("<td>".$assessment->getAssessmentName() . "</td>");
    echo("<td>".$assessment->getUploadedCount() . "</td>");
    echo("<td>".$assessment->getMatchedCount()."</td>");
    // echo("<pre>");
    // var_dump($assessment->getGradedCounts());
    // echo("</pre>");
    echo("</tr>");
}
echo("<tr>");
echo("<td>Total</td>");
echo("<td>".$totalUploaded."</td>");
echo("<td>".$totalMatched."</td>");
echo("</tr>");
echo("</table>");
echo("End Time:" . date("Y-m-d H:i:s") . "<br>");
