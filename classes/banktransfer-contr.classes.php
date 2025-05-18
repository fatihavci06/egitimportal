<?php

include_once "../classes/student.classes.php";
include_once "../classes/dateformat.classes.php";
require_once '../classes/Mailer.php';

class AddBankTransferContr extends AddBankTransfer
{

	private $transfer_id;
	private $user_id;

	public function __construct($transfer_id, $user_id)
	{
		$this->transfer_id = $transfer_id;
		$this->user_id = $user_id;
	}



	public function updateTransferDb()
	{

		function gucluSifreUret($uzunluk = 12)
		{
			$karakterler = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
			$karakterUzunlugu = strlen($karakterler);
			$sifre = '';

			$ozelKarakterVar = false;
			$harfVar = false;
			$rakamVar = false;

			for ($i = 0; $i < $uzunluk; $i++) {
				$rastgeleIndex = rand(0, $karakterUzunlugu - 1);
				$rastgeleKarakter = $karakterler[$rastgeleIndex];
				$sifre .= $rastgeleKarakter;

				if (preg_match('/[^a-zA-Z0-9]/', $rastgeleKarakter)) {
					$ozelKarakterVar = true;
				} elseif (ctype_alpha($rastgeleKarakter)) {
					$harfVar = true;
				} elseif (ctype_digit($rastgeleKarakter)) {
					$rakamVar = true;
				}
			}

			// Gerekli karakter türlerinin olduğundan emin ol
			if (!$ozelKarakterVar || !$harfVar || !$rakamVar) {
				return gucluSifreUret($uzunluk); // Eksik karakter varsa yeniden üret
			}

			return $sifre;
		}

		$yeniSifre = gucluSifreUret(15);

		$yeniSifre2 = gucluSifreUret(15);

		$password = password_hash($yeniSifre, PASSWORD_DEFAULT);

		$password2 = password_hash($yeniSifre2, PASSWORD_DEFAULT);

		// Get Transfer Details
		$transferDetails = new Student();
		$transferData = $transferDetails->getMoneyTransferInfo($this->transfer_id);

		// Get Pack Period
		$subscriptionPeriod = $transferDetails->getPackName($transferData[0]['pack_id']);

		$suAn = new DateTime();

		$bitis = $suAn->modify('+' . $subscriptionPeriod[0]['subscription_period'] . ' month');

		$nowTime = date('Y-m-d H:i:s');

		$endTime = $bitis->format('Y-m-d H:i:s');

		$this->addPackagePayment($this->user_id, $transferData[0]['order_no'], $transferData[0]['ip_address'], $transferData[0]['pack_id'], $transferData[0]['amount'], $transferData[0]['coupon']);

		$this->updateTransfer($this->transfer_id);
		$this->updateUser($this->user_id, $nowTime, $endTime, $password);
		$this->updateParent($this->user_id, $password2);
		$userEmail = $this->getuserEmail($this->user_id);
		$parentName = $this->getParentName($this->user_id);
		$mailer = new Mailer();
		$emailResult = $mailer->sendBankTransferApprovedEmail($parentName[0]['name'], $parentName[0]['surname'], $userEmail[0]['email'], $yeniSifre, $yeniSifre2, $parentName[0]['username'], $userEmail[0]['username']);


		echo json_encode(["status" => "success", "message" => 'Ödeme durumu güncellenmiştir.']);
	}
}
