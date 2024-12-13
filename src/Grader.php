<?php

namespace Waterloobae\CrowdmarkDashboard;

class Grader{
    protected string $user_id;
    protected string $name;
    protected string $email;
 
    public function __construct(object $grader)
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
