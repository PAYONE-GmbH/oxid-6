<?php
/** 
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */
 
class MockResultPayPal
{

    public $EOF = false;
    public $fields = array('someValue','someValue','someValue','someValue','someValue');

    public function recordCount() 
    {
        return 1;
    }

    public function moveNext() 
    {
        $this->EOF = true;
    }

}

class Unit_fcPayOne_Application_Models_fcpopaypal extends OxidTestCase
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
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set protected/private attribute value
     *
     * @param object &$object      Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value        value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) 
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }
    
    
    /**
     * Testing fcpoGetMessages for coverage
     */
    public function test_fcpoGetMessages_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');
        $this->invokeSetAttribute($oTestObject, '_aAdminMessages', 'someValue');
        
        $this->assertEquals('someValue', $oTestObject->fcpoGetMessages());
    }
    
    
    /**
     * Testing fcpoGetPayPalLogos for coverage
     */
    public function test_fcpoGetPayPalLogos_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');

        $aMockResult = array(array('someValue','someValue','someValue','someValue','someValue'));
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $aResponse = $aExpect = $oTestObject->fcpoGetPayPalLogos();
        $this->assertEquals($aExpect, $aResponse);
    }
    
    
    /**
     * Testing _fcpoAddLogoPath for coverage
     */
    public function test__fcpoAddLogoPath_Coverage() 
    {
        $oTestObject = $this->getMock('fcpopaypal', array('_fcpoGetLogoEnteredAndExisting'));
        $oTestObject->expects($this->any())->method('_fcpoGetLogoEnteredAndExisting')->will($this->returnValue(true));
        $aResponse = $oTestObject->_fcpoAddLogoPath('someLogo', array('existingLogos'));
        $this->assertEquals(true, is_array($aResponse));
    }
    
    
    /**
     * Testing fcpoUpdatePayPalLogos for coverage
     */
    public function test_fcpoUpdatePayPalLogos_Coverage() 
    {
        $oTestObject = $this->getMock('fcpopaypal', array('_handleUploadPaypalExpressLogo', '_fcpoTriggerUpdateLogos'));
        $oTestObject->expects($this->any())->method('_handleUploadPaypalExpressLogo')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoTriggerUpdateLogos')->will($this->returnValue(true));
        
        $aMockLogos = array(1=>array('active'=>'existingLogo', 'langid'=>'someId'));

        $oMockDatabase = $this->getMock('oxDb', array('Execute', 'quote'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        $oMockDatabase->expects($this->any())->method('quote')->will($this->returnValue(''));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(null, $oTestObject->fcpoUpdatePayPalLogos($aMockLogos));
    }
    
    
    /**
     * Testing _fcpoTriggerUpdateLogos for coverage
     */
    public function test__fcpoTriggerUpdateLogos_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');
        
        $oMockDatabase = $this->getMock('oxDb', array('Execute', 'quote'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        $oMockDatabase->expects($this->any())->method('quote')->will($this->returnValue(''));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(1));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals(null, $oTestObject->_fcpoTriggerUpdateLogos());
    }
    
    
    /**
     * Testing fcpoAddPaypalExpressLogo for coverage
     */
    public function test_fcpoAddPaypalExpressLogo_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');
        
        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(null, $oTestObject->fcpoAddPaypalExpressLogo());
    }
    
    
    /**
     * Testing _fcpoGetLogoEnteredAndExisting for coverage
     */
    public function test__fcpoGetLogoEnteredAndExisting_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoGetLogoEnteredAndExisting('someValue'));
    }
    
    /**
     * Testing _handleUploadPaypalExpressLogo for current shop versions
     * 
     * @param  void
     * @return void
     */
    public function test__handleUploadPaypalExpressLogo_NewerShopVersion() 
    {
        $oTestObject = $this->getMock('fcpopaypal', array('_fcpoValidateFile','_fcpoHandleFile'));
        $oTestObject->expects($this->any())->method('_fcpoValidateFile')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleFile')->will($this->returnValue('someQueryAddition'));
        
        $aFiles = array(
            'logo_1' => array('error'=>0),
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetFiles')->will($this->returnValue($aFiles));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals("someQueryAddition", $this->invokeMethod($oTestObject, '_handleUploadPaypalExpressLogo', array(1)));
    }
    
    
    /**
     * Testing _fcpoHandleFile for coverage
     */
    public function test__fcpoHandleFile_Coverage() 
    {
        $oTestObject = $this->getMock('fcpopaypal', array('_fcpoFetchMediaUrl'));
        $oTestObject->expects($this->any())->method('_fcpoFetchMediaUrl')->will($this->returnValue('someValue'));
        
        $aFiles = array(
            'logo_1' => array('error'=>0),
        );
        
        $this->assertEquals(", FCPO_LOGO = 'someValue'", $oTestObject->_fcpoHandleFile(1, $aFiles));
    }
    
    
    /**
     * Testing _fcpoFetchMediaUrl for newer shop versions
     */
    public function test__fcpoFetchMediaUrl_NewerShopVersion() 
    {
        $oTestObject = oxNew('fcpopaypal');
        
        $oMockUtilsFile = $this->getMock('oxUtilsFile', array('handleUploadedFile', 'processFile'));
        $oMockUtilsFile->expects($this->any())->method('handleUploadedFile')->will($this->returnValue('someValue'));
        $oMockUtilsFile->expects($this->any())->method('processFile')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        $oHelper->expects($this->any())->method('fcpoGetUtilsFile')->will($this->returnValue($oMockUtilsFile));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);        
        
        $aFiles = array(
            'logo_1' => array('error'=>0),
        );
        
        $this->assertEquals('someValue', $oTestObject->_fcpoFetchMediaUrl(1, $aFiles));
    }
    
    
    /**
     * Testing _fcpoFetchMediaUrl for older shop versions
     */
    public function test__fcpoFetchMediaUrl_OlderShopVersion() 
    {
        $oTestObject = oxNew('fcpopaypal');
        
        $oMockUtilsFile = $this->getMock('oxUtilsFile', array('handleUploadedFile', 'processFile'));
        $oMockUtilsFile->expects($this->any())->method('handleUploadedFile')->will($this->returnValue('someValue'));
        $oMockUtilsFile->expects($this->any())->method('processFile')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        $oHelper->expects($this->any())->method('fcpoGetUtilsFile')->will($this->returnValue($oMockUtilsFile));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $aFiles = array(
            'logo_1' => array('error'=>0),
        );
        
        $this->assertEquals('someValue', $oTestObject->_fcpoFetchMediaUrl(1, $aFiles));
    }
    
    
    /**
     * Testing _fcpoValidateFile for coverage
     */
    public function test__fcpoValidateFile_Coverage() 
    {
        $oTestObject = oxNew('fcpopaypal');
        $aFiles = array(
            'logo_1' => array('error'=>0),
        );

        $this->assertEquals(true, $oTestObject->_fcpoValidateFile(1, $aFiles));
    }

}
