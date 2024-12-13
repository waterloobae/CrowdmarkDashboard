<?php

namespace Waterloobae\CrowdmarkDashboard;

class Page{
    protected string $assessment_id;
    protected string $booklet_id;
    protected string $response_id;
    protected string $page_id;
    protected string $page_url;
    protected string $page_number;
 
    public function __construct(object $page)
    {
        // $this->question_id = $question->id;
        // $this->end_point = 'questions/' . $question->id;
        // $this->question_name = $question->attributes->label;
        // $temp = "max-points"; // "-" does not work in PHP Standard Ojbect variable names
        // $this->max_points = $question->attributes->$temp;

    }

    // public function getQuestionId()
    // {
    //     return $this->question_id;
    // }

 
}
