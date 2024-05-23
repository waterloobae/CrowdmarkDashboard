<?php

namespace CrowdmarkDashboard;

use Exception;

class API
{
    protected string $url = 'https://app.crowdmark.com/';
    protected string $api_key_string;

    // $this->exec uses and returns
    protected object $api_response;
    // $this->multExec uses and returns
    protected array $api_responses;

    protected int $max_retries = 5;
    protected int $current_try = 0;

    public function __construct() 
    {
        // constructor
        $this->buildApiKeyString();
    }

    public function buildApiKeyString()
    {
        //require_once '../config/API_KEY.php';
        require __DIR__ . '/../config/API_KEY.php';
        $this->api_key_string = 'api_key=' . $api_key;
    }


    public function exec(string $end_point){

        $curl = curl_init();
        // Does end_point have a ? in it?
        if (strpos($end_point, '?') !== false) {
           curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '&' . $this->api_key_string);
        } else {
           curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '?' . $this->api_key_string);
        }

       //  
       //echo("Run Time:" . date("Y-m-d H:i:s") . "<br>");
       //echo "URL: " . $this->url . $end_point . '?' . $this->api_key_string . "<br>";


       curl_setopt($curl, CURLOPT_TIMEOUT, 6000);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       
       set_time_limit(300);
       //$response = curl_exec($curl);
       do {
           $response = curl_exec($curl);
       
           if (json_decode($response) === null) {
               $this->current_try++;
                $this->consoleLog("Attempt $this->current_try failed. Retrying...");

               sleep(2); // Optional: wait 2 seconds before retrying
           }
       } while (json_decode($response) === null && $this->current_try < $this->max_retries);
       curl_close($curl);

        try {
            if(json_decode($response) !== null){
                $this->api_response = json_decode($response);
            }else{
                throw new Exception("API call returned non JSON response.");
            }
        }
        catch(Exception $msg){
                $this->consoleLog("Exception: ". $msg);
        }
    }

    public function multiExec(array $end_points){
        ini_set('memory_limit', '1024M');
        // Initialize the multi cURL handler
        $mh = curl_multi_init();

        // Array to hold individual cURL handles
        $curlHandles = [];

        // Loop through each URL and create a cURL handle for it
        foreach ($end_points as $i => $end_point) {
            $ch = curl_init();
            if (strpos($end_point, '?') !== false) {
                curl_setopt($ch, CURLOPT_URL, $this->url . $end_point . '&' . $this->api_key_string);
             } else {
                curl_setopt($ch, CURLOPT_URL, $this->url . $end_point . '?' . $this->api_key_string);
             }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Add the handle to the multi cURL handler
            curl_multi_add_handle($mh, $ch);

            // Save the handle for later reference
            $curlHandles[$i] = $ch;
        }

        // Execute the multi cURL handles
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        // Collect the results
        foreach ($curlHandles as $i => $ch) {
            // Get the response content
            $response = curl_multi_getcontent($ch);
            if(json_decode($response) !== null){
                $this->api_responses[$i] = json_decode($response);
            }
            // Remove the handle from the multi cURL handler
            curl_multi_remove_handle($mh, $ch);

            // Close the individual cURL handle
            curl_close($ch);
        }

        // Close the multi cURL handler
        curl_multi_close($mh);
    }
    

    public function getResponse()
    {
        return $this->api_response;
    }
    public function getResponses()
    {
        return $this->api_responses;
    }

    public function consoleLog($msg){
        $output = $msg;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Error message(s): " . $output . "' );</script>";
    }

}