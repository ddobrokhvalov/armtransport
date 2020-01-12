<?
class Model_Issues extends Model{
	
	public function get_data($sortby = "id", $ord = "asc", $count_per_page = 3, $page_num=1){
		$sortby = htmlspecialchars($sortby);
		$ord = htmlspecialchars($ord);
		$count_issues = db::sql_select("select count(*) as issues_count from issues");
		$page_count = ceil($count_issues[0]["issues_count"] / $count_per_page);
		$issues = db::sql_select("select * from issues order by ".$sortby." ".$ord." limit ".($page_num-1)*$count_per_page.", ".$count_per_page);
		$ret = array("issues"=>$issues, "page_count"=>$page_count);
		return $ret;
	}
	
	public function get_by_id($id){
		$issues = db::sql_select("select * from issues where id = :id", array("id"=>$id));
		return $issues;
	}
	
	public function add_issue($uploaded, $text){
		$user = Model_Users::get_by_id($_POST["user_id"]);
		$username = $user[0]["username"];
		$email = $user[0]["email"];
		db::insert_record("issues", array("username"=>$username, "email"=>$email, "text"=>$text, "image"=>$uploaded, "status"=>0));
	}
	
	public function update_issue($id, $text, $completed){
		db::update_record("issues", array("text"=>$text, "status"=>$completed), array(), array("id"=>$id));
	}

}
