<?php 
	  require("includes/header.php"); 
	  require("includes/connect.php");
	  require("includes/function_die.php");  	 
 	  require("includes/sidebar_a.php"); 
?>
<div id="content">
    <?php
        if(isset($_POST['submit'])){
            $error = array(); // tạo cờ
            // mặc định cho các trương nhập liệu là FALSE
            $fn = $ln = $e = $p = FALSE;
            if(preg_match('/^[\w\'.-]{2-20}$/i', trim($_POST['first_name']))){
                /*
                    [\w\'.-] : gồm các kí tự a-z A-Z 0-9, dấu ' và -
                    {2-20}: gồm từ 2-20 kí tự
                    i: ko phân biệt chữ hoa và thường
                */
                $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name'])); // $fn = TRUE
            } else {
                $error[] = "first name";
            }
            if(preg_match('/^[\w\'.-]{2-20}$/i', trim($_POST['last_name']))){
                $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name'])); // $ln = TRUE
            } else {
                    $error[] = "last name";
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $e = mysqli_real_escape_string($dbc, $_POST['email']);
            } else {
                $error[] = "email";
            }
            if(preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['password1']))){
                if($_POST['password1'] == $_POST['password2']){
                    // nếu mk1 = mk2 thì lưu vào csdl
                    $p = mysqli_real_escape_string($dbc, trim($_POST['password1']));
                } else{
                    // nếu mk1 !== mk2 => báo lỗi
                    $error[] = "Password not match";
                }
            } else { // chưa nhập mật khẩu, hoặc nhập ko đúng định dạng
                $error[] = "password";
            }
            
            if($fn && $ln && $p && $e){ // nếu tồn tại cả 4 giá trị thì truy vấn csdl tìm xem $email đã tồn tại hay chưa
                $q = "SELECT user_id FROM user WHERE email = '{$e}' ";
                $r = mysqli_query($dbc, $q);
                confirm_query($r,$q);
                if(mysqli_num_rows($r) == 0){  //giá trị $email người dùng nhập ko có trong csdl => cho phép dki  
                    // tạo 1 chuỗi activation key => chuỗi kích hoạt để gửi => email người dki
                    $a = md5(uniqid(rand(), true)); 
                    $q = "INSERT INTO user (first_name, last_name, email, pass, active, registration_date) 
                          VALUES('{$fn}', '{$ln}', '{$e}', '{$p}', '{$a}', NOW()) ";
                    $r = mysqli_query($dbc,$q);
                    confirm_query($r,$q);
                    if(mysqli_affected_rows($dbc) == 1){
                        $body = "Cảm ơn bạn đã dăng kí ở IZCSM. email kích hoạt đã dc gửi đến email của bạn.
                                 Phiền bạn click vào đường link sau để kích hoạt tài khoản \n\n";
                        $body .= BASE_URL . "admin/active.php?x=".urlencode($e)."&y={$a} ";
                        if(mail($_POST['email'], 'Kích hoạt tài khoản tại Izcms', $body, 'FROM:localhost')){
                            $mesage = "<p class='success'>Tài khoản của bạn đã dăng kí thành công. Vui Lòng click vào link xác nhận được gửi tới email của bạn </p>";
                        } else { //
                            $mesage = "<p class='warning'> ko thể gửi dc email cho bạn. rất xin lỗi bạn vì sự bắt tiện này </p>";
                        }
                    }else{ // lỗi hệ thống
                        $mesage =  "<p class='warning'> Sorry, your order could not be processed due a sýtem error </p>"; 
                    }
                }else{
                    // email đã tồn tại, phải dk email khác
                    $mesage = "<p class='warning'>The email was already used previously. Please choice an other email address.</p>";
                }
            } else { // nếu 1 trong 4 giá trị trên ko có hoặc ko đúng định dạng
                $mesage = "<p class='warning'>Please fill all the require field</p>";
            }    
        }// end main IF
    ?>
<h2>Register</h2>
<?php if(!empty($mesage)) echo $mesage ; ?>
<form action="register.php" method="post">
    <fieldset>
   	    <legend>Register</legend>
            <div>
                <label for="First Name">First Name <span class="required">*</span>
                <?php if(isset($error) && in_array('first name', $error)) echo "<span class='warning'>Please enter your first name</span>"; ?> 
               </label> 
	           <input type="text" name="first_name" size="20" maxlength="20" value="<?php if(isset($_POST['first_name'])) echo htmlentities($_POST['first_name']) ;?>" tabindex='1' />
            </div>
            
            <div>
                <label for="Last Name">Last Name <span class="required">*</span>
                <?php if(isset($error) && in_array('last name', $error)) echo "<span class='warning'>Please enter your last name</span>"; ?> 
                </label> 
	           <input type="text" name="last_name" size="20" maxlength="40" value="<?php if(isset($_POST['last_name'])) echo htmlentities($_POST['last_name']) ;?>" tabindex='2' />
            </div>
            
            <div>
                <label for="email">Email <span class="required">*</span>
                <?php if(isset($error) && in_array('email', $error)) echo "<span class='warning'>Please enter your email</span>"; ?> 
                </label> 
	           <input type="text" name="email" size="20" maxlength="80" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']) ;?>" tabindex='3' />
            </div>
            
            <div>
                <label for="password">Password <span class="required">*</span>
                <?php if(isset($error) && in_array('password', $error)) echo "<span class='warning'>Please enter your password</span>"; ?>    
                </label> 
	           <input type="password" name="password1" size="20" maxlength="20" value="<?php if(isset($_POST['password1'])) echo htmlentities($_POST['password1']) ;?>" tabindex='4' />
            </div>
            
            <div>
                <label for="password">Confirm Password <span class="required">*</span> 
                <?php if(isset($error) && in_array('password not match', $error)) echo "<span class='warning'>your confirm password does not match</span>"; ?>    
                </label> 
	           <input type="password" name="password2" size="20" maxlength="20" value="<?php if(isset($_POST['password2'])) echo htmlentities($_POST['password2']) ;?>" tabindex='5' />
            </div>
    </fieldset>
    <p><input type="submit" name="submit" value="Register" /></p>
</form>
</div><!--end content-->
<?php require("includes/sidebar_b.php"); ?>    
<?php require("includes/footer.php"); ?>