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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_log_list extends OxidTestCase
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
     * Testing getPortalId for getting coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getPortalId_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log_list');
        
        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);        

        $this->assertEquals('someValue', $oTestObject->getPortalId());
    }

    
    /**
     * Testing getSubAccountId for getting coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getSubAccountId_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log_list');
        
        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);        

        $this->assertEquals('someValue', $oTestObject->getSubAccountId());
    }

    
    /**
     * Testing _prepareWhereQuery for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__prepareWhereQuery_Coverage() 
    {
        $oTestObject = $this->getMockBuilder('fcpayone_log_list')->disableOriginalConstructor()->getMock();
        $oTestObject->expects($this->any())->method('getSubAccountId')->will($this->returnValue('mysubaccountid'));
        $oTestObject->expects($this->any())->method('getPortalId')->will($this->returnValue('myportalid'));
        
        $sExpectString = " AND fcpotransactionstatus.fcpo_portalid = 'myportalid' AND fcpotransactionstatus.fcpo_aid = 'mysubaccountid' ";
        
        $this->assertEquals($sExpectString, $this->invokeMethod($oTestObject, '_prepareWhereQuery', array(array(),'')));
    }
    
    
    /**
     * Testing getListFilter for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getListFilter_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aListFilter', null);
        
        $aExpect = array('someValue');
        
        $this->assertEquals($aExpect, $oTestObject->getListFilter());
    }
    
    
    /**
     * Testing getListSorting for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getListSorting_Coverage() 
    {
        $oMockListObject = $this->getMockBuilder('oxList')->disableOriginalConstructor()->getMock();
        $oMockAdminListObject = $this->getMockBuilder('oxAdminList')->disableOriginalConstructor()->getMock();
        $oMockAdminListObject->expects($this->any())->method('getItemListBaseObject')->will($this->returnValue($oMockListObject));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        
        $oTestObject = $this->getMock('fcpayone_log_list', array('getItemListBaseObject'));
        $oTestObject->method('getItemListBaseObject')->will($this->returnValue($oMockAdminListObject));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aCurrSorting', null);
        $this->invokeSetAttribute($oTestObject, '_sDefSortField', 'sortValue');
        
        $aExpect[''] = array('sortValue'=>'asc');
        
        $this->assertEquals($aExpect, $oTestObject->getListSorting());
    }
    
    
    /**
     * Testing fcGetInputName for coverage on newer shop
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetInputName_NewerShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_log_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "where[Table][Field]";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetInputName('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetInputName for coverage on newer shop
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetInputName_OlderShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_log_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "where[Table.Field]";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetInputName('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetWhereValue on newer shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetWhereValue_NewerShopVersion() 
    {
        $aWhere = array();
        $aWhere['Table']['Field'] = 'someValue';
        $oTestObject = $this->getMock('fcpayone_log_list', array('getListFilter'));
        $oTestObject->method('getListFilter')->will($this->returnValue($aWhere));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "someValue";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetWhereValue('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetWhereValue on older shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetWhereValue_OlderShopVersion() 
    {
        $aWhere = array();
        $aWhere['Table.Field'] = 'someValue';
        $oTestObject = $this->getMock('fcpayone_log_list', array('getListFilter'));
        $oTestObject->method('getListFilter')->will($this->returnValue($aWhere));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "someValue";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetWhereValue('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetSortingJavascript on newer shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetSortingJavascript_NewerShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_log_list');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "Javascript:top.oxid.admin.setSorting( document.search, 'Table', 'Field', 'asc');document.search.submit();";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetSortingJavascript('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetSortingJavascript on older shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetSortingJavascript_OlderShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_log_list');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = "Javascript:document.search.sort.value='Table.Field';document.search.submit();";
        
        $this->assertEquals($sExpect, $oTestObject->fcGetSortingJavascript('Table', 'Field'));
    }
    
    
}
