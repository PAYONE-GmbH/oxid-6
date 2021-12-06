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

    public function wrapAssertStringContainsString($needle, $haystack, $message = '')
    {
        if(method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
        } else {
            $this->assertContains($needle, $haystack, $message, false);
        }
    }

    public function wrapAssertStringContainsStringIgnoringCase($needle, $haystack, $message = '')
    {
        if(method_exists($this, 'assertStringContainsStringIgnoringCase')) {
            $this->assertStringContainsStringIgnoringCase($needle, $haystack, $message);
        } else {
            $this->assertContains($needle, $haystack, $message, true);
        }
    }

    public function testNothing()
    {
        $this->assertNull(null);
    }
}