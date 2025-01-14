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
        $type = $_GET['type'];
        $course_name = $_GET['course_name'];
        $page_number = $_GET['page_number'];
        
        $assessment_ids = $crowdmark->returnAssessmentIDs([$course_name]);
        $crowdmark->downloadPagesByPageNumber($assessment_ids, $page_number);

    }
}

$download = new Download();

if(isset($_GET['type']) == 'page'){
    $download->downloadPage();
}
