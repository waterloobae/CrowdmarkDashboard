<?php

namespace Waterloobae\CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use setasign\Fpdi\Fpdi;

class Crowdmark
{
    protected array $courses = [];
    protected array $course_ids = [];
    protected array $assessment_ids = [];

    protected object $api_response;

    public function __construct()
    {
        // constructor
        $api = new API();
        $api->exec('api/courses');
        $this->api_response = $api->getResponse();
        $course_data = array();
        foreach ($this->api_response->data as $course_data) {
            $this->courses[] = new Course($course_data->id);
            $this->course_ids[] = $course_data->id;
        }
        $this->setAssessmentIDs();
    }

    public function setAssessmentIDs()
    {
        foreach ($this->courses as $course) {
            $this->assessment_ids = array_merge($this->assessment_ids, $course->getAssessmentIds());
        }
    }   

    public function downloadCoverPages(array $assessment_ids)
    {
        $assessments = [];
        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            $temp->setCoverPages($temp->getBooklets());
            $assessments[] = $temp;
        }   

        $pageUrls = [];
        foreach($assessments as $assessment) {
            foreach($assessment->getBooklets() as $booklet) {
                foreach($booklet->getPages() as $page) {
                    if($page->getPageNumber() == "1"){
                        $pageUrls[] = $page->getPageUrl();
                    }
                }
            }
        }

        $pdf = new Fpdi();
        foreach ($pageUrls as $url) {
            $image = file_get_contents($url);
            $imagePath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
            file_put_contents($imagePath, $image);

            list($width, $height) = getimagesize($imagePath);
            $pdf->AddPage('P', [$width, $height]);
            $pdf->Image($imagePath, 0, 0, $width, $height);
            unlink($imagePath);
        }

        $dateTime = date("Ymd_His");
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="cover_pages_' . $dateTime . '.pdf"');
        $pdf->Output('php://output', 'I');
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getCourseIds()
    {
        return $this->course_ids;
    }

    public function getAPIResponse()
    {
        return $this->api_response;
    }

}

