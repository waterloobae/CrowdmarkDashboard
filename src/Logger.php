<?php

class Logger {
    private $logs = [];

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
}
