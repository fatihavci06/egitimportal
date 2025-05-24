<?php

class Sms extends Dbh
{
	protected $username;
	protected $password;
	protected $msgHeader;

	public function __construct()
	{
		$stmt = $this->connect()->prepare('SELECT * FROM sms_settings_lnp LIMIT 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->username = $data['username'];
		$this->password = $data['password'];
		$this->msgHeader = $data['msgheader'];
	}

	public function sendSms(string $phoneNumber, string $message): string
	{
		$payload = [
			"msgheader" => $this->msgHeader,
			"messages" => [
				[
					"msg" => $message,
					"no" => $phoneNumber
				]
			],
			"encoding" => "TR"
		];

		$headers = [
			'Content-Type: application/json',
			'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password)
		];

		return $this->makeRequest(
			'https://api.netgsm.com.tr/sms/rest/v2/send',
			$payload,
			$headers
		);
	}

	public function getBalance()
	{
		$payload = [
			'usercode' => $this->username,
			'password' => $this->password,
			'stip'     => 1
		];

		$headers = ['Content-Type: application/json'];

		$response = $this->makeRequest(
			'https://api.netgsm.com.tr/balance',
			$payload,
			$headers
		);

		$decoded = json_decode($response, true);

		if (!isset($decoded['balance']) || !is_array($decoded['balance'])) {
			throw new \Exception("Geçersiz API yanıtı");
		}

		foreach ($decoded['balance'] as $item) {
			if (isset($item['balance_name']) && $item['balance_name'] === 'Adet SMS') {
				return (int)$item['amount'].' '.$item['balance_name'];
			}
		}

		// Eğer "Adet SMS" bulunamazsa null döndür
		return null;
	}

	protected function makeRequest(string $url, array $payload, array $headers): string
	{
		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($payload),
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => 30
		]);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			$errorMessage = curl_error($ch);
			curl_close($ch);
			throw new \Exception("cURL Error: " . $errorMessage);
		}

		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpStatusCode !== 200) {
			throw new \Exception("HTTP Error: " . $httpStatusCode . " Response: " . $response);
		}

		return $response;
	}
}
