<?php

class LoginContr extends Login
{

	private $email;
	private $password;

	public function __construct($email, $password, $screenSize)
	{
		//login.classes içindeki __construct metodunu ezmemesi için
		parent::__construct();
		$this->email = $email;
		$this->password = $password;
		$this->screenSize = $screenSize;
		
	}

	public function loginUser()
	{
			
		if ($this->emptyInput() == false) {
			// echo "Empty input!";
			header("location: ../index.php?error=emptyinput");
			exit();
		}

		$this->getUser($this->email, $this->password);
	}

	private function emptyInput()
	{
		$result = "";
		if (empty($this->email) || empty($this->password)) {
			$result = false;
		} else {
			$result = true;
		}
		return $result;
	}
}
