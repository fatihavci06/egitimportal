<?php

class Dbh
{
	protected $host;
	protected $user;
	protected $pass;
	protected $dbName;

	public function __construct()
	{
		$this->host = "localhost";
		$this->user = "root";
		$this->pass = "";
		$this->dbName = "lineup25academy00";
	}

	public function connect()
	{
		try {
			$dbh = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->user, $this->pass);
			$dbh->exec("set names utf8mb4");
			return $dbh;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			echo json_encode(["status" => "error", "message" => "Hata: Veritabanı Hatası"]);
			die();
		}
	}
}
