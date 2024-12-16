<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
//include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use Waterloobae\CrowdmarkDashboard\Crowdmark;

//$crowdmark = new Crowdmark('courses');
$assessment_ids = [];
$assessments = [];
$crowdmark = new Crowdmark();

echo("Start Time:" . date("Y-m-d H:i:s") . "<br>");
//$courses = Array("CSMC 2024 F", "CSMC 2024 G");

foreach($crowdmark->getCourseIds() as $course_id) {
    $course = new Course($course_id);
    if (strpos($course->getCourseName(), '2024') !== false && strpos($course->getCourseName(), 'CIMC') !== false){
        $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
    }
}

echo("<pre>");
var_dump($assessment_ids);
echo("</pre>");

foreach($assessment_ids as $assessment_id) {
    $temp = new Assessment($assessment_id);
    $temp->setResponses($temp->getBooklets());
    $assessments[] = $temp;
}   

$question_id_to_names = [];
$grader_id_to_names = [];
$grader_id_to_emails = [];
$grades = [];

foreach($assessments as $assessment) {
    // 1. Creating questions array
    foreach($assessment->getQuestions() as $question) {
        $question_id_to_names[$question->getQuestionId()] = $question->getQuestionName();
    }

    // 2. Creating Graders array
    foreach($assessment->getGraders() as $grader) {
        $grader_id_to_names[$grader->getUserId()] = $grader->getName();
        $grader_id_to_emails[$grader->getUserId()] = $grader->getEmail();
        //echo($grader->getEmail() . "<br>");
    }
}

// Sort questions by question name
uasort($question_id_to_names, function($a, $b) {
    return strcmp($a, $b);
});

// echo("<pre>");
// var_dump($question_id_to_names);
// echo("</pre>");

foreach($assessments as $assessment) {
    // 3. Creating Grade Counts array
    foreach($assessment->getBooklets() as $booklet) {
        foreach($booklet->getResponses() as $response) {
            if (!isset($grades[$response->getGraderId()][$response->getQuestionLabel()])) {
                $grades[$response->getGraderId()][$response->getQuestionLabel()] = 0;
            }
            $grades[$response->getGraderId()][$response->getQuestionLabel()]++;
        }
    }
}

// Sort graders by name and then by question label

uksort($grades, function($a, $b) use ($grader_id_to_names) {
    return strcmp($grader_id_to_names[$a] ?? 'Unknown', $grader_id_to_names[$b] ?? 'Unknown');
});

// echo("<pre>");
// var_dump($grades);
// echo("</pre>");

$filename = 'grades.csv';
$fp = fopen($filename, 'w');

// Write the header
$header = ['Grader Name', 'Grader Email'];
$question_labels = array_unique(array_merge(...array_values(array_map('array_keys', $grades))));
sort($question_labels);

$header = array_merge($header, array_map('strtoupper', $question_labels));
fputcsv($fp, $header);

// Write the data
foreach ($grades as $grader_id => $questions) {
    $row = [
        $grader_id_to_names[$grader_id] ?? 'Unknown',
        $grader_id_to_emails[$grader_id] ?? 'Unknown'
    ];
    foreach ($question_labels as $label) {
        $row[] = $questions[$label] ?? 0;
    }
    fputcsv($fp, $row);
}


fclose($fp);
echo "CSV file has been generated: $filename<br>";

echo("End Time:" . date("Y-m-d H:i:s") . "<br>");

