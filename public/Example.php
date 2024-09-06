<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Course;

echo("Start Time1:" . date("Y-m-d H:i:s") . "<br>");
$course1 = new Course('euclid-d-2024');
//$course2 = new Course('euclid-flex-2024-9a7cc');

echo("Start Time2:" . date("Y-m-d H:i:s") . "<br>");

foreach($course1->getAssessments() as $assessment) {
    echo("Uploaded :". $assessment->getUploadedCount() . "<br>");
    echo("Matched :". $assessment->getMatchedCount() . "<br>");
    echo("<pre>");
    var_dump($assessment->getGradedCounts());
    echo("</pre>");
}

echo("End Time:" . date("Y-m-d H:i:s") . "<br>");

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
