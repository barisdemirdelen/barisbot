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

if ($answer == '') {
    echo $return;
    return;
}

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

    if ($maxCount > 0) {
        $return = $db->getCumleById($maxCevaps[array_rand($maxCevaps)]);

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
$return = stripslashes($return);
echo $return;
return;
?>
