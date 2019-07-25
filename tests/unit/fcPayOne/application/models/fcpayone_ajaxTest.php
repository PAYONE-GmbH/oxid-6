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
 
class MockResultAjax
{

    public $EOF = false;
    public $fields = array('someValue','someValue','someValue','someValue','someValue');

    public function recordCount() 
    {
        return 1;
    }

    public function moveNext() 
    {
        $this->EOF = true;
    }

}

class Unit_fcPayOne_Application_Models_fcpayone_ajax extends OxidTestCase
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
     * Testing fcpoGetAmazonReferenceId for coverage
     */
    public function test_fcpoGetAmazonReferenceId_Coverage() {
        $oTestObject = $this->getMock('fcpayone_ajax', array(
            '_fcpoHandleGetOrderReferenceDetails',
            '_fcpoHandleSetOrderReferenceDetails',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoHandleGetOrderReferenceDetails')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoHandleSetOrderReferenceDetails')
            ->will($this->returnValue(null));

        $oMockSession = $this->getMock('oxSession', array(
            'deleteVariable',
            'setVariable',
            'getVariable',
        ));
        $oMockSession
            ->expects($this->any())
            ->method('deleteVariable')
            ->will($this->returnValue(true));
        $oMockSession
            ->expects($this->any())
            ->method('setVariable')
            ->will($this->returnValue(true));
        $oMockSession
            ->expects($this->any())
            ->method('getVariable')
            ->will($this->returnValue('someSessionValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockJson = '{"some":"jsonparam"}';

        $this->assertEquals(null, $oTestObject->fcpoGetAmazonReferenceId($sMockJson));
    }

    /**
     * Testing _fcpoHandleSetOrderReferenceDetails for status ok
     */
    public function test__fcpoHandleSetOrderReferenceDetails_StatusOK() {
        $oTestObject = oxNew('fcpayone_ajax');

        $aMockResponse = array('status'=>'OK');

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestSetAmazonOrderReferenceDetails',
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('sendRequestSetAmazonOrderReferenceDetails')
            ->will($this->returnValue($aMockResponse));

        $oMockUser = $this->getMock('oxUser', array('fcpoSetAmazonOrderReferenceDetailsResponse'));
        $oMockUser
            ->expects($this->any())
            ->method('fcpoSetAmazonOrderReferenceDetailsResponse')
            ->will($this->returnValue(null));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oMockConfig = $this->getMock('oxConfig', array('getShopUrl'));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://www.someshop.com/'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->onConsecutiveCalls($oMockRequest,$oMockUser));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoHandleSetOrderReferenceDetails('someReferenceId','someAccessToken')
        );
    }

    /**
     * Testing _fcpoHandleSetOrderReferenceDetails for status error
     */
    public function test__fcpoHandleSetOrderReferenceDetails_StatusError() {
        $oTestObject = oxNew('fcpayone_ajax');

        $aMockResponse = array('status'=>'ERROR');

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestSetAmazonOrderReferenceDetails',
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('sendRequestSetAmazonOrderReferenceDetails')
            ->will($this->returnValue($aMockResponse));

        $oMockUser = $this->getMock('oxUser', array('fcpoSetAmazonOrderReferenceDetailsResponse'));
        $oMockUser
            ->expects($this->any())
            ->method('fcpoSetAmazonOrderReferenceDetailsResponse')
            ->will($this->returnValue(null));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oMockConfig = $this->getMock('oxConfig', array('getShopUrl'));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://www.someshop.com/'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->onConsecutiveCalls($oMockRequest,$oMockUser));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoHandleSetOrderReferenceDetails('someReferenceId','someAccessToken')
        );
    }

    /**
     * Testing _fcpoHandleGetOrderReferenceDetails for status OK
     */
    public function test__fcpoHandleGetOrderReferenceDetails_StatusOK() {
        $oTestObject = oxNew('fcpayone_ajax');

        $aMockResponse = array('status'=>'OK');

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestGetAmazonOrderReferenceDetails',
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('sendRequestGetAmazonOrderReferenceDetails')
            ->will($this->returnValue($aMockResponse));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oMockConfig = $this->getMock('oxConfig', array('getShopUrl'));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://www.someshop.com/'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockRequest));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $oHelper
            ->expects($this->any())
            ->method('fcpoDeleteSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoHandleGetOrderReferenceDetails('someReferenceId','someAccessToken')
        );
    }

    /**
     * Testing _fcpoHandleGetOrderReferenceDetails for status error
     */
    public function test__fcpoHandleGetOrderReferenceDetails_StatusError() {
        $oTestObject = oxNew('fcpayone_ajax');

        $aMockResponse = array('status'=>'ERROR');

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestSetAmazonOrderReferenceDetails',
        ));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oMockConfig = $this->getMock('oxConfig', array('getShopUrl'));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://www.someshop.com/'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockRequest));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $oHelper
            ->expects($this->any())
            ->method('fcpoDeleteSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoHandleGetOrderReferenceDetails('someReferenceId','someAccessToken')
        );
    }

    /**
     * Testing fcpoTriggerPrecheck for coverage
     */
    public function test_fcpoTriggerPrecheck_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $oMockPayment = $this->getMock('payment', array('setPayolutionAjaxParams', 'fcpoPayolutionPreCheck'));
        $oMockPayment->expects($this->any())->method('setPayolutionAjaxParams')->will($this->returnValue(null));
        $oMockPayment->expects($this->any())->method('fcpoPayolutionPreCheck')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('SUCCESS', $oTestObject->fcpoTriggerPrecheck('someId', 'someJson'));
    }

    /**
     * Testing fcpoTriggerInstallmentCalculation coverage
     */
    public function test_fcpoTriggerInstallmentCalculation_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $oMockPayment = $this->getMock('payment', array('fcpoPerformInstallmentCalculation', 'fcpoGetInstallments'));
        $oMockPayment->expects($this->any())->method('fcpoPerformInstallmentCalculation')->will($this->returnValue(null));
        $oMockPayment->expects($this->any())->method('fcpoGetInstallments')->will($this->returnValue(array('someResult')));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(array('someResult'), $oTestObject->fcpoTriggerInstallmentCalculation());
    }

    /**
     * Testing fcpoParseCalculation2Html coverage
     */
    public function test_fcpoParseCalculation2Html_Coverage() 
    {
        $aMockCalculation = array(
            'someIndex' => array(
                'Months' => array(
                    'someMonth' => array('rateDetails'),
                ),
            ),
        );

        $oTestObject = $this->getMock(
            'fcpayone_ajax', array(
            '_fcpoGetInsterestHiddenFields',
            '_fcpoGetInsterestRadio',
            '_fcpoGetInsterestLabel',
            '_fcpoGetInsterestMonthDetail',
            '_fcpoGetLightView',
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoGetInsterestHiddenFields')->will($this->returnValue('someValue'));
        $oTestObject->expects($this->any())->method('_fcpoGetInsterestRadio')->will($this->returnValue('someValue'));
        $oTestObject->expects($this->any())->method('_fcpoGetInsterestLabel')->will($this->returnValue('someValue'));
        $oTestObject->expects($this->any())->method('_fcpoGetInsterestMonthDetail')->will($this->returnValue('someValue'));
        $oTestObject->expects($this->any())->method('_fcpoGetLightView')->will($this->returnValue('someValue'));

        $oMockLang = $this->getMock('oxlang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->fcpoParseCalculation2Html($aMockCalculation);

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetLightView for coverage
     */
    public function test__fcpoGetLightView_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $sResponse = $sExpect = $oTestObject->_fcpoGetLightView();
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoReturnErrorMessage for coverage
     */
    public function test_fcpoReturnErrorMessage_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $oMockConfig = $this->getMock('oxconfig', array('isUtf'));
        $oMockConfig->expects($this->any())->method('isUtf')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->fcpoReturnErrorMessage('someMessage');
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetInsterestHiddenFields for coverage
     */
    public function test__fcpoGetInsterestHiddenFields_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $aMockInstallment = array(
            'Amount'=>'someAmount',
            'Duration'=>'someDuration',
            'EffectiveInterestRate'=>'someRate',
            'InterestRate'=>'someInterest',
            'TotalAmount'=>'someTotal',
        );

        $sResponse = $sExpect = $oTestObject->_fcpoGetInsterestHiddenFields('someKey', $aMockInstallment);
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetInsterestMonthDetail for coverage
     */
    public function test__fcpoGetInsterestMonthDetail_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $aMockRatesDetail = array(
            'Due'=>'someDue',
            'Amount'=>'someAmount',
            'Currency'=>'someCurrency',
        );

        $oMockLang = $this->getMock('oxlang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->_fcpoGetInsterestMonthDetail('someMonth', $aMockRatesDetail);
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetInsterestRadio for coverage
     */
    public function test__fcpoGetInsterestRadio_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');

        $sResponse = $sExpect = $oTestObject->_fcpoGetInsterestRadio('someKey', array());
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetInsterestRadio for coverage
     */
    public function test__fcpoGetInsterestLabel_Coverage() 
    {
        $oTestObject = $this->getMock('fcpayone_ajax', array('_fcpoGetInsterestCaption'));
        $oTestObject->expects($this->any())->method('_fcpoGetInsterestCaption')->will($this->returnValue('someCaption'));

        $sResponse = $sExpect = $oTestObject->_fcpoGetInsterestLabel('someKey', array());
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetInsterestCaption for coverage
     */
    public function test__fcpoGetInsterestCaption_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_ajax');
        $aMockInstallment = array(
            'Duration'=>'someDuration',
            'Amount'=>'someAmount',
            'Currency'=>'someCurrency',
        );

        $oMockLang = $this->getMock('oxlang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->_fcpoGetInsterestCaption($aMockInstallment);
        $this->assertEquals($sExpect, $sResponse);
    }

}
