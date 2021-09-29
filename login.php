<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require 'vendor/phpmailer/phpmailer/src/Exception.php';
	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require 'vendor/phpmailer/phpmailer/src/SMTP.php';
	
	session_start();

	require 'fungsi.php';
	

	
	if (isset($_SESSION["mahasiswa"])) {
		header('location: mahasiswa.php');
		exit;
	} elseif (isset($_SESSION["dosen"])) {
		header('location: dosen.php');
		exit;
	} elseif (isset($_SESSION["staff"])) {
		header('location: dashboard.php');
		exit;
	}


	
	if( isset($_POST["daftar"]))
	{
		if(registrasi($_POST) > 0)
		{
			global $conn;
			$emailTo = $_POST["email"];

			$result = mysqli_query($db, "SELECT * FROM user WHERE email = '$emailTo' ");
			if(mysqli_num_rows($result) === 1){
				$row = mysqli_fetch_assoc($result);
				$code = uniqid();

				$mail = new PHPMailer(true);

				try {
				$mail->SMTPDebug = 0;
                $mail->isSMTP();
                //Server settings
                $mail->isSMTP();                                          //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
                $mail->Username   = 'aziizpranaja4@gmail.com';     		  //SMTP username
                $mail->Password   = 'MAp200102';               			  //SMTP password
                $mail->SMTPSecure = 'ssl';                                //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 465;                                  //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

				//Recipients
                $mail->setFrom('aziizpranaja@gmail.com', 'Login-Aziiz');
                $mail->addAddress($emailTo);                     //Add a recipient
                $mail->addReplyTo('no-reply@gmail.com', 'No Reply');

				//Content
                $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/verification.php?code=$code&email=$emailTo";
                $mail->isHTML(true);                              //Set email format to HTML
                $mail->Subject = 'Your Verification Account Link';
                $mail->Body    = "<h1>Please click this link to verification your account</h1><br>
                    Click <a href='$url'>This Link</a> to verification your account";
                $mail->AltBody = 'Welcome to our site.';

                $mail->send();
				}catch (Exception $e) {
					echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}
			echo "<script>
					alert('akun berhasil dibuat');
					document.location.href = 'login.php';  
				</script>";
		}else
		{
			mysqli_error($db);
		}
	}
	if(isset($_POST["login"]))
    {
		try {
			global $db;
			$email = $_POST["email"];
			$password = $_POST["password"];
			$result = mysqli_query($db, "SELECT * FROM user WHERE email = '$email'") or die(mysqli_error($db));

			if(mysqli_num_rows($result) === 1)
			{
				$row = mysqli_fetch_assoc($result);

				if (password_verify($password, $row["password"]))
				{
					if ($row["verification"] == "yes") 
					{
						$_SESSION["id_user"] = $row["id_user"];

						if ($row["level"] == "mahasiswa") {
							// SET SESSION FREE
							$_SESSION["mahasiswa"] = true;

							// QUERY LOG ACTIVITY
							$userLog = $row["id_user"];
							$timeLog = date("Y-m-d H:i:s");
							$query_log = "INSERT INTO tb_log(id_user, time_log)	VALUES('$userLog', '$timeLog')";

							mysqli_query($db, $query_log) or die(mysqli_error($db));

							// Cek Remember
							// if (isset($_POST['remember_me'])) {
							// 	// Buat Cookie
							// 	setcookie('id_user', $row['id_user'], time() + 86400, '/');
							// 	setcookie('lodon', hash('sha256', $row['username']), time() + 86400, '/');
							// }

							header('location: mahasiswa.php');
						}
						else if ($row["level"] == "dosen") {
							// SET SESSION FREE
							$_SESSION["dosen"] = true;

							// QUERY LOG ACTIVITY
							$userLog = $row["id_user"];
							$timeLog = date("Y-m-d H:i:s");
							$query_log = "INSERT INTO tb_log(id_user, time_log)	VALUES('$userLog', '$timeLog')";

							mysqli_query($db, $query_log) or die(mysqli_error($db));

							// Cek Remember
							// if (isset($_POST['remember_me'])) {
							// 	// Buat Cookie
							// 	setcookie('id_user', $row['id_user'], time() + 86400, '/');
							// 	setcookie('lodon', hash('sha256', $row['username']), time() + 86400, '/');
							// }

							header('location: dosen.php');
						}
						else if ($row["level"] == "staff") {
							// SET SESSION FREE
							$_SESSION["staff"] = true;

							// QUERY LOG ACTIVITY
							$userLog = $row["id_user"];
							$timeLog = date("Y-m-d H:i:s");
							$query_log = "INSERT INTO tb_log(id_user, time_log)	VALUES('$userLog', '$timeLog')";

							mysqli_query($db, $query_log) or die(mysqli_error($db));

							// Cek Remember
							// if (isset($_POST['remember_me'])) {
							// 	// Buat Cookie
							// 	setcookie('id_user', $row['id_user'], time() + 86400, '/');
							// 	setcookie('lodon', hash('sha256', $row['username']), time() + 86400, '/');
							// }

							header('location: dashboard.php');
						}else {
							throw new Exception("Level akses account anda tidak terdaftar !!");
						}
					}else {
                    	throw new Exception("Anda belum melakukan verifikasi account. Silahkan cek email anda !!");
                	}
				}else {
					throw new Exception("Password yang anda masukkan salah !!");
				}
			}else {
				throw new Exception("Email yang anda masukkan tidak terdaftar !!");
			}
			exit;
		}catch (Exception $error) {
			echo "<script>
			alert ('" . $error->getMessage() . "');
				document.location.href = 'login.php';
			</script>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="login.php" method="post">
			<h1>Create Account</h1><br>
			<span>or use your email for registration</span><br>
			<input type="text" placeholder="Name" name="nama" id="nama" required />
			<input type="email" placeholder="Email" name="email" id="email" required />
			<input type="password" placeholder="Password" name="password" id="password" required />
			<input type="password" placeholder="Konfirmasi Password" name="password2" id="password2" required />
			<input type="number" placeholder="No.Telp" name="telp" id="telp" required />
			<select class="form-control" id="exampleFormControlSelect1" name="level">
				<option value="dosen">Dosen</option>
				<option value="mahasiswa">Mahasiswa</option>
				<option value="staff">Staff</option>
			</select><br>
			<button type="submit" name="daftar">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<?php if(isset($error)) : ?>
        <p style="color: red; font-style: italic; text-align: center;">username / password error</p>
    <?php endif; ?>
		<form action="" method="post">
			<h1>Sign in</h1><br>
			<span>or use your account</span><br>
			<input type="email" placeholder="Email" name="email" required />
			<input type="password" placeholder="Password" name="password" required /><br>
			<p>Lupa Password? <a href="forgotpassword.php">Klik Disini</a></p><br>	
			<button name="login" type="submit">Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
</body>

<script  >
	const signUpButton = document.getElementById('signUp');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');

	signUpButton.addEventListener('click', () => {
		container.classList.add("right-panel-active");
	});

	signInButton.addEventListener('click', () => {
		container.classList.remove("right-panel-active");
	});
</script>

</html>