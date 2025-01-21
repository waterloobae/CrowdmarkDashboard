<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Waterloobae\CrowdmarkDashboard\API;

class Dashboard{
    private object $logger;
    private object $crowdmark;

    public function __construct(){
        // constructor
        $this->logger = new Logger();
        $this->crowdmark = new Crowdmark();
    }

    public function getData($name) {
        return array("status" => "success", "data" => "Hello, $name! This is data from the server!");
    }
}



