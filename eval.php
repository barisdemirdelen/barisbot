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
$maxCevap = 0;
foreach ($cevapSayilari as $cevap => $count) {
    if ($cevap > 0) {
        if ($count > $maxCount || ($count === $maxCount && rand(0, 2) == 1)) {
            $maxCevap = $cevap;
            $maxCount = $count;

        }
    }
}

$cevapCumleleri = array();
foreach ($cevapSayilari as $cevap => $count) {
    $cevapCumleleri[$cevap] = $db->getCumleById($cevap);
}
echo json_encode($cevapCumleleri) . '<br/>';
echo $db->getCumleById($maxCevap);


?>