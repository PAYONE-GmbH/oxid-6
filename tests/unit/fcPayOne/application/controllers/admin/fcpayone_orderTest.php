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

class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_order extends OxidTestCase
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
     * Testing render method on coverage
     *
     * @param  void
     * @return void
     */
    public function test_Render_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oMockOrder = $this->getMock('oxOrder', array('load'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(1));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('fcpayone_order.tpl', $oTestObject->render());
    }

    /**
     * Testing fcpoGetStatusOxid on false status
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetStatusOxid_HasFalseStatus() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_sStatusOxid', false);

        $this->assertEquals('-1', $oTestObject->fcpoGetStatusOxid());
    }

    /**
     * Testing fcpoGetStatusOxid on false status
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetStatusOxid_HasStatus() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_sStatusOxid', false);

        $this->assertEquals('someValue', $oTestObject->fcpoGetStatusOxid());
    }

    /**
     * Testing fcpoGetCurrentStatus for having a transaction status
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetCurrentStatus_HasTransaction() 
    {
        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpotxid = new oxField('1234');

        $oMockTransactionStatus = $this->getMock('fcpotransactionstatus', array('load'));
        $oMockTransactionStatus->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockTransactionStatus->fcpotransactionstatus__fcpo_txid = new oxField('1234');

        $oTestObject = $this->getMock('fcpayone_order', array('fcpoGetStatusOxid', 'fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockTransactionStatus));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($oMockTransactionStatus, $oTestObject->fcpoGetCurrentStatus());
    }

    /**
     * Testing fcpoGetCurrentStatus for having no transaction status
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetCurrentStatus_NoTransaction() 
    {
        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpotxid = new oxField('1234');

        $oMockTransactionStatus = $this->getMock('fcpotransactionstatus', array('load'));
        $oMockTransactionStatus->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockTransactionStatus->fcpotransactionstatus__fcpo_txid = new oxField('4321');

        $oTestObject = $this->getMock('fcpayone_order', array('fcpoGetStatusOxid', 'fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockTransactionStatus));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->fcpoGetCurrentStatus());
    }

    /**
     * Testing getStatus for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getStatus_Coverage() 
    {
        $this->_fcpoPrepareTransactionstatusTable();

        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load','fcpoGetStatus'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('fcpoGetStatus')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpotxid = new oxField('156452317');

        $oMockTransactionStatus = $this->getMock('fcpotransactionstatus', array('load'));
        $oMockTransactionStatus->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockTransactionStatus->fcpotransactionstatus__fcpo_txid = new oxField('4321');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockTransactionStatus));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aStatus', false);

        $aResponse = $aExpect = $oTestObject->getStatus();

        $this->assertEquals($aExpect, $aResponse);

        $this->_fcpoTruncateTable('fcpotransactionstatus');
    }

    /**
     * Testing getStatus for returning value
     *
     * @param  void
     * @return void
     */
    public function test_getStatus_ReturnValue() 
    {
        $this->_fcpoPrepareTransactionstatusTable();

        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load','fcpoGetStatus'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('fcpoGetStatus')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpotxid = new oxField('156452317');

        $oMockTransactionStatus = $this->getMock('fcpotransactionstatus', array('load'));
        $oMockTransactionStatus->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockTransactionStatus->fcpotransactionstatus__fcpo_txid = new oxField('4321');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockTransactionStatus));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aStatus', array('someValue','someOtherValue'));

        $aResponse = $aExpect = $oTestObject->getStatus();

        $this->assertEquals($aExpect, $aResponse);

        $this->_fcpoTruncateTable('fcpotransactionstatus');
    }


    /**
     * Testing capture method on having a certain amount
     *
     * @param  void
     * @return void
     */
    public function test_Capture_AmountAvailable() 
    {
        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestCapture'));
        $oMockRequest->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestCapture')->will($this->returnValue('returnValue'));
        $aMockPositions = array('1' => array('capture' => '0'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', '1', '1,99', $aMockPositions));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->capture());
    }

    /**
     * Testing capture method on certain positions
     *
     * @param  void
     * @return void
     */
    public function test_Capture_PositionsAvailable() 
    {
        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestCapture'));
        $oMockRequest->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestCapture')->will($this->returnValue('returnValue'));
        $aMockPositions = array('1' => array('capture' => '0'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', '1', null, $aMockPositions));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->capture());
    }

    /**
     * Testing debit method on having a certain amount
     *
     * @param  void
     * @return void
     */
    public function test_Debit_AmountAvailable() 
    {
        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestDebit'));
        $oMockRequest->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestDebit')->will($this->returnValue('returnValue'));
        $aMockPositions = array('1' => array('debit' => '0'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', 'someCountry', 'someAccount', 'someBacnkCode', 'someHolder', '1,99', $aMockPositions));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->debit());
    }

    /**
     * Testing debit method on certain positions
     *
     * @param  void
     * @return void
     */
    public function test_Debit_PositionsAvailable() 
    {
        $oTestObject = $this->getMock('fcpayone_order', array('getOrder', 'fcpoGetStatusOxid'));
        $oTestObject->expects($this->any())->method('fcpoGetStatusOxid')->will($this->returnValue('someId'));

        $oMockOxOrder = $this->getMock('oxOrder', array('load'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestDebit'));
        $oMockRequest->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestDebit')->will($this->returnValue('returnValue'));
        $aMockPositions = array('1' => array('debit' => '0'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', 'someCountry', 'someAccount', 'someBacnkCode', 'someHolder', null, $aMockPositions));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockOxOrder, $oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->debit());
    }

    /**
     * Testing method fcpoGetMandatePdfUrl when param exists
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetMandatePdfUrl_ParamExists() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oMockOxOrder = $this->getMock('oxOrder', array('load', 'fcpoGetMandateFilename'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('fcpoGetMandateFilename')->will($this->returnValue('someFilename'));
        $oMockOxOrder->oxorder__oxpaymenttype = new oxField('fcpodebitnote');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOxOrder));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('1'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $sResponse = $sExpect = $oTestObject->fcpoGetMandatePdfUrl();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing method fcpoGetMandatePdfUrl when param does not exist
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetMandatePdfUrl_ParamNotExists() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oMockOxOrder = $this->getMock('oxOrder', array('load', 'fcpoGetMandateFilename'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('fcpoGetMandateFilename')->will($this->returnValue('someFilename'));
        $oMockOxOrder->oxorder__oxpaymenttype = new oxField('fcpodebitnote');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOxOrder));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('1'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $sResponse = $sExpect = $oTestObject->fcpoGetMandatePdfUrl();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing method _redownloadMandate for coverage
     *
     * @param  void
     * @return void
     */
    public function test__redownloadMandate_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oMockOxOrder = $this->getMock('oxOrder', array('load', 'getId', 'sendRequestGetFile'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('getId')->will($this->returnValue('1'));
        $oMockOxOrder->expects($this->any())->method('sendRequestGetFile')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpomode = new oxField('test');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOxOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_redownloadMandate('someFilename.pdf'));
    }

    /**
     * Testing method _redownloadMandate for coverage
     *
     * @param  void
     * @return void
     */
    public function test__redownloadMandate_Skip() 
    {
        $oTestObject = oxNew('fcpayone_order');

        $oMockOxOrder = $this->getMock('oxOrder', array('load', 'getId', 'sendRequestGetFile'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOxOrder->expects($this->any())->method('getId')->will($this->returnValue('1'));
        $oMockOxOrder->expects($this->any())->method('sendRequestGetFile')->will($this->returnValue(true));
        $oMockOxOrder->oxorder__fcpomode = new oxField('test');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('-1'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOxOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_redownloadMandate('someFilename.pdf'));
    }

    /**
     * Testing download method for coverage
     *
     * @param  void
     * @return void
     */
    public function test_Download_Coverage() 
    {
        $oMockOxOrder = $this->getMock('oxOrder', array('fcpoGetMandateFilename', 'load'));
        $oMockOxOrder->expects($this->any())->method('fcpoGetMandateFilename')->will($this->returnValue('someFilename'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcpayone_order', array('_redownloadMandate', 'fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('_redownloadMandate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockOxOrder));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someFilename.pdf'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->download(true));
    }

    /**
     * Testing download method with existing file
     *
     * @param  void
     * @return void
     */
    public function test_Download_FileExists() 
    {
        $oMockOxOrder = $this->getMock('oxOrder', array('fcpoGetMandateFilename', 'load'));
        $oMockOxOrder->expects($this->any())->method('fcpoGetMandateFilename')->will($this->returnValue('someFilename'));
        $oMockOxOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcpayone_order', array('_redownloadMandate', 'fcpoGetInstance'));
        $oTestObject->expects($this->any())->method('_redownloadMandate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockOxOrder));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someFilename.pdf'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->download(true));
    }

    /**
     * Testing download method when having no filename
     *
     * @param  void
     * @return void
     */
    public function test_Download_NoFile() 
    {
        $oTestObject = $this->getMock('fcpayone_order', array('_redownloadMandate'));
        $oTestObject->expects($this->any())->method('_redownloadMandate')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('1'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $oTestObject->download(true));
    }

    /**
     * Testing fcpoGetRequestMessage for approved state
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetRequestMessage_Approved() 
    {
        $oTestObject = oxNew('fcpayone_order');
        $aResponse['status'] = 'APPROVED';
        $this->invokeSetAttribute($oTestObject, '_aResponse', $aResponse);
        $this->invokeSetAttribute($oTestObject, '_sResponsePrefix', 'somePrefix');
        $this->invokeSetAttribute($oTestObject, '_sResponsePrefix', $aResponse);

        $sResponse = $sExpect = $oTestObject->fcpoGetRequestMessage();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetRequestMessage for approved state
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetRequestMessage_Error() 
    {
        $oTestObject = oxNew('fcpayone_order');
        $aResponse['status'] = 'ERROR';
        $this->invokeSetAttribute($oTestObject, '_aResponse', $aResponse);
        $this->invokeSetAttribute($oTestObject, '_sResponsePrefix', 'somePrefix');
        $this->invokeSetAttribute($oTestObject, '_sResponsePrefix', $aResponse);

        $sResponse = $sExpect = $oTestObject->fcpoGetRequestMessage();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Prepare some entries in transaction table
     */
    protected function _fcpoPrepareTransactionstatusTable() 
    {
        $this->_fcpoTruncateTable('fcpotransactionstatus');
        $sQuery = "
            INSERT INTO `fcpotransactionstatus` (`OXID`, `FCPO_TIMESTAMP`, `FCPO_ORDERNR`, `FCPO_KEY`, `FCPO_TXACTION`, `FCPO_PORTALID`, `FCPO_AID`, `FCPO_CLEARINGTYPE`, `FCPO_TXTIME`, `FCPO_CURRENCY`, `FCPO_USERID`, `FCPO_ACCESSNAME`, `FCPO_ACCESSCODE`, `FCPO_PARAM`, `FCPO_MODE`, `FCPO_PRICE`, `FCPO_TXID`, `FCPO_REFERENCE`, `FCPO_SEQUENCENUMBER`, `FCPO_COMPANY`, `FCPO_FIRSTNAME`, `FCPO_LASTNAME`, `FCPO_STREET`, `FCPO_ZIP`, `FCPO_CITY`, `FCPO_EMAIL`, `FCPO_COUNTRY`, `FCPO_SHIPPING_COMPANY`, `FCPO_SHIPPING_FIRSTNAME`, `FCPO_SHIPPING_LASTNAME`, `FCPO_SHIPPING_STREET`, `FCPO_SHIPPING_ZIP`, `FCPO_SHIPPING_CITY`, `FCPO_SHIPPING_COUNTRY`, `FCPO_BANKCOUNTRY`, `FCPO_BANKACCOUNT`, `FCPO_BANKCODE`, `FCPO_BANKACCOUNTHOLDER`, `FCPO_CARDEXPIREDATE`, `FCPO_CARDTYPE`, `FCPO_CARDPAN`, `FCPO_CUSTOMERID`, `FCPO_BALANCE`, `FCPO_RECEIVABLE`, `FCPO_CLEARING_BANKACCOUNTHOLDER`, `FCPO_CLEARING_BANKACCOUNT`, `FCPO_CLEARING_BANKCODE`, `FCPO_CLEARING_BANKNAME`, `FCPO_CLEARING_BANKBIC`, `FCPO_CLEARING_BANKIBAN`, `FCPO_CLEARING_LEGALNOTE`, `FCPO_CLEARING_DUEDATE`, `FCPO_CLEARING_REFERENCE`, `FCPO_CLEARING_INSTRUCTIONNOTE`) VALUES
            (1,	'2015-02-26 11:06:31',	23005,	'f053795653c9c136ae16c400104705fc',	'appointed',	2017762,	17102,	'cc',	'2015-02-26 11:04:01',	'EUR',	'63074422',	'',	'',	'',	'test',	33.8,	'156452317',	0,	0,	'Fatchip GmbH',	'Markus',	'Riedl',	'Helmholtzstr. 2-9',	'10587',	'Berlin',	'markus.riedl@fatchip.de',	'DE',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'1701',	'V',	'411111xxxxxx1111',	35645,	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	''),
            (2,	'2015-03-05 14:06:05',	0,	'f053795653c9c136ae16c400104705fc',	'appointed',	2017762,	17102,	'wlt',	'2015-03-05 14:04:27',	'EUR',	'10262077',	'',	'',	'',	'test',	53.8,	'157116888',	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	13,	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	''),
            (3,	'2015-03-05 14:12:11',	0,	'f053795653c9c136ae16c400104705fc',	'paid',	2017762,	17102,	'wlt',	'2015-03-05 14:04:27',	'EUR',	'10262077',	'',	'',	'',	'test',	53.8,	'157116888',	0,	0,	'PAYONE',	'Robert',	'Müller',	'Helmholtzstr. 2-9',	'10587',	'Berlin',	'robert.mueller@fatchip.de',	'DE',	'',	'Test',	'Buyer',	'ESpachstr. 1',	'79111',	'Freiburg',	'DE',	'',	'',	'',	'',	'',	'',	'',	13,	-53.8,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'');            
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
