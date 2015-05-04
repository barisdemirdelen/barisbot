<!DOCTYPE html>

<?php
include_once 'DatabaseManager.php';
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Barış Demirdelen</title>
</head>
<body>
<div id="divDebug">
    <?php
    $db = new DatabaseManager();
    $logger = new Logger("debug.log", "r");
    ?>
    <table>
        <tr>
            <td> Cümle sayısı:</td>
            <td> <?php echo $db->getCumleCount(); ?></td>
            <td></td>
        </tr>
        <tr>
            <td> Son cümle:</td>
            <td> <?php echo stripslashes($db->getSonCumle()); ?></td>
            <td></td>
        </tr>
        <tr>
            <td> Öğrenilmiş son cevap:</td>
            <td> <?php echo stripslashes($db->getSonOgrenilmisSoru()); ?></td>
            <td> <?php echo stripslashes($db->getSonOgrenilmisCevap()); ?></td>
        </tr>
        <tr>
            <td> Son günlük:</td>
            <td> <?php echo $logger->readLog(); ?></td>
        </tr>
    </table>
</div>
</body>
</html>
