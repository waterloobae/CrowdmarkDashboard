<?php

namespace Waterloobae\CrowdmarkDashboard;
//include_once '../src/API.php';
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Question;
use Waterloobae\CrowdmarkDashboard\Grader;
use Waterloobae\CrowdmarkDashboard\Page;

class Response{
    protected string $assessment_id;
    protected string $booklet_id;
    protected string $response_id;

    protected string $score_id;
    protected string $is_graded_status;
    protected float $score;

    protected string $question_id;
    protected string $grader_id;

    protected array $pages = [];

    public function __construct(string $assessment_id, object $response)
    {
        $this->assessment_id = $assessment_id;
        $this->response_id = $response->id;
        $temp = $response->relationships->question->links->self;
        $items = explode("/", $temp);
        // $this->question_id = $response->relationships->question->data->id;
        // $this->question_label = end($items);
        $this->score_id = $response->relationships->scores->data->id ?? "NA";
        $this->is_graded_status = $response->attributes->status;
        $this->pages = $response->relationships->pages->data ?? [];
        $this->booklet_id = $response->relationships->booklet->data->id;
    }
    

}