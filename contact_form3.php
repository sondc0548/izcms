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
        use PHPMailer\PHPMailer\PHPMailer;
        if(isset($_POST['submit'])){
            require_once "PHPMailer/PHPMailer.php";
            require_once "PHPMailer/SMTP.php";
            require_once "PHPMailer/Exception.php";

            $errors = array(); // tạo flag
            if(empty($_POST['name'])){
                $errors[] = "name";
            } else {
                $name = $_POST['name'];
            }
            // kIỂM TRA tính hợp lệ của email dùng preg_match
            //'/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/'
            if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/',$_POST['email'])){
                $errors[] = "email";
            } else {
                $email = $_POST['email'];
            }
            if(empty($_POST['comment'])){
                $errors[] = 'comment';
            } else {
                $comment = $_POST['comment'];
            }
            $email = $_POST['email'];
            // kiểm tra xem có lỗi ở form ko? nếu ko có, gửi email
            if(empty($errors)){
                $to = "soncshs1986@gmail.com";
                $subject = "Contact form submition";
                $txt = "tessst";
                $headers = "From: localhost" . "\r\n" ;
                $body = "Name: {$_POST['name']} \n\n Comment: \n".strip_tags($_POST['comment']) ;
                $body = wordwrap($body, 70);// 70 chữ thì xuống dòng 1 lần

                $mail = new PHPMailer();
                //SMTP Settings
                   $mail->isSMTP();
                   $mail->Host = "smtp.gmail.com";
                   $mail->SMTPAuth = true;
                   $mail->Username = "soncshs1986@gmail.com";
                   $mail->Password = 'edaphyzyqehtfckx';
                   $mail->Port = 587; //587 //465
                   $mail->SMTPSecure = "tls"; //tls ssl
   
                   //Email Settings
                   $mail->isHTML(true);
                   $mail->setFrom($email, $name);
                   $mail->addAddress("soncshs1986@gmail.com");
                   $mail->Subject = $subject;
                   $mail->Body = $body;
   
                   if ($mail->send()) {
                       $status = "success";
                       $response = "Email is sent!";
                   } else {
                       $status = "failed";
                       $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
                   }
                   exit(json_encode(array("status" => $status, "response" => $response)));

            } else {
                // nếu có lỗi do người dùng chưa nhập đủ 1 trường nào đó
                echo "<p class='warning'>Please fill all the required field</p>";     
            } // end if empty($errors) 
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