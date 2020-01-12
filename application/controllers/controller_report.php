<?
class Controller_report extends Controller{
	
	function __construct(){
		$this->model = new Model_Report();
		$this->view = new View();
		$this->action_index();
	}
	
	function action_index(){
		$report = $this->model->get_data($_GET['period']);
		$data['report'] = $report;
		$data['period'] = $_GET['period'];
		$this->view->generate('report.php', $data);
	}
}
