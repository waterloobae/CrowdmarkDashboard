<?php

namespace Waterloobae\CrowdmarkDashboard;
//include_once '../src/API.php';
use CrowdmarkDashboard\API;

class Question{
    protected string $question_id;
    protected string $question_name;
    protected string $question_sequence_number;
    protected string $created_at;
    protected string $end_point;
    protected string $response_count;

    public function __construct(object $question)
    {
        $this->question_id = $question->id;
        $this->end_point = 'questions/' . $question->id;
        $this->question_name = $question->attributes->label;
        $this->question_sequence_number = $question->attributes->sequence;
        $this->response_count = $question->relationships->responses->meta->count;
        // "-" does not work in PHP Standard Ojbect variable names
        $temp = "created-at";
        $this->created_at = $question->attributes->$temp;
    }

    public function getQuestionId()
    {
        return $this->question_id;
    }

    public function getQuestionName()
    {
        return $this->question_name;
    }

    public function getQuestionSequenceNumber()
    {
        return $this->question_sequence_number;
    }
}
