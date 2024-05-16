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
        $api = new API('aip/courses');
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

    // public function getCourse($course_id)
    // {
    //     $api = new API('courses/' . $course_id);
    //     $response = $api->getResponse();
    //     return new Course($response);
    // }

    // public function getAssignments($course_id)
    // {
    //     $api = new API('courses/' . $course_id . '/assignments');
    //     $response = $api->getResponse();
    //     $assignments = array();
    //     foreach ($response as $assignment) {
    //         $assignments[] = new Assignment($assignment);
    //     }
    //     return $assignments;
    // }

    // public function getAssignment($course_id, $assignment_id)
    // {
    //     $api = new API('courses/' . $course_id . '/assignments/' . $assignment_id);
    //     $response = $api->getResponse();
    //     return new Assignment($response);
    // }

    // public function getSubmissions($course_id, $assignment_id)
    // {
    //     $api = new API('courses/' . $course_id . '/assignments/' . $assignment_id . '/submissions');
    //     $response = $api->getResponse();
    //     $submissions = array();
    //     foreach ($response as $submission) {
    //         $submissions[] = new Submission($submission);
    //     }
    //     return $submissions;
    // }

    // public function getSubmission($course_id, $assignment_id, $submission_id)
    // {
    //     $api = new API('courses/' . $course_id . '/assignments/' . $assignment_id . '/submissions/' . $submission_id);
    //     $response = $api->getResponse();
    //     return new Submission($response);
    // }

    // public function getSubmissionPDF($course_id, $assignment_id, $submission_id)
    // {
    //     $api = new API('courses/' . $course_id . '/assignments/' . $assignment_id . '/submissions/' . $submission_id . '/pdf');
    //     $response = $api->getResponse();
    //     return $response;
    // }

}

