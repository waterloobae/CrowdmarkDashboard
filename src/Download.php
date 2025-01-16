<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
use Waterloobae\CrowdmarkDashboard\Crowdmark;

class Download{

    public function __construct(){
        // constructor
    }

    public function downloadPage() {
        $crowdmark = new Crowdmark();
        $course_names = explode("~",$_GET['course_name']);
        $page_number = $_GET['page_number'];
        
        $assessment_ids = $crowdmark->returnAssessmentIDs($course_names);
        $crowdmark->downloadPagesByPageNumber($assessment_ids, $page_number);
    }

    public function generateStudentInfo() {
        $crowdmark = new Crowdmark();
        $course_names = explode("~",$_GET['course_name']);
        
        $assessment_ids = $crowdmark->returnAssessmentIDs($course_names);
        $crowdmark->generateStudentInformation($assessment_ids);
    }
}

$download = new Download();
switch($_GET['type']){
    case "page":
        $download->downloadPage();
        break;
    case "studentinfo":
        $download->generateStudentInfo();
        break;
}
