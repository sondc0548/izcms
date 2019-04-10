<?php
	ob_start(); //chống lỗi headers already sent by	
	require("../includes/connect.php");
	require("../includes/function_die.php"); 
	require("../includes/header.php"); 
	require("../includes/sidebar_admin.php"); 
			// xác nhận biến $_GET['cid'] tồn  tại  và  thuộc  loại dữ liệu cho phép
			if(isset($_GET['cid']) && filter_var($_GET['cid'],FILTER_VALIDATE_INT, array('min_range' => 1))){
				$cid = $_GET['cid'];
			}else{
				 //header("location:index.php");			
            	 //exit();
				// gọi hàm redirect_to() từ trang function_die.php
				redirect_to('admin/admin.php'); // điều hướng xang trang admin.php
		    }	
			if(isset($_POST['submit'])){
				$error = array(); // tạo flag $error khi chưa có lỗi gì xảy ra
				// kiểm tra tên của category
				if(empty($_POST['category'])){
					$error[] = "category";	
				}else{
					$cat_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));
				}
				// kiểm tra position của category
				if(empty($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range'=> 1))){
					// hàm filter_var kiểm tra $_POST['position'] nhâp vào số nguyên có giá trị >=1, nếu ko thì gán vào hàm error[] 
					// FILTER_VALIDATE_IN ktra $_POST['position'] có phải số nguyên hay ko? đúng -> true, sai -> false cho vào mảng error[]
					// có tác dụng chống hack
					// array('min_range'=> 1))  số nguyên có giá trị >=1
					$error[] = "position";
				}else{				
					$position = $_POST['position'];
				}
				if(empty($error)){
				   // nếu ko có lỗi xẩy ra thì chèn vào csdl
					$q = "UPDATE categories SET cat_name = '{$cat_name}', position = $position WHERE cat_id = {$cid} LIMIT 1";
					$r = mysqli_query($dbc,$q); 
					confirm_query($r, $q);
					if(mysqli_affected_rows($dbc) == 1){
						$message =  "<p class='success'>The category was edit successfully</p>";
					}else{
						$message =  "<p class='warning'> Could not edit the category due to a system error</p>";
					}
				}else{ //nếu có lỗi xảy ra hiện thông báo
					$message =  "<p class='warning'> please fill all required field </p>";
				}
			}// end main if submit
?>
	<div id="content">
    	<?php 
			// truy xuất bảng categories để đổ dl ra form
        	$q = "SELECT cat_name, position from categories where cat_id = {$cid}";
			$r = mysqli_query($dbc, $q);
			confirm_query($r,$q);
			if(mysqli_num_rows($r)==1){
				// nếu category tôn tại trong cdsl, xuất ra trình duyệt dựa vào cid đã chọn trc đó
				list($cat_name,$position) = mysqli_fetch_array($r, MYSQLI_NUM);
			}else{
				// nếu cid ko hợp lệ sẽ ko hiển thị category
				$message = "<p class='warning'>The category is not exist </p>";	
			}
		?>
 		<h2>Edit category: <?php if(isset($cat_name)) echo $cat_name ; ?></h2>
        <?php if(!empty($message)) echo $message; ?>
		<form id="edit_cat" action="" method="post">
        	<fieldset>
            	<legend>Edit category </legend>
                <div>
                	<?php 
						if(isset($error) && in_array('category',$error)){
							echo "<p>Please fill in the category name</p>";
						}
					?>
                    <label for="category">Category Name <span class="required"> * </span> </label>
                    <input type="text" size="25" name="category" id="category" maxlength="80" tabindex="1" value="<?php if(isset($cat_name)) echo $cat_name ; ?>">                
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
								for($i=1; $i<=$num+1; $i++){ //tạo vòng for để ra option, công thêm 1 giá trị cho position
									echo "<option value='$i'";
										if(isset($position) && ($position == $i)) echo "selected = 'selected'";										
									echo ">".$i."</option>";
								}
							}
						?>
                    </select>
                </div>
            </fieldset>
            <input type="submit" name="submit" name="Edit category">
        </form>
	</div>
<?php require("../includes/sidebar_b.php"); ?>    
<?php require("../includes/footer.php"); ?>