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

class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_status_forwarding extends OxidTestCase
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
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }


    /**
     * Testing getting forwardings on coverage
     *
     * @param  void
     * @return void
     */
    public function test_getForwardings_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_forwarding');
        $this->_fcpoAddSampleForwarding();


        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aResponse = $aExpect = $oTestObject->getForwardings();

        $this->assertEquals($aExpect, $aResponse);

        $this->_fcpoTruncateTable('fcpostatusforwarding');
    }


    /**
     * Testing getPayoneStatusList on coverage
     *
     * @param  void
     * @return void
     */
    public function test_getPayoneStatusList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_status_forwarding');
        $aResponse = $aExpect = $oTestObject->getPayoneStatusList();
        $this->assertEquals($aExpect, $aResponse);
    }


    /**
     * Testing save method on deleting an entry
     *
     * @param  void
     * @return void
     */
    public function test_save_Delete() 
    {
        $oTestObject = oxNew('fcpayone_status_forwarding');

        $this->_fcpoAddSampleForwarding();

        $aForwardings = array(
            '6'=>array(
                'delete'                =>'absolutelyYes',
                'sPayoneStatus'         =>'someStatus',
                'sForwardingUrl'        =>'someUrl',
                'iForwardingTimeout'    =>90,
            )
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aForwardings));
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue(oxRegistry::get('oxUtilsObject')));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
        $this->_fcpoTruncateTable('fcpostatusforwarding');
    }


    /**
     * Testing save method on adding an entry
     *
     * @param  void
     * @return void
     */
    public function test_save_NewEntry() 
    {
        $oTestObject = oxNew('fcpayone_status_forwarding');

        $this->_fcpoAddSampleForwarding();

        $aForwardings = array(
            'new'=>array(
                'sPayoneStatus'         =>'someStatus',
                'sForwardingUrl'        =>'someUrl',
                'iForwardingTimeout'    =>90,
            )
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aForwardings));
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue(oxRegistry::get('oxUtilsObject')));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
        $this->_fcpoTruncateTable('fcpostatusforwarding');
    }


    /**
     * Testing save method on adding an entry
     *
     * @param  void
     * @return void
     */
    public function test_save_UpdateEntry() 
    {
        $oTestObject = oxNew('fcpayone_status_forwarding');

        $this->_fcpoAddSampleForwarding();

        $aForwardings = array(
            '6'=>array(
                'sPayoneStatus'         =>'someOtherStatus',
                'sForwardingUrl'        =>'someOtherUrl',
                'iForwardingTimeout'    =>90,
            )
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aForwardings));
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue(oxRegistry::get('oxUtilsObject')));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
        $this->_fcpoTruncateTable('fcpostatusforwarding');
    }


    /**
     * Adds a sample forwarding
     *
     * @param  void
     * @return void
     */
    protected function _fcpoAddSampleForwarding() 
    {
        $this->_fcpoTruncateTable('fcpostatusforwarding');
        $sQuery = "
            INSERT INTO `fcpostatusforwarding` (`OXID`, `FCPO_PAYONESTATUS`, `FCPO_URL`, `FCPO_TIMEOUT`) VALUES
            (6, 'paid', 'http://paid.sample', 10);
        ";

        oxDb::getDb()->Execute($sQuery);
    }


    /**
     * Truncates table
     *
     * @param  void
     * @return void
     */
    protected function _fcpoTruncateTable($sTableName) 
    {
        $sQuery = "DELETE FROM `{$sTableName}` ";

        oxDb::getDb()->Execute($sQuery);
    }


}
