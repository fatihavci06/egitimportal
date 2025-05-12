<?php

class Dbh {

	protected function connect(){
		try {
			$host = "localhost";
			$user = "acaLineIzm0025";
			$pass = "Xav3DW6HeBj!ZRr4EgdY2M";
			$dbName = "lineup25academy00";
			$dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $user, $pass);
			$dbh->exec("set names utf8mb4");
			return $dbh;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage(). "<br/>";
			echo json_encode(["status" => "error", "message" => "Hata: Veritabanı Hatası"]);
			die();
		}
	}
}