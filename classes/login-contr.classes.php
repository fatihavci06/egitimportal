<?php

class LoginContr extends Login
{

	private $email;
	private $password;
	protected $screenSize;
	protected $deviceModel;
	protected $deviceType;
	protected $browser;
	protected $os;

	public function __construct($email, $password, $screenSize, $deviceModel, $deviceType, $browser, $os)
	{
		//login.classes içindeki __construct metodunu ezmemesi için
		parent::__construct();
		$this->email = $email;
		$this->password = $password;
		$this->screenSize = $screenSize;
		$this->deviceModel = $deviceModel;
		$this->deviceType = $deviceType;
		$this->browser = $browser;
		$this->os = $os;
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
