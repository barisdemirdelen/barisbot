<?php

include_once 'DatabaseManager.php';

if (!isset($_GET['sent']) || !isset($_GET['message'])) {
    header('location: naber.shtml');
    exit();
}
$lastQuestıonId = $_GET['sent'];
$answer = $_GET['message'];

$return = "0 buna bir cevabım yok açıkçası.";
$found = false;

$db = new DatabaseManager();
$answer = $db->prepareQuery($answer);

$answerId = 0;
if ($db->isCumlelerContainsCumle($answer)) {
    $answerId = $db->getCumleId($answer);

    /*if ($db->isVerildiContainsSoruId($answerId)) {
        $tempSentence = $db->getRandCevapOfSoruId($answerId);
        if ($tempSentence != "") {
            $return = $tempSentence;
            $found = true;
        }
    }*/
} else {
    $answerId = $db->insertCumle($answer);
}

if ($answerId) {
    $db->cevapVerildi($lastQuestıonId, $answerId);
}

if (!$found) {
    $words = explode(" ", $answer);

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
    foreach ($cevaplar as $cevap => $count) {
        if ($cevap > 0) {
            if ($count > $maxCount || ($count === $maxCount && rand(0, 2) == 1)) {
                $maxCevap = $cevap;
                $maxCount = $count;

            }
        }
    }

    if ($maxCount > 0) {
        $return = $db->getCumleById($maxCevap);

        $found = true;

    }

}

if (!$found) {
    $tempSentence = $db->getRandCevaplanmamisSoru();
    if ($tempSentence != "") {
        $return = $tempSentence;
        $found = true;
    }
}

if (!$found) {
    $tempSentence = $db->getRandSoru();
    if ($tempSentence != "") {
        $return = $tempSentence;
        $found = true;
    }
}

echo $return;
return;
?>
