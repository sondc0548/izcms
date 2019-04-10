<?php
ob_start();
require('includes/header.php');	
require('includes/connect.php');
require('includes/function_die.php');
require('includes/sidebar_a.php');
?>
<div id ='content'>
<?php
	if($uid = validate_id($_GET['uid'])){
		// phân trang cho author
		$display = 4; // số trang muốn hiển thị ra trình duyệt
		// xác định vị trí bắt đầu
		$start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ?  $_GET['s'] : 0 ;		
		// nếu $uid  tồn tại và hợp lệ -> truy vần CSDL
		$q = " SELECT p.page_name, p.page_id, p.content, 
			   DATE_FORMAT(p.post_on,'%b %d %Y') AS date, 
			   CONCAT_WS(' ',u.first_name, u.last_name) AS name, u.user_id 
			   FROM page AS p 
			   JOIN user AS u 
			   USING(user_id)
			   WHERE u.user_id = {$uid}
			   ORDER BY date ASC LIMIT {$start}, {$display} ";
		$r = mysqli_query($dbc,$q);	   
		confirm_query($r,$q);
		if(mysqli_num_rows($r)>0){ // có dl -> đổ ra trìn duyệt
			while($author = mysqli_fetch_array($r, MYSQLI_ASSOC)){
				echo "<div class='post'>
						<h2><a href='single.php?pid={$author['page_id']}'>{$author['page_name']}</a></h2>	
						<p>".paragraph(the_excerpt($author['content']))."...<a href='single.php?pid={$author['page_id']}'>Read more</a></p>
						<p class='meta'><strong>Post by: </strong><a href='author.php?uid={$author['user_id']}'>{$author['name']}</a> | <strong>Post on:      </strong>{$author['date']}</p>
					  </div>";
		    } // end while
			  // Phân trang  
			echo pagination($uid, $display); // chỗ này phải echo vì hàm trả lại giá trị của biến $output
		} else {
			// $uid ko tồn tại do bị xóa -> báo lỗi
			echo "<p class='warning'>The author you are trying to view is no longger available</p>";
		}			   
	}else{
		// nếu $uid truyền vào ko hợp lệ -> điều hường về trang index.php
		redirect_to();
	}
?>
</div>
<?php require('includes/sidebar_b.php');?>
<?php require('includes/footer.php');?>
