<?php
$meyveler = ["elma", "armut", "kiraz", "elma", "muz"];
$kaldirilacak_meyve = "elma";

$meyveler2 = array_filter($meyveler, function ($meyve) use ($kaldirilacak_meyve) {
    return $meyve !== $kaldirilacak_meyve;
});

print_r($meyveler);

$newRole = implode(",", $meyveler2);

print_r($newRole);
// Çıktı: Array ( [1] => armut [2] => kiraz [4] => muz )
?>