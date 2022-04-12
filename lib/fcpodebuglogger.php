<?php
/**
 * 0079203 : Temporary debug logger class
 * */

class fcpodebuglogger extends oxBase
{

    const LOG_DIR_SUFFIX = 'payone/debug/';

    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * @var string
     */
    private $sLogDirPath = '';

    public function __construct()
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->sLogDirPath = $this->_oFcpoHelper->fcpoGetConfig()->getLogsDir() . self::LOG_DIR_SUFFIX;
    }

    /**
     * @param string $message
     * @param string $location
     */
    public function writeEntry($message = '', $location = '')
    {
        $sTimestamp = $this->getDate()->format('[Y-m-d H:i:s]');
        $sLogEntry = $sTimestamp . ' ' . $location . ' ' . $message . PHP_EOL;

        file_put_contents($this->getCurrentLogFile(), $sLogEntry, FILE_APPEND);
    }

    protected function getCurrentLogFile()
    {
        $sToday = $this->getDate()->format('Y-m-d');
        $sDailyLogFile = $this->getLogDir() . '' . $sToday . '_debug_log.log';

        return $sDailyLogFile;
    }

    /**
     * @return string
     */
    protected function getLogDir()
    {
        if (!is_dir($this->sLogDirPath)) {
            mkdir($this->sLogDirPath,0755, true);
        }

        return $this->sLogDirPath;
    }

    /**
     * @return DateTimeImmutable
     */
    private function getDate()
    {
        return new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin'));
    }

}
