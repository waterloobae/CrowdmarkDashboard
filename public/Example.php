<?php

namespace Crowdmark\Dashboard;
include_once '../src/API.php';
use Crowdmark\Dashboard\API;

class Example
{
    public function __construct()
    {
        $api = new API('courses');
        return $api;
    }
}

$example = new Example();
var_dump($example);
