<?php 
    $title = "Contact Us";
	  require("includes/header.php"); 
	  require("includes/connect.php");
	  require("includes/function_die.php");  	 
 	  require("includes/sidebar_a.php"); 
?>
<div id="content">
<form id="contact" action="" method="post">
    <?php
        if(isset($_POST['submit'])){
            $errors = array(); // tạo flag
            if(empty($_POST['name'])){
                $errors[] = "name";
            }
            // kIỂM TRA tính hợp lệ của email dùng preg_match
                         //'/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/'
            if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/',$_POST['email'])){
                $errors[] = "email";
            }
            if(empty($_POST['comment'])){
                $errors[] = 'comment';
            }
            // kiểm tra xem có lỗi ở form ko? nếu ko có, gửi email
            if(empty($errors)){
                $to = "soncshs1986@gmail.com";
                $subject = "Contact form submition";
                $txt = "tessst";
                $headers = "From: localhost@localhost" . "\r\n" ;
                $body = "Name: {$_POST['name']} \n\n Comment: \n".strip_tags($_POST['comment']) ;
                $body = wordwrap($body, 70);// 70 chữ thì xuống dòng 1 lần
                if(mail($to, $subject, $body, $headers)){
                    echo "<p class='success'>Thank you for contact me. I will get back to you ASAP</p>";
                } else {
                    echo "<p class='warning'>Sorry your email could not be sent</p>";
                }
            } else {
                // nếu có lỗi do người dùng chưa nhập đủ 1 trường nào đó
                echo "<p class='warning'>Please fill all the required field</p>";     
            }
        } // end main if submit
    ?>
    <fieldset>
    	<legend>Contact</legend>
            <div>
                <label for="Name">Your Name: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('name',$errors)) {
                            echo "<span class='warning'>Please enter your name.</span>";
                        }
                    ?>
                </label>
                <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="1" />
            </div>
        	<div>
                <label for="email">Email: <span class="required">*</span>
                <?php 
                        if(isset($errors) && in_array('email',$errors)) {
                            echo "<span class='warning'>Please enter your email.</span>";
                        }
                    ?>
                </label>
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="2" />
            </div>
            <div>
                <label for="comment">Your Message: <span class="required">*</span>
                    <?php 
                        if(isset($errors) && in_array('comment',$errors)) {
                            echo "<span class='warning'>Please enter your message.</span>";
                            }
                    ?>
                </label>
                <div id="comment"><textarea name="comment" rows="10" cols="45" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
            </div>
    </fieldset>
    <div><input type="submit" name="submit" value="Send Email" /></div>
</form>
</div><!--end content-->
<?php require("includes/sidebar_b.php"); ?>    
<?php require("includes/footer.php"); ?>