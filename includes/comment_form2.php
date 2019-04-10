<?php
	if(isset($_POST['submit'])){
		$error = array();
		if(empty($_POST['name'])){  // VALIDATE NAME
			$error[] = "name";
		} else {
			$name = mysqli_real_escape_string($dbc,strip_tags($_POST['name']));
		} 
		// nêu đi kèm với filter_var -> dùng isset
		if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // validate email
            $email = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
		} else {
			$error[] = "email";
        }
        if(empty($_POST['comment'])){ // validate comment
            $error[] = "comment";
        } else {
            $comment = mysqli_real_escape_string($dbc, $_POST['comment']);
        }
        // validate capcha question
        if(isset($_POST['captcha']) && trim($_POST['captcha']) != 5){ 
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

        if(empty($error)){ // nếu ko có lỗi thì chèn comment vào csdl
            $q = "INSERT INTO comments(	page_id, author, email, comment, comment_date) 
                 VALUES({$pid}, '{$name}', '{$email}', '{$comment}', NOW())";
            $r = mysqli_query($dbc, $q) ;     
            confirm_query($r,$q);
            if(mysqli_affected_rows($dbc)==1){
                $message = "<p class='success'>Thank for comment</p>";
            } else {
                $message = "<p class='warning'>You comment could not be inserted due a system error</p>";
            }
        } else{
            $message = "<p>Please try again</p>";
        }	
	} // end if submit	
?>
<?php
	// display comment from database
	$q = "SELECT author, comment, DATE_FORMAT(comment_date,'%b %d %Y') AS date FROM comments WHERE page_id={$pid}";
	$r = mysqli_query($dbc,$q);
	confirm_query($r, $q);
	if(mysqli_num_rows($r) > 0){ // có comment để hiển thị ra trình duyệt
		echo "<ol id='disscuss'>";
			while(list($author,$comment,$date) = mysqli_fetch_array($r,MYSQLI_NUM)){
				echo "<li class='comment-wrap'>
						 <p class='author'>{$author}</p>
						 <p class='comment-sec'>{$comment}</p>
						 <p class='date'>{$date}</p>
					  </li>";
			} // end while loop		
		echo "</ol>";		
	} else {
		// nếu ko có comment thì báo ra trình duyệt
		echo "<p class='warning'>Be the first to leave a comment</p>";
	}
?>
<form id="comment-form" action="" method="post">
	<?php if(!empty($message)) echo $message ; ?>
    <fieldset>
    	<legend>Leave a comment</legend>
            <div>
            <label for="name">Name: <span class="required">*</span>
            	<?php if(isset($error) && in_array('name',$error)){
					  	  echo "<p class='warning'>Please fill in the Name</p>";
					  } 
				?>
            </label>
            <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo htmlentities($_POST['name'], ENT_COMPAT,'UTF-8');?>" size="20" maxlength="80" tabindex="1" />
        </div>
        <div>
                <label for="email">Email: <span class="required">*</span>
                <?php if(isset($error) && in_array('email',$error)){
					  	  echo "<p class='warning'>Please fill in the email</p>";
					  } 
				?>
                </label>                
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']);?>" size="20" maxlength="80" tabindex="2" />
            </div>
        <div>
            <label for="comment">Your Comment: <span class="required">*</span>
            	 <?php if(isset($error) && in_array('comment',$error)){
					  	  echo "<p class='warning'>Please fill in the comment</p>";
					  } 
				 ?>
            </label>
            <div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?php if(isset($_POST['comment'])) echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');?></textarea></div>
        </div>
        
        <div>
        <label for="captcha">Answer question with number: <?php echo captcha() ;?>  <span class="required">*</span>
        	<?php if(isset($error) && in_array('wrong',$error)){
					  	  echo "<p class='warning'>Please give correct answer</p>";
					  } 
			?>
        </label>
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