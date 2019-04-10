<?php
ob_start();
require('includes/header.php');	
require('includes/connect.php');
require('includes/function_die.php');
require('includes/sidebar_a.php');
?>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<div id ='content'>
    <?php
		if($uid = validate_id($_GET['uid'])){ 
			$display = 4; // số record hiển thị trong 1 trang
		// phân trang cho author
		// tham số $_GET['s'] và $_GET['p'] dc xd từ đâu trên trình duyệt
			$start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
			
			// nếu $uid  tồn tại và hợp lê -> truy vấn csdl
			$q = " SELECT p.page_id, p.page_name, p.content, 
			       DATE_FORMAT(p.post_on,'%b %d Y') AS date, 
				   CONCAT_WS(' ',u.first_name, u.last_name) AS name, u.user_id
				   FROM page AS p
				   JOIN user AS u
				   USING(user_id)
				   WHERE u.user_id = {$uid}					   
				   ORDER BY date ASC LIMIT {$start}, {$display} ";
		    $r = mysqli_query($dbc,$q);		   
			confirm_query($r, $q);
			if(mysqli_num_rows($r) > 0){ // có dl về author -> hiển thị ra trình duyệt
				while($author = mysqli_fetch_array($r, MYSQLI_ASSOC)){
					echo "<div class='post'>
							 <h2><a href='single.php?pid={$author['page_id']}'>{$author['page_name']}</a></h2>	
							 <p>".paragraph(the_excerpt($author['content']))."... <a href='single.php?pid={$author['page_id']}'>Read more</a></p>	
							 <p class='meta'><strong>Post by: </strong> <a href='author2.php?uid={$author['user_id']}'>{$author['name']}</a>| <strong>Post on: </strong>{$author['date']}</p>
						 </div>";
				} // end while				  
				   // phân trang
			  	echo pagination2($uid, $display);  
			} else {
				// $uid ko tồn tại do bị xóa -> báo lỗi
			    echo "<p class='warning'>The author you are trying to view is no longer available</p>";
			}			
		} else { // nếu $uid ko hợp lệ -> điều hướng về trang index.php
			redirect_to();	
		}
	?>	
</div><!--end content-->   
<?php require('includes/sidebar_b.php');?>
<?php require('includes/footer.php');?>