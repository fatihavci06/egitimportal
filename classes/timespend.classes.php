<?php

class timeSpend extends Dbh
{

	public function getTimeSpend($id)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM timespend_lnp WHERE user_id = ?');
		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$time = 0;

		foreach ($getData as $key => $value) {
			$time += $value['timeSpent'];
		}

		return $time;
		$stmt = null;
	}


	public function saniyeyiGoster($toplamSaniye)
	{
		$gun = floor($toplamSaniye / (60 * 60 * 24));
		$toplamSaniye -= $gun * (60 * 60 * 24);
		$saat = floor($toplamSaniye / (60 * 60));
		$toplamSaniye -= $saat * (60 * 60);
		$dakika = floor($toplamSaniye / 60);
		$saniye = $toplamSaniye % 60;

		return sprintf("%d gün, %d saat, %d dakika", $gun, $saat, $dakika);
	}
}
