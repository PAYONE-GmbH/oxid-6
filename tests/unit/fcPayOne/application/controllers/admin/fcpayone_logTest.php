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

class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_log extends OxidTestCase
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
     * Testing render method for coverage
     *
     * @param  void
     * @return void
     */
    public function test_Render_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('TheOxid'));
        $oHelper->expects($this->any())->method('fcpoGetHelpUrl')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('fcpayone_log.tpl', $oTestObject->render());
    }


    /**
     * Testing render method for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getStatus_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log');

        $oOrder = oxNew('oxOrder');
        $oOrder->oxorder__fcpotxid = new oxField('156452317');

        $this->_fcpoPrepareTransactionTable();
        $aReturn = $oTestObject->getStatus($oOrder);

        $this->assertEquals(true, is_array($aReturn));

        $this->_fcpoTruncateTransactionTable();
    }


    /**
     * Testing capture method for coverage
     *
     * @param  void
     * @return void
     */
    public function test_Capture_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_log');

        $oMockRequest = $this->getMockBuilder('fcporequest')->disableOriginalConstructor()->getMock();
        $oMockRequest->expects($this->any())->method('sendRequestCapture')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('1', '20'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->capture());
    }


    /**
     * Testing getCaptureMessage method for APPROVED state
     *
     * @param  void
     * @return void
     */
    public function test_GetCaptureMessage_Approved() 
    {
        $oTestObject = oxNew('fcpayone_log');
        $aResponse['status'] = 'APPROVED';
        $this->invokeSetAttribute($oTestObject, '_aResponse', $aResponse);

        $aPossibleReturns = array(
            '<span style="color: green;">FCPO_CAPTURE_APPROVED</span>',
            '<span style="color: green;">Buchung war erfolgreich</span>',
        );

        $this->assertTrue(in_array($oTestObject->getCaptureMessage(), $aPossibleReturns));
    }


    /**
     * Testing getCaptureMessage method for ERROR state
     *
     * @param  void
     * @return void
     */
    public function test_GetCaptureMessage_Error() 
    {
        $oTestObject = oxNew('fcpayone_log');
        $aResponse['status'] = 'ERROR';
        $this->invokeSetAttribute($oTestObject, '_aResponse', $aResponse);

        $aPossibleReturns = array(
            '<span style="color: red;">FCPO_CAPTURE_ERROR</span>',
            '<span style="color: red;">Fehler bei Buchung: </span>',
        );

        $this->assertTrue(in_array($oTestObject->getCaptureMessage(), $aPossibleReturns));
    }


    /**
     * Creates some entries in fcpotransactionstatus table
     *
     * @param  void
     * @return void
     */
    protected function _fcpoPrepareTransactionTable() 
    {
        $sQuery = "
            INSERT INTO `fcpotransactionstatus` (`OXID`, `FCPO_TIMESTAMP`, `FCPO_ORDERNR`, `FCPO_KEY`, `FCPO_TXACTION`, `FCPO_PORTALID`, `FCPO_AID`, `FCPO_CLEARINGTYPE`, `FCPO_TXTIME`, `FCPO_CURRENCY`, `FCPO_USERID`, `FCPO_ACCESSNAME`, `FCPO_ACCESSCODE`, `FCPO_PARAM`, `FCPO_MODE`, `FCPO_PRICE`, `FCPO_TXID`, `FCPO_REFERENCE`, `FCPO_SEQUENCENUMBER`, `FCPO_COMPANY`, `FCPO_FIRSTNAME`, `FCPO_LASTNAME`, `FCPO_STREET`, `FCPO_ZIP`, `FCPO_CITY`, `FCPO_EMAIL`, `FCPO_COUNTRY`, `FCPO_SHIPPING_COMPANY`, `FCPO_SHIPPING_FIRSTNAME`, `FCPO_SHIPPING_LASTNAME`, `FCPO_SHIPPING_STREET`, `FCPO_SHIPPING_ZIP`, `FCPO_SHIPPING_CITY`, `FCPO_SHIPPING_COUNTRY`, `FCPO_BANKCOUNTRY`, `FCPO_BANKACCOUNT`, `FCPO_BANKCODE`, `FCPO_BANKACCOUNTHOLDER`, `FCPO_CARDEXPIREDATE`, `FCPO_CARDTYPE`, `FCPO_CARDPAN`, `FCPO_CUSTOMERID`, `FCPO_BALANCE`, `FCPO_RECEIVABLE`, `FCPO_CLEARING_BANKACCOUNTHOLDER`, `FCPO_CLEARING_BANKACCOUNT`, `FCPO_CLEARING_BANKCODE`, `FCPO_CLEARING_BANKNAME`, `FCPO_CLEARING_BANKBIC`, `FCPO_CLEARING_BANKIBAN`, `FCPO_CLEARING_LEGALNOTE`, `FCPO_CLEARING_DUEDATE`, `FCPO_CLEARING_REFERENCE`, `FCPO_CLEARING_INSTRUCTIONNOTE`) VALUES
            (1,	'2015-02-26 11:06:31',	23005,	'f053795653c9c136ae16c400104705fc',	'appointed',	2017762,	17102,	'cc',	'2015-02-26 11:04:01',	'EUR',	'63074422',	'',	'',	'',	'test',	33.8,	'156452317',	0,	0,	'Fatchip GmbH',	'Markus',	'Riedl',	'Helmholtzstr. 2-9',	'10587',	'Berlin',	'markus.riedl@fatchip.de',	'DE',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'1701',	'V',	'411111xxxxxx1111',	35645,	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	''),
            (2,	'2015-03-05 14:06:05',	0,	'f053795653c9c136ae16c400104705fc',	'appointed',	2017762,	17102,	'wlt',	'2015-03-05 14:04:27',	'EUR',	'10262077',	'',	'',	'',	'test',	53.8,	'157116888',	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	13,	0,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	''),
            (3,	'2015-03-05 14:12:11',	0,	'f053795653c9c136ae16c400104705fc',	'paid',	2017762,	17102,	'wlt',	'2015-03-05 14:04:27',	'EUR',	'10262077',	'',	'',	'',	'test',	53.8,	'157116888',	0,	0,	'PAYONE',	'Robert',	'Müller',	'Helmholtzstr. 2-9',	'10587',	'Berlin',	'robert.mueller@fatchip.de',	'DE',	'',	'Test',	'Buyer',	'ESpachstr. 1',	'79111',	'Freiburg',	'DE',	'',	'',	'',	'',	'',	'',	'',	13,	-53.8,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'');            
        ";

        oxDb::getDb()->Execute($sQuery);
    }


    /**
     * Truncates fcpotransactionstatus table
     *
     * @param  void
     * @return void
     */
    protected function _fcpoTruncateTransactionTable() 
    {
        $sQuery = "DELETE FROM `fcpotransactionstatus` ";

        oxDb::getDb()->Execute($sQuery);
    }
}
