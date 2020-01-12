<?
class Controller_ways extends Controller{
	
	function __construct(){
		$this->model = new Model_Ways();
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
		$ways = $this->model->get_data();
		$data['ways'] = $ways;
		$this->view->generate('list_ways.php', $data);
	}
	
	function action_new(){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->add_way($data);
			header('Location: /dispatcher/ways.php');
		}
		$this->view->generate('way_form.php');
	}
	function action_edit($id){
		if($_POST['submit']){
			$data = $_POST;
			$this->model->update_way($id, $data);
			header('Location: /dispatcher/ways.php');
		}
		$ways = $this->model->get_by_id($id);
		if(count($ways)){
			$data['way'] = $ways[0];
			$this->view->generate('way_form.php', $data);
		}else{
			header('Location: /dispatcher/ways.php');
		}
	}
}
