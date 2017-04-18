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
 
class Unit_fcPayOne_Application_Models_fcporequestlog extends OxidTestCase
{
    
    
    /**
     * Testing getRequestArray for coverage
     */
    public function test_getRequestArray_Coverage() 
    {
        $oTestObject = $this->getMock('fcporequestlog', array('getArray'));
        $oTestObject->expects($this->any())->method('getArray')->will($this->returnValue('someValue'));
        
        $this->assertEquals('someValue', $oTestObject->getRequestArray());
    }

    
    /**
     * Testing getResponseArray for coverage
     */
    public function test_getResponseArray_Coverage() 
    {
        $oTestObject = $this->getMock('fcporequestlog', array('getArray'));
        $oTestObject->expects($this->any())->method('getArray')->will($this->returnValue('someValue'));
        
        $this->assertEquals('someValue', $oTestObject->getResponseArray());
    }
    
    
    /**
     * Testing getArray for coverage
     */
    public function test_getArray_Coverage() 
    {
        $oTestObject = oxNew('fcporequestlog');
        
        $aMockData = array('someVar'=>'someValue');
        $sMockData = serialize($aMockData);
        
        $this->assertEquals($aMockData, $oTestObject->getArray($sMockData));
    }
    
    
}
