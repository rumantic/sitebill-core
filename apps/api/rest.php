<?php
require_once __DIR__.'/../system/bootstrap.php';

use api\classes\API_Controller;

/**
 * REST API
 * @author Kondin Dmitriy <kondin@etown.ru> http://www.sitebill.ru
 */
error_reporting(E_ERROR | E_WARNING);
ini_set('display_errors', 'On');
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
//header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
//cors();
//header('Access-Control-Allow-Origin: *');

$api_controller = new API_Controller();
$api_controller->main();
