<?php 
	  require("includes/header.php"); 
	  require("includes/connect.php");
	  require("includes/function_die.php");  	 
 	  require("includes/sidebar_a.php"); 
?>
	<div id="content">
    <?php
		// nhận biến $_GET['cid'] truyền xang từ trang sidebar-a.php 
		// kiểm tra xem có tồn tại $_GET['cid'] hay ko và kiểm tra kiểu dữ liệu của nó
    	if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range'=>1))){ //$cid = validate_id($_GET['cid'])	
			// nếu $cid tồn tại và hợp lệ -> truy vấn csdl
			  $set = get_page_by_cid($_GET['cid']);
			  if(mysqli_num_rows($set) > 0){
				  // nếu có category để hiển thị ra trình duyệt
				  while($page = mysqli_fetch_array($set, MYSQLI_ASSOC)){
					 echo " <div class='post'>
								<h2><a href='single.php?pid={$page['page_id']}'>{$page['page_name']}</a></h2>
								<p>".paragraph(the_excerpt($page['content']))." ... <a href='single.php?pid={$page['page_id']}'>Read more</a></p>
								<p class='meta'><strong>Posted on: </strong><a href='author2.php?uid={$page['user_id']}'>{$page['name']}</a> | <strong> On:</strong> {$page['date']}</p>
							</div> ";  
				  }// end while loop
			  } else { // nếu  ko tồn tai $cid -> báo lỗi
				 $message = "there are'nt any post in database";
			  }
		} else if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range'=>1))){ //$pid = validate_id($_GET['pid']) ,nhận biến $_GET['pid'] truyền xang từ trang sidebar_a.php 
			$set = get_page_by_pid($_GET['pid']);
			if(mysqli_num_rows($set) > 0){
				  // nếu có category để hiển thị ra trình duyệt
				  while($page = mysqli_fetch_array($set, MYSQLI_ASSOC)){
					 echo " <div class='post'>
								<h2><a href='single.php?pid={$page['page_id']}'>{$page['page_name']}</a></h2>
								<p class='comments'><a href='single.php?pid={$pid}#disscuss'>{$page['count']}</a></p>
								<p>".paragraph(the_excerpt($page['content']))." ... <a href='single.php?pid={$page['page_id']}'>Read more</a></p>
								<p class='meta'><strong>Posted on: </strong><a href='author2.php?uid={$page['user_id']}'>{$page['name']}</a> | <strong> On:</strong> {$page['date']}</p>
							</div> ";  
				  }// end while loop
			  } else { // nếu  ko có kq hoặc pid tồn tại ko hợp lệ(do bị xóa)
				 $message = " <p class='waning'> The article you are viewing is not available </p>";
			  }			
		} else {  // nếu ko tồn tại $cid hay $pid mới hiển thị nội dung bên dưới
	?>
		<h2>Wellcome to izcms</h2>
		<div>
        	<p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
            <p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
            <p>
            	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
        </div>
        <?php } ?>
	</div><!--end content-->
<?php require("includes/sidebar_b.php"); ?>    
<?php require("includes/footer.php"); ?>