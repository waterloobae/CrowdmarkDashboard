<?php

namespace Crowdmark\Dashboard;

class API
{
    protected string $url = 'https://app.crowdmark.com/api/';
    protected string $api_string;

    public function __construct(string $end_point) 
    {
        // constructor
        $this->buildAPIString();
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url . $end_point .$this->api_string);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function buildAPIString()
    {
        require_once '../config/API_KEY.php';
        $this->api_string = '?api_key=' . $api_key;
    }

}