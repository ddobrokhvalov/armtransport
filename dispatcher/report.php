<?
//phpinfo();
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header.php';

require_once $_SERVER["DOCUMENT_ROOT"].'/application/controllers/controller_report.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_report.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_traffic.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_drivers.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_cars.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_ways.php';
$traffic = new Controller_report;

require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/footer.php';
