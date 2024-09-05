<?php

namespace Waterloobae\CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Assessment.php';
use CrowdmarkDashboard\API;
use CrowdmarkDashboard\Assessment;

class Course{
    protected string $course_id;
    protected string $course_name;
    protected string $created_at;
    protected string $end_point;
    protected object $response;
    protected array $assessments = [];

    public function __construct($course_id)
    {
        $this->course_id = $course_id;

        $this->end_point = 'api/courses/' . $course_id;
        $api = new API();
        $api->exec($this->end_point);
        $this->response = $api->getResponse();
        $this->course_name = $this->response->data->attributes->name;

        // "-" does not work in PHP Standard Ojbect variable names
        $temp = "created-at";
        $this->created_at = $this->response->data->attributes->$temp;

        $this->setAssessment($course_id);

    }

    public function setAssessment($course_id)
    {
         $api = new API();
         $api->exec('api/courses/' . $course_id . '/assessments');
         $response = $api->getResponse();
         foreach ($response->data as $assessment) {
             $this->assessments[] = new Assessment($assessment->id);
         }
     }

     public function getAssessments()
     {
         return $this->assessments;
     }


}
