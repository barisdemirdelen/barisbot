<?php

/**
 * Description of Logger
 *
 * @author baris
 */
class Logger
{

    private $logFileName = "debug.txt";
    private $logFile;
    private $operated;
    private $mode;

    public function writeLine($line)
    {
        if (!$this->operated) {
            if (is_writable($this->logFileName)) {
                $this->logFile = fopen($this->logFileName, $this->mode);
            }
            $this->operated = true;
        }
        fwrite($this->logFile, $line . "<br />\r\n");
    }

    public function readLog()
    {
        if (!$this->operated) {
            $this->logFile = fopen($this->logFileName, $this->mode);
            $this->operated = true;
        }
        return file_get_contents('./' . $this->logFileName);
    }

    public function __construct($fileName, $mode)
    {
        $this->logFileName = $fileName;
        $this->mode = $mode;
        $this->operated = false;
    }

    public function __destruct()
    {
        if ($this->logFile) {
            fclose($this->logFile);
        }
    }

}

?>
