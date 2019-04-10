<?php
	ob_start(); // chống lỗi can not modify header 
	include("includes/header.php"); 
 	include("includes/connect.php");
	include("includes/function_die.php"); 
 	include("includes/sidebar_a.php");
?>	
<div id="content">
    <?php
    	if($pid = validate_id($_GET['pid'])){
				  $set = get_page_by_id($pid); // lưu giá trị biến $result trong hàm get_page_by_id($pid) => biến $set
				  if(mysqli_num_rows($set) > 0){
					  // nếu có pót để hiển thị ra trình duyệt
					     $page = mysqli_fetch_array($set, MYSQLI_ASSOC);
						 echo " <div class='post'>
									<h2>{$page['page_name']}</h2>
									<p>{$page['content']}</p>
									<p class='meta'> <strong>Posted on:</strong> {$page['name']} | <strong> On:</strong> {$page['date']}</p>
							   	</div>
							";  	  
				  } else {
				     $message = "there are'nt any post in database";
		          }
		} else {
			redirect_to(); //nếu pid ko hợp lệ thì chuyển về trang index.php
		}
	?>
</div>
<?php 
	  require("includes/sidebar_b.php");
      require("includes/footer.php"); 
?>