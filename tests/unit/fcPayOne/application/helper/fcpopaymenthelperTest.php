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

use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\EshopCommunity\Application\Model\Payment;
use OxidEsales\Eshop\Core\Field;

class fcpopaymenthelperTest extends OxidTestCase
{
    public function testLoadPaymentMethodFalse()
    {
        $oPayment = $this->getMockBuilder(Payment::class)->disableOriginalConstructor()->getMock();
        $oPayment->method('load')->willReturn(false);

        UtilsObject::setClassInstance(Payment::class, $oPayment);

        $oClassToTest = new fcpopaymenthelper();
        $sResult = $oClassToTest->loadPaymentMethod('test');

        $this->assertFalse($sResult);

        UtilsObject::resetClassInstances();
    }

    public function testIsPaymentMethodActive()
    {
        $oPayment = $this->getMockBuilder('oxpayment')->disableOriginalConstructor()->getMock();
        $oPayment->method('load')->willReturn(true);
        $oPayment->method('__get')->willReturn(new Field(true));

        UtilsObject::setClassInstance('oxpayment', $oPayment);

        $oClassToTest = new fcpopaymenthelper();
        $sResult = $oClassToTest->isPaymentMethodActive('test');

        $this->assertTrue($sResult);

        UtilsObject::resetClassInstances();
    }

    public function testIsLiveMode()
    {
        $oPayment = $this->getMockBuilder('oxpayment')->disableOriginalConstructor()->getMock();
        $oPayment->method('load')->willReturn(true);
        $oPayment->method('__get')->willReturn(new Field(true));

        UtilsObject::setClassInstance('oxpayment', $oPayment);

        $oClassToTest = new fcpopaymenthelper();
        $sResult = $oClassToTest->isLiveMode('test');

        $this->assertTrue($sResult);

        UtilsObject::resetClassInstances();
    }
}