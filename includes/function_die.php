<?php 
// khai bao hằng số BASE_URL để sau này đưa lên host sẽ dễ hơn
	define('BASE_URL', 'http://localhost/izcms/'); // xác định hằng số cho địa chỉ tuyệt đối	
	function redirect_to($page = 'index.php'){
		$url = BASE_URL.$page;
		header("location:$url");
		exit();
	}
	// kiểm tra xem kết quả trả về có đúng hay ko
 	function confirm_query($result, $query){
		global $dbc;
		if(!$result){
 			die("Query {$q} \n <br/> Mysql Error: " .mysqli_error($dbc));
 		}
 	}
	// dùng cho chức năng read more
	function the_excerpt($text){
		$sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8'); // chống crossside script
		// đếm số  kí tự trong biến $text để trả về theo ý muốn
		if(strlen($sanitized) > 400){
			$cutString	= substr($sanitized, 0 ,400);//cắt kí tự, cắt từ kí tự 0 đến kí tự 400 gắn vào biến $cutString làm mốc
			$word = substr($sanitized, 0, strpos($cutString, ' ')); // cắt 400 chữ từ vị trí  0 -> $cutString
			return $word;
		}else{
			return $sanitized;
		}
		// áp dụng cho link read more
	}
	//tạo paragraph từ CSDL dùng để xuống dòng trong văn bản của trang tin khi đổ từ CSDL ra web
	function paragraph($text){
		$sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8'); // chống crossside script
		return str_replace(array("\r\n", "\n"),array("<p>", "</p>"),$sanitized);
	}
	//
	function validate_id($id){
		if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' => 1))){
				$val_id = $id;
				return $val_id;
		} else {
			return NULL;
		}		
	}// end validate_id
	// giúp code gọn hơn dùng trong trang single.php
	function get_page_by_id($id){
		  global $dbc;	
		  $q = "SELECT p.page_name, p.page_id, p.content, ";
		  $q .= " DATE_FORMAT(p.post_on, '%b %d %y') AS date, ";
		  $q .= " CONCAT_WS(' ', u.first_name,u.last_name) AS name, u.user_id ";
		  $q .= " FROM page AS p ";
		  $q .= " INNER JOIN user AS u ";
		  $q .= " USING(user_id) ";
		  $q .= " WHERE p.page_id = {$id} ";
		  $q .= " ORDER BY date ASC LIMIT 1";
		  $result = mysqli_query($dbc,$q);
		  confirm_query($result,$q);	
		  return $result;
	}
	// giúp code gọn hơn dùng trong trang index.php
	function get_page_by_cid($id){
		  global $dbc;	
		  $q = "SELECT p.page_name, p.page_id, p.content, ";
		  $q .= " DATE_FORMAT(p.post_on, '%b %d %y') AS date, ";
		  $q .= " CONCAT_WS(' ', u.first_name,u.last_name) AS name, u.user_id ";
		  $q .= " FROM page AS p ";
		  $q .= " INNER JOIN user AS u ";
		  $q .= " USING(user_id) ";
		  $q .= " WHERE p.cat_id = {$id} ";
		  $q .= " ORDER BY date ASC ";
		  $result = mysqli_query($dbc,$q);
		  confirm_query($result,$q);	
		  return $result;
	}
	// giúp code gọn hơn dùng trong trang index.php
	// lấy dl từ 3 bảng user, page, comments -> hiển thị tất cả các page, thuộc category mà người dùng chọn ra trình duyệt
	function get_page_by_pid($id){
		  global $dbc;	
		  $q = "SELECT p.page_name, p.page_id, p.content, ";
		  $q .= " DATE_FORMAT(p.post_on, '%b %d %y') AS date, ";
		  $q .= " CONCAT_WS(' ', u.first_name,u.last_name) AS name, u.user_id, ";
		  $q .= " COUNT(c.comment_id) AS count";
		  $q .= " FROM page AS p ";
		  $q .= " INNER JOIN user AS u ";
		  $q .= " USING(user_id) ";
		  $q .= " LEFT JOIN comments AS c ";
		  $q .= " ON p.page_id = c.page_id";
		  $q .= " WHERE p.page_id = {$id} ";
		  $q .= " GROUP BY p.page_name ";
		  $q .= " ORDER BY date ASC ";
		  $result = mysqli_query($dbc,$q);
		  confirm_query($result,$q);	
		  return $result;
	}	
	// hàm capstcha dùng trong trang comment_form đê chống spam
	function captcha(){
		$qna = array( 1 => array('question' => 'một cộng  một', 'answer' => 2),
					  2 => array('question' => 'ba nhân hai', 'answer' => 6 ),
					  3 => array('question' => '3 nhân 3', 'answer' => 9 ),
					  4 => array('question' => 'Xe đạp có ... bánh', 'answer' => 2 ),
         		5 => array('question' => 'Oto có ... bánh', 'answer' => 4 ),
					  6 => array('question' => 'Lương sơn bạc có ... anh hùng', 'answer' => 108 ),
					  7 => array('question' => 'Bạch Tuyết và ... chú lùn', 'answer' => 7 ),
					  8 => array('question' => 'con chó có mấy chân', 'answer' => 4)
					 );
		$rand_key = array_rand($qna);
		$_SESSION['q'] = $qna[$rand_key];
		return $question = $qna[$rand_key]['question'];
   }
   // hàm phân trang
   /*function pagination($uid, $display = 4){
	   global $dbc; 
	   global $start;
		if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))){
			$page = $_GET['p'];
		} else {
			// nếu ko có biến p sẽ truy xuất csdl để tìm xem có bao nhiêu page dể hiển thị
			$q = " SELECT COUNT(page_id) FROM page";
			$r = mysqli_query($dbc,$q);
			confirm_query($r,$q);
			list($record) = mysqli_fetch_array($r, MYSQLI_NUM); // tính tổng số record có trong csdl <=> $A =  mysqli_num_rows($query);
			  // tìm tổng số trang = cách chia số dl cho display 
			if($record > $display){ 							// nếu tổng số dl trả về > sô dl hiển thị trên 1 trang
					$page = ceil($record/$display); 			// tổng số trang
			  } else {
					$page = 1;	 								// trang đầu tiên
			  }
		}// END  IF		
		    //  Phân trang 
		   $output = "<ul class='pagination'>"; // thay echo = $output -> dễ sử dụng và tái sử dụng sau này hơn
				if($page > 1){ // nếu co nhiều hơn 1 trang -> tiến hành phân trang
					$current_page = ($start/$display) + 1; //-> tìm trang hiện hành
					// nếu ko phải ở trang đầu tiên -> xuất hiện nút Prev
					if($current_page != 1){
						$output .= "<li><a href='author.php?uid={$uid}&s=0&p={$page}'>First</a></li>";   // -> nút First
						$Y = $start - $display ; // gán kết quả = $Y ->đỡ rối
						$output .= "<li><a href='author.php?uid={$uid}&s={$Y}&p={$page}'>Prev</a></li>"; // -> nút Prev
					}
					// phân đoạn
					$begin = $current_page - 2;
					if($begin < 1){ // xử lí khi so trang < 1 -> ko có
						$begin = 1;
					}			
					$end = $current_page + 2;
					if($end > $page){ // xử lí khi so trang > tổng số trang -> ko có
						$end = $page; 
					}
					// hiển thị những trang còn lại
					for($i=$begin; $i <= $end; $i++ ){
						if($current_page != $i){ // nếu  ko ở trang hiện hành
							$Y = $display*($i - 1) ;   
							$output .= "<li><a href='author.php?uid={$uid}&s={$Y}&p={$page}'>{$i}</a></li>";
						} else { // nếu ở trang hiện hành
							$output .= "<li class='current'>$i</li>";
						}
					}// end for loop
					// nếu ko phải ở trang cuôi -> nút Next
					if($current_page != $page){
						$Y = $start + $display; 
						$output .= "<li><a href='author.php?uid={$uid}&s={$Y}&p={$page}'>Next</a></li>";	  // -> nút Next
						$Y = $display*($page-1);
						$output .= "<li><a href='author.php?uid={$uid}&s={$Y}&p={$page}'>Last</a></li>";   // -> nút Last
					}
				}		
			$output .= "</ul>";
			return $output ;
   } */    /* end pagination */
   function pagination2($uid, $display = 4){
	   global $dbc;
	   global $start;
	   // tìm $page 
	   if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' =>1))){
				$page = $_GET['p'];		
	   } else {
			// nếu ko có biến p sẽ truy vấn csdl để tìm xem có bao nhiêu page để hiển thị	
			$q = " SELECT COUNT(page_id) FROM page";
			$r = mysqli_query($dbc,$q);
			confirm_query($r,$q);
			list($record) = mysqli_fetch_array($r, MYSQLI_NUM);
			if($record > $display){
				$page = ceil($record/$display);
			} else {
				$page = 1;
			}
		}
		 // phân trang
		$output = "<ul class='pagination'>";
			if($page > 1){ // nếu tổng số  trang > 1 -> tiến hành phân trang
				$current_page = ($start/$display) + 1;// +1 bù cho trường hợp ban đầu khi $start = 0
				if($current_page != 1){ // nếu ko ở trang đầu -> hiển thị nút Prev
					$output .= "<a href='author2.php?uid={$uid}&s=0&p={$page}' class='link'>First</a>"; // nút First xuất hiện khi s = 0
					$Y = $start - $display ; // $Y cũng là trang hiện hành
					$output .=  "<a href='author2.php?uid={$uid}&s={$Y}&p={$page}' class='link'>Prev</a>"; //-> Nút Prev
				}			
				// phân đoạn
					$begin = $current_page - 2;
					if($begin < 1){ // xử lí khi so trang < 1 -> ko có
						$begin = 1;
					}			
					$end = $current_page + 2;
					if($end > $page){ // xử lí khi so trang > tổng số trang -> ko có
						$end = $page; 
					}
				// end  phân đoạn	
				// tìm số còn lại trong trang
				for($i=begin; $i <= $end; $i++){
					if($current_page == $i){
						$output .=  "<li class='current'>$i</li>";
					} else {
						$Y = $display*($i-1);
						$output .=  "<li><a href='author2.php?uid={$uid}&s={$Y}&p={$page}' class='link'>$i</a></li>";	
					}
				} // end for loop
				if($current_page != $page){ // nếu ko ở trang cuối hiện nút Next
					$Y = $start + $display;
					$output .=  "<li><a href='author2.php?uid={$uid}&s={$Y}&p={$page}' class='link'>Next</a></li>"; // nút Next
					$Y = $display*($page-1); // nut Last
					$output .= "<li><a href='author2.php?uid={$uid}&s={$Y}&p={$page}' class='link'>Last</a></li>";
				}					
			}
		$output .=  "</ul>";
		return $output;
    }// end function pagination
   
   
   
?>