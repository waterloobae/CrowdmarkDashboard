<?php

namespace Waterloobae\CrowdmarkDashboard;
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Booklet;
use Waterloobae\CrowdmarkDashboard\Question;
use Exception;

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
        $this->setUploadedAndMatchedCounts();
        $this->setGradedCounts();
    }

    public function setUploadedAndMatchedCounts()
    {
        foreach($this->booklets as $booklet) {

            if ($booklet->getResponsesCount() > 0) {
                $this->uploaded_count += 1;
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
        // This will be faster than setGradedCountsFromBooklets, but
        // 504 Gateway Time-out error will occur since there are too many booklets
        foreach($this->questions as $question){
            $end_point = 'api/questions/' . $question->getQuestionId() . '/responses';

            $api = new API();

            try {
                $api->exec($end_point);
                $response = $api->getResponse();

                foreach ($response->data as $data) {
                    if ($data->type == "response" && $data->attributes->status == "graded"){
                        $temp = $data->relationships->question->links->self;
                        $items = explode("/", $temp);
                        $question_label = end($items);
                        $this->graded_counts[$question_label] += 1;
                    }
                }
            }
            catch (Exception $e) {
                error_log('Caught exception: '.$e->getMessage());
                $this->setGradedCountsFromBooklets();
                // This will break the loop 
                // to stop going through the rest of the questions
                break;
            }

        }
    }

    public function setGradedCountsFromBooklets()
    {

        foreach($this->questions as $question){
            $sequence[ $question->getQuestionName() ] = $question->getQuestionSequenceNumber();
            $this->graded_counts[$question->getQuestionSequenceNumber()] = 0;
        }

        echo "==== Response Debug ====<br>";
        echo("<pre>");
        var_dump($sequence);
        echo("</pre>");


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

             foreach ($response as $data) {
                if ($data->type == "response" && $data->attributes->status == "graded"){
                    $temp = $data->relationships->question->links->self;
                    $items = explode("/", $temp);
                    $question_label = end($items);
                    $this->graded_counts[$sequence[$question_label]] += 1;
                }
            }
        }
        
        // Sorting by sequence number
        ksort($this->graded_counts, 1);
        

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
