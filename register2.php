<?php 
	  require("includes/header.php"); 
	  require("includes/connect.php");
	  require("includes/function_die.php");  	 
 	  require("includes/sidebar_a.php"); 
?>
<div id="content">    
<h2>Register</h2>
<form action="register.php" method="post">
    <?php
        if(isset($_POST['submit'])){
            $error = array(); // tao flag
            $fn = $ln = $e = $p = FALSE;
            // với tên người nên dùng regular expression
            if(preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['first_name']))){
                $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
            } else{
                $error[] = "first name";
            }
            if(preg_match('/^[\w\'.-]{2,20}$/i',trim($_POST['last_name']))){
                $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
            } else {
                $error[] = "last name";
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $e = mysqli_real_escape_string($dbc, $_POST['email']);
            } else {
                $error[] = "email";
            }
            if(preg_match('/^[\w\'.-]{4,20}$/i', trim($_POST['password1']))){
                if($_POST['password1'] == $_POST['password2']){
                    // nếu  mk1 = mk2 thì lưu vào csdl
                    $p = mysqli_real_escape_string($dbc,trim($_POST['password1']));
                } else {
                    // nếu mk1 != mk2 => báo lỗi
                    $error[] = "password not match";
                }
            } else{ // chưa nhập mật khẩu, hoặc nhập ko đúng định dạng
                $error[] = "password";
            }
            if($fn && $ln && $e && $p){ // nếu tồn tại cả 4 giá trị => truy vấn csdl xem email người dùng nhập vào có trong csdl ko
                $q = "SELECT user_id from user WHERE EMAIL ='{$e}' ";
                $r = mysqli_query($dbc,$q);
                confirm_query($r,$q);
                if(mysqli_num_rows($r) == 0){ // $email người dùng nhập ko có trong csdl => cho phếp dk
                    $a = md5(uniqid(rand(), true)); //// tạo 1 chuỗi activation key => chuỗi kích hoạt để gửi => email người dki
                    $q = "INSERT INTO user(first_name, last_name, email, pass, active, registration_date)
                          VALUES('{$fn}','{$ln}','{$e}','{$p}','{$a}', NOW()) ";    
                    $r = mysqli_query($dbc,$q);
                    confirm_query($r, $q);
                    if(mysqli_affected_rows($dbc)==1){ // insert successfull
                        $body = "Cảm ơn bạn đã đăng kí ở izcms. link kích hoạt đã dc gửi đến email của bạn.
                                Phiền bạn click vào đường link này để kích hoạt tài khoản";
                        $body .= BASE_URL ."active.php?x=".urlencode."y={$a} " ;
                        if(mail($_POST['email'],'Kích hoạt tài khoản tại Izcms',$body, 'FROM: localhost')){

                        }
                    }

                } else {

                }
            } else { // email đã tồn tại trong csdl => báo lỗi
                $message =  "<p class='warning'> The email was already exist. please choice an other email </p>";
            }

        }
    
    ?>

    <fieldset>
   	    <legend>Register</legend>
            <div>
                <label for="First Name">First Name <span class="required">*</span></label> 
	           <input type="text" name="first_name" size="20" maxlength="20" value="" tabindex='1' />
            </div>
            
            <div>
                <label for="Last Name">Last Name <span class="required">*</span></label> 
	           <input type="text" name="last_name" size="20" maxlength="40" value="" tabindex='2' />
            </div>
            
            <div>
                <label for="email">Email <span class="required">*</span></label> 
	           <input type="text" name="email" size="20" maxlength="80" value="" tabindex='3' />
            </div>
            
            <div>
                <label for="password">Password <span class="required">*</span></label> 
	           <input type="password" name="password1" size="20" maxlength="20" value="" tabindex='4' />
            </div>
            
            <div>
                <label for="email">Confirm Password <span class="required">*</span> </label> 
	           <input type="password" name="password2" size="20" maxlength="20" value="" tabindex='5' />
            </div>
    </fieldset>
    <p><input type="submit" name="submit" value="Register" /></p>
</form>
</div><!--end content-->
<?php require("includes/sidebar_b.php"); ?>    
<?php require("includes/footer.php"); ?>