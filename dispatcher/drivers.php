<?
//phpinfo();
require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/header.php';

require_once $_SERVER["DOCUMENT_ROOT"].'/application/controllers/controller_drivers.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_drivers.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/application/models/model_cars.php';
$drivers = new Controller_drivers;

require_once $_SERVER["DOCUMENT_ROOT"].'/application/views/footer.php';

