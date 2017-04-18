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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_apilog extends OxidTestCase
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
     * Run render method just for getting the coverage
     * 
     * @param  void
     * @return void
     */
    public function test_Render_Coverage() 
    {
        $oApiLog = oxNew('fcpayone_apilog');
        
        // prepared obeject
        $oFactoryObject = $this->getMockBuilder('fcporequestlog')
            ->disableOriginalConstructor()
            ->getMock();
        $oFactoryObject->expects($this->any())
            ->method('load')
            ->will($this->returnValue(true));
        
        // helper answers
        $oHelper = $this->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oFactoryObject));
        $oHelper->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue($oFactoryObject));

        $this->invokeSetAttribute($oApiLog, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('fcpayone_apilog.tpl', $this->invokeMethod($oApiLog, 'render'));
    }
    
}
