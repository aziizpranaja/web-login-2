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
$email = $_GET["email"];

$getEmailQuery = mysqli_query($db, "SELECT * FROM user WHERE email='$email'");

if (mysqli_num_rows($getEmailQuery) === 0) {
    echo "<script>
        alert( 'The link has expired' );
        document.location.href = 'login.php';
    </script>";
}

if (verification_account($email) > 0) {
    echo "<script>
        alert ('Email anda sudah terverifikasi !!');
        document.location.href = 'login.php';
    </script>";
} else {
    echo "<script>
        alert ('Error. Silahkan coba lagi !!');
        document.location.href = 'login.php';
    </script>";
}
