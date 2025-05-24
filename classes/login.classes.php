<?php

class Login extends Dbh
{
	protected $screenSize;
	protected $os;
	protected $browser;
	protected $deviceType;
	protected $deviceModel;
	protected $ip_address;
	protected $attempt_time;

	public function __construct()
	{
		date_default_timezone_set('Europe/Istanbul');
		$this->attempt_time = Date('Y-m-d H:i:s');
		$this->ip_address = $this->getIpAddr();
		$this->deviceModel = $this->deviceModel ?? 'Unknown Model';
		$this->os = $this->getOs();
		$this->browser = $this->getBrowser();
		$this->deviceType = $this->getDeviceType();
	}

	protected function getIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	protected function attemptNumber()
	{
		$this->ip_address = $this->getIpAddr();
		$time = time() - 600;

		$stmt = $this->connect()->prepare('SELECT count(*) as total_count, TryTime FROM loginlogs_lnp WHERE TryTime > ? AND IpAddress=?');

		if (!$stmt->execute(array($time, $this->ip_address))) {
			$stmt = null;
			exit();
		}

		$attemptData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $attemptData;
	}

	protected function getOs()
	{
		return preg_replace('/\s.*$/', '', php_uname('s'));
	}

	protected function getBrowser()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		if (strpos($userAgent, 'OPR/') !== false || strpos($userAgent, 'Opera') !== false) {
			return 'Opera';
		}
		if (strpos($userAgent, 'Edg/') !== false || strpos($userAgent, 'Edge') !== false) {
			return 'Edge';
		}
		if (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edg/') === false && strpos($userAgent, 'OPR/') === false) {
			return 'Chrome';
		}
		if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false && strpos($userAgent, 'Chromium') === false) {
			return 'Safari';
		}
		if (strpos($userAgent, 'Firefox') !== false) {
			return 'Firefox';
		}

		return 'Unknown';
	}

	protected function getDeviceType()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		if (preg_match('/mobile|android|touch|iphone|ipad|phone/i', $userAgent)) {
			return 'Mobile';
		}
		return 'Desktop';
	}

	protected function getUser($email, $password)
	{
		session_start();

		$atemptData = $this->attemptNumber();
		$total_count = $atemptData['total_count'];
		$timeofTry = $atemptData['TryTime'];
		$timeofTry = $timeofTry + 600;
		$timeofTry = $timeofTry - time();
		$this->ip_address = $this->getIpAddr();

		// print($this->attempt_time);
		// die();

		$stmt = $this->connect()->prepare('SELECT password FROM users_lnp WHERE email = ? OR username = ?');

		if (!$stmt->execute([$email, $email])) {
			$stmt = null;
			$_SESSION['err'] = 1;

			if ($total_count == 10) {
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				$this->logSuspiciousAttempt();
			} else {
				$total_count++;
				$rem_attm = 10 - $total_count;

				if ($rem_attm == 0) {
					$this->logSuspiciousAttempt();
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				} else {
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time);
				}
			}

			header("location: ../index");
			exit();
		}

		if ($stmt->rowCount() == 0) {
			$stmt = null;
			$_SESSION['err'] = 2;

			if ($total_count == 10) {
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				$this->logSuspiciousAttempt();
			} else {
				$total_count++;
				$rem_attm = 10 - $total_count;

				if ($rem_attm == 0) {
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
					$this->logSuspiciousAttempt();
				} else {
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time);
				}
			}
			header("location: ../index");
			exit();
		}

		$pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$checkPwd = password_verify($password, $pwdHashed[0]['password']);

		if ($checkPwd == false) {
			$stmt = null;
			$_SESSION['err'] = 3;

			if ($total_count == 10) {
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				$this->logSuspiciousAttempt();
			} else {
				$total_count++;
				$rem_attm = 10 - $total_count;

				if ($rem_attm == 0) {
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
					$this->logSuspiciousAttempt();
				} else {
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time);
				}
			}
			header("location: ../index");
			exit();
		} elseif ($checkPwd == true) {
			if ($total_count > 8) {
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				header("location: ../index");
				exit();
			} else {
				$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE (email = ? OR username = ?) AND password = ? AND active = ?');

				if (!$stmt->execute([$email, $email, $pwdHashed[0]['password'], 1])) {
					$stmt = null;
					$_SESSION['err'] = 1;
					header("location: ../index");
					exit();
				}

				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (count($user) == 0) {
					$stmt = null;
					$_SESSION['err'] = 2;
					header("location: ../index");
					exit();
				}

				$this->delAttempt($this->ip_address);

				$_SESSION['email'] = $user[0]["email"];
				$_SESSION['id'] = $user[0]["id"];
				$_SESSION['role'] = $user[0]["role"];
				$_SESSION['photo'] = $user[0]["photo"];
				$_SESSION['class_id'] = $user[0]["class_id"];
				$_SESSION['school_id'] = $user[0]["school_id"];
				$_SESSION['teacher_id'] = $user[0]["teacher_id"];
				$_SESSION['parent_id'] = $user[0]["parent_id"];
				$_SESSION['lesson_id'] = $user[0]["lesson_id"];
				$_SESSION['name'] = $user[0]["name"] . ' ' . $user[0]["surname"];

				$stmt = null;

				$this->loginInfo($_SESSION['id']);
			}
		}

		$stmt = null;
	}

	protected function addAttempt($try_time)
	{
		$this->ip_address = $this->getIpAddr();
		$stmt = $this->connect()->prepare('INSERT INTO loginlogs_lnp SET TryTime=?, IpAddress=?');

		if (!$stmt->execute([$try_time, $this->ip_address])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$stmt = null;
	}

	protected function delAttempt()
	{

		$stmt = $this->connect()->prepare('DELETE FROM loginlogs_lnp WHERE IpAddress=?');

		if (!$stmt->execute([$this->ip_address])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$stmt = null;
	}


	protected function logSuspiciousAttempt()
	{
		$stmt = $this->connect()->prepare('INSERT INTO suspicious_attempts_lnp SET 
		resolution = :resolution,
        deviceOs = :deviceOs,
        browser = :browser,
        deviceType = :deviceType,
        deviceModel = :deviceModel,
		ipAddress = :ip_address,
		attempt_time = :attempt_time');


		$stmt->bindValue(':resolution', $this->screenSize);
		$stmt->bindValue(':deviceOs', $this->os);
		$stmt->bindValue(':browser', $this->browser);
		$stmt->bindValue(':deviceType', $this->deviceType);
		$stmt->bindValue(':deviceModel', $this->deviceModel);
		$stmt->bindValue(':ip_address', $this->ip_address);
		$stmt->bindValue(':attempt_time', $this->attempt_time);

		if (!$stmt->execute()) {
			$stmt = null;
			exit();
		}
	}


	protected function loginInfo($userId)
	{

		$sessionId = rand(100000000, 2000000000);

		$conn = $this->connect();

		$stmt = $conn->prepare(
			'INSERT INTO logininfo_lnp SET 
		user_id = :user_id,
		session_id = :session_id,
		resolution = :resolution,
        deviceOs = :deviceOs,
        browser = :browser,
        deviceType = :deviceType,
        deviceModel = :deviceModel,
		ipAddress = :ip_address,
		loginTime = :loginTime'
		);

		$stmt->bindValue(':user_id', $userId);
		$stmt->bindValue(':session_id', $sessionId);
		$stmt->bindValue(':resolution', $this->screenSize);
		$stmt->bindValue(':deviceOs', $this->os);
		$stmt->bindValue(':browser', $this->browser);
		$stmt->bindValue(':deviceType', $this->deviceType);
		$stmt->bindValue(':deviceModel', $this->deviceModel);
		$stmt->bindValue(':ip_address', $this->ip_address);
		$stmt->bindValue(':loginTime', $this->attempt_time);

		if (!$stmt->execute()) {
			$stmt = null;
			exit();
		}

		$_SESSION['login_id'] = $conn->lastInsertId();
	}
}
