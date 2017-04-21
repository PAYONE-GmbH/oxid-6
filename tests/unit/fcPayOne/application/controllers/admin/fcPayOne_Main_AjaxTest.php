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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcPayOne_Main_Ajax extends OxidTestCase
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
     * Testing _getQuery for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__getQuery_Coverage_1() 
    {
        $oTestObject = oxNew('fcPayOne_Main_Ajax');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', '2', '3'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $this->invokeMethod($oTestObject, '_getQuery');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    

    /**
     * Testing _getQuery for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__getQuery_Coverage_2() 
    {
        $oTestObject = oxNew('fcPayOne_Main_Ajax');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls(false, '2', '3'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $this->invokeMethod($oTestObject, '_getQuery');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing addpaycountry for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_addpaycountry_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOne_Main_Ajax', array('_getActionIds'));
        $oTestObject->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('1','2')));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', '2', '3'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $this->invokeMethod($oTestObject, 'addpaycountry');
        
        $this->assertEquals($sExpect, $sResponse);
    }

    
    /**
     * Testing removepaycountry for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_removepaycountry_Coverage_1() 
    {
        $oTestObject = $this->getMock('fcPayOne_Main_Ajax', array('_getActionIds'));
        $oTestObject->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('1','2')));

        $oMockDatabase = $this->getMock('oxDb', array('quoteArray', 'Execute'));
        $oMockDatabase->expects($this->any())->method('quoteArray')->will($this->returnValue(''));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someValue'));
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $this->invokeMethod($oTestObject, 'removepaycountry');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing removepaycountry for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_removepaycountry_Coverage_2() 
    {
        $oTestObject = $this->getMock('fcPayOne_Main_Ajax', array('_getActionIds'));
        $oTestObject->expects($this->any())->method('_getActionIds')->will($this->returnValue(array('1','2')));

        $oMockDatabase = $this->getMock('oxDb', array('quoteArray', 'Execute'));
        $oMockDatabase->expects($this->any())->method('quoteArray')->will($this->returnValue(array('someResult')));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $this->invokeMethod($oTestObject, 'removepaycountry');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
}
