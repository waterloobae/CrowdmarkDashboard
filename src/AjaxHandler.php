<?php
namespace Waterloobae\CrowdmarkDashboard;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} elseif (session_status() !== PHP_SESSION_ACTIVE) {
    session_destroy();
    session_start();
}

class AjaxHandler {
    private $actions;

    // handling closures
    //
    public function __construct() {
        $this->actions = [
            'sayHello' => function($params) {
                return json_encode("Hello, $params!");
                //return "Hello, " . $params;
                //return "Hello, ";
            },
            'addNumbers' => function($a, $b) {
                return $a + $b;
            },
            // Add more closures as needed
        ];
    }

    public function handleRequest($actionName, $params) {
        if (isset($this->actions[$actionName])) {
            return call_user_func_array($this->actions[$actionName], $params);
        } else {
            return "Invalid action!";
        }
    }

    public static function generateCSRFToken() {
        if (empty($_SESSION['crowdmark_dashboard']['csrf_token'])) {
            $_SESSION['crowdmark_dashboard']['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['crowdmark_dashboard']['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        return $token === $_SESSION['crowdmark_dashboard']['csrf_token'];
    }

}

$ajaxHandler = new AjaxHandler();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    
    if(!$ajaxHandler::validateCSRFToken($csrfToken)) {
        $response = array("status" => "error", "message" => "Invalid CSRF token.");
    } else {
        $params = isset($_POST['name']) ? $_POST['name'] : [];
        // $params = isset($_POST) ? $_POST : [];
        // $params = isset($_POST['params']) ? $_POST['params'] : [];
        $response = $ajaxHandler->handleRequest($action, [$params]);
        echo json_encode($response);
    }
    //header('Content-Type: application/json');
    //echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['csrf'])) {
    echo json_encode(array("csrf_token" => AjaxHandler::generateCSRFToken()));
}