<?php

class Login extends Dbh {

	protected function getIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ipAddr=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ipAddr=$_SERVER['REMOTE_ADDR'];
		}
			return $ipAddr;
	}

	protected function attemptNumber(){

		$time = time()-600; 
		$ip_address = $this->getIpAddr(); 

		$stmt = $this->connect()->prepare('SELECT count(*) as total_count, TryTime FROM loginlogs_lnp WHERE TryTime > ? AND IpAddress=?');

		if(!$stmt->execute(array($time, $ip_address))){
			$stmt = null;
			exit();
		}
	
		$attemptData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $attemptData;

	}

	protected function getUser($email, $password){
		session_start();

		$atemptData = $this->attemptNumber();
		$total_count = $atemptData['total_count'];
		$timeofTry = $atemptData['TryTime'];
		$timeofTry = $timeofTry+600;
		$timeofTry = $timeofTry - time();
		$ip_address = $this->getIpAddr();

		$stmt = $this->connect()->prepare('SELECT password FROM users_lnp WHERE email = ? OR username = ?');

		if(!$stmt->execute([$email, $email])){
			$stmt = null;
			$_SESSION['err'] = 1;
			
			if($total_count==10){
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
			}else{
				$total_count++;
				$rem_attm = 10-$total_count;
				
				if($rem_attm==0){
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				}else{
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time, $ip_address);
				}
			}

			header("location: ../index");
			exit();
		}

		if($stmt->rowCount() == 0){
			$stmt = null;
			$_SESSION['err'] = 2;
			
			if($total_count==10){
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
			}else{
				$total_count++;
				$rem_attm = 10 - $total_count;
				
				if($rem_attm==0){
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				}else{
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time, $ip_address);
				}
			}
			header("location: ../index");
			exit();
		}

		$pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$checkPwd = password_verify($password, $pwdHashed[0]['password']);

		if($checkPwd == false){
			$stmt = null;
			$_SESSION['err'] = 3;
			
			if($total_count==10){
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
			}else{
				$total_count++;
				$rem_attm = 10-$total_count;
				
				if($rem_attm==0){
					$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				}else{
					$_SESSION['msg'] = $rem_attm . ' deneme hakkınız kaldı. ';

					$try_time = time();
					$this->addAttempt($try_time, $ip_address);
				}
			}
			header("location: ../index");
			exit();
		}
		elseif($checkPwd == true){
			if($total_count>8){
				$_SESSION['msg'] = 'Çok fazla deneme yaptınız. Lütfen ' . date("i:s", $timeofTry) . ' dakika sonra tekrar deneyin.';
				header("location: ../index");
				exit();
			}else{
			$stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE (email = ? OR username = ?) AND password = ? AND active = ?');

			if(!$stmt->execute([$email, $email,$pwdHashed[0]['password'], 1])){
			$stmt = null;
			$_SESSION['err'] = 1;
			header("location: ../index");
			exit();
			}
			
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if(count($user) == 0){
			$stmt = null;
			$_SESSION['err'] = 2;
			header("location: ../index");
			exit();
			}

			$this->delAttempt($ip_address);

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
			}
		}
		
		$stmt = null;
	}

	protected function addAttempt($try_time, $ip_address){
		
		$stmt = $this->connect()->prepare('INSERT INTO loginlogs_lnp SET TryTime=?, IpAddress=?');

		if(!$stmt->execute([$try_time, $ip_address])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$stmt = null;

	}

	protected function delAttempt($ip_address){
		
		$stmt = $this->connect()->prepare('DELETE FROM loginlogs_lnp WHERE IpAddress=?');

		if(!$stmt->execute([$ip_address])){
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$stmt = null;

	}

}



