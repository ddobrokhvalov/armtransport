<?
class Controller_drivers extends Controller{
	
	function __construct(){
		$this->model = new Model_Drivers();
		$this->view = new View();
		if(!$_GET['action'] && !$_GET['id']){
			$this->action_index();
		}elseif($_GET['action'] == 'new'){
			$this->action_new();
		}elseif($_GET['action'] == 'edit' && $_GET['id']){
			$this->action_edit($_GET['id']);
		}else{
			header('Location: /dispatcher/');
		}
	}
	
	function action_index(){
		$drivers = $this->model->get_data();
		$data['drivers'] = $drivers;
		$this->view->generate('list_drivers.php', $data);
	}
	
	function action_new(){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->add_car($data);
			header('Location: /dispatcher/drivers.php');
		}
		$cars = Model_Cars::get_data();
		$data['cars'] = $cars;
		$this->view->generate('driver_form.php', $data);
	}
	
	function action_edit($id){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->update_car($id, $data);
			header('Location: /dispatcher/drivers.php');
		}
		$drivers = $this->model->get_by_id($id);
		if(count($drivers)){
			$data['driver'] = $drivers[0];
			$cars = Model_Cars::get_data();
			$data['cars'] = $cars;
			$this->view->generate('driver_form.php', $data);
		}else{
			header('Location: /dispatcher/drivers.php');
		}
	}
}
