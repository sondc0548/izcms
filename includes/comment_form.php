<?php
	if(isset($_POST['submit'])){
		$error = array(); // gán biến $error vào 1 mảng rỗng để chứa nếu có lỗi ở các trường sẽ cho vào
		//validate name
		if(empty($_POST['name'])){
			$error[] = "name";
		}else{
			$name = mysqli_real_escape_string($dbc,strip_tags($_POST['name']));
		}
		//validate email
		if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$email = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
		}else{
			$error[] = "email";
		}
		//validate comment
		if(empty($_POST['comment'])){
			$error[] = "comment";
		}else{
			$comment = mysqli_real_escape_string($dbc,$_POST['comment']);
		}
		// validate capcha question
		if(isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']){
			$error[] = "wrong";		
		}		
		// honey pot captcha
		if(!empty($_POST['url'])){
			redirect_to("thankyou.html");
			exit();
		}
		// salt pot captcha
		if(!empty($_POST['question'])){
			$error[] = "delete";
		}

		if(empty($error)){
			// nếu ko có lỗi them commmnet vào csdl
			$q = "INSERT INTO comments(page_id,author,email,comment,comment_date) VALUES({$pid},'{$name}','{$email}','{$comment}',NOW())";
			$r = mysqli_query($dbc,$q);
			confirm_query($r,$q);
			if(mysqli_affected_rows($dbc) == 1){
				//success
				$message = "<p class='success'>Thank you for comment</p>";
			}else{
				// no match vas made
				$message = "<p class='error'>your comment could not be posted due a system error </p>";	
			}
		}else{
			// có lỗi khi người dùng quên ko điền form, báo lỗi
			$message = "<p class='error'>Please try again </p>";	
		}	
	}// End if
?>
<?php
	// hiển thị comment từ CSDL	
	$q = "SELECT author, comment, DATE_FORMAT(comment_date, '%b %d, %y') AS date from comments where page_id = {$pid}"; 
	// có thể gọi biến $pid vì từ trang single.php đã gọi trang commnet_form.php
	$r = mysqli_query($dbc,$q);
	confirm_query($r,$q);
	if(mysqli_num_rows($r) > 0){
		// nếu có comment thì hiện ra trình duyệt
		echo "<ol id='disscuss'>";
		while(list($author,$comment,$date) = mysqli_fetch_array($r, MYSQLI_NUM) ){
			echo "<li class='comment-wrap'>
				     <p class='author'>{$author}</p>
					 <p class='comment-sec'>{$comment}</p>
					 <p class='date'>{$date}</p>
				  </li>"; 
		}// END  WWHILE LOOP
		echo "</ol>";
	}else{
		// nếu ko có comment thì báo ra trình duyệt
		echo "<h2>Be the first to leave a comment</h2>";
	}
?>
<?php if(!empty($message)) echo $message; ?>
<form id="comment-form" action="" method="post">
    <fieldset>
    	<legend>Leave a comment</legend>
            <div>
            <label for="name">Name: <span class="required">*</span>
            	<?php if(isset($error) && in_array('name',$error)) { echo "<span class='warning'>Please enter your name</span>"; } ?>            
            </label>
            <input type="text" name="name" id="name" value="<?php if(isset($_POST['name']))
			{ echo htmlentities($_POST['name'], ENT_COMPAT,'UTF-8');}?>" size="20" maxlength="80" tabindex="1" />
        </div>
        <div>
                <label for="email">Email: <span class="required">*</span>
                <?php if(isset($error) && in_array('email',$error)) echo "<span class='warning'>Please enter your email</span>"; ?>	
                </label>
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']); } ?>" size="20" maxlength="80" tabindex="2" />
            </div>
        <div>
            <label for="comment">Your Comment: <span class="required">*</span>
            <?php if(isset($error) && in_array('comment',$error)) echo "<span class='warning'>Please enter your comment</span>";?>
            </label>
            <div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8'); } ?></textarea></div>
        </div>
   
        <div>
        <label for="captcha">Phiền bạn trả lời câu hỏi với dạng số: <?php echo captcha() ; ?> <span class="required">*</span>
        	  <?php if(isset($error) && in_array('wrong', $error)){ echo "<span class='warning'>Please enter a correct answer</span>"; }?></label>
        <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="5" tabindex="4" />
        </div>
		<div>
			<label for="question">Phiền bạn xóa nội dung ở trường dưới trước khi submit <span class="required">*</span>
				<?php if(isset($error) && in_array('delete',$error)) echo "<p class='warning'>Bạn chưa xóa ở đây</p>";?>
			</label>
        <input type="text" name="question" id="question" value="Xóa nội dung này" size="20" maxlength="10" tabindex="4" />
        </div>
        
        <div class="website">
			<label for="website">Nếu bạn thấy nội dung này thì ĐỪNG điền gì vào hết <span class="required">*</span></label>
			<input type="text" name="url" id="url" value="" size="20" maxlength="10" tabindex="4" />
        </div>
    </fieldset>
    <div><input type="submit" name="submit" value="Post Comment" /></div>
</form>