<?
//phpinfo();
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header.php';

require_once $_SERVER["DOCUMENT_ROOT"].'/application/controllers/controller_cars.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_cars.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_ways.php';
$ways = new Controller_cars;

require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/footer.php';
