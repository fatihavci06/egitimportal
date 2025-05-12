<?php

class TestContr
{

	public function testResult($rightAnswers, $studentAnswers): array
	{
		$rightAnswers = explode(":/;", $rightAnswers);
		$studentAnswers = explode(":/;", $studentAnswers);
		$totalQuestions = count($rightAnswers);
		$correct = 0;
    	$resultDetails = [];

		foreach ($rightAnswers as $questionNo => $correctAnswer) {
			$answerOfStudent = $studentAnswers[$questionNo] ?? ""; // Cevap verilmemişse ""
	
			if ($answerOfStudent == $correctAnswer) {
				$correct++;
				$sonucDetaylari[$questionNo] = "Correct";
			} else {
				$sonucDetaylari[$questionNo] = "Wrong";
			}
		}

		$successRate = ($totalQuestions > 0) ? ($correct / $totalQuestions) * 100 : 0;
	
		return [
			"toplam_soru" => $totalQuestions,
			"dogru_sayisi" => $correct,
			"basari_orani" => " %" . number_format($successRate, 2),
			"sonuc_detaylari" => $resultDetails,
		];
	}

	public function controlAnswer($questions, $answers, $correct) {
		
		$questionList = explode(':/;', $questions);
	
		$answerList = explode(':/;', $answers);
	
		$correctListIndexes = explode(':/;', $correct);
	
		$result = ""; 
	
		// Her bir soru için çıktı oluştur
		for ($i = 0; $i < count($questionList); $i++) {
			$result .= "<b>" . ($i + 1) . ". Soru: </b>" . $questionList[$i] . "<br>";
	
			// Cevap şıklarını ayır
			$options = explode('*-*', $answerList[$i]);
			foreach ($options as $key => $option) {
				// Doğru cevabı bul ve sakla
				if ($i < count($correctListIndexes) && chr(65 + $key) == trim($correctListIndexes[$i])) {
					$correctOne = $option;
				}
			}
	
			// Doğru cevabı metin olarak yazdır
			if (isset($correctOne)) {
				$result .= "<b>Doğru Cevap: </b>" . $correctOne . "<br><br>";
				unset($correctOne); // Bir sonraki soru için değişkeni temizle
			} else {
				$result .= "Doğru Cevap Bilgisi Bulunamadı<br><br>";
			}
		}
	
		return $result; // Oluşturulan çıktıyı geri döndür
	}

	public function testDetailsForStudent($questions, $answers, $correct, $studentAnswers) {
		// Soruları ayır
		$questionList = explode(':/;', $questions);
	
		// Cevap şıklarını ayır
		$answerList = explode(':/;', $answers);
	
		// Doğru cevapları ayır (indeks olarak tutuluyor)
		$correctListIndexes = explode(':/;', $correct);

		$studentAnswers = explode(':/;', $studentAnswers);
	
		$result = ""; // Oluşturulacak çıktıyı saklamak için bir değişken
	
		// Her bir soru için döngü
		for ($i = 0; $i < count($questionList); $i++) {
			$result .= ($i + 1) . ") " . $questionList[$i] . "<br>";
	
			// Cevap şıklarını ayır
			$options = explode('*-*', $answerList[$i]);
			foreach ($options as $key => $option) {
				$optionLetter = chr(65 + $key);
				$color = "";
	
				// Kullanıcının cevabını kontrol et ve renklendir
				if (isset($studentAnswers[$i])) {
					if (trim($studentAnswers[$i]) == $optionLetter) {
						if (trim($correctListIndexes[$i]) == $optionLetter) {
							$color = "style='color: green;'"; // Doğru cevap ve kullanıcı seçimi aynı
						} else {
							$color = "style='color: red;'";   // Yanlış cevap
						}
					}
				} elseif (trim($correctListIndexes[$i]) == $optionLetter) {
					$color = "style='font-weight: bold;'"; // Kullanıcı cevap vermediyse doğru cevabı vurgula
				}
	
				$result .= "<span " . $color . ">" . $optionLetter . ")" . $option . " </span> ";
			}
			$result .= "<br>";
	
			// Doğru cevabı yazdır (kullanıcı cevap verdiyse)
			if (isset($studentAnswers[$i]) && trim($studentAnswers[$i]) != trim($correctListIndexes[$i])) {
				$correctAnswerText = "";
				$correctAnswerIndex = trim($correctListIndexes[$i]);
				$correctAnswerKey = ord($correctAnswerIndex) - ord('A'); // A=0, B=1,...
				if (isset($options[$correctAnswerKey])) {
					$correctAnswerText = $options[$correctAnswerKey];
				}
				$result .= "<span style='color: blue;'>Doğru cevap: " . $correctAnswerIndex . ") " . $correctAnswerText . "</span><br>";
			}
			$result .= "<br>";
		}
	
		return $result;
	}
}
