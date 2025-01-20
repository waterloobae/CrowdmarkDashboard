<?php

namespace Waterloobae\CrowdmarkDashboard;
use Waterloobae\CrowdmarkDashboard\API;
use Waterloobae\CrowdmarkDashboard\Course;
use Waterloobae\CrowdmarkDashboard\Assessment;
use Waterloobae\CrowdmarkDashboard\Page;

use setasign\Fpdi\Fpdi;

class Crowdmark
{
    protected array $courses = [];
    protected array $course_ids = [];
    protected array $assessment_ids = [];

    protected object $api_response;

    public function __construct()
    {
        // constructor
        $api = new API();
        $api->exec('api/courses');
        $this->api_response = $api->getResponse();
        $course_data = array();
        foreach ($this->api_response->data as $course_data) {
            $this->courses[] = new Course($course_data->id);
            $this->course_ids[] = $course_data->id;
        }
        $this->setAssessmentIDs();
    }

    public function setAssessmentIDs()
    {
        foreach ($this->courses as $course) {
            $this->assessment_ids = array_merge($this->assessment_ids, $course->getAssessmentIds());
        }
    }   

    public function returnAssessmentIDs(array $course_names)
    {
        $assessment_ids = [];
        foreach($this->courses as $course) {
            if(in_array($course->getCourseName(), $course_names)) {
                $assessment_ids = array_merge($assessment_ids, $course->getAssessmentIds());
            }
        }
        return $assessment_ids;
    }

    public function createDownloadLinks(string $type, array $course_names, string $page_number = null)
    {
        $valid_encoded_course_names = [];
        $relativePath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
        
        switch($type) {
            case "page":
                echo "<h2>Download Booklet Pages</h2>";
                break;
            case "studentinfo":
                echo "<h2>Download Student Information</h2>";
                break;
            case "studentemaillist":
                echo "<h2>Download Student Email List</h2>";
                break;
            case "grader":
                echo "<h2>Download Grader's Grading List</h2>";
                break;
            case "grading":
                echo "<h2>Download Grading Status</h2>";
                break;
        }
        
        foreach($course_names as $course_name) {
            $is_valid = false;
            
            foreach($this->courses as $course) {
                if($course->getCourseName() == $course_name) {
                    $is_valid = true;
                    break;
                }
            }
            $encoded_course_name = urlencode($course_name);
            
            $download_link = $relativePath."/Download.php?type=" . $type . "&course_name=" . $encoded_course_name. "&page_number=" . $page_number;
            if($is_valid) {
                $valid_encoded_course_names[] = $encoded_course_name;    
                echo '<a href="' . $download_link . '" download onclick="this.innerText=\'Loading '.$course_name.'. Please wait!\'; this.style.pointerEvents = \'none\';">Download (' . $course_name . ')</a><br>';
            } else {
                echo "Invalid course name: " . $course_name . "<br>";
            }
        }

        echo("<br>");
        if(empty($valid_encoded_course_names)) {
            echo "No valid course names found.";
        }else{
            $download_link = $relativePath."/Download.php?type=" . $type . "&course_name=" . implode("~", $valid_encoded_course_names). "&page_number=" . $page_number;
            echo '<a href="' . $download_link . '" download onclick="this.innerText=\'Loading All Courses. Please wait!\'; this.style.pointerEvents = \'none\';">Download All Course</a><br>';
        }
        echo("<br>");
    }

    //=================================
    //
    // Functions with Business Logic
    //=================================
    public function generateGradingStatus(array $assessment_ids)
    {
        $graded_counts = [];
        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            $graded_counts['course'] = $temp->getCourseName();
            $courseName = $graded_counts['course'];
            $temp->setUploadedAndMatchedCounts();
            $temp->setGradedCountsFromBooklets();
        
            $graded_counts[$courseName]['!booket_count'] = $temp->getUploadedCount();
            // Add counts per course, excluding '!course'
            foreach ($temp->getGradedCounts() as $key => $value) {
                if ($key !== 'course') {
                    if (!isset($graded_counts[$courseName][$key])) {
                        $graded_counts[$courseName][$key] = 0;
                    }
                    $graded_counts[$courseName][$key] += $value;
                }
            }
        }
        
         foreach ($graded_counts as &$subarray) {
            if (is_array($subarray)) {
                ksort($subarray);
            }
        }
        
        $filename = "graded_counts_" . date("Ymd-His") . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Get headers from the first subarray
        $headers = ['Course'];
        foreach($graded_counts as $firstSubarray) {
            if(is_array($firstSubarray)){
                break;
            }
        }
        $headers = array_merge($headers, array_keys($firstSubarray));
        fputcsv($output, $headers);

