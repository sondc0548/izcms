<div id="content-container">
    <div id="section-navigation">
        <h2>Site Navigation</h2>
	   <ul class="navi">
       		<?php 
			// xác định cat_id có được chọn ko để tô đậm link
			if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' => 1))){
				$cid = $_GET['cid'];
				$pid = NULL; // chỉ ấn vào $cid
			}else if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' => 1))){
				$pid = $_GET['pid'];
				$cid = NULL; // chỉ ấn vào $cid 
			}else{				
				// trướng hợp  lần đầu đăng nhập vào trang index sẽ ko có $cid va $pid => $cid = null	
				$cid = NULL; 	
				$pid = NULL;
			}			
			// truy xuất categories
				$q = "select cat_name, cat_id from categories ORDER BY position ASC";
				$r = mysqli_query($dbc,$q);
				confirm_query($r,$q);
				// lấy category từ CSDL
				while($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)){
					  echo "<li><a href='index.php?cid=$cats[cat_id]'";
					  		if($cats['cat_id'] == $cid) echo "class = 'selected' ";
					  echo ">".$cats['cat_name']."</a>";
					   // truy xuất pages
						$q1 = "select page_name, page_id from page where cat_id = {$cats['cat_id']} order by position ASC";
						$r1 = mysqli_query($dbc, $q1);
						confirm_query($r,$q);						
						echo "<ul class='pages'>";
						// lay pages từ csdl
						while($pages = mysqli_fetch_array($r1 ,MYSQLI_ASSOC)){
							echo "<li><a href='index.php?pid={$pages['page_id']}'";
								if($pages['page_id'] == $pid) echo "class ='selected'";
							echo ">".$pages['page_name']."</a></li>";
						}
						echo "</ul>";
					echo "</li>";
				}// end while $cats
			?>
       </ul>
</div><!--end section-navigation-->