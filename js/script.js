	function isInteger(num) {
		if(isNaN(parseInt(num))){
			return false;
		}else{
			return true;
		}
	}
	function isFloat(num) {
		if(isNaN(parseFloat(num))){
			return false;
		}else{
			return true;
		}
	}
	function validate_form(id){
		validated = true;
		$(id+" .required").each(function(){
			if(!$(this).val()){
				$(this).addClass("error_input");
			}else{	
				$(this).removeClass("error_input");
			}
		});
		$(id+" .numeric").each(function(){
			
			if($(this).val() && isInteger($(this).val())){
				$(this).removeClass("error_input");
			}else{
				$(this).addClass("error_input");
			}
		});
		$(id+" .float").each(function(){
			if($(this).val() && isFloat($(this).val())){
				$(this).removeClass("error_input");
			}else{
				$(this).addClass("error_input");
			}
		})
		if($(".error_input").size()){
			validated = false;
		}
		return validated;
	}
	$(document).ready(function(){
		$("#form").submit(function(){
			return validate_form("#form");
		});
		
		$("#form .required").change(function(){
			if($(".error_input").size()){
				validate_form("#form");
			}
		});
		$("#form .numeric").change(function(){
			if($(".error_input").size()){
				validate_form("#form");
			}
		});
		
		$("#form_pause").submit(function(){
			return validate_form("#form_pause");
		});
		
		$("#form_pause .required").change(function(){
			if($(".error_input").size()){
				validate_form("#form_pause");
			}
		});
		$("#form_pause .numeric").change(function(){
			if($(".error_input").size()){
				validate_form("#form_pause");
			}
		});
		
		$("#link_form_pause").click(function(){
			if($("#form_pause").is(':visible')){
				$("#form_pause").hide();
			}else{
				$("#form_pause").show();
			}
			return false;
		});
		$("#link_form_end").click(function(){
			if($("#form_end").is(':visible')){
				$("#form_end").hide();
			}else{
				$("#form_end").show();
			}
			return false;
		});
		$(".link_traffics").click(function(){
			var id = $(this).attr("id");
			if($("#table_"+id).is(":visible")){
				$("#table_"+id).hide();
			}else{
				$("#table_"+id).show();
			}
			return false;
		});
		$(".link_traffic_points").click(function(){
			var id = $(this).attr("id");
			if($("#table_"+id).is(":visible")){
				$("#table_"+id).hide();
			}else{
				$("#table_"+id).show();
			}
			return false;
		});
		
	});
