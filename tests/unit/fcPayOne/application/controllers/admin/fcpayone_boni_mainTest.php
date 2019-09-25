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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_boni_main extends OxidTestCase
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
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing render method for code coverage
     * 
     * @param  void
     * @return void
     */
    public function test_Render_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_boni_main');
        $oMockLang = oxNew('oxLang');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(''));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'fcpayone_boni_main.tpl';
        $this->assertEquals($sExpect, $oTestObject->render());
    }

    /**
     * Testing render method for code coverage
     * 
     * @param  void
     * @return void
     */
    public function test_Save_Coverage() {
        $oTestObject = $this->getMock('fcpayone_boni_main', array(
            '_fcpoValidateAddresscheckType',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateAddresscheckType')
            ->will($this->returnValue(null));

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('saveShopConfVar')->will($this->returnValue(true));

        $aConfVars = array();
        $aConfVars['sFCPOApprovalText'] = 'VarValue';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls('', $aConfVars, $aConfVars));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
    }

    /**
     * Testing fcpoShowRegularAddresscheck for coverafge
     *
     * @param void
     * @return void
     */
    public function test_fcpoShowRegularAddresscheck_Coverage() {
        $oTestObject = $this->getMock('fcpayone_boni_main', array(
            '_fcpoCheckBonicheckIsActive',
            '_fcpoDeactivateRegularAddressCheck',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckBonicheckIsActive')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoDeactivateRegularAddressCheck')
            ->will($this->returnValue(null));

        $this->assertEquals(false, $oTestObject->fcpoShowRegularAddresscheck());
    }

    /**
     * Testing _fcpoCheckBonicheckIsActive for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoCheckBonicheckIsActive_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue('-1'));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoCheckBonicheckIsActive());
    }

    /**
     * Testing _fcpoCheckRegularAddressCheckActive for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoCheckRegularAddressCheckActive_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue('NO'));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            false,
            $oTestObject->_fcpoCheckRegularAddressCheckActive()
        );
    }

    /**
     * Testing _fcpoBoniAddresscheckActive for coverage
     *
     * @param void
     * @return void
     */
    protected function _fcpoValidateAddresscheckType() {
        $this->_aValidationCodes = array();
        $this->_fcpoCheckIssetBoniAddresscheck();
        $this->_fcpoValidateDuplicateAddresscheck();
        $this->_fcpoValidateAddresscheckBasic();
        $this->_fcpoValidateAddresscheckPerson();
        $this->_fcpoValidateAddresscheckBoniversum();
        $this->_fcpoDisplayValidationMessages();
    }

    /**
     * Testing _fcpoDeactivateRegularAddressCheck for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoDeactivateRegularAddressCheck_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $oMockConfig = $this->getMock('oxConfig', array('saveShopConfVar'));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            false,
            $oTestObject->_fcpoDeactivateRegularAddressCheck()
        );
    }

    /**
     * Testing _fcpoValidateAddresscheckType for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoValidateAddresscheckType_Coverage() {
        $oTestObject = $this->getMock('fcpayone_boni_main', array(
            '_fcpoCheckIssetBoniAddresscheck',
            '_fcpoValidateDuplicateAddresscheck',
            '_fcpoValidateAddresscheckBasic',
            '_fcpoValidateAddresscheckPerson',
            '_fcpoValidateAddresscheckBoniversum',
            '_fcpoDisplayValidationMessages',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckIssetBoniAddresscheck')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateDuplicateAddresscheck')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateAddresscheckBasic')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateAddresscheckPerson')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateAddresscheckBoniversum')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoDisplayValidationMessages')
            ->will($this->returnValue(null));

        $this->assertEquals(
            null,
            $oTestObject->_fcpoValidateAddresscheckType()
        );
    }

    /**
     * Testing _fcpoValidateAddresscheckBasic for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoValidateAddresscheckBasic_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $aMockConfStrs = array (
            'sFCPOBonicheck' => 'IB',
            'sFCPOConsumerAddresscheck' => 'BB',
        );

        $oMockConfig = $this->getMock('oxConfig', array('saveShopConfVar'));
        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue($aMockConfStrs));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoValidateAddresscheckBasic()
        );
    }

    /**
     * Testing _fcpoValidateAddresscheckPerson for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoValidateAddresscheckPerson_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $aMockConfStrs = array (
            'sFCPOBonicheck' => 'IB',
            'sFCPOConsumerAddresscheck' => 'PB',
        );

        $oMockConfig = $this->getMock('oxConfig', array('saveShopConfVar'));
        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue($aMockConfStrs));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoValidateAddresscheckPerson()
        );
    }

    /**
     * Testing _fcpoCheckIssetBoniAddresscheck for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoCheckIssetBoniAddresscheck_Coverage() {
        $oTestObject = $this->getMock('fcpayone_boni_main', array(
            '_fcpoCheckBonicheckIsActive',
            '_fcpoBoniAddresscheckActive',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckBonicheckIsActive')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoBoniAddresscheckActive')
            ->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('saveShopConfVar'));
        $oMockConfig
            ->expects($this->any())
            ->method('saveShopConfVar')
            ->will($this->returnValue(null));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertEquals(
            null,
            $oTestObject->_fcpoCheckIssetBoniAddresscheck()
        );
    }

    /**
     * Testing _fcpoValidateDuplicateAddresscheck for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoValidateDuplicateAddresscheck_Coverage() {
        $oTestObject = $this->getMock('fcpayone_boni_main', array(
            '_fcpoCheckBonicheckIsActive',
            '_fcpoDeactivateRegularAddressCheck',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckBonicheckIsActive')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoDeactivateRegularAddressCheck')
            ->will($this->returnValue(null));

        $this->assertEquals(
            null,
            $oTestObject->_fcpoValidateDuplicateAddresscheck()
        );
    }

    /**
     * Testing _fcpoValidateAddresscheckBoniversum for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoValidateAddresscheckBoniversum_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $oMockConfig = $this->getMock('oxConfig', array(
            'getConfigParam',
            'saveShopConfVar',
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->onConsecutiveCalls('CE', 'SOMEWRONGVALUE'));
        $oMockConfig
            ->expects($this->any())
            ->method('saveShopConfVar')
            ->will($this->returnValue(null));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            null,
            $oTestObject->_fcpoValidateAddresscheckBoniversum()
        );
    }

    /**
     * Testing _fcpoDisplayValidationMessages for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoDisplayValidationMessages_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $sExpect = 'someTranslatedMessage';

        $oMockLang = $this->getMock('oxLang', array(
            'translateString',
        ));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue($sExpect));

        $oMockUtilsView = $this->getMock('oxUtilsView', array(
            'addErrorToDisplay',
        ));
        $oMockUtilsView
            ->expects($this->any())
            ->method('addErrorToDisplay')
            ->will($this->returnValue(null));

        $oHelper = $this
            ->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetLang')
            ->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aValidationCodes', array('1'));

        $this->assertEquals(null, $oTestObject->_fcpoDisplayValidationMessages());
    }

    /**
     * Testing _fcpoDisplayMessage for coverage
     */
    public function test__fcpoDisplayMessage_Coverage() {
        $oTestObject = oxNew('fcpayone_boni_main');
        $iMockValidateCode = 1;

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(null));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('translatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoDisplayMessage($iMockValidateCode));
    }
}
