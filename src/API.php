<?php

namespace CrowdmarkDashboard;

class API
{
    protected string $url = 'https://app.crowdmark.com/';
    protected string $api_string;
    protected object $response;

    public function __construct(string $end_point) 
    {
        // constructor
        $this->buildAPIString();
 
        $curl = curl_init();
         // Does end_point have a ? in it?
         if (strpos($end_point, '?') !== false) {
            curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '&' . $this->api_string);
        } else {
            curl_setopt($curl, CURLOPT_URL, $this->url . $end_point . '?' . $this->api_string);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 600);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        set_time_limit(300);
        $response = curl_exec($curl);
        curl_close($curl);
        $this->response = json_decode($response);
    }

    public function buildAPIString()
    {
        //require_once '../config/API_KEY.php';
        require __DIR__ . '/../config/API_KEY.php';
        $this->api_string = 'api_key=' . $api_key;
    }

    public function getResponse()
    {
        return $this->response;
    }

}