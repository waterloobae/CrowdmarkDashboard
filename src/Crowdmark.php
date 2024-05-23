<?php

namespace CrowdmarkDashboard;
include_once '../src/API.php';
include_once '../src/Course.php';
use CrowdmarkDashboard\API;
use CrowdmarkDashboard\Course;

class Crowdmark
{
    protected array $courses = [];
    protected object $response;

    public function __construct()
    {
        // constructor
        $api = new API();
        $api->exec('aip/courses');
        $this->response = $api->getResponse();
        $course_data = array();
        foreach ($this->response->data as $course_data) {
            $this->courses[] = new Course($course_data->id);
        }
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getResponse()
    {
        return $this->response;
    }

}

