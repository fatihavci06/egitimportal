<?php

class CreatePassword
{

	public function gucluSifreUret($uzunluk = 12)
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
}
