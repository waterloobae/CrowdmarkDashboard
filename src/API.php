<?php

namespace CrowdmarkDashboard;

class API
{
    protected string $url = 'https://app.crowdmark.com/';
    protected string $api_key_string;
    protected object $api_response;
    protected int $max_retries = 5;
    protected int $current_try = 0;

    public function __construct(string $end_point) 
    {
        // constructor
        $this->buildApiKeyString();
 
        $curl = curl_init();
         // Does end_point have a ? in it?
         if (strpos($end_point, '?') !== false) {
            curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '&' . $this->api_key_string);
        } else {
            curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '?' . $this->api_key_string);
        }
        echo "URL: " . $this->url . $end_point . '?' . $this->api_key_string . "<br>";
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        set_time_limit(300);
        //$response = curl_exec($curl);
        do {
            $this->current_try++;
            $response = curl_exec($curl);
        
            if (json_decode($response) === null) {
                echo "Attempt $this->current_try failed. Retrying..."."<br>";
                $this->current_try++;


                sleep(2); // Optional: wait 2 seconds before retrying
                
                // $error_code = curl_errno($curl);
                // Check if the error was due to a timeout
                // if ($error_code == CURLE_OPERATION_TIMEDOUT) {
                //     echo "Attempt $this->current_try failed due to timeout. Retrying...\n";
                //     sleep(1); // Optional: wait 1 second before retrying
                // } else {
                //     echo "Attempt $this->current_try failed due to error: " . curl_error($curl) . "\n";
                //     break; // Exit the loop for non-timeout errors
                // }
            }
        } while (json_decode($response) === null && $this->current_try < $this->max_retries);
        curl_close($curl);
        $this->api_response = json_decode($response);
    }

    public function buildApiKeyString()
    {
        //require_once '../config/API_KEY.php';
        require __DIR__ . '/../config/API_KEY.php';
        $this->api_key_string = 'api_key=' . $api_key;
    }

    public function getResponse()
    {
        return $this->api_response;
    }

}