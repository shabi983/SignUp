<!DOCTYPE html>
<html>
<head>
	<title>Sign Up _ Techy Trends</title>
</head>
<center><body background="https://img.freepik.com/free-vector/abstract-design-background-with-blue-purple-gradient_1048-13167.jpg?size=626&ext=jpg" style="position: relative; background-size: cover; color: #fff;">
<h2 style="font-family: 'Segoe Print';">Register Here | Techy Trends </h2>
<form action="home.php" method="post">
	<table border="none" width="500" height="150">



<tr>
	  
	  <td class="lalbe">Name:</td>
      <td><input class="nami" style="" type="text" placeholder="Enter your name"  name="name" required=""></td>

 </tr>


<tr>
	
	 <td class="lalbe">Password:</td>
     <td><input  class="passi" style="" type="password" placeholder="Enter your password" name="password" required=""></td>

</tr>

<tr>

	<td class="lalbe">Email Id:</td>
    <td><input class="emai" style="" type="text" placeholder="Enter your email" name="email" required=""></td>

</tr>

<tr>
<td class="lalbe" colspan="5" align="center">
	<input class="btn_tbn" type="submit" name="signup" value="signup">
</td>
</tr>

	</table>
</form>
<style type="text/css">
	tr td{
		padding: 10px 10px 10px 10px;
		border: none;
	}
	table{
		border:none;
		border-radius: 10px;
		background-color: #3AB1F7;
		box-shadow: 15px 15px 40px lightskyblue;
	}
	.btn_tbn{
		width: 80%;
		padding: 10px;
		border: none;
		border-radius: 10px;
		cursor: pointer;
		font-size: 25px;
		font-family: 'Original Surfer';
		text-transform: uppercase;
		background: #F2A024;
		box-shadow: 10px 10px 20px #F5BD66;
		margin-bottom: 20px;
		transition: 0.3s;
		font-weight: bolder;
		color: #fff;
	}
	.btn_tbn:hover{
		transform: scale(1.1);
		transition: 0.3s;
		background-color: #8937F7;
		box-shadow: 10px 10px 20px #9F62F1;
		color: yellow;
	}
	.nami:hover{
		box-shadow: 5px 5px 20px blue;
		transition: 0.3s;
	}
	.nami{
		transition: 0.3s;
		color: #000; 
		background: #fff; 
		width: 90%; 
		box-shadow: 5px 5px 20px lightgray; 
		border-radius: 10px; 
		height: 3.5vh; border: none;
	}
	.passi:hover{
		box-shadow: 5px 5px 20px blue;
		transition: 0.3s;
	}
	.passi{
		transition: 0.3s;
		color: #000; 
		background: #fff; 
		width: 90%; 
		box-shadow: 5px 5px 20px lightgray; 
		border-radius: 10px; 
		height: 3.5vh; border: none;
	}
	.emai:hover{
		box-shadow: 5px 5px 20px blue;
		transition: 0.3s;
	}
	.emai{
		transition: 0.3s;
		color: #000; 
		background: #fff; 
		width: 90%; 
		box-shadow: 5px 5px 20px lightgray; 
		border-radius: 10px; 
		height: 3.5vh; border: none;
	}
</style>
</body>
</center>
</html>

<?php 
session_start();
require "connection.php";
$email = "";
$name = "";
$errors = array();

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status)
                        values('$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: shahiprem7890@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }

}
    //if user click verification code submit button
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if($update_res){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: home.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM usertable WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;
                $status = $fetch['status'];
                if($status == 'verified'){
                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $password;
                    header('location: home.php');
                }else{
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp.php');
                }
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
        }
    }

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM usertable WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "From: shahiprem7890@gmail.com";
                if(mail($email, $subject, $message, $sender)){
                    $info = "We've sent a passwrod reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login-user.php');
    }
?>
 	