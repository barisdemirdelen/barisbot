<?php

include_once 'DatabaseManager.php';

$message = $_GET['m'];

$db = new DatabaseManager();

$words = explode(" ", $message);

$cevapSayilari = array();
foreach ($words as $word) {
    if ($word != "") {
        $cevaplar = $db->getCevaplarOfKelime($word);
        foreach ($cevaplar as $cevap) {
            if (!isset($cevapSayilari[$cevap])) {
                $cevapSayilari[$cevap] = 0;
            }
            $cevapSayilari[$cevap]++;
        }

    }
}

$maxCount = 0;
$maxCevaps = array();
foreach ($cevapSayilari as $cevap => $count) {
    if ($cevap > 0) {
        if ($count > $maxCount) {
            $random = rand(0, 2);
            if ($random < 2) {
                $maxCevaps = array();
            }
        }
        if ($count >= $maxCount || (rand(0, 5) == 5)) {
            $maxCevaps[] = $cevap;
            if ($count > $maxCount) {
                $maxCount = $count;
            }
        }
    }
}

$cevapCumleleri = array();
foreach ($cevapSayilari as $cevap => $count) {
    $cevapCumleleri[$cevap] = $db->getCumleById($cevap);
}
echo json_encode($cevapSayilari) . '<br/><br/>';
echo json_encode($cevapCumleleri) . '<br/><br/>';
echo json_encode($maxCevaps) . '<br/><br/>';
echo $db->getCumleById($maxCevaps[array_rand($maxCevaps)]);


?>