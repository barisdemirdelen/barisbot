<html>
<body>
<form action="learn.php" method="post">
    <p>Soru: <input type="text" name="soru"/></p>

    <p>Cevap: <input type="text" name="cevap"/></p>
    <input type="submit"/>
</form>


<?php
include_once 'DatabaseManager.php';
if (isset($_POST["soru"])) {
    $soru = $_POST["soru"];
    $cevap = $_POST["cevap"];
    if($soru!="" && $cevap!="") {


        $db = new DatabaseManager();
        $soru = $db->prepareQuery($soru);
        $cevap = $db->prepareQuery($cevap);

        $soruId = 0;
        if ($db->isCumlelerContainsCumle($soru)) {
            $soruId = $db->getCumleId($soru);
        } else {
            $soruId = $db->insertCumle($soru);
        }

        $cevapId = 0;
        if ($db->isCumlelerContainsCumle($cevap)) {
            $cevapId = $db->getCumleId($cevap);
        } else {
            $cevapId = $db->insertCumle($cevap);
        }

        if($soruId && $cevapId) {
            $db->cevapVerildi($soruId, $cevapId);
        }

        echo '<p> Got it! </p>';
        echo '<p>-' . $soru . '</p>';
        echo '<p>-' . $cevap . '</p>';
    } else {
        echo '<p> That was a mistake. </p>';
    }
}
?>

</body>

</html>

