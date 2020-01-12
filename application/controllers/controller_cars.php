<?
class Controller_cars extends Controller{
	
	function __construct(){
		$this->model = new Model_Cars();
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
		$cars = $this->model->get_data();
		$data['cars'] = $cars;
		$this->view->generate('list_cars.php', $data);
	}
	
	function action_new(){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->add_car($data);
			header('Location: /dispatcher/cars.php');
		}
		$ways = Model_Ways::get_data();
		$data['ways'] = $ways;
		$this->view->generate('car_form.php', $data);
	}
	
	function action_edit($id){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->update_car($id, $data);
			header('Location: /dispatcher/cars.php');
		}
		$cars = $this->model->get_by_id($id);
		if(count($cars)){
			$data['car'] = $cars[0];
			$ways = Model_Ways::get_data();
			$data['ways'] = $ways;
			$this->view->generate('car_form.php', $data);
		}else{
			header('Location: /dispatcher/cars.php');
		}
	}
}
