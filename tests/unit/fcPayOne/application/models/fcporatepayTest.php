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
 
class MockResultRatepay
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

class Unit_fcPayOne_Application_Models_fcporatepay extends OxidTestCase
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
     * testing fcpoInsertProfile for deletion
     */
    public function test_fcpoInsertProfile_Delete() 
    {
        $aMockData = array('delete'=>'someValue');
        $oTestObject = $this->getMock('fcporatepay', array('_fcpoUpdateRatePayProfile'));
        $oTestObject->expects($this->any())->method('_fcpoUpdateRatePayProfile')->will($this->returnValue(null));

        $oMockDb = $this->getMock('oxdb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->fcpoInsertProfile('someId', $aMockData));
    }

    /**
     * testing fcpoInsertProfile for updating
     */
    public function test_fcpoInsertProfile_Update() 
    {
        $aMockData = array('someIndex'=>'someValue');
        $oTestObject = $this->getMock('fcporatepay', array('_fcpoUpdateRatePayProfile'));
        $oTestObject->expects($this->any())->method('_fcpoUpdateRatePayProfile')->will($this->returnValue(null));

        $oMockDb = $this->getMock('oxdb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->fcpoInsertProfile('someId', $aMockData));
    }

    /**
     * Testing fcpoGetRatePayProfiles for coverage
     */
    public function test_fcpoGetRatePayProfiles_Coverage() 
    {
        $oTestObject = oxNew('fcporatepay');

        $aMockResult = array(
            array(
                'OXID'=>'someValue',
                'Index1'=>'someValue',
                'Index2'=>'someValue',
                'Index3'=>'someValue',
                'Index4'=>'someValue'
            )
        );
        $oMockDatabase = $this->getMock('oxDb', array('getAll', 'quote'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));
        $oMockDatabase->expects($this->any())->method('quote')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = array(
            'someValue' => array(
                'OXID'=>'someValue',
                'Index1'=>'someValue',
                'Index2'=>'someValue',
                'Index3'=>'someValue',
                'Index4'=>'someValue'
            ),
        );

        $this->assertEquals($aExpect, $oTestObject->fcpoGetRatePayProfiles('somePaymentId'));
    }

    /**
     * Testing fcpoAddRatePayProfile for coverage
     */
    public function test_fcpoAddRatePayProfile_Coverage() 
    {
        $oTestObject = oxNew('fcporatepay');

        $oMockDb = $this->getMock('oxdb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $oMockOxUtils = $this->getMock('oxUtils', array('generateUId'));
        $oMockOxUtils->expects($this->any())->method('generateUId')->will($this->returnValue('someOxid'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue($oMockOxUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoAddRatePayProfile());
    }

    /**
     * Testing fcpoGetProfileData for coverage
     */
    public function test_fcpoGetProfileData_Coverage() 
    {
        $aMockResult = array('one'=>'value1', 'two'=>'value2', 'three'=>'value3');
        $aExpect = array('one'=>'value1', 'two'=>'value2', 'three'=>'value3');
        
        $oTestObject = oxNew('fcporatepay');

        $oMockDb = $this->getMock('oxdb', array('GetRow', 'quote'));
        $oMockDb->expects($this->any())->method('GetRow')->will($this->returnValue($aMockResult));
        $oMockDb->expects($this->any())->method('quote')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDb));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aExpect, $oTestObject->fcpoGetProfileData('someId'));
    }

    /**
     * Testing fcpoGetFields for coverage
     */
    public function test_fcpoGetFields_Coverage() 
    {
        $oTestObject = oxNew('fcporatepay');

        $aMockResult = array('someValue');
        $oMockDatabase = $this->getMock('oxDb', array('getRow'));
        $oMockDatabase->expects($this->any())->method('getRow')->will($this->returnValue($aMockResult));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = array('someValue');

        $this->assertEquals($aExpect, $oTestObject->fcpoGetFields());
    }

    /**
     * Testing  _fcpoUpdateRatePayProfile for coverage
     */
    public function test__fcpoUpdateRatePayProfile_Coverage() 
    {
        $aMockResponse = array('status'=>'OK');

        $oTestObject = $this->getMock('fcporatepay', array('fcpoGetProfileData', '_fcpoUpdateRatePayProfileByResponse'));
        $oTestObject->expects($this->any())->method('fcpoGetProfileData')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoUpdateRatePayProfileByResponse')->will($this->returnValue(null));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestRatePayProfile'));
        $oMockRequest->expects($this->any())->method('sendRequestRatePayProfile')->will($this->returnValue($aMockResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoUpdateRatePayProfile('someId'));
    }

    /**
     * Testing _fcpoUpdateRatePayProfileByResponse for coverage
     */
    public function test__fcpoUpdateRatePayProfileByResponse_Coverage() 
    {
        $oTestObject = oxNew('fcporatepay');

        $oMockDb = $this->getMock('oxdb', array('Execute', 'quote'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $oMockDb->expects($this->any())->method('quote')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->_fcpoUpdateRatePayProfileByResponse('someId', array()));
    }
}
