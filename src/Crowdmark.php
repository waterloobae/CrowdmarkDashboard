<?php

namespace Waterloobae\CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Course.php';
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Course;

class Crowdmark
{
    protected array $courses = [];
    protected array $course_ids = [];

    protected object $response;

    public function __construct()
    {
        // constructor
        $api = new API();
        $api->exec('api/courses');
        $this->response = $api->getResponse();
        $course_data = array();
        foreach ($this->response->data as $course_data) {
            //$this->courses[] = new Course($course_data->id);
            $this->course_ids[] = $course_data->id;
        }
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getCourseIds()
    {
        return $this->course_ids;
    }

    public function getResponse()
    {
        return $this->response;
    }

}

