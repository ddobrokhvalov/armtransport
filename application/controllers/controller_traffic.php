<?
class Controller_traffic extends Controller{
	
	function __construct(){
		$this->model = new Model_Traffic();
		$this->view = new View();
		if(!isset($_GET['id']) && !isset($_GET['action'])){
			$this->action_index();
		}elseif(isset($_GET['action']) && $_GET['action'] == 'new'){
			$this->action_new();
		}elseif(isset($_GET['action']) && $_GET['action'] == 'control' && $_GET['id']){
			$this->action_control($_GET['id']);
		}
	}
	
	function action_index(){
		$traffics = $this->model->get_data();
		$data['traffics'] = $traffics;
		$this->view->generate('list_traffics.php', $data);
	}
	
	function action_new(){
		if($_POST['submit']){
			$data = $_POST;
			$car = Model_Cars::get_by_id($data['car_id']);
			$data['way_id'] = $car[0]['way_id'];
			$this->model->start_traffic($data);
			header('Location: /dispatcher/traffic.php');
		}
		$cars_ways = $this->model->get_free_cars_ways();
		$data['cars_ways'] = $cars_ways;
		$this->view->generate('new_traffic.php', $data);
	}
	
	function action_control($id){
		$traffic = $this->model->get_by_id($id);
		if(count($traffic)){
			if($_POST['submit'] && $_POST['point_type'] == 'pause'){
				$data = $_POST;
				$this->model->traffic_pause($id, $data);
				header('Location: /dispatcher/traffic.php?action=control&id='.$id);
			}
			if($_POST['submit'] && $_POST['point_type'] == 'end'){
				$data = $_POST;
				$this->model->traffic_end($id, $data);
				header('Location: /dispatcher/traffic.php');
			}
			if($_POST['submit'] && $_POST['point_type'] == 'continue'){
				$data = $_POST;
				$this->model->traffic_continue($id, $data);
				header('Location: /dispatcher/traffic.php?action=control&id='.$id);
			}
			$traffic = $traffic[0];
			$car = Model_Cars::get_by_id($traffic['car_id']);
			$way = Model_Ways::get_by_id($traffic['way_id']);
			$traffic_points = $this->model->get_traffic_points($traffic['id']);
			$data['traffic'] = $traffic;
			$data['car'] = $car[0];
			$data['way'] = $way[0];
			$data['traffic_points'] = $traffic_points;
			$this->view->generate('control_traffic.php', $data);
		}else{
			header('Location: /dispatcher/traffic.php');
		}
	}
}
