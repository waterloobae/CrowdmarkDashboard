<?php

namespace Waterloobae\CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Response.php';
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Response;

class Booklet{
    protected string $assessment_id;
    protected string $booklet_id;
    protected string $enrollment_id;
    protected string $booklet_number;
    protected string $responses_link;
    protected int $responses_count;    
    protected string $booklet_link;
    protected array $responses = [];

    public function __construct(string $assessment_id, object $booklet)
    {
        //var_dump($booklet);
        //die();
        $this->assessment_id = $assessment_id;
        $this->booklet_id = $booklet->id;
        $this->enrollment_id = $booklet->relationships->enrollment->data->id ?? "NA";
        $this->booklet_number = $booklet->attributes->number;
        $this->responses_link = $booklet->relationships->responses->links->related ?? "NA";
        $this->responses_count = $booklet->relationships->responses->meta->count;
        $this->booklet_link = $booklet->links->self;
    }
    
    public function setResponsesByAPI()
    {
        $api = new API();
        $api->exec($this->responses_link);
        $response = $api->getResponse();
        foreach ($response->data as $response) {
            $this->responses[] = new Response($this->assessment_id, $response);
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

    public function getBookletId()
    {
        return $this->booklet_id;
    }

    public function getBookletNumber()
    {
        return $this->booklet_number;
    }
}