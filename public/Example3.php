<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use Waterloobae\CrowdmarkDashboard\Booklet;
use Waterloobae\CrowdmarkDashboard\Crowdmark;

//$crowdmark = new Crowdmark('courses');
$assessment_ids = [];
$assessments = [];
$crowdmark = new Crowdmark();

echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");

foreach($crowdmark->getCourseIds() as $course_id) {

    $course = new Course($course_id);
    if ($course->getCourseName() == "CSMC 2024 F" || $course->getCourseName() == "CSMC 2024 G") {
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
}

foreach($assessment_ids as $assessment_id) {
    $assessments[] = new Assessment($assessment_id);
}   

echo("<table>");
echo("<tr> <td>Assessment ID</td><td>Booklet Number</td><td>Booklet Id</td><td>Responses Count</td></tr>");
foreach($assessments as $assessment) {
    foreach($assessment->getBooklets() as $booklet) {
        //if($booklet->getResponsesCount() != "9" && $booklet->getEnrollmentId() != "NA") {        
        if($booklet->getResponsesCount() > 0 && $booklet->getEnrollmentId() == "NA") {                    
            echo("<tr>");
            echo("<td>".$assessment->getAssessmentID() . "</td>");
            echo("<td>".$booklet->getBookletNumber() . "</td>");
            echo("<td>".$booklet->getBookletId()."</td>");
            echo("<td>".$booklet->getResponsesCount()."</td>");
    echo("</tr>");
        }
    }
}
echo("</table>");
echo("End Time:" . date("Y-m-d H:i:s") . "<br>");


// echo("<pre>");
// var_dump($crowdmark->getCourseIds());
// echo("</pre>");

// $course1 = new Course('euclid-d-2024');
// //$course2 = new Course('euclid-flex-2024-9a7cc');

// echo("Start Time2:" . date("Y-m-d H:i:s") . "<br>");

// foreach($course1->getAssessments() as $assessment) {
//     echo("Uploaded :". $assessment->getUploadedCount() . "<br>");
//     echo("Matched :". $assessment->getMatchedCount() . "<br>");
//     echo("<pre>");
//     var_dump($assessment->getGradedCounts());
//     echo("</pre>");
// }


echo("<pre>");
//var_dump($course1->getAssessments());   
//var_dump($course2->getAssessments());
echo("</pre>");


// include_once '../src/Crowdmark.php';
// use Waterloobae\CrowdmarkDashboard\Crowdmark;

// $crowdmark = new Crowdmark('courses');
// echo("<pre>");
// var_dump($crowdmark->getCourses());
// echo("</pre>");


// include_once '../src/API.php';
// use Waterloobae\CrowdmarkDashboard\API;

// $api = new API('courses');
// echo("<pre>");
// var_dump($api->getResponse());
// echo("</pre>");
