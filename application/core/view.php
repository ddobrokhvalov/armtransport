<?
class View{
	function generate($content_view, $data=array()){
		include $_SERVER["DOCUMENT_ROOT"].'/application/views/'.$content_view;
	}
}
