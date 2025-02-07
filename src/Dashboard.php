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
    private static $logDiv = '<div id="crowdmarkdashboard_logger" style="position: relative; background-color: #f1f1f1; border: 1px solid #d3d3d3; padding: 10px; z-index: 1000; overflow: auto; max-height: 200px; width: 80%;"></div>';    

    public function __construct(){
        // constructor
        $this->logger = new Logger();
        $this->crowdmark = new Crowdmark( $this->logger );
    }

    public function getLogDiv() {
        return self::$logDiv;
    }
    
    public function echoLoggerDiv() {
        echo $this->getLogDiv();
    }

    public function echoLoggerMessage() {
        $relativePath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
        echo "<script data-status='logger'>
            var logger = document.getElementById('crowdmarkdashboard_logger');
            if (!logger) {
                document.write('".$this->getLogDiv()."');
                var logger = document.getElementById('crowdmarkdashboard_logger');
            }

            function updateLoggerMessage() {
                fetch('".$relativePath."/LoggerMessage.php')
                    .then(response => response.json()) // Convert response to JSON
                    .then(data => {
                        let error = data.error_msg;
                        let warning = data.warning_msg;
                        let info = data.info_msg;

                        if (error !== 'NA') {
                            logger.innerHTML = '<div style=\"color: red;\">Error: ' + error + '</div>';
                        }
                        if (warning !== 'NA') {
                            logger.innerHTML = '<div style=\"color: orange;\">Warning: ' + warning + '</div>';
                        }
                        if (info !== 'NA') {
                            logger.innerHTML = '<div style=\"color: blue;\">Info: ' + info + '</div>';
                        }

                    })
                    .catch(error => {
                        console.error('Error fetching logger:', error);
                        logger.innerHTML = '<div style=\"color: red;\">Error fetching logger.</div>';
                    });
            }

            // Run `updateLoggerMessage` every second
            let updateLoggerMessageInterval = setInterval(updateLoggerMessage, 1000);
            </script>";
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
