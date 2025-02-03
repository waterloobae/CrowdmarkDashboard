<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
use Waterloobae\CrowdmarkDashboard\Dashboard;

class Download{
    private object $dashboard;
    private array $course_names = [];
    private array $assessment_ids = [];
    private string $page_number = "NA";
    private string $link_type = "NA";

    public function __construct(){
        // constructor
        $this->dashboard = new Dashboard();
    }

    public function setParams(){
        $this->course_names = explode("~",$_GET['course_name']);
        $this->page_number = $_GET['page_number'];    
        $this->link_type = $_GET['type'];
        $this->assessment_ids = $this->dashboard->getCrowdmark()->returnAssessmentIDs($this->course_names);
    }

    public function downloadPage() {
        $this->dashboard->getCrowdmark()->downloadPagesByPageNumber($this->assessment_ids, $this->page_number);
    }

    public function generateStudentInfo() {
        $this->dashboard->getCrowdmark()->generateStudentInformation($this->assessment_ids);
    }

    public function generateStudentEmailList() {
        $this->dashboard->getCrowdmark()->generateStudentEmailList($this->assessment_ids);
    }

    public function generateGradersGradingList(){
        $this->dashboard->getCrowdmark()->generateGradersGradingList($this->assessment_ids);
    }

    public function generateGradingStatus(){
        $this->dashboard->getCrowdmark()->generateGradingStatus($this->assessment_ids);
    }

    public function generateUploadedMatchedCounts(){
        $this->dashboard->getCrowdmark()->generateUploadedMatchedCounts($this->assessment_ids);
    }

    public function generateIntegrityCheckReport(){
        $this->dashboard->getCrowdmark()->generateIntegrityCheckReport($this->assessment_ids);
    }

    public function createLink(){

        switch($this->link_type){
            case "page":
                $this->downloadPage();
                break;
            case "studentinfo":
                $this->generateStudentInfo();
                break;
            case "studentemaillist":
                $this->generateStudentEmailList();
                break;
            case "grader": 
                $this->generateGradersGradingList();
                break;
            case "grading":
                $this->generateGradingStatus();
                break;
            case "uploadedmatched":
                $this->generateUploadedMatchedCounts();
                break;
            case "integritycheck":
                $this->generateIntegrityCheckReport();
                break;
        }
        

    }

}

$dwonload = new Download();
$dwonload->setParams();
$dwonload->createLink();

