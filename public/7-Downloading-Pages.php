<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Crowdmark;
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use Waterloobae\CrowdmarkDashboard\Booklet;
use Waterloobae\CrowdmarkDashboard\Response;
use Waterloobae\CrowdmarkDashboard\Page;

//$crowdmark = new Crowdmark('courses');
$assessment_ids = [];
$assessments = [];
$crowdmark = new Crowdmark();

// ==============================
// This script list Booklets whose response Count is off.
// It hardly happens, but it is good to check.
// ==============================

echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");

foreach($crowdmark->getCourseIds() as $course_id) {

    $course = new Course($course_id);
    if ($course->getCourseName() == "CSMC 2024 G") {
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
}

foreach($assessment_ids as $assessment_id) {
    $temp = new Assessment($assessment_id);
    $temp->setResponses($temp->getBooklets());
    $assessments[] = $temp;
}   

$pageUrls = [];
foreach($assessments as $assessment) {
    foreach($assessment->getBooklets() as $booklet) {
        foreach($booklet->getResponses() as $response) {
            foreach($response->getPages() as $page) {
                if ($page instanceof Page ) {
                    if($page->getPageNumber() == "2"){
                        $pageUrls[] = $page->getPageUrl();
                    }
                }
            }
        }
    }
}

echo("<pre>");
var_dump($pageUrls);
echo("</pre>");
echo("End Time:" . date("Y-m-d H:i:s") . "<br>");
