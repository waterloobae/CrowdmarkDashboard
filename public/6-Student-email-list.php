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

// ==============================
// This script list Booklets whose response Count is off.
// It hardly happens, but it is good to check.
// ==============================

// echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");

foreach($crowdmark->getCourseIds() as $course_id) {
    $course = new Course($course_id);
    if ($course->getCourseName() == "CSMC 2024 G") {
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
}

$email_list = [];

foreach($assessment_ids as $assessment_id) {
    $temp = new Assessment($assessment_id);
    $temp->setMatchedEmailList();
    $email_list = array_merge($email_list, $temp->getMatchedEmailList());
}   
// Download the EmailList as a txt file
$datetime = date('Y-m-d_H-i-s');
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="student_email_list_' . $datetime . '.txt"');
echo implode("\n", $email_list);
exit;

// echo("<pre>");
// var_dump($temp ->getMatchedEmailList());
// echo("</pre>");

// echo("End Time:" . date("Y-m-d H:i:s") . "<br>");
