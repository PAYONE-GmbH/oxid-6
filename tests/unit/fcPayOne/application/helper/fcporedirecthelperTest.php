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

class fcporedirecthelperTest extends OxidTestCase
{
    public function testGetErrorUrl()
    {
        $sExpected = 'abortTest';

        $oClassToTest = new fcporedirecthelper();
        $sResult = $oClassToTest->getErrorUrl($sExpected, true);

        $this->assertStringContainsString($sExpected, $sResult);
        $this->assertStringContainsString('fcpoamzaction=logoff', $sResult);
    }

    public function testGetSuccessUrl()
    {
        $oSession = $this->getMockBuilder(\OxidEsales\Eshop\Core\Session::class)->disableOriginalConstructor()->getMock();
        $oSession->method('sid')->willReturn('sid12345');

        $oConfig = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->disableOriginalConstructor()->getMock();
        $oConfig->method('getCurrentShopUrl')->willReturn('shopURL');

        $oHelper = $this->getMockBuilder(fcpohelper::class)->disableOriginalConstructor()->getMock();
        $oHelper->method('fcpoGetRequestParameter')->willReturn('testValue');
        $oHelper->method('fcpoGetSession')->willReturn($oSession);
        $oHelper->method('fcpoGetConfig')->willReturn($oConfig);

        UtilsObject::setClassInstance(fcpohelper::class, $oHelper);

        $oClassToTest = new fcporedirecthelper();
        $sResult = $oClassToTest->getSuccessUrl('testRefNr', false, false, false);

        $this->assertStringContainsString('sid12345', $sResult);
        $this->assertStringContainsString('testValue', $sResult);
        $this->assertStringContainsString('testRefNr', $sResult);
        $this->assertStringContainsString('shopURL', $sResult);

        UtilsObject::resetClassInstances();
    }

    public function testDestroyInstance()
    {
        $this->assertNull(fcporedirecthelper::destroyInstance());
    }
}
