<?php

abstract class fcpobasehelper
{
    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oHelper = null;

    /**
     * Returns fcpohelper object
     *
     * @return fcpohelper
     */
    public function getMainHelper() {
        if ($this->_oHelper === null) {
            $this->_oHelper = oxNew('fcpohelper');
        }
        return $this->_oHelper;
    }
}