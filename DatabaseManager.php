<?php

include_once 'Logger.php';

/**
 * Description of DatabaseManager
 *
 * @author baris
 */
class DatabaseManager
{

    private $ip = '127.0.0.1';
    private $username = 'barisdem_barisd';
    private $password = 'barbar2B';
    private $dbname = 'barisdem_bardb';
    private $connection;
    private $logger;

    public function cevapVerildi($soruid, $cevapid)
    {
        $id = 0;
        if ($this->isCumlelerContainsId($soruid) && $this->isCumlelerContainsId($cevapid)) {
            $stmt = mysqli_prepare($this->connection, "INSERT INTO verildi VALUES (0,?,?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "dd", $soruid, $cevapid);
                mysqli_stmt_execute($stmt);
                $id = mysqli_insert_id($this->connection);
                mysqli_stmt_close($stmt);
            }
        }
        return $id;
    }

    public function insertCumle($cumle)
    {
        $cumle = $this->prepareQuery($cumle);
        $id = 0;
        $stmt2 = mysqli_prepare($this->connection, "INSERT INTO cumleler VALUES (0,?)");
        if ($stmt2) {
            mysqli_stmt_bind_param($stmt2, "s", $cumle);
            mysqli_stmt_execute($stmt2);
            $id = mysqli_insert_id($this->connection);
            mysqli_stmt_close($stmt2);
        }
        return $id;
    }

    public function getCumleId($cumle)
    {
        $cumle = $this->prepareQuery($cumle);
        $id = 0;
        $stmt = mysqli_prepare($this->connection, "SELECT id FROM cumleler where cumle=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $cumle);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1);
            if (mysqli_stmt_fetch($stmt)) {
                $id = $id1;
            }
            mysqli_stmt_close($stmt);
        }
        return $id;
    }

    public function getCumleById($cumleId)
    {
        $cumle = "";
        $stmt = mysqli_prepare($this->connection, "SELECT cumle FROM cumleler where id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "d", $cumleId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cumle1);
            if (mysqli_stmt_fetch($stmt)) {
                $cumle1 = stripslashes($cumle1);
                $cumle = $cumle1;
            }
            mysqli_stmt_close($stmt);
        }
        return $cumleId . ' ' . $cumle;
    }

