<?php

namespace Rml\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class RmlHandler extends StreamHandler
{
    protected $baseDir;
    private $prevUrl = "";

    /**
     * RmlHandler constructor.
     * @param string $baseDir
     * @param int $level
     * @param bool $bubble
     * @param int|null $filePermission
     * @param bool $useLocking
     */
    public function __construct(
        $baseDir,
        $level = Logger::DEBUG,
        $bubble = true,
        $filePermission = null,
        $useLocking = false
    )
    {
        $this->baseDir = $baseDir;
        $this->url = $this->makeRequiredUrl($level);
        parent::__construct($this->url, $level, $bubble, $filePermission, $useLocking);
    }

    protected function write(array $record)
    {
        $this->url = $this->makeRequiredUrl($record['level']);
        $this->actualizeStream();
        parent::write($record);
    }

    /**
     * Close the stream if new record has a new log level
     */
    private function actualizeStream(){
        if($this->url !== $this->prevUrl){
            $this->close();
            $this->prevUrl = $this->url;
        }
    }

    /**
     * Make filename of required log
     * @param int $level
     * @return string
     */
    private function makeRequiredUrl($level)
    {
        $dirName = date("Y-m-d");
        $path = $this->baseDir . "/" . $dirName;
        $filename = $path . "/" . Logger::getLevelName($level) . ".log";
        return $filename;
    }

}