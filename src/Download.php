<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
use Waterloobae\CrowdmarkDashboard\Crowdmark;

class Download{
    private object $logger;
    private object $crowdmark;
    private array $course_names = [];
    private array $assessment_ids = [];
    private string $page_number = "NA";

    public function __construct( object $logger ){
        // constructor
        $this->logger = $logger;
        $this->crowdmark = new Crowdmark( $this->logger );
        $this->course_names = explode("~",$_GET['course_name']);
        $this->page_number = $_GET['page_number'];
        $this->assessment_ids = $this->crowdmark->returnAssessmentIDs($this->course_names);
    }

    public function downloadPage() {
        $this->crowdmark->downloadPagesByPageNumber($this->assessment_ids, $this->page_number);
    }

    public function generateStudentInfo() {
        $this->crowdmark->generateStudentInformation($this->assessment_ids);
    }

    public function generateStudentEmailList() {
        $this->crowdmark->generateStudentEmailList($this->assessment_ids);
    }

    public function generateGradersGradingList(){
        $this->crowdmark->generateGradersGradingList($this->assessment_ids);
    }

    public function generateGradingStatus(){
        $this->crowdmark->generateGradingStatus($this->assessment_ids);
    }

    public function generateUploadedMatchedCounts(){
        $this->crowdmark->generateUploadedMatchedCounts($this->assessment_ids);
    }

    public function generateIntegrityCheckReport(){
        $this->crowdmark->generateIntegrityCheckReport($this->assessment_ids);
    }

}

$dashboard = new Dashboard();

switch($_GET['type']){
    case "page":
        $dashboard->getDownload()->downloadPage();
        break;
    case "studentinfo":
        $dashboard->getDownload()->generateStudentInfo();
        break;
    case "studentemaillist":
        $dashboard->getDownload()->generateStudentEmailList();
        break;
    case "grader": 
        $dashboard->getDownload()->generateGradersGradingList();
        break;
    case "grading":
        $dashboard->getDownload()->generateGradingStatus();
        break;
    case "uploadedmatched":
        $dashboard->getDownload()->generateUploadedMatchedCounts();
        break;
    case "integritycheck":
        $dashboard->getDownload()->generateIntegrityCheckReport();
        break;

}
