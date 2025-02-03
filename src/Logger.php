<?php
namespace Waterloobae\CrowdmarkDashboard;

class Logger {
    private array $logs = [];
    private string $error_msg = '';
    private string $warning_msg = '';
    private string $info_msg = '';
    private static $logDiv = '<div id="crowdmarkdashboard_logger" style="position: relative; background-color: #f1f1f1; border: 1px solid #d3d3d3; padding: 10px; z-index: 1000; overflow: auto; max-height: 200px; width: 80%;"></div>';

    public function __construct() {
        // constructor
    }

    public function log($message, $type, $className) {
        $this->logs[] = [
            'message' => $message,
            'type' => $type,
            'class' => $className
        ];
    }

    public function getLogs() {
        return $this->logs;
    }

    public function displayLogs() {
        foreach ($this->logs as $log) {
            echo "Class: " . $log['class'] . "<br>";
            echo "Type: " . $log['type'] . "<br>";
            echo "Message: " . $log['message'] . "<br><br>";
        }
    }

    public function error($message, $className) {
        $this->error_msg = $message;
        $this->log($message, 'error', $className);
    }

    public function warning($message, $className) {
        $this->warning_msg = $message;
        $this->log($message, 'warning', $className);
    }

    public function info($message, $className) {
        $this->info_msg = $message;
        $this->log($message, 'info', $className);
    }

    public function getError() {
        return $this->error_msg;
    }

    public function getWarning() {
        return $this->warning_msg;
    }

    public function getInfo() {
        return $this->info_msg;
    }

    public function getLogDiv() {
        return self::$logDiv;
    }

    public function setError($message) {
        $this->error_msg = $message;
    }

    public function setWarning($message) {
        $this->warning_msg = $message;
    }

    public function setInfo($message) {
        $this->info_msg = $message;
    }

    public function clearLogs() {
        $this->logs = [];
    }

    public function clearError() {
        $this->error_msg = '';
    }

    public function clearWarning() {
        $this->warning_msg = '';
    }

    public function clearInfo() {
        $this->info_msg = '';
    }

    public function echoLoggerDiv(){
        if($this->getError() != '' || $this->getWarning() != '' || $this->getInfo() != ''){
            echo $this->getLogDiv();
        }
    }

    public function echoJavaScript($color, $message) {
        echo "<script data-status='logger'>
            document.querySelectorAll(\"script[data-status]\").forEach(el => el.remove());
            var logger = document.getElementById('crowdmarkdashboard_logger');
            if (!logger) {
                document.write('".$this->getLogDiv()."');
                var logger = document.getElementById('crowdmarkdashboard_logger');
            }

            logger.innerHTML = '<div style=\"color: ".$color.";\">". addslashes($message) . "</div>';
            </script>";
    }

    public function echoMessage($type, $message) {


        switch
        ($type) {
            case 'error':
                $this->setError($message);
                $this->echoJavaScript('red', ucfirst($type).": ".$message);
                $this->clearError();
                flush();
                die();
                break;
            case 'warning':
                $this->setWarning($message);
                $this->echoJavaScript('orange', ucfirst($type).": ".$message);
                $this->clearWarning();
                flush();
                break;
            case 'info':
                $this->setInfo($message);
                $this->echoJavaScript('blue', ucfirst($type).": ".$message);
                $this->clearInfo();
                flush();
                break;
            default:
                $this->echoJavaScript('lightgrey', $message);
                flush();
                break;
        }
    }
}
