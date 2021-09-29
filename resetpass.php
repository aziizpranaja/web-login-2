<?php
// MENGHUBUNGKAN KONEKSI DATABASE
require "fungsi.php";

if (!isset($_GET["code"])) {
    echo "<script>
        alert( 'The link has expired' );
        document.location.href = 'login.php';
    </script>";
}

$code = $_GET["code"];

$getEmailQuery = mysqli_query($db, "SELECT * FROM tb_reset_password WHERE code='$code'");

if (mysqli_num_rows($getEmailQuery) === 0) {
    echo "<script>
        alert( 'The link has expired' );
        document.location.href = 'login.php';
    </script>";
}
?>

<?php
if (isset($_POST["reset"])) {
    // CEK APAKAH BERHASIL DIUBAH ATAU TIDAK
    if (forgot_password($_POST) > 0) {
        echo "<script>
            alert( 'recovery password success !' );
            document.location.href = 'login.php';
        </script>";
    } else {
        echo "<script>
            alert( 'recovery password failed !' );
            document.location.href = 'resetpass.php?code=" . $code . " ';
        </script>";
    }
}
?>

<html>
	<head>
		<title>Change Password</title>
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
				<h3>Change Password</h3>
			</div>
			<form action="" method="post" class="login-form">
				<div class="input-container">
                <input type="text" class="input" name="otp" placeholder="Code-OTP" required>
				</div>
                <div class="input-container">
                <input type="password" class="input" name="password" placeholder="Input Your New Password" required>
				</div>
                <div class="input-container">
                <input type="password" class="input" name="password2" placeholder="Confirm New Password" required>
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