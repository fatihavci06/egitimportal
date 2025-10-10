<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	// Grabbing the data
	$email = $_POST['email'];
	$password = $_POST['password'];
	$screenSize = $_POST['screen_size'];
	$deviceModel = $_POST['device_model'];
	$deviceType = $_POST['device_type'];
	$browser = $_POST['browser'];
	$os = $_POST['device_os'];
	$remember = isset($_POST['remember']) ? true : false; // ✅ Beni hatırla seçili mi

	// Classes
	include_once "../classes/dbh.classes.php";
	include_once "../classes/login.classes.php";
	include_once "../classes/login-contr.classes.php";

	$login = new LoginContr($email, $password, $screenSize, $deviceModel, $deviceType, $browser, $os);

	// Running error handlers and user login
	$login->loginUser();

	// Eğer login başarılı olduysa (session açılmıştır)
	if (isset($_SESSION['role'])) {

		// ✅ Beni Hatırla işlemi
		if ($remember) {
			$token = bin2hex(random_bytes(32));
			$hashedToken = hash('sha256', $token);

			$dbh = new Dbh();
			$pdo = $dbh->connect();
			$stmt = $pdo->prepare("UPDATE users_lnp SET remember_token = ? WHERE id = ?");
			$stmt->execute([$hashedToken, $_SESSION['id']]);

			// Cookie’ye **hash’lenmemiş token** koyuyoruz
			setcookie("remember_me", $token, time() + (86400 * 30), "/", "", false, true);
		}


		// Yönlendirme
		if (
			$_SESSION['role'] == 1 or $_SESSION['role'] == 2 or $_SESSION['role'] == 3 or
			$_SESSION['role'] == 4 or $_SESSION['role'] == 5 or $_SESSION['role'] == 6 or
			$_SESSION['role'] == 7 or $_SESSION['role'] == 8 or $_SESSION['role'] == 9 or
			$_SESSION['role'] == 10 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002 or
			$_SESSION['role'] == 10005 or $_SESSION['role'] == 20001
		) {
			header("location: ../dashboard");
			exit;
		}
	}
}
