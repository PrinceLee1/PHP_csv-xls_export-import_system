
<?php
$success = "";
$error_message = "";
    $conn = new mysqli("localhost","dbusername","****","dbname") ;
if($conn){
    if(isset($_POST["submit_email"])) {
        $result = mysqli_query($conn,"SELECT * FROM registered_users WHERE email='" . $_POST["email"] . "'");
        $count  = mysqli_num_rows($result);
        // print_r($count);exit;

        if($count>0) {
            // generate OTP
			$otp = rand(100000,999999);
			$email = $_POST['email'];
            // Send OTP
//Composer's autoload file loads all necessary files
require 'phpmailer/vendor/phpmailer/phpmailer/class.phpmailer.php';
$message_body = "One Time Password for PHP login authentication is:<br/><br/>" . $otp;
$mail = new PHPMailer;
$mail->isSMTP();  // Set mailer to use SMTP
$mail->Host = 'smtp.mailgun.org';  // Specify mailgun SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = ''; // SMTP username from https://mailgun.com/cp/domains
$mail->Password = ''; // SMTP password from https://mailgun.com/cp/domains
$mail->SMTPSecure = 'tls';   // Enable encryption, 'ssl'
$mail->From = 'majornwa189@gmail.com'; // The FROM field, the address sending the email 
$mail->FromName = 'Prince Lee'; // The NAME field which will be displayed on arrival by the email client
$mail->addAddress($email, 'BOB');     // Recipient's email address and optionally a name to identify him
$mail->isHTML(true);   // Set email to be sent as HTML, if you are planning on sending plain text email just set it to false
// The following is self explanatory
$mail->Subject = 'OTP to Login';
$mail->Body    = "One Time Password for Login authentication is:<br/><br/>".'<span style="color:red">' .$otp.'</span>';
$mail->AltBody = $message_body;
$mail_status = $mail->send();
$date = date('Y-m-d H:i:s');
            
            if($mail_status == 1 ){
				$sql = "INSERT INTO otp_expiry (otp, is_expired, create_at) VALUES ('$otp', 0, '$date');";
				$insertOTP =$conn->query($sql);
				// var_dump($insertOTP);exit;
                $current_id = mysqli_insert_id($conn);
                if(!empty($current_id)) {
                    $success=1;
                }else{echo 'Didnt insert';}
            }else{
				echo "Message hasn't been sent.";
				echo 'Mailer Error: ' . $mail->ErrorInfo . "n";
            }
        } else {
       echo   '  $error_message = "Email not exists!"';
        }
    }
	
}else{
    echo 'Couldnt connect';
}

if(!empty($_POST["submit_otp"])) {
	$result = mysqli_query($conn,"SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
	$count  = mysqli_num_rows($result);
	if(!empty($count)) {
		$result = mysqli_query($conn,"UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
		$success = 2;	
	} else {
		$success =1;
		$error_message = "Invalid OTP!";
	}	
}
?>


<form name="frmUser" method="post" action="">
	<div class="tblLogin">
		<?php 
			if(!empty($success == 1)) { 
		?>
		<div class="tableheader">Enter OTP</div>
		<p style="color:#31ab00;">Check your email for the OTP</p>
			
		<div class="tablerow">
			<input type="text" name="otp" placeholder="One Time Password" class="login-input" required>
		</div>
		<div class="tableheader"><input type="submit" name="submit_otp" value="Submit" class="btnSubmit"></div>
		<?php 
			} else if ($success == 2) {
        ?>
<?php
header("Location: handleCSv.php");
?>
		<?php
			}
			else {
		?>
		
		<div class="tableheader">Enter Your Login Email</div>
		<div class="tablerow"><input type="text" name="email" placeholder="Email" class="login-input" required></div>
		<div class="tableheader"><input type="submit" name="submit_email" value="Submit" class="btnSubmit"></div>
		<?php 
			}
		?>
	</div>
</form>
