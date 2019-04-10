<?php require("../includes/connect.php"); ?>
<?php require("../includes/header.php"); ?>
<?php require("../includes/sidebar_admin.php"); ?>
<?php require("../includes/function_die.php"); ?>
	  <div id="content"> 	
      		<h2>Manage Categories</h2>
            <table>
            	<thead>
                	<tr>
                    	<th>STT</th>	
                    	<th><a href="view_categories.php?sort=cat">Categories</a></th>
                        <th><a href="view_categories.php?sort=pos">Position</a></th>
                        <th><a href="view_categories.php?sort=by">Post by</a></th>
                        <th><a href="">Edit</a></th>
                        <th><a href="">Delete</a></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
					//săp xếp theo thứ tự table head
					if(isset($_GET['sort'])){
						switch ($_GET['sort']){
							case 'cat':
								$order_by = "cat_name";
							break;
							case 'pos':
								$order_by = "position";
							break;
							case 'by':
								$order_by = "name";
							break;
							default:
								$order_by = "position";
							break;	
						}// end switch
					}// end if(isset($_GET['sort']))
					else{
						// ko có sort	
						$order_by = "position";
					}
					// truy xuất csdl để hiển thị categories
					$q = "SELECT c.cat_id, c.cat_name, c.position, c.user_id, CONCAT(' ', first_name, last_name) AS name "; 
					$q .= " FROM categories AS c "; 
					$q .= " JOIN user AS u ";
					$q .= " USING(user_id) "; 
					$q .= " ORDER BY {$order_by} ASC ";
					$r = mysqli_query($dbc,$q);
					confirm_query($r, $q);
					$stt = 0;
					while($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)){
						$stt++;
						echo "
						    <tr>
								<td> {$stt} </td>
								<td> {$cats['cat_name']} </td>
								<td> {$cats['position']} </td>
								<td>  {$cats['name']}     </td>
								<td><a class='edit' href='edit_categories.php?cid={$cats['cat_id']}'>Edit</a></td>
								<td><a class='delete' href='delete_categories.php?cid={$cats['cat_id']}&cat_name={$cats['cat_name']}'>Delete</a></td>
							</tr> 
						";
					}
				?>               	
                </tbody>
            </table>
      </div> <!--end content-->
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>