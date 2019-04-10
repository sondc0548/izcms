<?php require("../includes/header.php"); ?>
<?php require("../includes/connect.php"); ?>
<?php require("../includes/sidebar_admin.php"); ?>
<?php require("../includes/function_die.php"); ?>
	  <div id="content"> 
      <?php
      		if(isset($_GET['cid'], $_GET['cat_name']) && filter_var($_GET['cid'],FILTER_VALIDATE_INT, array('min_range'=>1)) ){
				$cid = $_GET['cid'];
				$cat_name = $_GET['cat_name'];
				// nếu cid và cat_name tồn tại thì sẽ xóa khỏi csdl
				if(isset($_POST['submit'])){
					if(isset($_POST['delete']) && ($_POST['delete']== 'yes')){
						$q = "DELETE FROM categories WHERE cat_id = '{$cid}'";
						$r = mysqli_query($dbc,$q);
						confirm_query($r,$q);
						if(mysqli_affected_rows($dbc) == 1){
							$message = "<p class='success'>The category was delete successfully</p>";
						}else{
							$message = "<p class='warning'>The category was not delete due to a system error</p>";
						}
					}else{
						$message = "<p class='warning'>i don't want to delete this category anymore</p>";
					}
				}
			}else{
				// nếu CID ko tồn tại hoặc ko đúng định dạng 
				redirect_to("admin/view_categories.php");	
			}
	  ?>	
      		<h2>Delete Categories: <?php if(isset($cat_name)) echo htmlentities($cat_name,ENT_COMPAT,'UTF-8') ;?></h2>
            <?php if(!empty($message)) echo $message; ?>
            <form action="" method="post">
            	<fieldset>
					<legend>Delete category</legend>
                    <label for="delete">Are you sure</label>
                    <div>
                    	<input type="radio" name="delete" value="no" checked="checked" /> No
                        <input type="radio" name="delete" value="yes" /> Yes
                    </div>
                    <div><input type="submit" name="submit" value="Delete" onClick="return confirm('Are you sure')" /></div>
                </fieldset>
            </form>
      </div> <!--end content-->
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>