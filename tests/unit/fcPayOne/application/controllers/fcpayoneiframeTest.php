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
 
class Unit_fcPayOne_Application_Controllers_fcpayoneiframeTest extends OxidTestCase
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
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }    

    
    /**
     * Test render method to cover code
     * 
     * @param  void
     * @return void
     */
    public function test_Render_CheckCoverage() 
    {
        $oFcPoIframe = oxNew('fcpayoneiframe');

        
        $oHelper = $this->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper->expects($this->any())
            ->method('fcpoGetIntShopVersion')
            ->will($this->returnValue(4600));

        $this->invokeSetAttribute($oFcPoIframe, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('page/checkout/fcpayoneiframe.tpl', $this->invokeMethod($oFcPoIframe, 'render'));
    }

    
    
    /**
     * Test getOrder method to cover code
     * 
     * @param  void
     * @return void
     */
    public function test_getOrder_CheckCoverage() 
    {
        $oFcPoIframe = oxNew('fcpayoneiframe');
        
        // mock order object
        $oMockOrder = $this->getMockBuilder('oxOrder')
            ->disableOriginalConstructor()
            ->getMock();
        $oMockOrder->expects($this->any())
            ->method('load')
            ->will($this->returnValue(true));

        // mock helper object
        $oHelper    = $this->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper->expects($this->any())
            ->method('fcpoGetSessionVariable')
            ->will($this->returnValue(1));
        $oHelper->method('getFactoryObject')
            ->will($this->returnValue($oMockOrder));
                
        
        $this->invokeSetAttribute($oFcPoIframe, '_oFcpoHelper', $oHelper);
        
        $oOrderInstance = $this->invokeMethod($oFcPoIframe, 'getOrder');
        
        $blValidInstance = (
                $oOrderInstance instanceof Mock_oxOrder ||
                $oOrderInstance instanceof oxOrder ||
                $oOrderInstance instanceof fcPayOneOrder
        );
        
        $this->assertTrue($blValidInstance);
    }
    
    
    /**
     * Test getFactoryObject method to cover code
     * 
     * @param  void
     * @return void
     */
    public function test_getFactoryObject_Coverage() 
    {
        $oFcPoIframe = oxNew('fcpayoneiframe');
        $this->assertInstanceOf('fcPayOneOrder', $this->invokeMethod($oFcPoIframe, 'getFactoryObject', array('fcPayOneOrder')));
    }
    
    
    /**
     * Testing getIframeUrl on session variable set
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeUrl_InSession() 
    {
        $oFcPoIframe = oxNew('fcpayoneiframe');
        // mock helper object
        $oHelper    = $this->getMockBuilder('fcpohelper')
            ->disableOriginalConstructor()
            ->getMock();
        $oHelper->expects($this->any())
            ->method('fcpoGetSessionVariable')
            ->will($this->returnValue('http://www.example.org'));
        $this->invokeSetAttribute($oFcPoIframe, '_oFcpoHelper', $oHelper);

        $this->assertEquals('http://www.example.org', $oFcPoIframe->getIframeUrl());
    }
    
    
    /**
     * Testing getIframeUrl on session variable set
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeUrl_NoSession() 
    {
        // mock config
        $oConfig = $this->getMock('oxConfig', array('getShopCurrentURL'));
        $oConfig->method('getShopCurrentURL')->will($this->returnValue('http://www.example.org'));
        
        // mock utils
        $oUtils = $this->getMock('oxUtils', array('redirect'));
        $oUtils->method('redirect')->will($this->returnValue('http://www.example.org'));
        
        // mock helper object
        $oHelper = $this->getMock('fcpohelper', array('fcpoGetUtils', 'fcpoGetSessionVariable'));
        $oHelper->method('fcpoGetSessionVariable')->will($this->returnValue(false));
        $oHelper->method('fcpoGetUtils')->will($this->returnValue($oUtils));
        
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getConfig'));
        $oFcPoIframe->method('getConfig')->will($this->returnValue($oConfig));
        
        $this->invokeSetAttribute($oFcPoIframe, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oFcPoIframe->getIframeUrl());
    }
    
    
    /**
     * Covers the code for getIframeHeight
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeHeight_Coverage() 
    {
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oFcPoIframe->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));
        
        $this->assertEquals(700, $oFcPoIframe->getIframeHeight());
    }


    /**
     * Covers the code for getIframeWidth
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeWidth_Coverage() 
    {
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oFcPoIframe->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));
        
        $this->assertEquals(360, $oFcPoIframe->getIframeWidth());
    }
    

    /**
     * Covers the code for getIframeStyle
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeStyle_Coverage() 
    {
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oFcPoIframe->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));
        
        $this->assertEquals('border:0;margin-top:20px;', $oFcPoIframe->getIframeStyle());
    }
    

    /**
     * Covers the code for getIframeHeader
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeHeader_Coverage() 
    {
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oFcPoIframe->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));
        
        $aPossibleReturns = array(
            'FCPO_CC_IFRAME_HEADER',
            'Bezahlung mit Kreditkarte',
        );
        
        $this->assertTrue(in_array($oFcPoIframe->getIframeHeader(), $aPossibleReturns));
    }
    
    
    /**
     * Covers the code for getIframeText
     * 
     * @param  void
     * @return void
     */
    public function test_getIframeText_Coverage() 
    {
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oFcPoIframe->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));
        
        $this->assertEquals(false, $oFcPoIframe->getIframeText());
    }
    
    
    /**
     * Testing getIframeUrl on session variable set
     * 
     * @param  void
     * @return void
     */
    public function test_getPaymentType_PaymentTypeSet() 
    {
        // mock oxorder
        $oOrder = oxNew('oxOrder');
        $oOrder->oxorder__oxpaymenttype = new oxField('mockpaymentid');
        
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getOrder'));
        $oFcPoIframe->method('getOrder')->will($this->returnValue($oOrder));

        $this->assertEquals('mockpaymentid', $oFcPoIframe->getPaymentType());
    }
    
    
    /**
     * Testing getIframeUrl on session variable set
     * 
     * @param  void
     * @return void
     */
    public function test_getPaymentType_PaymentTypeFromSession() 
    {
        // mock oxorder
        $oOrder = oxNew('oxOrder');
        $oOrder->oxorder__oxpaymenttype = new oxField(false);
        
        
        // mock helper object
        $oHelper = $this->getMock('fcpohelper', array('fcpoGetSessionVariable'));
        $oHelper->method('fcpoGetSessionVariable')->will($this->returnValue('mockpaymentid'));
        
        $oFcPoIframe = $this->getMock('fcpayoneiframe', array('getOrder'));
        $oFcPoIframe->method('getOrder')->will($this->returnValue($oOrder));
        
        $this->invokeSetAttribute($oFcPoIframe, '_oFcpoHelper', $oHelper);

        $this->assertEquals('mockpaymentid', $oFcPoIframe->getPaymentType());
    }
    
}
