<?php 
	// kết nói csdl
	$dbc = mysqli_connect("localhost","root","","izcms");
	// nếu kn ko thành công thì bao lỗi qua trình duyệt
	if(!$dbc){
		trigger_error("Could not connect DB: ".mysqli_connect_error());	
	}else{
		// đặt phương thức kết nối là utf8
		mysqli_set_charset($dbc,'utf-8');
	}
	

?>