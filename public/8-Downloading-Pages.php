<?php
namespace Waterloobae\CrowdmarkDashboard;
require_once __DIR__ . '/../vendor/autoload.php';
use Waterloobae\CrowdmarkDashboard\Dashboard;

$dashboard = new Dashboard();

// ==============================
// This script list Booklets whose response Count is off.
// It hardly happens, but it is good to check.
// ==============================

$start_time = "Start Time:" . date("Y-m-d H:i:s") . "<br>";
echo($start_time);

//$dashboard->getCrowdmark()->createDownloadLinks('page', ['CIMC 2024 French','Course A', 'CSMC 2024 French'], '2');
$dashboard->getCrowdmark()->createDownloadLinks('page', ['2025 TerryB Test','Course A', 'CSMC 2024 French'], '2');

echo("End Time:" . date("Y-m-d H:i:s") . "<br>");
