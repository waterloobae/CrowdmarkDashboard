<?php
namespace Waterloobae\CrowdmarkDashboard;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} elseif (session_status() !== PHP_SESSION_ACTIVE) {
    session_destroy();
    session_start();
}
use Waterloobae\CrowdmarkDashboard\API;

class Dashboard{
    private object $logger;
    private object $crowdmark;
    private object $engine;
    private static $logDiv = "";
    private static $head = "";    
    private static $thisPath = "";
    private static $form = "";
    private string $api_key;
    private array  $actions = [
        "sayHello" => "Say Hello (Check API)",        
        "page_1" => "Download Cover Page",
        "page_2" => "Download Page 2",
        "studentinfo" => "Generate Student Info",
        "studentemaillist" => "Generate Student Email List",
        "grader" => "Generate Graders Grading List",
        "grading" => "Generate Grading Status",
        "uploadedmatched" => "Generate Uploaded Matched Counts",
        "integritycheck" => "Generate Integrity Check Report"
    ];
    
    public function __construct($api_key){
        $this->api_key = $api_key;
        // constructor
        $this->logger = new Logger();
        $this->engine = new Engine();
        //$this->setCrowdmark();
        self::$thisPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
        self::$logDiv = $this->engine->render('logger_div');
        self::$head = $this->engine->render('head', ['_PATH' => self::$thisPath]);
        $this->writeAPIKEY();
        $this->setForm();
    }

    public function writeAPIKEY(){
        $api_key = $this->api_key;
        $file_path = __DIR__."/../config/API_KEY.php";
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $api_key_file = fopen($file_path, "w") or die("Unable to open file!");
        $txt  = "<?php\n";
        $txt .= "namespace Waterloobae\CrowdmarkDashboard;\n";
        $txt
        .=  "\$api_key = '$api_key';\n";
        fwrite($api_key_file, $txt);
        fclose($api_key_file);
    }
    public function getForm(){
        return self::$form;
    }
    
    public function setForm(){
        $head = $this->insertHead();
        $chips = $this->getCourseFilterChipList();
        $actions = $this->generateActionSelect();
        $ajaxJs = $this->engine->render('ajax_js_csrf', ['_PATH' => self::$thisPath]);

        self::$form = $this->engine->render('form', ['_CHIPS' => $chips, '_ACTIONS' => $actions, '_AJAXJS' => $ajaxJs, '_HEAD' => $head]);
    }
    public function setCrowdmark(){
        $this->crowdmark = new Crowdmark( );
    }

    public function getLogDiv() {
        return self::$logDiv;
    }
    
    public function echoLoggerDiv() {
        echo $this->getLogDiv();
    }

    public function echoLoggerMessage() {
        echo $this->engine->render('logger_script', ['_LoggerDiv' => self::$logDiv, '_WebRootPath' => self::$thisPath]);
    }

    public function insertHead() {
        die($this->engine->render('head_script', ['_Head' => self::$head]));
        return $this->engine->render('head_script', ['_Head' => self::$head]);
    }

    public function getCrowdmark(){
        return $this->crowdmark;
    }

    // Validations
    // 1. API_KEY.php exists
    // 2. $api_key is set
    // 3. API returns 200 response

    public function getEngine(){
        return $this->engine;
    }

    public function getLogger(){
        return $this->logger;
    }

    public function getThisPath(){
        return self::$thisPath;
    }

    public function getCourseFilterChipList(){

        $courseNames = [];
        $api = new API( $this->logger );
        $api->exec('api/courses');
        $apiResponses= $api->getResponse();
        foreach ($apiResponses->data as $apiResponse){
            $courseNames[] = $apiResponse->attributes->name;
        }

        $chips = [];
        foreach($courseNames as $courseName){
            $chips[] = $this->engine->render('filter_chip', ['_LABEL' => $courseName]);
        }
        return implode(" ",$chips);
    }   

    public function generateActionSelect(){
        $options = [];
        $options[] = "<select id='action' name='action'>";
        foreach($this->actions as $key => $value){
            $options[] = "<option value='$key'>$value</option>";
        }
        $options[] = "</select>";
        return implode(" ",$options);
    }   

}
