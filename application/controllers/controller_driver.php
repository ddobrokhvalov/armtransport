<?
class Controller_driver extends Controller{
	
	function __construct(){
		$this->model = new Model_Traffic();
		$this->view = new View();
		
		$this->action_control();
	}
	
	function action_control(){
		$traffic = $this->model->get_by_car_id($_SESSION['user']['car_id']);
		if($_POST['submit'] && $_POST['point_type'] == 'start'){
				$data = $_POST;
				$car = Model_Cars::get_by_id($_SESSION['user']['car_id']);
				$data['car_id'] = $car[0]['id'];
				$data['way_id'] = $car[0]['way_id'];
				unset($data['point_type']);
				$this->model->start_traffic($data);
				header('Location: /driver/');
		}
		if(is_array($traffic) && count($traffic)){
			$traffic = $traffic[0];
			if($_POST['submit'] && $_POST['point_type'] == 'pause'){
				$data = $_POST;
				$this->model->traffic_pause($traffic['id'], $data);
				header('Location: /driver/');
			}
			if($_POST['submit'] && $_POST['point_type'] == 'continue'){
				$data = $_POST;
				$this->model->traffic_continue($traffic['id'], $data);
				header('Location: /driver/');
			}
			if($_POST['submit'] && $_POST['point_type'] == 'end'){
				$data = $_POST;
				$this->model->traffic_end($traffic['id'], $data);
				header('Location: /driver/');
			}
			
			$data['traffic'] = $traffic;
			$traffic_points = $this->model->get_traffic_points($traffic['id']);
			$data['traffic_points'] = $traffic_points;
			$car = Model_Cars::get_by_id($traffic['car_id']);
			$way = Model_Ways::get_by_id($traffic['way_id']);
			$data['car'] = $car[0];
			$data['way'] = $way[0];
		}else{
			$car = Model_Cars::get_by_id($_SESSION['user']['car_id']);
			$way = Model_Ways::get_by_id($car[0]['way_id']);
			$data['car'] = $car[0];
			$data['way'] = $way[0];
			$traffic = false;
		}
				
		$this->view->generate('control_traffic_driver.php', $data);
	}
}
