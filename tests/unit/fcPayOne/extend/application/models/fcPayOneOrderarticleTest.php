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
 
class Unit_fcPayOne_Extend_Application_Models_fcPayOneOrderarticleTest extends OxidTestCase
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
     * Testing save method for calling parent save
     */
    public function test_save_Parent() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('isPayOnePaymentType'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(false));

        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoGetBefore'));
        $oTestObject->expects($this->any())->method('_fcpoGetBefore')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $oTestObject->save($oMockOrder, true);
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing save method for coverage
     */
    public function test_save_Coverage_1() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('isPayOnePaymentType'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));

        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoGetBefore', 'getOrder'));
        $oTestObject->expects($this->any())->method('_fcpoGetBefore')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getOrder')->will($this->returnValue($oMockOrder));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $mResponse = $mExpect = $oTestObject->save($oMockOrder, true);

        $this->assertEquals($mResponse, $mExpect);
    }
    

    /**
     * Testing save method for coverage
     */
    public function test_save_Coverage_2() 
    {
        $oMockOrder = $this->getMock('fcPayOneOrder', array('isPayOnePaymentType'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));

        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoGetBefore', 'getOrder'));
        $oTestObject->expects($this->any())->method('_fcpoGetBefore')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getOrder')->will($this->returnValue($oMockOrder));
        
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls(true, true, false, true, true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $mResponse = $mExpect = $oTestObject->save($oMockOrder, true);

        $this->assertEquals($mResponse, $mExpect);
    }
    
    
    /**
     * Testing save method for calling parent delete
     */
    public function test_delete_Parent() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));
        
        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        
        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoIsPayonePaymentType'));
        $oTestObject->expects($this->any())->method('_fcpoIsPayonePaymentType')->will($this->returnValue(false));
        $oTestObject->oxorderarticles__oxstorno = new oxField(0);
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $oTestObject->delete('someId');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing save method for coverage
     */
    public function test_delete_Coverage() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));
        
        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        
        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoIsPayonePaymentType', '_fcpoProcessBaseDelete'));
        $oTestObject->expects($this->any())->method('_fcpoIsPayonePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessBaseDelete')->will($this->returnValue(true));
        $oTestObject->oxorderarticles__oxstorno = new oxField(0);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sResponse = $sExpect = $oTestObject->delete('someId');
        
        $this->assertEquals($sExpect, $sResponse);
    }
    
    
    /**
     * Testing _fcpoGetBefore for receiving a positive answer
     */
    public function test__fcpoGetBefore_ExpectTrue() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        
        $oTestObject = $this->getMock('fcPayOneOrderarticle', array('_fcpoIsPayonePaymentType'));
        $oTestObject->expects($this->any())->method('_fcpoIsPayonePaymentType')->will($this->returnValue(true));
        $oTestObject->oxorderarticles__oxstorno = new oxField(0);
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls(true, false));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoGetBefore(true));
    }
    
    
    /**
     * Testing _fcpoIsPayonePaymentType for case iframe
     */
    public function test__fcpoIsPayonePaymentType_IFrame() 
    {
        $oTestObject = oxNew('fcPayOneOrderarticle');
        $this->assertEquals(false, $oTestObject->_fcpoIsPayonePaymentType('someId', true));
    }
    

    /**
     * Testing _fcpoIsPayonePaymentType for case no iframe
     */
    public function test__fcpoIsPayonePaymentType_NoFrame() 
    {
        $oTestObject = oxNew('fcPayOneOrderarticle');
        $this->assertEquals(false, $oTestObject->_fcpoIsPayonePaymentType('someId', false));
    }
    
    
    /**
     * Testing _fcpoProcessBaseDelete for coverage
     */
    public function test__fcpoProcessBaseDelete_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderarticle');
        $this->assertEquals(false, $oTestObject->_fcpoProcessBaseDelete('someId'));
        
    }
}
