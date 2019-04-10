<?php require("../includes/function_die.php"); ?>
<?php require("../includes/connect.php"); ?>
<?php require("../includes/header.php"); ?>
<?php require("../includes/sidebar_admin.php"); ?>
	  <div id="content"> 	
      		<h2>Manage Pages</h2>
            <table>
            	<thead>
                	<tr>
                    	<th>STT</th>	
                    	<th><a href="view_pages.php?sort=page">Pages</a></th>
                        <th><a href="view_pages.php?sort=on">Posted on</a></th>
                        <th><a href="view_pages.php?sort=by">Post by</a></th>
                        <th>Content</th>
                        <th><a href="">Edit</a></th>
                        <th><a href="">Delete</a></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
					//săp xếp theo thứ tự table head
					if(isset($_GET['sort'])){
						switch ($_GET['sort']){
							case 'page':
								$order_by = "page_name";
							break;
							case 'on':
								$order_by = "date";
							break;
							case 'by':
								$order_by = "name";
							break;
							default:
								$order_by = "date";
							break;	
						}// end switch
					}// end if(isset($_GET['sort']))
					else{
						// ko có sort	
						$order_by = "date";
					}
					// truy xuất csdl để hiển thị categories
					$q = "SELECT p.page_id, p.page_name, DATE_FORMAT(p.post_on, '%b %d %Y') AS date, CONCAT(' ', first_name, last_name) AS name, p.content "; 
					$q .= " FROM page AS p "; 
					$q .= " JOIN user AS u ";
					$q .= " USING(user_id) "; 
					$q .= " ORDER BY {$order_by} ASC ";
					$r = mysqli_query($dbc,$q);
					confirm_query($r, $q);
					if(mysqli_num_rows($r) > 0){
						// nếu có pages để hiển thị thì đổ ra 
						$stt = 0;
						while($page = mysqli_fetch_array($r, MYSQLI_ASSOC)){
							$stt++;
							echo "
								<tr>
									<td> {$stt} </td>
									<td> {$page['page_name']} </td>
									<td> {$page['date']} </td>
									<td>  {$page['name']}     </td>
									<td>".the_excerpt($page['content'])."</td>
									<td><a class='edit' href='edit_pages.php?pid={$page['page_id']}'>Edit</a></td>
									<td><a class='delete' href='delete_pages.php?pid={$page['page_id']}&pn={$page['page_name']}'>Delete</a></td>
								</tr> 
							";
						}
					}else{
						// nếu ko có page để hiển thị thi báo lỗi, nhắc người dùng tạo page
						$message = "<p class='warning'>there isn't any page to display, please creat a page first</p> ";
					}
				?>               	
                </tbody>
            </table>
      </div> <!--end content-->
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>