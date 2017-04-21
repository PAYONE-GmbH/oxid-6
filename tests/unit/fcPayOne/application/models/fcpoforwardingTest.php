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
 
class MockResultForwarding
{

    public $EOF = false;
    public $fields = array('someValue', 'someValue', 'someValue', 'someValue');

    public function recordCount() 
    {
        return 1;
    }

    public function moveNext() 
    {
        $this->EOF = true;
    }

}

/**
 * Description of fcpoforwardingTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2016-06-01
 */
class Unit_fcPayOne_Application_Models_fcpoforwarding extends OxidTestCase
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
     * Testing fcpoGetExistingForwardings for coverage
     */
    public function test_fcpoGetExistingForwardings_Coverage() 
    {
        $oTestObject = oxNew('fcpoforwarding');

        $aMockResult = array(
            array(
                'oxid'=>'someOxid',
                'fcpo_payonestatus'=>'someStatus',
                'fcpo_url'=>'someUrl',
                'fcpo_timeout'=>'someTimeout'
            ),
        );
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aResponse = $aExpect = $oTestObject->fcpoGetExistingForwardings();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoUpdateForwardings for coverage
     */
    public function test_fcpoUpdateForwardings_Coverage() 
    {
        $oTestObject = $this->getMock('fcpoforwarding', array('_fcpoGetQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetQuery')->will($this->returnValue(true));

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $aMockForwardings = array('someIndex' => array('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoUpdateForwardings($aMockForwardings));
    }

    /**
     * Testing _fcpoGetQuery for case of removing an entry
     */
    public function test__fcpoGetQuery_Delete() 
    {
        $oTestObject = $this->getMock('fcpoforwarding', array('_fcpoGetUpdateQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetUpdateQuery')->will($this->returnValue(true));

        $aMockData = array('delete' => true);
        $sMockOxid = 'someId';
        $sQuotedOxid = oxDb::getDb()->quote($sMockOxid);

        $sExpect = "DELETE FROM fcpostatusforwarding WHERE oxid = {$sQuotedOxid}";

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetQuery($sMockOxid, $aMockData));
    }

    /**
     * Testing _fcpoGetQuery for case of adding/updating an entry
     */
    public function test__fcpoGetQuery_Update() 
    {
        $oTestObject = $this->getMock('fcpoforwarding', array('_fcpoGetUpdateQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetUpdateQuery')->will($this->returnValue('someValue'));

        $aMockData = array('donotdelete' => true);
        $sMockOxid = 'someId';
        $sExpect = "someValue";

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetQuery($sMockOxid, $aMockData));
    }

    /**
     * Testing _fcpoGetUpdateQuery inserting a new entry
     */
    public function test__fcpoGetUpdateQuery_Insert() 
    {
        $oTestObject = $this->getMock('fcpoforwarding', array('_fcpoIsValidNewEntry'));
        $oTestObject->expects($this->any())->method('_fcpoIsValidNewEntry')->will($this->returnValue(true));

        $oMockUtilsObject = $this->getMock('oxUtilsObject', array('generateUID'));
        $oMockUtilsObject->expects($this->any())->method('generateUID')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue($oMockUtilsObject));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockForwardingId = 'someForwardingId';
        $sMockPayoneStatus = 'someStatus';
        $sMockUrl = 'someUrl';
        $iMockTimeout = 90;
        $sMockOxid = 'someOxid';


        $sResponse = $sExpect = $oTestObject->_fcpoGetUpdateQuery($sMockForwardingId, $sMockPayoneStatus, $sMockUrl, $iMockTimeout, $sMockOxid);

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetUpdateQuery inserting a new entry
     */
    public function test__fcpoGetUpdateQuery_Update() 
    {
        $oTestObject = $this->getMock('fcpoforwarding', array('_fcpoIsValidNewEntry'));
        $oTestObject->expects($this->any())->method('_fcpoIsValidNewEntry')->will($this->returnValue(false));

        $oMockUtilsObject = $this->getMock('oxUtilsObject', array('generateUID'));
        $oMockUtilsObject->expects($this->any())->method('generateUID')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue($oMockUtilsObject));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockForwardingId = 'someForwardingId';
        $sMockPayoneStatus = 'someStatus';
        $sMockUrl = 'someUrl';
        $iMockTimeout = 90;
        $sMockOxid = 'someOxid';

        $sResponse = $sExpect = $oTestObject->_fcpoGetUpdateQuery($sMockForwardingId, $sMockPayoneStatus, $sMockUrl, $iMockTimeout, $sMockOxid);

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoIsValidNewEntry for coverage
     */
    public function test__fcpoIsValidNewEntry_Coverage() 
    {
        $oTestObject = oxNew('fcpoforwarding');

        $sMockForwardingId = 'new';
        $sMockPayoneStatus = 'someStatus';
        $sMockUrl = 'someUrl';
        $iMockTimeout = 90;

        $this->assertEquals(true, $oTestObject->_fcpoIsValidNewEntry($sMockForwardingId, $sMockPayoneStatus, $sMockUrl, $iMockTimeout));
    }

}