        foreach ($graded_counts as $course => $counts) {
            if(is_array($counts)){
                $row = array_merge([$course], $counts);
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }

    public function generateGradersGradingList(array $assessment_ids){
        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            $temp->setResponses($temp->getBooklets());
            $assessments[] = $temp;
        }   
        
        $question_id_to_names = [];
        $grader_id_to_names = [];
        $grader_id_to_emails = [];
        $grades = [];
        
        foreach($assessments as $assessment) {
            // 1. Creating questions array
            foreach($assessment->getQuestions() as $question) {
                $question_id_to_names[$question->getQuestionId()] = $question->getQuestionName();
            }
        
            // 2. Creating Graders array
            foreach($assessment->getGraders() as $grader) {
                $grader_id_to_names[$grader->getUserId()] = $grader->getName();
                $grader_id_to_emails[$grader->getUserId()] = $grader->getEmail();
                //echo($grader->getEmail() . "<br>");
            }
        }
        
        // Sort questions by question name
        uasort($question_id_to_names, function($a, $b) {
            return strcmp($a, $b);
        });
        
        foreach($assessments as $assessment) {
            // 3. Creating Grade Counts array
            foreach($assessment->getBooklets() as $booklet) {
                foreach($booklet->getResponses() as $response) {
                    if (!isset($grades[$response->getGraderId()][$response->getQuestionLabel()])) {
                        $grades[$response->getGraderId()][$response->getQuestionLabel()] = 0;
                    }
                    $grades[$response->getGraderId()][$response->getQuestionLabel()]++;
                }
            }
        }
        
        // Sort graders by name and then by question label
        uksort($grades, function($a, $b) use ($grader_id_to_names) {
            return strcmp($grader_id_to_names[$a] ?? 'Unknown', $grader_id_to_names[$b] ?? 'Unknown');
        });
        
        // Set headers to download the CSV file
        $filename = 'graders_' . date('Ymd-His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        // Open the output stream
        $fp = fopen('php://output', 'w');
        
        // Write the header
        $header = ['Grader Name', 'Grader Email'];
        $question_labels = array_unique(array_merge(...array_values(array_map('array_keys', $grades))));
        sort($question_labels);
        
        $header = array_merge($header, array_map('strtoupper', $question_labels));
        fputcsv($fp, $header);
        
        // Write the data
        foreach ($grades as $grader_id => $questions) {
            $row = [
                $grader_id_to_names[$grader_id] ?? 'Unknown',
                $grader_id_to_emails[$grader_id] ?? 'Unknown'
            ];
            foreach ($question_labels as $label) {
                $row[] = $questions[$label] ?? 0;
            }
            fputcsv($fp, $row);
        }
        
        fclose($fp);
        exit();        
    }


    public function generateStudentEmailList(array $assessment_ids){
        $email_list = [];

        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            $temp->setMatchedEmailList();
            $email_list = array_merge($email_list, $temp->getMatchedEmailList());
        }   
        // Download the EmailList as a txt file
        $datetime = date('Ymd-His');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="student_email_list_' . $datetime . '.txt"');
        echo implode("\n", $email_list);
        exit;
    }

    public function generateStudentInformation(array $assessment_ids){
        $student_list = [];
        $student_list[] = "Email, First Name, Last Name, Participant ID";

        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            $temp->setStdentCSVList();
            $student_list = array_merge($student_list, $temp->getStudentCSVList());
        }   
        // Download the EmailList as a txt file
        $datetime = date('Ymd-His');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="student_list_' . $datetime . '.csv"');
        echo implode("\n", $student_list);
        exit;
    }

    public function downloadPagesByPageNumber(array $assessment_ids, string $page_number)
    {
        $assessments = [];
        foreach($assessment_ids as $assessment_id) {
            $temp = new Assessment($assessment_id);
            if($page_number == '1'){
                $temp->setCoverPages($temp->getBooklets());
            }else{
                $temp->setResponses($temp->getBooklets());
            }
            $assessments[] = $temp;
        }   

        $pageUrls = [];
        foreach($assessments as $assessment) {
            foreach($assessment->getBooklets() as $booklet) {
                if($page_number == '1'){
                    foreach($booklet->getPages() as $page) {
                        if($page->getPageNumber() == $page_number){
                            $pageUrls[] = $page->getPageUrl();
                        }
                    }
                }else{
                    foreach($booklet->getResponses() as $response) {
                        foreach($response->getPages() as $page) {
                            // Some of $page is not an instance of Page, but stdClass.
                            if ($page instanceof Page ) {
                                //error_log("Page Number: ".$page->getPageNumber());
                                if($page->getPageNumber() == $page_number){
                                    $pageUrls[] = $page->getPageUrl();
                                }
                            }
                        }
                    }
                }
            }
        }

        $pdf = new Fpdi();
        foreach ($pageUrls as $url) {
            // debug
            // error_log(substr($url, 0, 10));
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Set timeout to 30 seconds
            $image = curl_exec($ch);

            curl_close($ch);
            $imagePath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
            file_put_contents($imagePath, $image);

            list($width, $height) = getimagesize($imagePath);
            $pdf->AddPage('P', [$width, $height]);
            $pdf->Image($imagePath, 0, 0, $width, $height);
            unlink($imagePath);
        }

        $dateTime = date("Ymd_His");
        $fileName = "Page_".$page_number."_". $dateTime . ".pdf";
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'. $fileName . '"');
        $pdf->Output($fileName, 'D');

        // $pdf->Output('F', sys_get_temp_dir() . "/cover_pages_" . $dateTime . ".pdf");
        // echo '<a href="'. sys_get_temp_dir() . $dateTime . '.pdf" download>Download PDF</a>';
    }
    

    //=================================
    // Ordinary Setters and Getters
    //=================================

    public function getCourses()
    {
        return $this->courses;
    }

    public function getCourseIds()
    {
        return $this->course_ids;
    }

    public function getAPIResponse()
    {
        return $this->api_response;
    }

}

