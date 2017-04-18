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
 
class Unit_fcPayOne_Application_Models_fcpotransactionstatus extends OxidTestCase
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
     * Testing getAction for coverage
     */
    public function test_getAction_Coverage() 
    {
        $oTestObject = oxNew('fcpotransactionstatus');
        $oTestObject->fcpotransactionstatus__fcpo_txaction = new oxField('paid');
        $oTestObject->fcpotransactionstatus__fcpo_txreceivable = new oxField(10);
        $oTestObject->fcpotransactionstatus__fcpo_balance = new oxField(-20);

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $oTestObject->getAction();
        $this->assertEquals('someTranslation', $sResponse);
    }

    /**
     * Testing getClearingtype for coverage
     */
    public function test_getClearingtype_Coverage() 
    {
        $oMockOrder = oxNew('oxOrder');
        $oMockOrder->oxorder__oxpaymenttype = new oxField('somePaymentType');

        $oTestObject = $this->getMock('fcpotransactionstatus', array('_fcpoGetOrderByTxid'));
        $oTestObject->expects($this->any())->method('_fcpoGetOrderByTxid')->will($this->returnValue($oMockOrder));
        $oTestObject->fcpotransactionstatus__fcpo_txid = new oxField('someTxid');
        $oTestObject->fcpotransactionstatus__fcpo_clearingtype = new oxField('fnc');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $oTestObject->getClearingtype();
        $this->assertEquals('someTranslation', $sResponse);
    }

    /**
     * Testing _fcpoGetOrderByTxid for coverage
     */
    public function test__fcpoGetOrderByTxid_Coverage() 
    {
        $oTestObject = oxNew('fcpotransactionstatus');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someOxid'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $oMockOrder = $this->getMock('oxOrder', array('load'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($oMockOrder, $oTestObject->_fcpoGetOrderByTxid('someTxid'));
    }

    /**
     * Testing getCaptureAmount for coverage
     */
    public function test_getCaptureAmount_Coverage() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('load'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockOrder->oxorder__oxtotalordersum = new oxField(100);

        $oTestObject = $this->getMock('fcpotransactionstatus', array('_fcpoGetOrderByTxid'));
        $oTestObject->expects($this->any())->method('_fcpoGetOrderByTxid')->will($this->returnValue($oMockOrder));
        $oTestObject->fcpotransactionstatus__fcpo_txid = new oxField('someTxid');

        $this->assertEquals($oMockOrder->oxorder__oxtotalordersum, $oTestObject->getCaptureAmount());
    }

    /**
     * Testing getCardtype for coverage
     */
    public function test_getCardtype_Coverage() 
    {
        $oTestObject = oxNew('fcpotransactionstatus');
        $oTestObject->fcpotransactionstatus__fcpo_cardtype = new oxField('B');

        $this->assertEquals('Carte Bleue', $oTestObject->getCardtype());
    }

    /**
     * Testing getDisplayNameReceivable for coverage
     */
    public function test_getDisplayNameReceivable_Coverage() 
    {
        $oTestObject = $this->getMock('fcpotransactionstatus', array('_fcpoGetLangIdent', '_fcpoGetMapAction'));
        $oTestObject->expects($this->any())->method('_fcpoGetLangIdent')->will($this->returnValue('someTranslationIdent'));
        $oTestObject->expects($this->any())->method('_fcpoGetMapAction')->will($this->returnValue('someLangIdent'));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oTestObject->fcpotransactionstatus__fcpo_txid = new oxField('someTxid');
        $oTestObject->fcpotransactionstatus__fcpo_clearingtype = new oxField('fnc');

        $this->assertEquals('someTranslation', $oTestObject->getDisplayNameReceivable(100));
    }

    /**
     * Testing getDisplayNamePayment for coverage
     */
    public function test_getDisplayNamePayment_Coverage() 
    {
        $oTestObject = $this->getMock('fcpotransactionstatus', array('_fcpoGetLangIdent', '_fcpoGetMapAction'));
        $oTestObject->expects($this->any())->method('_fcpoGetLangIdent')->will($this->returnValue('someTranslationIdent'));
        $oTestObject->expects($this->any())->method('_fcpoGetMapAction')->will($this->returnValue('someLangIdent'));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oTestObject->fcpotransactionstatus__fcpo_txid = new oxField('someTxid');
        $oTestObject->fcpotransactionstatus__fcpo_clearingtype = new oxField('fnc');

        $this->assertEquals('someTranslation', $oTestObject->getDisplayNamePayment(100));
    }

    /**
     * Testing _fcpoGetLangIdent for coverage
     */
    public function test__fcpoGetLangIdent_Coverage() 
    {
        $oTestObject = oxNew('fcpotransactionstatus');

        $sResponse = $oTestObject->_fcpoGetLangIdent(100, 'Option1', 'Option2');

        $this->assertEquals('Option1', $sResponse);
    }

    /**
     * Testing _fcpoGetMapAction for coverage
     */
    public function test__fcpoGetMapAction_Coverage() 
    {
        $sMockTxAction = 'someTxAction';
        $aMockMatchMap = array($sMockTxAction => 'someAssignment');

        $oTestObject = oxNew('fcpotransactionstatus');

        $sResponse = $oTestObject->_fcpoGetMapAction($sMockTxAction, $aMockMatchMap, 'someDefaultValue');

        $this->assertEquals('someAssignment', $sResponse);
    }

}
