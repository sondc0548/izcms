<?php 
	ob_start(); // chống lỗi can not modify header 
	  require("includes/connect.php");
	  require("includes/function_die.php"); 
	  if($pid = validate_id($_GET['pid'])){				
				  // nếu tồn tại $pid thì truy xuất csdl
				  $set = get_page_by_id($pid); // lưu giá trị biến $result trong hàm get_page_by_id($pid) => biến $set
				  $posts = array(); // tạo 1 array trống để lưu giá trị vào sử dụng sau này cho phần nội dung
				  if(mysqli_num_rows($set) > 0){
					  // nếu có pót để hiển thị ra trình duyệt
					  $page = mysqli_fetch_array($set, MYSQLI_ASSOC);
					  $title = $page['page_name']; // gán giá trị của tên trang vào biến $title => gọi ra trong trang header
					  $posts[] = array(
								 	'page_name' => $page['page_name'],
									'content' => $page['content'],
									'author' => $page['name'],
									'post-on' => $page['date'],
									'aid' => $page['user_id']
								); 
				  } else {
				     $message = "there are'nt any post in database";
		          }
		} else{
			redirect_to(); //nếu pid  ko hợp lệ thì đưa về trang index.php	
		}
 	  require("includes/header.php"); 
 	  require("includes/sidebar_a.php"); 
?>
<div id="content">
    <?php    	
		foreach($posts as $post){
		 echo "<div class='post'>
					<h2>{$post['page_name']}</h2>
					<p>".paragraph($post['content'])."</p> 
					<p class='meta'> <strong>Posted by:</strong> <a href='author2.php?uid={$post['aid']}'> {$post['author']} </a> | <strong> On:</strong> {$post['post-on']}</p>
				</div> ";  
				// hàm the_content($text) để xuống dòng trong văn bản
		}		
    	require("includes/comment_form.php");
	?>
</div><!--End content-->
<?php require("includes/sidebar_b.php"); ?>    
<?php require("includes/footer.php"); ?>