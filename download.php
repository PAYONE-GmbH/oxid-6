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
 
set_time_limit(0);
ini_set('memory_limit', '1024M');
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

include_once dirname(__FILE__) . "/../../../bootstrap.php";

/**
 * Description of fcPayOneMandateDownload
 *
 * @author Robert
 */
class fcPayOneMandateDownload extends oxUBase
{
    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * init object construction
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
    }

    /**
     * Render overloading
     *
     * @param void
     * @return void
     */
    public function render()
    {
        parent::render();
        $this->_fcpoMandateDownloadAction();
        exit();
    }

    /**
     * Redownload existing mandate from payone platform
     *
     * @param $sMandateFilename
     * @param $sOrderId
     * @param $sPaymentId
     */
    protected function _redownloadMandate($sMandateFilename, $sOrderId, $sPaymentId)
    {
        $sMandateIdentification = str_replace('.pdf', '', $sMandateFilename);
        $oPayment = oxNew('oxPayment');
        $oPayment->load($sPaymentId);
        $sMode = $oPayment->fcpoGetOperationMode();

        $oPORequest = oxNew('fcporequest');
        $oPORequest->sendRequestGetFile($sOrderId, $sMandateIdentification, $sMode);
    }

    /**
     * Returns user id which has been sent directly as param
     * or fetch userid from other sources
     *
     * @param void
     * @return mixed
     */
    protected function _fcpoGetUserId()
    {
        $sUserId = $this->_oFcpoHelper->fcpoGetRequestParameter('uid');
        $oUser = $this->getUser();
        if ($oUser) {
            $sUserId = $oUser->getId();
        }

        return $sUserId;
    }

    /**
     * Return query for fetching mandate mandatory information
     *
     * @param void
     * @return string
     */
    protected function _fcpoGetMandateQuery()
    {
        $sOrderId = $this->_oFcpoHelper->fcpoGetRequestParameter('id');
        $sUserId = $this->_fcpoGetUserId();

        $sWhere = "
            b.oxuserid = ".oxDb::getDb()->quote($sUserId)."
        ";
        $sOrderBy = "
            ORDER BY
                b.oxorderdate DESC        
        ";
        if ($sOrderId) {
            $sWhere = "
                b.oxid = ".oxDb::getDb()->quote($sOrderId)." AND
                b.oxuserid = ".oxDb::getDb()->quote($sUserId)."
            ";
            $sOrderBy = "";
        }

        $sQuery = "
            SELECT 
                a.fcpo_filename,
                b.oxid,
                b.fcpomode,
                b.oxpaymenttype
            FROM 
                fcpopdfmandates AS a
            INNER JOIN
                oxorder AS b ON a.oxorderid = b.oxid
            WHERE {$sWhere} {$sOrderBy} LIMIT 1        
        ";

        return $sQuery;
    }

    /**
     * Triggers download action for mandate
     *
     * @param void
     * @return void
     */
    protected function _fcpoMandateDownloadAction()
    {
        $oDb = oxDb::getDb();
        $sQuery = $this->_fcpoGetMandateQuery();
        $aResult = $oDb->GetRow($sQuery);

        if (!is_array($aResult)) {
            echo 'Permission denied!';
            return;
        }

        $sFilename = (string) $aResult[0];
        $sOrderId = (string) $aResult[1];
        $sPaymentId = (string) $aResult[2];

        $sPath = getShopBasePath().'modules/fc/fcpayone/mandates/'.$sFilename;

        if(!file_exists($sPath)) {
            $this->_redownloadMandate($sFilename, $sOrderId, $sPaymentId);
        }

        if(!file_exists($sPath)) {
            echo 'Error: File not found!';#
            return;
        }

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"{$sFilename}\"");
        readfile($sPath);
    }
}

$oDownload = oxNew('fcPayOneMandateDownload');
$oDownload->render();
