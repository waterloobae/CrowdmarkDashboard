<?php

namespace CrowdmarkDashboard;
// include_once '../src/API.php';
// include_once '../src/Question.php';
use CrowdmarkDashboard\API;
use CrowdmarkDashboard\Booklet;
use CrowdmarkDashboard\Question;

class Assessment{
    protected string $assessment_id;
    protected string $assessment_name;
    protected string $created_at;
    protected int $booklet_count;
    protected string $end_point;
    protected array $questions = [];
    protected array $booklets = [];

    // Total counts
    protected int $uploaded_count = 0;
    protected int $matched_count = 0;
    protected array $graded_counts = [];

    protected object $response;

    public function __construct(string $assessment_id)
    {
        $this->assessment_id = $assessment_id;

        $this->end_point = 'api/assessments/' . $assessment_id;
        $api = new API();
        $api->exec($this->end_point);
        $this->response = $api->getResponse();
        $this->assessment_name = $this->response->data->attributes->title;
        $this->booklet_count = $this->response->data->relationships->booklets->meta->count;

        // "-" does not work in PHP Standard Ojbect variable names
        $temp = "created-at";
        $this->created_at = $this->response->data->attributes->$temp;

        $this->setQuestions($assessment_id);
        $this->setBooklets($assessment_id);
        $this->setTotalCounts();
        $this->setGradedCounts();
    }

    public function setTotalCounts()
    {
        foreach($this->booklets as $booklet) {

            if ($booklet->getResponsesCount() > 0) {
                $this->uploaded_count += 1;
            
                // if ($response->status == "graded") {
                //         foreach($booklet->getResponses() as $response) {
                //         $this->graded_counts[$response->question_label] += 1;
                //     }
                // }
            }

            if ($booklet->getEnrollmentId() !== "NA") {
                $this->matched_count += 1;
            }
        }
    }

    public function setQuestions($assessment_id)
    {
         $api = new API();
         $api->exec('api/assessments/' . $assessment_id . '/questions');
         $response = $api->getResponse();
         foreach ($response->data as $question) {
             $this->questions[] = new Question($question);
         }
    }

    public function setGradedCounts()
    {

        foreach($this->booklets as $booklet){
            $end_points[] = 'api/booklets/' . $booklet->getBookletId() . '/responses';
        }

        $api = new API();
        $api->multiExec($end_points);
        $responses = $api->getResponses();

        foreach($responses as $response){
            // echo "==== Response Debug ====<br>";
            // echo("<pre>");
            // var_dump($response->data);
            // echo("</pre>");

             foreach ($response->data as $data) {
                if ($data->type == "response" && $data->attributes->status == "graded"){

                    $temp = $data->relationships->question->links->self;
                    $items = explode("/", $temp);
                    $question_label = end($items);

                    $this->graded_counts[$question_label] += 1;
                }
            }
        }   
    }

    public function setBooklets($assessment_id)
    {
        $self_link = 'api/assessments/' . $assessment_id . '/booklets';

        do {
            $api = new API();
            $api->exec($self_link);
            $response = $api->getResponse();
            // echo "==== Booklet Debug ====<br>";
            // echo("<pre>");
            // var_dump($response->data);
            // echo("</pre>");

            foreach ($response->data as $booklet) {
                $this->booklets[] = new Booklet($booklet);
            }
            $self_link = $response->links->next ?? "end";
        } while ( $self_link != "end");
    }

    public function getQuestions()
    {
         return $this->questions;
    }

    public function getBooklets()
    {
        return $this->booklets;
    }

    public function getUploadedCount()
    {
        return $this->uploaded_count;
    }

    public function getMatchedCount()
    {
        return $this->matched_count;
    }

    public function getGradedCounts()
    {
        return $this->graded_counts;
    }

}
