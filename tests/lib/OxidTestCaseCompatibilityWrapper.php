<?php

class OxidTestCaseCompatibilityWrapper extends OxidTestCase {

    public function wrapExpectException($param) {
        if(method_exists($this, 'expectException')) {
            $this->expectException($param);
        }

        if(method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($param);
        }
    }

    public function testNothing()
    {
        $this->assertNull(null);
    }
}