<?php

/**
 * Created by PhpStorm.
 * User: andre
 * Date: 31.01.17
 * Time: 16:48
 */
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_error_mappingTest extends OxidTestCase
{

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }


    /**
     * Testing getMappings for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getMappings_Coverage() 
    {
        $aMockDataMappings = array(
            'some' => 'Data'
        );

        $oTestObject = $this->getMock('fcpayone_error_mapping', array('_fcpoGetExistingMappings', '_fcpoAddNewMapping'));
        $oTestObject->expects($this->any())->method('_fcpoGetExistingMappings')->will($this->returnValue($aMockDataMappings));
        $oTestObject->expects($this->any())->method('_fcpoAddNewMapping')->will($this->returnValue($aMockDataMappings));

        $this->assertEquals($aMockDataMappings, $oTestObject->getMappings());
    }

    /**
     * Testing getIframeMappings for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getIframeMappings_Coverage() 
    {
        $aMockDataMappings = array(
            'some' => 'Data'
        );

        $oTestObject = $this->getMock('fcpayone_error_mapping', array('_fcpoGetExistingIframeMappings', '_fcpoAddNewIframeMapping'));
        $oTestObject->expects($this->any())->method('_fcpoGetExistingIframeMappings')->will($this->returnValue($aMockDataMappings));
        $oTestObject->expects($this->any())->method('_fcpoAddNewIframeMapping')->will($this->returnValue($aMockDataMappings));

        $this->assertEquals($aMockDataMappings, $oTestObject->getIframeMappings());
    }

    /**
     * Testing fcpoGetPayoneErrorMessages for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetPayoneErrorMessages_Coverage() 
    {
        $aMockErrorCodes = array(
            'some' => 'Data'
        );
        $oTestObject = oxNew('fcpayone_error_mapping');

        $oHelper = $this->getMockBuilder('fcpoerrormapping')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetAvailableErrorCodes')->will($this->returnValue($aMockErrorCodes));

        $this->invokeSetAttribute($oTestObject, '_oFcpoErrorMapping', $oHelper);

        $this->assertEquals($aMockErrorCodes, $oTestObject->fcpoGetPayoneErrorMessages());
    }

    /**
     * Testing getLanguages for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getLanguages_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_error_mapping');
        $aMockLang = array(
            'some' => 'Lang',
        );

        $oMockLang = $this->getMock('oxlang', array('getLanguageArray'));
        $oMockLang->expects($this->any())->method('getLanguageArray')->will($this->returnValue($aMockLang));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aMockLang, $oTestObject->getLanguages());
    }

    /**
     * Testing _fcpoAddNewMapping for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoAddNewMapping_Coverage() 
    {
        $aMockDataMappings = array();
        $oTestObject = oxNew('fcpayone_error_mapping');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = $aResponse = $oTestObject->_fcpoAddNewMapping($aMockDataMappings);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoAddNewIframeMapping for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoAddNewIframeMapping_Coverage() 
    {
        $aMockDataMappings = array();
        $oTestObject = oxNew('fcpayone_error_mapping');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = $aResponse = $oTestObject->_fcpoAddNewIframeMapping($aMockDataMappings);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetExistingMappings for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetExistingMappings_Coverage() 
    {
        $aMockMappings = array(
            'some' => 'Data'
        );
        $oTestObject = oxNew('fcpayone_error_mapping');

        $oHelper = $this->getMockBuilder('fcpoerrormapping')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetExistingMappings')->will($this->returnValue($aMockMappings));

        $this->invokeSetAttribute($oTestObject, '_oFcpoErrorMapping', $oHelper);

        $this->assertEquals($aMockMappings, $oTestObject->_fcpoGetExistingMappings());
    }

    /**
     * Testing _fcpoGetExistingIframeMappings^for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetExistingIframeMappings_Coverage() 
    {
        $aMockMappings = array(
            'some' => 'Data'
        );
        $oTestObject = oxNew('fcpayone_error_mapping');

        $oHelper = $this->getMockBuilder('fcpoerrormapping')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetExistingMappings')->will($this->returnValue($aMockMappings));

        $this->invokeSetAttribute($oTestObject, '_oFcpoErrorMapping', $oHelper);

        $this->assertEquals($aMockMappings, $oTestObject->_fcpoGetExistingIframeMappings());
    }

    /**
     * Testing save for coverage
     *
     * @param  void
     * @return void
     */
    public function test_save_Coverage() 
    {
        $aMockMappings = array(
            'some' => 'Data'
        );

        $oMockErrorMapping = $this->getMock('fcpoerrormapping', array('fcpoUpdateMappings'));
        $oMockErrorMapping->expects($this->any())->method('fcpoUpdateMappings')->will($this->returnValue(null));

        $oTestObject = $this->getMock('fcpayone_error_mapping', array('fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockErrorMapping));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockMappings));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
    }

    /**
     * Testing saveIframe for coverage
     *
     * @param  void
     * @return void
     */
    public function test_saveIframe_Coverage() 
    {
        $aMockMappings = array(
            'some' => 'Data'
        );

        $oMockErrorMapping = $this->getMock('fcpoerrormapping', array('fcpoUpdateMappings'));
        $oMockErrorMapping->expects($this->any())->method('fcpoUpdateMappings')->will($this->returnValue(null));

        $oTestObject = $this->getMock('fcpayone_error_mapping', array('fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockErrorMapping));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockMappings));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->saveIframe());
    }
}