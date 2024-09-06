<?php

namespace Waterloobae\CrowdmarkDashboard;
//include_once '../src/API.php';
use Waterloobae\CrowdmarkDashboard\API;

class Response{
    public string $response_id;
    public string $question_id;
    public string $question_label;
    public string $score_id;
    public string $status;
    public array $pages;
    public string $booklet_id;

    public function __construct(object $response)
    {
        $this->response_id = $response->id;
        $this->question_id = $response->relationships->question->data->id;
        $temp = $response->relationships->question->links->self;
        $items = explode("/", $temp);
        $this->question_label = end($items);
        $this->score_id = $response->relationships->scores->data->id ?? "NA";
        $this->status = $response->attributes->status;
        $this->pages = $response->relationships->pages->data ?? [];
        $this->booklet_id = $response->relationships->booklet->data->id;
    }
    

}