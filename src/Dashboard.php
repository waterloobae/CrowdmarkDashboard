<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Waterloobae\CrowdmarkDashboard\API;

class Dashboard{
    public function getData($name) {
        return array("status" => "success", "data" => "Hello, $name! This is data from the server!");
    }
}



