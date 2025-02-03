<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} elseif (session_status() !== PHP_SESSION_ACTIVE) {
    session_destroy();
    session_start();
}

// use Waterloobae\CrowdmarkDashboard\API;

class Dashboard{
    private object $logger;
    private object $crowdmark;

    public function __construct(){
        // constructor
        $this->logger = new Logger();
        $this->crowdmark = new Crowdmark( $this->logger );
    }

    public function getCrowdmark(){
        return $this->crowdmark;
    }

    // Validations
    // 1. API_KEY.php exists
    // 2. $api_key is set
    // 3. API returns 200 response


    public function getLogger(){
        return $this->logger;
    }

    public function getData($name) {
        return array("status" => "success", "data" => "Hello, $name! This is data from the server!");
    }
}
