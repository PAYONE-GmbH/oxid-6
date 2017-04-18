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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_apilog_main extends OxidTestCase
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
    public function invokeSetAttribute(&$object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }    
    
    
    /**
     * Testing getViewId for getting coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getViewId_Coverage() 
    {
        $oApiLogMain = oxNew('fcpayone_apilog_main');
        $this->assertEquals('dyn_fcpayone', $oApiLogMain->getViewId());
    }
    
    
    /**
     * Testing respond fcGetAdminSeperator on older shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetAdminSeperator_OlderShopVersion() 
    {
        $oApiLogMain = oxNew('fcpayone_apilog_main');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oApiLogMain, '_oFcpoHelper', $oHelper);
        
        $sExpect = "?";
        
        $this->assertEquals($sExpect, $oApiLogMain->fcGetAdminSeperator());
    }
    

    /**
     * Testing respond fcGetAdminSeperator on newer shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetAdminSeperator_NewerShopVersion() 
    {
        $oApiLogMain = oxNew('fcpayone_apilog_main');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oApiLogMain, '_oFcpoHelper', $oHelper);
        
        $sExpect = "&";
        
        $this->assertEquals($sExpect, $oApiLogMain->fcGetAdminSeperator());
    }
}
