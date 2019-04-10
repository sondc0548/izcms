<?php 
 ob_start();
 require("../includes/connect.php");
 require("../includes/function_die.php"); 
 require("../includes/header.php"); 
 require("../includes/sidebar_admin.php"); ?> 
     <div id="content">
     <?php
      		if(isset($_GET['pid'], $_GET['pn']) && filter_var($_GET['pid'],FILTER_VALIDATE_INT, array('min_range'=>1)) ){
				$pid = $_GET['pid'];
				$page_name = $_GET['pn'];
				// nếu cid và cat_name tồn tại thì sẽ xóa khỏi csdl
				if(isset($_POST['submit'])){
					if(isset($_POST['delete']) && ($_POST['delete']== 'yes')){
						$q = "DELETE FROM page WHERE page_id = {$pid} LIMIT 1";
						$r = mysqli_query($dbc,$q);
						confirm_query($r,$q);
						if(mysqli_affected_rows($dbc)==1){
							$message = "<p class='success'>The page was delete succsessfully</p>";
						}else{
							$message = "<p class='warning'>The page was not delete due to a system error</p>";
						}
					}else{
						$message = "<p class='warning'>i don't want to delete this page anymore</p>";
					}
				}
			}else{
				// nếu CID ko tồn tại hoặc ko đúng định dạng 
				redirect_to("admin/view_pages.php");	
			}
	  ?>
     <h2>Delete Page: <?php if(isset($page_name)) echo htmlentities($page_name,ENT_COMPAT,'UTF-8') ;?></h2>
            <?php if(!empty($message)) echo $message; ?>
            <form action="" method="post">
            	<fieldset>
					<legend>Delete Page</legend>
                    <label for="delete">Are you sure</label>
                    <div>
                    	<input type="radio" name="delete" value="no" checked="checked" /> No
                        <input type="radio" name="delete" value="yes" /> Yes
                    </div>
                    <div><input type="submit" name="submit" value="Delete" onClick="return confirm('Are you sure')" /></div>
                </fieldset>
            </form>
      </div> <!--end content-->
<?php require("../includes/footer.php"); ?>