    public function getKelimeLikeCount($kelime)
    {
        $kelime = $this->prepareQuery($kelime);
        $this->logger->writeLine("getKelimeLikeCount gets: " . $kelime);
        $sayi = -1;
        $stmt = mysqli_prepare($this->connection, "SELECT COUNT(*) FROM cumleler WHERE cumle LIKE '%" . $kelime . "%'");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $sayi2);
            if (mysqli_stmt_fetch($stmt)) {
                $sayi = $sayi2;
            }
            mysqli_stmt_close($stmt);
        }
        $this->logger->writeLine("getKelimeLikeCount returns: " . $sayi);
        return $sayi;
    }

    public function isCumlelerContainsId($cumleid)
    {
        $contains = false;
        $stmt = mysqli_prepare($this->connection, "SELECT id FROM cumleler where id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "d", $cumleid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1);
            if (mysqli_stmt_fetch($stmt)) {
                $contains = true;
            }
            mysqli_stmt_close($stmt);
        }
        return $contains;
    }

    public function isCumlelerContainsCumle($cumle)
    {
        $cumle = $this->prepareQuery($cumle);
        $contains = false;
        $stmt = mysqli_prepare($this->connection, "SELECT cumle FROM cumleler where cumle=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $cumle);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1);
            if (mysqli_stmt_fetch($stmt)) {
                $contains = true;
            }
            mysqli_stmt_close($stmt);
        }
        return $contains;
    }

    public function getRandSoru()
    {
        $this->logger->writeLine("getRandSoru");
        $cevap = "";
        $stmt = mysqli_prepare($this->connection, "SELECT id,cumle FROM cumleler ORDER BY RAND() LIMIT 1");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1, $cevap1);
            if (mysqli_stmt_fetch($stmt)) {
                $cevap1 = stripslashes($cevap1);
                $cevap = $id1 . " " . $cevap1;
            }
            mysqli_stmt_close($stmt);
        }

        $this->logger->writeLine("getRandSoru returns: " . $cevap);
        return $cevap;
    }

    public function getRandCevaplanmamisSoru()
    {
        $this->logger->writeLine("getRandCevaplanmamisSoru");
        $cevap = "";
        $stmt = mysqli_prepare($this->connection, "SELECT cumleler.id,cumleler.cumle FROM cumleler WHERE cumleler.id NOT IN 
            ( SELECT soruid FROM verildi WHERE soruid = cumleler.id ) ORDER BY RAND() LIMIT 1");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1, $cevap1);
            if (mysqli_stmt_fetch($stmt)) {
                $cevap1 = stripslashes($cevap1);
                $cevap = $id1 . " " . $cevap1;
            }
            mysqli_stmt_close($stmt);
        }
        $this->logger->writeLine("getRandCevaplanmamisSoru returns: " . $cevap);
        return $cevap;
    }

    public function getCevaplarOfKelime($kelime)
    {
        $kelime = $this->prepareQuery($kelime);
        $this->logger->writeLine("getCevaplarOfKelime gets: " . $kelime);
        $cevaplar = array();
        $stmt = mysqli_prepare($this->connection, "SELECT cevapid FROM verildi WHERE soruid IN (SELECT id FROM cumleler WHERE cumle= '" . $kelime . "' OR cumle LIKE '% " . $kelime . " %'  OR cumle LIKE '" . $kelime . " %'  OR cumle LIKE '% " . $kelime . "')");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cevap1);
            while (mysqli_stmt_fetch($stmt)) {
                if ($cevap1 != 0) {
                    $cevap1 = stripslashes($cevap1);
                    $cevaplar[] = $cevap1;
                }
            }
            mysqli_stmt_close($stmt);
        }
        $this->logger->writeLine("getCevaplarOfKelime returns: " . json_encode($cevaplar));
        return $cevaplar;
    }

    public function getRandCevapOfSoruId($soruid)
    {
        $this->logger->writeLine("getRandCevapOfSoruId gets: " . $soruid);
        $cevap = "";
        $stmt = mysqli_prepare($this->connection, "SELECT id,cumle FROM cumleler WHERE id IN 
            ( SELECT cevapid FROM verildi WHERE soruid = ? ) ORDER BY RAND() LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "d", $soruid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id1, $cevap1);
            if (mysqli_stmt_fetch($stmt)) {
                $cevap1 = stripslashes($cevap1);
                $cevap = $id1 . " " . $cevap1;
            }
            mysqli_stmt_close($stmt);
        }

        $this->logger->writeLine("getRandCevapOfSoruId returns: " . $cevap);
        return $cevap;
    }

    public function isVerildiContainsSoruId($soruid)
    {
        $contains = false;
        $stmt = mysqli_prepare($this->connection, "SELECT soruid FROM verildi where soruid=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "d", $soruid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $soruid1);
            if (mysqli_stmt_fetch($stmt)) {
                $contains = true;
            }
            mysqli_stmt_close($stmt);
        }
        return $contains;
    }

    public function getCumleCount()
    {
        $sayi = -1;
        $stmt = mysqli_prepare($this->connection, "SELECT COUNT(*) FROM cumleler");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $sayi1);
            if (mysqli_stmt_fetch($stmt)) {
                $sayi = $sayi1;
            }
            mysqli_stmt_close($stmt);
        }
        return $sayi;
    }

    public function getSonCumle()
    {
        $cumle = "";
        $stmt = mysqli_prepare($this->connection, "SELECT cumle FROM cumleler ORDER BY id DESC LIMIT 1");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cumle1);
            if (mysqli_stmt_fetch($stmt)) {
                $cumle1 = stripslashes($cumle1);
                $cumle = $cumle1;
            }
            mysqli_stmt_close($stmt);
        }
        return $cumle;
    }

    public function getSonOgrenilmisSoru()
    {
        $cumle = "";
        $stmt = mysqli_prepare($this->connection, "SELECT cumleler.cumle FROM 
            (SELECT soruid FROM verildi ORDER BY id DESC LIMIT 1) AS A 
            JOIN cumleler ON cumleler.id = A.soruid ORDER BY A.soruid");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cumle1);
            if (mysqli_stmt_fetch($stmt)) {
                $cumle1 = stripslashes($cumle1);
                $cumle = $cumle1;
            }
            mysqli_stmt_close($stmt);
        }
        return $cumle;
    }

    public function getSonOgrenilmisCevap()
    {
        $cumle = "";
        $stmt = mysqli_prepare($this->connection, "SELECT cumleler.cumle FROM 
            (SELECT cevapid FROM verildi ORDER BY id DESC LIMIT 1) AS A 
            JOIN cumleler ON cumleler.id = A.cevapid ORDER BY A.cevapid");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cumle1);
            if (mysqli_stmt_fetch($stmt)) {
                $cumle1 = stripslashes($cumle1);
                $cumle = $cumle1;
            }
            mysqli_stmt_close($stmt);
        }
        return $cumle;
    }

    function prepareQuery($string)
    {
        $this->logger->writeLine("prepareQuery gets: " . $string);
        $string = stripslashes($string);
        $string = mb_strtolower($string, "UTF-8");
        $this->logger->writeLine("prepareQuery returns: " . $string);
        return $string;
    }

    public function __construct()
    {
        $this->logger = new Logger("debug.log", "w");
        $this->connection = mysqli_connect($this->ip, $this->username, $this->password, $this->dbname);
        if (!$this->connection) {
            exit('DatabaseManager: Could not connect: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, "utf8");
    }

    public function __destruct()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
        $this->connection = null;
    }

}

?>
