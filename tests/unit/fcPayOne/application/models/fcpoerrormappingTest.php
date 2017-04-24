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
 
class MockResultErrorMapping
{

    public $EOF = false;
    public $fields = array('someValue', 'someValue', 'someValue', 'someValue', 'someValue');

    public function recordCount() 
    {
        return 1;
    }

    public function moveNext() 
    {
        $this->EOF = true;
    }

}

/**
 * Description of fcpoerrormappingTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2017-02-01
 */
class Unit_fcPayOne_Application_Models_fcpoerrormapping extends OxidTestCase
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
     * Testing fcpoGetExistingMappings for coverage
     */
    public function test_fcpoGetExistingMappings_Coverage() 
    {
        $oTestObject = oxNew('fcpoerrormapping');

        $aMockResult = array(
            array(
                'oxid'=>'someOxid',
                'fcpo_error_code'=>'someErrorCode',
                'fcpo_lang_id'=>'someLangId',
                'fcpo_mapped_message'=>'someMappedMessage'
            ),
        );
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aResponse = $aExpect = $oTestObject->fcpoGetExistingMappings();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoGetAvailableErrorCodes general
     */
    public function test_fcpoGetAvailableErrorCodes_General() 
    {
        $aMockData = array('some'=>'Data');
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoParseXml'));
        $oTestObject->expects($this->any())->method('_fcpoParseXml')->will($this->returnValue($aMockData));

        $this->assertEquals($aMockData, $oTestObject->fcpoGetAvailableErrorCodes());
    }

    /**
     * Testing fcpoGetAvailableErrorCodes iframe
     */
    public function test_fcpoGetAvailableErrorCodes_Iframe() 
    {
        $aMockData = array('some'=>'Data');
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoParseXml'));
        $oTestObject->expects($this->any())->method('_fcpoParseXml')->will($this->returnValue($aMockData));

        $this->assertEquals($aMockData, $oTestObject->fcpoGetAvailableErrorCodes('iframe'));
    }

    /**
     * Testing fcpoGetAvailableErrorCodes throwing exception
     * @expectedException oxException
     */
    public function test_fcpoGetAvailableErrorCodes_Exception() 
    {
        $oMockException = new oxException('someErrorMessage');
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoParseXml'));
        $oTestObject->expects($this->any())->method('_fcpoParseXml')->will($this->throwException($oMockException));

        $this->assertEquals(oxException::class, $oTestObject->fcpoGetAvailableErrorCodes());
    }

    /**
     * Testing fcpoUpdateMappings for coverage
     */
    public function test_fcpoUpdateMappings_Coverage() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoGetQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetQuery')->will($this->returnValue(true));

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $aMockMappings = array('someIndex' => array('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoUpdateMappings($aMockMappings, 'someType'));
    }

    /**
     * Testing fcpoFetchMappedErrorMessage for coverage
     */
    public function test_fcpoFetchMappedErrorMessage_Coverage() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoGetSearchQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetSearchQuery')->will($this->returnValue('someQuery'));

        $oMockDb = $this->getMock('oxdb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('MappedMessage'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $oMockUBase = $this->getMock('oxubase', array('getActiveLangAbbr'));
        $oMockUBase->expects($this->any())->method('getActiveLangAbbr')->will($this->returnValue('de'));

        $oMockLangData = new stdClass();
        $oMockLangData->abbr = 'de';
        $oMockLangData->id = '1';
        $aMockLangData = array($oMockLangData);

        $oMockLang = $this->getMock('oxlang', array('getLanguageArray'));
        $oMockLang->expects($this->any())->method('getLanguageArray')->will($this->returnValue($aMockLangData));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUBase));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('MappedMessage', $oTestObject->fcpoFetchMappedErrorMessage('someMessage'));
    }

    /**
     * Testing _fcpoGetMappingWhere for coverage
     */
    public function test__fcpoGetMappingWhere_Coverage() 
    {
        $sExpect = "WHERE fcpo_error_type='general'";
        $oTestObject = oxNew('fcpoerrormapping');

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetMappingWhere('general'));
    }

    /**
     * Testing _fcpoParseXml for coverage
     */
    public function test__fcpoParseXml_Coverage() 
    {
        $oTestObject = oxNew('fcpoerrormapping');

        $oMockUBase = $this->getMock('oxubase', array('getActiveLangAbbr'));
        $oMockUBase->expects($this->any())->method('getActiveLangAbbr')->will($this->returnValue('de'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUBase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockXmlEntry = new stdClass();
        $oMockXmlEntry->error_code = 'someErrorCode';
        $oMockXmlEntry->error_message_de = 'someMessage';
        $oMockXmlEntry->error_message_ = 'someMessage';

        $oMockXml = new stdClass();
        $oMockXml->entry = array($oMockXmlEntry);

        $oMockEntry = new stdClass();
        $oMockEntry->sErrorCode = 'someErrorCode';
        $oMockEntry->sErrorMessage = 'someMessage';

        $aExpect = array($oMockEntry);

        $this->assertEquals($aExpect, $oTestObject->_fcpoParseXml($oMockXml));
    }

    /**
     * Testing _fcpoXml2Array for coverage
     */
    public function test__fcpoXml2Array_Coverage() 
    {
        $oTestObject = oxNew('fcpoerrormapping');

        $sMockXml = '<root><mocknode><mockvar>mockvalue</mockvar></mocknode></root>';
        $oMockXml = simplexml_load_string($sMockXml);
        $aExpect = array('mocknode'=>array('mockvar'=>'mockvalue'));

        $this->assertEquals($aExpect, $oTestObject->_fcpoXml2Array($oMockXml));
    }

    /**
     * Testing _fcpoGetQuery for case of removing an entry
     */
    public function test__fcpoGetQuery_Delete() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoGetUpdateQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetUpdateQuery')->will($this->returnValue(true));

        $aMockData = array('delete' => true);
        $sMockOxid = 'someId';
        $sQuotedOxid = oxDb::getDb()->quote($sMockOxid);

        $sExpect = "DELETE FROM fcpoerrormapping WHERE oxid = {$sQuotedOxid}";

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetQuery($sMockOxid, $aMockData, 'someErrorType'));
    }

    /**
     * Testing _fcpoGetQuery for case of adding/updating an entry
     */
    public function test__fcpoGetQuery_Update() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoGetUpdateQuery'));
        $oTestObject->expects($this->any())->method('_fcpoGetUpdateQuery')->will($this->returnValue('someValue'));

        $aMockData = array('donotdelete' => true);
        $sMockOxid = 'someId';
        $sExpect = "someValue";

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetQuery($sMockOxid, $aMockData, 'someErrorType'));
    }

    /**
     * Testing _fcpoGetUpdateQuery inserting a new entry
     */
    public function test__fcpoGetUpdateQuery_Insert() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoIsValidNewEntry'));
        $oTestObject->expects($this->any())->method('_fcpoIsValidNewEntry')->will($this->returnValue(true));

        $oMockUtilsObject = $this->getMock('oxUtilsObject', array('generateUID'));
        $oMockUtilsObject->expects($this->any())->method('generateUID')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue($oMockUtilsObject));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockMappingId = 'someMapId';
        $sMockPaymentId = 'somePaymentId';
        $sMockPayoneStatus = 'someStatus';
        $sMockFolder = 'someFolder';

        $aMockData = array('sPaymentType' => $sMockPaymentId, 'sPayoneStatus' => $sMockPayoneStatus, 'sShopStatus' => $sMockFolder);

        $sResponse = $sExpect = $oTestObject->_fcpoGetUpdateQuery($sMockMappingId, $aMockData, 'someErrorType');

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetUpdateQuery inserting a new entry
     */
    public function test__fcpoGetUpdateQuery_Update() 
    {
        $oTestObject = $this->getMock('fcpoerrormapping', array('_fcpoIsValidNewEntry'));
        $oTestObject->expects($this->any())->method('_fcpoIsValidNewEntry')->will($this->returnValue(false));

        $oMockUtilsObject = $this->getMock('oxUtilsObject', array('generateUID'));
        $oMockUtilsObject->expects($this->any())->method('generateUID')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsObject')->will($this->returnValue($oMockUtilsObject));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockMappingId = 'someMapId';
        $sMockPaymentId = 'somePaymentId';
        $sMockPayoneStatus = 'someStatus';
        $sMockFolder = 'someFolder';

        $aMockData = array('sPaymentType' => $sMockPaymentId, 'sPayoneStatus' => $sMockPayoneStatus, 'sShopStatus' => $sMockFolder);

        $sResponse = $sExpect = $oTestObject->_fcpoGetUpdateQuery($sMockMappingId, $aMockData, 'someErrorType');

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetSearchQuery for coverage
     */
    public function test__fcpoGetSearchQuery_Coverage() 
    {
        $oTestObject = oxNew('fcpoerrormapping');

        $sExpect = "
            SELECT fcpo_mapped_message FROM fcpoerrormapping 
            WHERE 
            fcpo_error_code = 'someErrorCode' AND
            fcpo_lang_id = 'someId'
            LIMIT 1
        ";

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetSearchQuery('someErrorCode', 'someId'));
    }

    /**
     * Testing _fcpoIsValidNewEntry for coverage
     */
    public function test__fcpoIsValidNewEntry_Coverage() 
    {
        $oTestObject = oxNew('fcpoerrormapping');

        $sMockMappingId = 'new';
        $sMockPaymentId = 'somePaymentId';
        $sMockPayoneStatus = 'someStatus';
        $sMockFolder = 'someFolder';

        $this->assertEquals(true, $oTestObject->_fcpoIsValidNewEntry($sMockMappingId, $sMockPaymentId, $sMockPayoneStatus, $sMockFolder));
    }

}
