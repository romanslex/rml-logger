<?php

namespace Rml\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class RmlHandler extends StreamHandler
{
    protected $baseDir;
    private $prevUrl = "";

    public function __construct(
        string $baseDir,
        int $level = Logger::DEBUG,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
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
    private function makeRequiredUrl(int $level): string
    {
        $dirName = date("Y-m-d");
        $path = $this->baseDir . "/" . $dirName;
        $filename = $path . "/" . Logger::getLevelName($level) . ".log";
        return $filename;
    }

}