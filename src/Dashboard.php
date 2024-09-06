<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once '../src/API.php';
use Waterloobae\CrowdmarkDashboard\API;

class Dashboard{
    public function getData($name) {
        return array("status" => "success", "data" => "Hello, $name! This is data from the server!");
    }
    
    public static function generateCSRFToken() {
        if (empty($_SESSION['cmd']['csrf_token'])) {
            $_SESSION['cmd']['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['cmd']['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        return $token === $_SESSION['cmd']['csrf_token'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    
    if (!Dashboard::validateCSRFToken($csrfToken)) {
        $response = array("status" => "error", "message" => "Invalid CSRF token.");
    } else {
        $dashboard = new Dashboard();
        switch ($action) {
            case 'getData':
                $response = $dashboard->getData($name);
                break;
            default:
                $response = array("status" => "error", "message" => "Invalid action.");
                break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['csrf'])) {
    echo json_encode(array("csrf_token" => Dashboard::generateCSRFToken()));
}

