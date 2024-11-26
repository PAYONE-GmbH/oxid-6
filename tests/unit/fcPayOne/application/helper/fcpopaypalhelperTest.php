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

class fcpopaypalhelperTest extends OxidTestCase
{
    public function testGetButtonColor()
    {
        $sExpected = "gold";

        $oConfig = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->disableOriginalConstructor()->getMock();
        $oConfig->method('getConfigParam')->willReturn($sExpected);

        $oHelper = $this->getMockBuilder(fcpohelper::class)->disableOriginalConstructor()->getMock();
        $oHelper->method('fcpoGetConfig')->willReturn($oConfig);

        UtilsObject::setClassInstance(fcpohelper::class, $oHelper);

        $oClassToTest = new fcpopaypalhelper();
        $sResult = $oClassToTest->getButtonColor();

        $this->assertEquals($sExpected, $sResult);

        UtilsObject::resetClassInstances();
    }

    public function testGetButtonShape()
    {
        $sExpected = "rect";

        $oConfig = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->disableOriginalConstructor()->getMock();
        $oConfig->method('getConfigParam')->willReturn($sExpected);

        $oHelper = $this->getMockBuilder(fcpohelper::class)->disableOriginalConstructor()->getMock();
        $oHelper->method('fcpoGetConfig')->willReturn($oConfig);

        UtilsObject::setClassInstance(fcpohelper::class, $oHelper);

        $oClassToTest = new fcpopaypalhelper();
        $sResult = $oClassToTest->getButtonShape();

        $this->assertEquals($sExpected, $sResult);

        UtilsObject::resetClassInstances();
    }

    public function testShowBNPLButton()
    {
        $oConfig = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->disableOriginalConstructor()->getMock();
        $oConfig->method('getConfigParam')->willReturn(true);

        $oHelper = $this->getMockBuilder(fcpohelper::class)->disableOriginalConstructor()->getMock();
        $oHelper->method('fcpoGetConfig')->willReturn($oConfig);

        UtilsObject::setClassInstance(fcpohelper::class, $oHelper);

        $oClassToTest = new fcpopaypalhelper();
        $sResult = $oClassToTest->showBNPLButton();

        $this->assertTrue($sResult);

        UtilsObject::resetClassInstances();
    }

    public function testGetJavascriptUrl()
    {
        $oPaymentHelper = $this->getMockBuilder(fcpopaymenthelper::class)->disableOriginalConstructor()->getMock();
        $oPaymentHelper->method('isLiveMode')->willReturn(true);

        UtilsObject::setClassInstance(fcpopaymenthelper::class, $oPaymentHelper);

        $oCurrency = new \stdClass();
        $oCurrency->name = "EURTEST";

        $oBasket = $this->getMockBuilder(OxidEsales\Eshop\Application\Model\Basket::class)->disableOriginalConstructor()->getMock();
        $oBasket->method('getBasketCurrency')->willReturn($oCurrency);

        $oSession = $this->getMockBuilder(\OxidEsales\Eshop\Core\Session::class)->disableOriginalConstructor()->getMock();
        $oSession->method('getBasket')->willReturn($oBasket);

        $oConfig = $this->getMockBuilder(\OxidEsales\Eshop\Core\Config::class)->disableOriginalConstructor()->getMock();
        $oConfig->method('getConfigParam')->willReturnMap([
            ['blFCPOPayPalV2MerchantID', null, "merchantId"],
            ['blFCPOPayPalV2BNPL', null, true],
        ]);
        
        $oLang = $this->getMockBuilder(\OxidEsales\Eshop\Core\Language::class)->disableOriginalConstructor()->getMock();
        $oLang->method('translateString')->willReturn('not_found');

        $oHelper = $this->getMockBuilder(fcpohelper::class)->disableOriginalConstructor()->getMock();
        $oHelper->method('fcpoGetConfig')->willReturn($oConfig);
        $oHelper->method('fcpoGetSession')->willReturn($oSession);
        $oHelper->method('fcpoGetLang')->willReturn($oLang);

        UtilsObject::setClassInstance(fcpohelper::class, $oHelper);

        $oClassToTest = new fcpopaypalhelper();
        $sResult = $oClassToTest->getJavascriptUrl();

        $this->assertStringContainsString("FZ8jE7shhaY2mVydsWsSrjmHk0qJxmgJoWgHESqyoG35jL", $sResult);
        $this->assertStringContainsString("merchantId", $sResult);
        $this->assertStringContainsString("EURTEST", $sResult);
        $this->assertStringContainsString("enable-funding=paylater", $sResult);

        UtilsObject::resetClassInstances();
        fcpopaymenthelper::destroyInstance();
    }

    public function testGetInstance()
    {
        $oResult = fcpopaypalhelper::getInstance();
        $this->assertInstanceOf(fcpopaypalhelper::class, $oResult);

        fcpopaypalhelper::destroyInstance();
    }
}