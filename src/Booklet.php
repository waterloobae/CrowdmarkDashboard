<?php

namespace CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Response.php';
use CrowdmarkDashboard\API;
use CrowdmarkDashboard\Response;

class Booklet{
    protected string $booklet_id;
    protected string $enrollment_id;
    protected string $booklet_number;
    protected string $responses_link;
    protected int $responses_count;    
    protected string $booklet_link;
    protected array $responses = [];

    public function __construct(object $booklet)
    {
        //var_dump($booklet);
        //die();
        $this->booklet_id = $booklet->id;
        $this->enrollment_id = $booklet->relationships->enrollment->data->id ?? "NA";
        $this->booklet_number = $booklet->attributes->number;
        $this->responses_link = $booklet->relationships->responses->links->related ?? "NA";
        $this->responses_count = $booklet->relationships->responses->meta->count;
        $this->booklet_link = $booklet->links->self;

        //Responses are calculated in the assessment class to seep up the process
        //$this->setResponses();
    }
    
    public function setResponses()
    {
        $api = new API($this->responses_link);
        $response = $api->getResponse();
        foreach ($response->data as $response) {
            $this->responses[] = new Response($response);
        }
    }

    public function getResponses()
    {
        return $this->responses;
    }

    public function getEnrollmentId()
    {
        return $this->enrollment_id;
    }

    public function getResponsesLink()
    {
        return $this->responses_link;
    }

    public function getResponsesCount()
    {
        return $this->responses_count;
    }
}