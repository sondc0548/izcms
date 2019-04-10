<?php require("../includes/connect.php"); ?>
<?php require("../includes/header.php"); ?>
<?php require("../includes/sidebar_admin.php"); ?>
<?php require("../includes/function_die.php"); ?>
	   	<?php
        	if(isset($_POST['submit'])){
				 $error = array();	
				 if(empty($_POST['page_name'])){
					$error[] = "page_name"; // gán page name vào mảng $error nếu để trống
				 }else{
					$page_name = mysqli_real_escape_string($dbc,strip_tags($_POST['page_name']));
				 }
				 if(isset($_POST['category']) && filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range'=>1))){
				 	$cat_id = $_POST['category'];
				 }else{
					$error[] = "category";
				 }
				 if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range'=> 1))){
					$pos = $_POST['position'];
				 } else {
					$error[] = "position";
				 }
				 if(empty($_POST['content'])){
				 	$error[] = "content";
				 }else{
				 	$cont = mysqli_real_escape_string($dbc, $_POST['content']);
				 }
				 if(empty($error)){ // nếu ko có lỗi mới chèn dl vào csdl
				 	$q = "insert into page(user_id, cat_id, page_name, content, position, post_on) 
					values (2,'$cat_id','$page_name','$cont', $pos, NOW())";
					$r = mysqli_query($dbc,$q);
					confirm_query($r, $q);
					if(mysqli_affected_rows($dbc) == 1){
						$message = "<p class='success'> The page was added successfully </p>";
					} else{
						$message = "<p class='warning'> The page could not be added due to a system error </p>";
					}
				 }else{
				 	$message = "<p class='warning'> please fill in all the required field </p>";
				 }
			}// end main if submit
		?>
	<div id="content">
 		<h2>Create a Page</h2>
        <?php if(!empty($message)) echo $message; ?>
		<form action="" method="post">
        	<fieldset>
            	<legend>Add a Page</legend>
                    <div>
                        <label for="page">Page Name <span class="required">*</span>
                        <?php 
							if(isset($error) && in_array('page_name', $error)){
								echo "<p class='warning'>Please fill in the page name</p>";
							}
						?>
                        </label>                       
                        <input type="text" name="page_name" id="page_name" maxlength="80" size="20" tabindex="1" value="<?php if(isset($_POST['page_name'])) echo strip_tags($_POST['page_name']);?>">
                    </div>
                    <div>
                        <label for="category">All Categories <span class="required">*</span></label>
                            <?php 
								if(isset($error) && in_array('category', $error)){
									echo "<p class='warning'>Please pick a category</p>";
								}
						    ?>
                        <select name="category">
                        	<option>Select a Category</option>
                        	<?php
                            	$q = "select cat_id, cat_name from categories order by position ASC";	
								$r = mysqli_query($dbc,$q);
								if(mysqli_num_rows($r) > 0){
									while($cats = mysqli_fetch_array($r, MYSQLI_NUM)){
										echo "<option value='$cats[0]'";
											if(isset($_POST['category']) && ($_POST['category']==$cats[0])) echo "selected = 'selected'";
										echo ">".$cats[1]."</option>";
									}
								}
							?>
                        </select>
                    </div>
                    <div>
                    	<label for="position"> Position <span class="required">*</span></label>
                        <?php 
								if(isset($error) && in_array('position',$error)){
									echo "<p class='warning'>Please pick a position</p>";
								}
						?>
                        <select name="position">
                        	<?php
								$sql = "select count(page_id) as count from page";
								$r = mysqli_query($dbc,$sql);
								if(mysqli_num_rows($r) == 1){
									list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
									for($i=1; $i<=$num+1; $i++){ //tạo vòng for để ra option, công thêm 1 giá trị cho position
										echo "<option value='$i'";
											if(isset($_POST['position']) && $_POST['position'] == $i) echo "selected = 'selected'";										
										echo ">".$i."</option>";
									}
								}
							?>
                        </select>
                    </div>
					<div>
                    	<label for="page-content"> content <span class="required">*</span>
                        	<?php 
								if(isset($error) && in_array('content',$error)){
									echo "<p class='warning'>Please fill in the content</p>";
								}
							?>
                        </label>
                        <textarea name="content" cols="50" rows="20"><?php if(isset($_POST['content'])) echo htmlentities($_POST['content'], ENT_COMPAT, 'UTF-8'); ?></textarea>
                    </div>
            </fieldset>
            <p><input type="submit" name="submit" name="Add page"></p>
        </form>
	</div>
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>