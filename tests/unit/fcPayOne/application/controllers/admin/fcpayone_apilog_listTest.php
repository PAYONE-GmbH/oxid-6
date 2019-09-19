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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_apilog_list extends OxidTestCase
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
     * Testing getPortalId for getting coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getPortalId_Coverage() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');
        
        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);        

        $this->assertEquals('someValue', $oApiLogList->getPortalId());
    }
    
    
    /**
     * Testing getSubAccountId for getting coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getSubAccountId_Coverage() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');
        
        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);        

        $this->assertEquals('someValue', $oApiLogList->getSubAccountId());
    }
    
    
    /**
     * Testing _prepareWhereQuery for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__prepareWhereQuery_Coverage() 
    {
        $oApiLogList = $this->getMockBuilder('fcpayone_apilog_list')->disableOriginalConstructor()->getMock();
        $oApiLogList->expects($this->any())->method('getSubAccountId')->will($this->returnValue('mysubaccountid'));
        $oApiLogList->expects($this->any())->method('getPortalId')->will($this->returnValue('myportalid'));
        
        $sExpectString = " AND fcporequestlog.fcpo_portalid = 'myportalid' AND fcporequestlog.fcpo_aid = 'mysubaccountid' ";
        
        $this->assertEquals($sExpectString, $this->invokeMethod($oApiLogList, '_prepareWhereQuery', array(array(),'')));
    }
    
    
    /**
     * Testing getListFilter for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getListFilter_Coverage() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));

        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oApiLogList, '_aListFilter', null);
        
        $aExpect = array('someValue');
        
        $this->assertEquals($aExpect, $oApiLogList->getListFilter());
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
        
        $oApiLogList = $this->getMock('fcpayone_apilog_list', array('getItemListBaseObject'));
        $oApiLogList->method('getItemListBaseObject')->will($this->returnValue($oMockAdminListObject));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oApiLogList, '_aCurrSorting', null);
        $this->invokeSetAttribute($oApiLogList, '_sDefSortField', 'sortValue');
        
        $aExpect[''] = array('sortValue'=>'asc');
        
        $this->assertEquals($aExpect, $oApiLogList->getListSorting());
    }
    
    
    /**
     * Testing fcGetInputName for coverage on newer shop
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetInputName_NewerShopVersion() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "where[Table][Field]";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetInputName('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetInputName for coverage on newer shop
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetInputName_OlderShopVersion() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "where[Table.Field]";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetInputName('Table', 'Field'));
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
        $oApiLogList = $this->getMock('fcpayone_apilog_list', array('getListFilter'));
        $oApiLogList->method('getListFilter')->will($this->returnValue($aWhere));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "someValue";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetWhereValue('Table', 'Field'));
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
        $oApiLogList = $this->getMock('fcpayone_apilog_list', array('getListFilter'));
        $oApiLogList->method('getListFilter')->will($this->returnValue($aWhere));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "someValue";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetWhereValue('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetSortingJavascript on newer shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetSortingJavascript_NewerShopVersion() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "Javascript:top.oxid.admin.setSorting( document.search, 'Table', 'Field', 'asc');document.search.submit();";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetSortingJavascript('Table', 'Field'));
    }
    
    
    /**
     * Testing fcGetSortingJavascript on older shop version
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetSortingJavascript_OlderShopVersion() 
    {
        $oApiLogList = oxNew('fcpayone_apilog_list');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        
        $this->invokeSetAttribute($oApiLogList, '_oFcpoHelper', $oHelper);

        $sExpect = "Javascript:document.search.sort.value='Table.Field';document.search.submit();";
        
        $this->assertEquals($sExpect, $oApiLogList->fcGetSortingJavascript('Table', 'Field'));
    }
    
}
