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

class MockResultStoreIds
{

    public $EOF = false;
    public $fields = array(array('oxid' => 'someId', 'fcpo_storeid' => 'someStore'));

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
 * Description of fcpoklarnaTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2016-09-08
 */
class Unit_fcPayOne_Application_Models_fcpoklarna extends OxidTestCase
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
     * Testing fcpoGetStoreIds for coverage
     */
    public function test_fcpoGetStoreIds_Coverage() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $aMockResult = array(array('oxid' => 'someId', 'fcpo_storeid' => 'someStore'));
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aResponse = $aExpect = $oTestObject->fcpoGetStoreIds();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoInsertCampaigns as Update and Delete call
     */
    public function test_fcpoInsertCampaigns_UpdateAndDelete() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aMockCampaignData = array(
            'someId' => array(
                'code' => 'someCode',
                'title' => 'someTitle',
                'language' => 'someLanguage',
                'currency' => 'someCurrency'
            ),
            'someOtherId' => array(
                'delete' => 'byebye',
            ),
        );

        $aResponse = $aExpect = $oTestObject->fcpoInsertCampaigns($aMockCampaignData);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoInsertCampaigns not having valid data to insert
     */
    public function test_fcpoInsertCampaigns_NoValidData() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aMockCampaignData = 'I am not an array!';

        $aResponse = $aExpect = $oTestObject->fcpoInsertCampaigns($aMockCampaignData);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoInsertStoreIds performing an update and delete task
     */
    public function test_fcpoInsertStoreIds_UpdateAndDelete() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aStoreIds = array(
            'someId' => array(
                'id' => 'someStoreId',
            ),
            'someOtherId' => array(
                'delete' => 'someStoreId',
            ),
        );

        $aResponse = $aExpect = $oTestObject->fcpoInsertStoreIds($aStoreIds);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoInsertStoreIds performing an insert call with invalid data
     */
    public function test_fcpoInsertStoreIds_Invalid() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aStoreIds = 'Hell, you guessed it: No array in sight!';

        $aResponse = $aExpect = $oTestObject->fcpoInsertStoreIds($aStoreIds);
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoAddKlarnaStoreId for coverage
     */
    public function test_fcpoAddKlarnaStoreId_Coverage() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertNull($oTestObject->fcpoAddKlarnaStoreId());
    }

    /**
     * Testing fcpoAddKlarnaCampaign for coverage
     */
    public function test_fcpoAddKlarnaCampaign_Coverage() 
    {
        $oTestObject = oxNew('fcpoklarna');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertNull($oTestObject->fcpoAddKlarnaCampaign());
    }

}
