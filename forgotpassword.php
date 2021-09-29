<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';


require "fungsi.php";





if (isset($_SESSION["mahasiswa"])) {
    header('location: mahasiswa.php');
    exit;
} elseif (isset($_SESSION["dosen"])) {
    header('location: index-userPremium.php');
    exit;
} elseif (isset($_SESSION["staff"])) {
    header('location: admin_dashboard.php');
    exit;
}
?>

<?php

if (isset($_POST["reset"])) {

    global $db;
    $emailTo = $_POST["email"];

    $result = mysqli_query($db, "SELECT * FROM user WHERE email = '$emailTo' ");

    if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);
        $code = uniqid();
        $otp = mt_rand(100000, 999999);

        
        $query = "INSERT INTO tb_reset_password(code, id_user, otp) VALUES('$code', '" . $row["id_user"] . "', '$otp')";
        mysqli_query($db, $query) or die(mysqli_error($db));


        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            //Server settings
            $mail->isSMTP();                                          //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
            $mail->Username   = 'aziizpranaja4@gmail.com';     //SMTP username
            $mail->Password   = 'MAp200102';               //SMTP password
            $mail->SMTPSecure = 'ssl';                                //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                  //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('aziizpranaja4@gmail.com', 'Login-Aziiz');
            $mail->addAddress($emailTo);                     //Add a recipient
            $mail->addReplyTo('no-reply@gmail.com', 'No Reply');

            //Content
            $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/resetpass.php?code=$code";
            $mail->isHTML(true);                              //Set email format to HTML
            $mail->Subject = 'Your Password Reset Link';
            $mail->Body    = "<h1>You Requested a password reset</h1><br>
            <h3>Kode OTP : " . $otp . "</h3>
            Click <a href='$url'>This Link</a> to do so";
            $mail->AltBody = 'Thankyou.';

            $mail->send();
            echo "<script>
                    alert ('Reset Password link has been sent to your email');
                    document.location.href = 'login.php';
                </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>
            alert ('Email yang anda masukkan tidak terdaftar !');
            document.location.href = 'forgotpassword.php';
        </script>";
    }
    exit();
}
?>

<html>
	<head>
		<title>Forgot Password</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="stylesheet" href="css/menu.css"/>
		<link rel="stylesheet" href="css/mainn.css"/>
		<link rel="stylesheet" href="css/bgimg.css"/>
		<link rel="stylesheet" href="css/bgimg-parallax.css"/>
		<link rel="stylesheet" href="css/font.css"/>
		<link rel="stylesheet" href="css/font-awesome.min.css"/>
		<script type="text/javascript" src="scripts/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="scripts/parallax.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
	</head>
<body>
<div class="menu">
		<a href="login.php" class="bars">
			<i class="fa fa-arrow-left"></i>
		</a>
	</div>
	<div class="background" id="background"></div>
	<div class="backdrop"></div>
	<div class="login-form-container" id="login-form">
		<div class="login-form-content">
			<div class="login-form-header">
				<h3>Forgot Password</h3>
			</div>
			<form action="" method="post" class="login-form">
				<div class="input-container">
					<input type="email" class="input" name="email" placeholder="Email"/>
				</div>
				<input type="submit" name="reset" value="Reset Password" class="button"/>
			</form>
		</div>
	</div>
	<script type="text/javascript">
	$('#background').mouseParallax({ moveFactor: 5 });
	</script>
</body>
</html>