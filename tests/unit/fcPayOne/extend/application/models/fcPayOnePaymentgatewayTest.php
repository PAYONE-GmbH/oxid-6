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
 
class Unit_fcPayOne_Extend_Application_Models_fcPayOnePaymentgatewayTest extends OxidTestCase
{
    

    /**
     * Testing parent call of execute payment
     */
    public function test_executePayment_Parent() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('isPayOnePaymentType'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(false));
        
        $oTestObject = oxNew('fcPayOnePaymentgateway');
        $mResult = $mExpect = $oTestObject->executePayment(1, $oMockOrder);
        
        $this->assertEquals($mExpect, $mResult);
    }
    
    
    /**
     * Covering the extended part of executePayment
     */
    public function test_executePayment_Coverage() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('isPayOnePaymentType','fcHandleAuthorization'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oMockOrder->expects($this->any())->method('fcHandleAuthorization')->will($this->returnValue('someValue'));
        
        $oTestObject = oxNew('fcPayOnePaymentgateway');
        
        $this->assertEquals('someValue', $oTestObject->executePayment(1, $oMockOrder));
    }
    
    
    /**
     * Testing fcSetLastErrorNr for coverage
     */
    public function test_fcSetLastErrorNr_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePaymentgateway');
        $this->assertEquals(null, $oTestObject->fcSetLastErrorNr('someValue'));
    }
    
    
    /**
     * Testing fcSetLastError for coverage
     */
    public function test_fcSetLastError_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePaymentgateway');
        $this->assertEquals(null, $oTestObject->fcSetLastError('someValue'));
    }
    
    
}
