<?php require("../includes/connect.php"); ?>
<?php require("../includes/header.php"); ?>
<?php require("../includes/sidebar_admin.php"); ?>
<?php require("../includes/function_die.php"); ?>
	   	<?php
        	if(isset($_POST['submit'])){
				$error = array(); // tạo flag $error khi chưa có lỗi gì xảy ra
				if(empty($_POST['category'])){
					$error[] = "category";	
				}else{
					$cat = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));
				}
				if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range'=> 1))){
					// hàm filter_var kiểm tra $_POST['position'] nhâp vào số nguyên có giá trị >=1, nếu ko thì gán vào hàm error[] 
					// FILTER_VALIDATE_IN ktra $_POST['position'] có phải số nguyên hay ko? đúng -> true, sai -> false cho vào mảng error[]
					// có tác dụng chống hack
					// array('min_range'=> 1)) mảng số nguyên nhập vào có giá trị >=1
					 $pos = $_POST['position'];
				}else{									
					$error[] = "position";
				}
				if(empty($error)){
				   // nếu ko có lỗi xẩy ra thì chèn vào csdl
					$q = "insert into categories(user_id,cat_name,position) values (1,'$cat',$pos)";
					$r = mysqli_query($dbc,$q); //or die("Query {$q} \n <br/> Mysql Error: " .mysqli_error($dbc));
					confirm_query($r, $q);
					if(mysqli_affected_rows($dbc)==1){
						$message =  "<p class='success'>The category was inserted successfully</p>";
					}else{
						$message =  "<p class='warning'> Could not inserted</p>";
					}
				}else{ //nếu có lỗi xảy ra hiện thông báo
					$message =  "<p class='warning'> please fill all required field </p>";
				}
			}// end main if submit
		?>
	<div id="content">
 		<h2>Create a category</h2>
        <?php if(!empty($message)) echo $message; ?>
		<form action="add_categories.php" method="post">
        	<fieldset>
            	<legend>Add category</legend>
                <div>
                	<?php 
						if(isset($error) && in_array('category',$error)){
							echo "<p>Please fill in the category name</p>";
						}
					?>
                    <label for="category">Category Name <span class="required"> * </span> </label>
                    <input type="text" size="25" name="category" id="category" maxlength="80" tabindex="1" value="<?php if(isset($_POST['category'])) echo strip_tags($_POST['category']); ?>">                
                </div>
                <div>
                	<?php 
						if(isset($error) && in_array('position',$error)){
							echo "please pick a position";
						}
					?>
                    <label for="position"> <span class="required"> * </span> </label>
                    <select name="position" tabindex="2">
                        <?php
							$sql = "select count(cat_id) as count from categories";
							$r = mysqli_query($dbc,$sql);
							if(mysqli_num_rows($r) == 1){
								list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
								 // gán các giá trị của mảng mysqli_fetch_array($r, MYSQLI_NUM) vào vào biến  $num thông qua hàm  list()
								 // hàm list có tác dụng tương đương như vòng lăp white nhưng xử lí nhanh hơn
								 // sử dụng tham số MYSQLI_NUM sẽ nhanh hơn vì giá trị nhận về là dạng số 
								for($i=1; $i<=$num+1; $i++){ //tạo vòng for để ra option, công thêm 1 giá trị cho position
									echo "<option value='$i'";
										if(isset($_POST['position']) && $_POST['position'] == $i) echo "selected = 'selected'";										
									echo ">".$i."</option>";
								}
							}
						?>
                    </select>
                </div>
            </fieldset>
            <p><input type="submit" name="submit" name="Add category"></p>
        </form>
	</div>
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>