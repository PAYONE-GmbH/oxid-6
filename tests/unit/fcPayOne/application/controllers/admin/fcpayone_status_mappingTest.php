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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_status_mapping extends OxidTestCase
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
     * Testings getMappings for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getMappings_Coverage() 
    {
        $aMockMapping = array('someValue');

        $oTestObject = $this->getMock('fcpayone_status_mapping', array('_fcpoGetExistingMappings', '_fcpoAddNewMapping'));
        $oTestObject->expects($this->any())->method('_fcpoGetExistingMappings')->will($this->returnValue($aMockMapping));
        $oTestObject->expects($this->any())->method('_fcpoAddNewMapping')->will($this->returnValue($aMockMapping));

        $aResponse = $oTestObject->getMappings();

        $this->assertEquals($aMockMapping, $aResponse);
    }

    /**
     * Testing _fcpoAddNewMapping for coverage
     */
    public function test__fcpoAddNewMapping_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_mapping');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aResponse = $aExpect = $oTestObject->_fcpoAddNewMapping(array());
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetExistingMappings for coverage
     */
    public function test__fcpoGetExistingMappings_Coverage() 
    {
        $aResult = array('someIndex' => 'someValue');
        $oTestObject = oxNew('fcpayone_status_mapping');

        $oMockStatusMapping = $this->getMock('fcpoklarna', array('fcpoGetExistingMappings'));
        $oMockStatusMapping->expects($this->any())->method('fcpoGetExistingMappings')->will($this->returnValue($aResult));
        $this->invokeSetAttribute($oTestObject, '_oFcpoMapping', $oMockStatusMapping);

        $this->assertEquals($aResult, $oTestObject->_fcpoGetExistingMappings());
    }

    /**
     * Testing getPaymentTypeList on coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getPaymentTypeList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_mapping');
        $aResponse = $aExpect = $oTestObject->getPaymentTypeList();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing getPayoneStatusList on coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getPayoneStatusList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_mapping');
        $aResponse = $aExpect = $oTestObject->getPayoneStatusList();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing getShopStatusList on coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getShopStatusList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_mapping');
        $aResponse = $aExpect = $oTestObject->getShopStatusList();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing save method for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_save_Coverage() 
    {
        $oMockMapping = $this->getMock('fcpomapping', array('fcpoUpdateMappings'));
        $oMockMapping->expects($this->any())->method('fcpoUpdateMappings')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcpayone_status_mapping', array('fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockMapping));

        $aForwardings = array(
            'new' => array(
                'sPaymentType' => 'somePaymentType',
                'sPayoneStatus' => 'somePayoneStatus',
                'sShopStatus' => 'someStatus',
            )
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aForwardings));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
    }

}
