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
 * PHP version 5
 *
 * @copyright 2003 - 2016 Payone GmbH
 * @version   OXID eShop CE
 * @link      http://www.payone.de
 */

/**
 * Eventhandler for module activation and deactivation.
 */
class fcpayone_events
{

    /**
     * Database object
     *
     * @var fcpohelper
     */
    protected static $_oFcpoHelper = null;

    /**
     * Paymnts that were once used but now deprecated and marked for removal
     *
     * @var array
     */
    public static $_aRemovedPaymentMethods = array(
        'fcpoyapital',
        'fcpocommerzfinanz',
        'fcpoklarna_installment',
        'fcpocreditcard_iframe',
        'fcpobillsafe',
        'fcpoonlineueberweisung',
        'fcpoklarna',
        'fcpopaydirekt_express',
        'fcpo_giropay',
        'fcpo_trustly',
        'fcpobarzahlen',
        'fcpopaydirekt',
    );

    /**
     * Tables that have the oxtimestamp column whose type needs to be updated.
     *
     * @var string[]
     */
    protected static $updateOxtimestampTypeTable = [
        'fcporefnr',
        'fcporequestlog',
        'fcpotransactionstatus',
        'fcpopayment2country',
        'fcpostatusforwarding',
        'fcpostatusmapping',
        'fcpoerrormapping',
        'fcpoklarnastoreids',
        'fcpopdfmandates',
        'fcpoklarnacampaigns',
        'fcpopayoneexpresslogos',
        'fcpocheckedaddresses',
        'fcporatepay',
        'fcpouser2flag',
    ];

    public static $sQueryTableFcporefnr = "
        CREATE TABLE fcporefnr (
          FCPO_REFNR int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TXID varchar(32) NOT NULL DEFAULT '',
          FCPO_REFPREFIX varchar(32) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (FCPO_REFNR, FCPO_REFPREFIX)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcporequestlog = "
        CREATE TABLE fcporequestlog (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TIMESTAMP timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FCPO_REFNR int(11) NOT NULL DEFAULT '0',
          FCPO_REQUESTTYPE varchar(32) NOT NULL DEFAULT '',
          FCPO_RESPONSESTATUS varchar(32) NOT NULL DEFAULT '',
          FCPO_REQUEST text NOT NULL,
          FCPO_RESPONSE text NOT NULL,
          FCPO_PORTALID varchar(32) NOT NULL DEFAULT '',
          FCPO_AID varchar(32) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpotransactionstatus = "
        CREATE TABLE fcpotransactionstatus (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TIMESTAMP timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FCPO_ORDERNR int(11) DEFAULT '0',
          FCPO_KEY varchar(32) NOT NULL DEFAULT '',
          FCPO_TXACTION varchar(32) NOT NULL DEFAULT '',
          FCPO_PORTALID int(11) NOT NULL DEFAULT '0',
          FCPO_AID int(11) NOT NULL DEFAULT '0',
          FCPO_CLEARINGTYPE varchar(32) NOT NULL DEFAULT '',
          FCPO_TXTIME timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          FCPO_CURRENCY varchar(32) NOT NULL DEFAULT '',
          FCPO_USERID int(11) NOT NULL DEFAULT '0',
          FCPO_ACCESSNAME varchar(32) NOT NULL DEFAULT '',
          FCPO_ACCESSCODE varchar(32) NOT NULL DEFAULT '',
          FCPO_PARAM varchar(255) NOT NULL DEFAULT '',
          FCPO_MODE varchar(8) NOT NULL DEFAULT '',
          FCPO_PRICE double NOT NULL DEFAULT '0',
          FCPO_TXID int(11) NOT NULL DEFAULT '0',
          FCPO_REFERENCE int(11) NOT NULL DEFAULT '0',
          FCPO_SEQUENCENUMBER int(11) NOT NULL DEFAULT '0',
          FCPO_COMPANY varchar(255) NOT NULL DEFAULT '',
          FCPO_FIRSTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_LASTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_STREET varchar(255) NOT NULL DEFAULT '',
          FCPO_ZIP varchar(16) NOT NULL DEFAULT '',
          FCPO_CITY varchar(255) NOT NULL DEFAULT '',
          FCPO_EMAIL varchar(255) NOT NULL DEFAULT '',
          FCPO_COUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_SHIPPING_COMPANY varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_FIRSTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_LASTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_STREET varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_ZIP varchar(16) NOT NULL DEFAULT '',
          FCPO_SHIPPING_CITY varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_COUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_BANKCOUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_BANKACCOUNT varchar(32) NOT NULL DEFAULT '',
          FCPO_BANKCODE varchar(32) NOT NULL DEFAULT '',
          FCPO_BANKACCOUNTHOLDER varchar(255) NOT NULL DEFAULT '',
          FCPO_CARDEXPIREDATE varchar(8) NOT NULL DEFAULT '',
          FCPO_CARDTYPE varchar(8) NOT NULL DEFAULT '',
          FCPO_CARDPAN varchar(32) NOT NULL DEFAULT '',
          FCPO_CUSTOMERID int(11) NOT NULL DEFAULT '0',
          FCPO_BALANCE double NOT NULL DEFAULT '0',
          FCPO_RECEIVABLE double NOT NULL DEFAULT '0',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpopayment2country = "
        CREATE TABLE IF NOT EXISTS fcpopayment2country (
          OXID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          FCPO_PAYMENTID char(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          FCPO_COUNTRYID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          FCPO_TYPE char(8) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (`OXID`),
          KEY `FCPO_PAYMENTID` (`FCPO_PAYMENTID`),
          KEY `FCPO_COUNTRYID` (`FCPO_COUNTRYID`),
          KEY `FCPO_TYPE` (`FCPO_TYPE`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoStatusForwarding = "
        CREATE TABLE fcpostatusforwarding(
                OXID INT(11) NOT NULL AUTO_INCREMENT,
                FCPO_PAYONESTATUS VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_URL VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_TIMEOUT DOUBLE NOT NULL DEFAULT '0' ,
                OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
                PRIMARY KEY (`OXID`)
        );";
    public static $sQueryTableFcpoStatusMapping = "
        CREATE TABLE fcpostatusmapping(
                OXID INT(11) NOT NULL AUTO_INCREMENT ,
                FCPO_PAYMENTID CHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                FCPO_PAYONESTATUS VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_FOLDER VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
                PRIMARY KEY (`OXID`)
        );";
    public static $sQueryTableFcpoErrorMapping = "
        CREATE TABLE fcpoerrormapping(
                OXID INT(11) NOT NULL AUTO_INCREMENT ,
                FCPO_ERROR_CODE VARCHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                FCPO_LANG_ID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_MAPPED_MESSAGE TEXT CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_ERROR_TYPE VARCHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
                PRIMARY KEY (`OXID`),
                KEY `FCPO_ERROR_TYPE` (`FCPO_ERROR_TYPE`)
        );";
    public static $sQueryTableFcpoklarnastoreids = "
        CREATE TABLE fcpoklarnastoreids (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_STOREID varchar(32) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoPdfMandates = "
        CREATE TABLE fcpopdfmandates (
          OXORDERID char(32) COLLATE latin1_general_ci NOT NULL,
          FCPO_FILENAME varchar(32) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXORDERID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoklarnacampaigns = "
        CREATE TABLE fcpoklarnacampaigns (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_CAMPAIGN_CODE varchar(32) NOT NULL DEFAULT '',
          FCPO_CAMPAIGN_TITLE varchar(128) NOT NULL DEFAULT '',
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpopaypalexpresslogos = "
        CREATE TABLE fcpopayoneexpresslogos (
            OXID int(11) NOT NULL AUTO_INCREMENT,
            FCPO_ACTIVE TINYINT( 1 ) NOT NULL DEFAULT '0',
            FCPO_LANGID INT( 11 ) NOT NULL ,
            FCPO_LOGO VARCHAR( 255 ) NOT NULL ,
            FCPO_DEFAULT TINYINT( 1 ) NOT NULL DEFAULT '0',
            OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
            PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpocheckedaddresses = "
        CREATE TABLE fcpocheckedaddresses (
          fcpo_address_hash CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          fcpo_checkdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (fcpo_address_hash)
        ) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

    public static $sQueryTableFcpoShadowBasket = "
        CREATE TABLE IF NOT EXISTS `fcposhadowbasket` (
          `FCPOSESSIONID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          `OXORDERID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT '',
          `FCPOBASKET` LONGTEXT NOT NULL,
          `FCPOCREATED` datetime NOT NULL,
          `FCPOCHECKED` datetime DEFAULT NULL,
          PRIMARY KEY (`FCPOSESSIONID`),
          KEY `OXORDERID` (`OXORDERID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    public static $sQueryTableRatePay = "
        CREATE TABLE `fcporatepay` (
          `OXID` char(32) COLLATE latin1_general_ci NOT NULL,
          `OXPAYMENTID` char(32) COLLATE latin1_general_ci NOT NULL,
          `shopid` int(11) NOT NULL,
          `merchant_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `merchant_status` int(11) DEFAULT NULL,
          `shop_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `currency` char(32) NOT NULL,
          `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `activation_status_elv` int(11) DEFAULT NULL,
          `activation_status_installment` int(11) DEFAULT NULL,
          `activation_status_invoice` int(11) DEFAULT NULL,
          `activation_status_prepayment` int(11) DEFAULT NULL,
          `amount_min_longrun` double DEFAULT NULL,
          `b2b_pq_full` tinyint(1) DEFAULT NULL,
          `b2b_pq_light` tinyint(1) DEFAULT NULL,
          `b2b_elv` tinyint(1) DEFAULT NULL,
          `b2b_installment` tinyint(1) DEFAULT NULL,
          `b2b_invoice` tinyint(1) DEFAULT NULL,
          `b2b_prepayment` tinyint(1) DEFAULT NULL,
          `country_code_billing` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `country_code_delivery` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_address_pq_full` tinyint(1) DEFAULT NULL,
          `delivery_address_pq_light` tinyint(1) DEFAULT NULL,
          `delivery_address_elv` tinyint(1) DEFAULT NULL,
          `delivery_address_installment` tinyint(1) DEFAULT NULL,
          `delivery_address_invoice` tinyint(1) DEFAULT NULL,
          `delivery_address_prepayment` tinyint(1) DEFAULT NULL,
          `device_fingerprint_snippet_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `eligibility_device_fingerprint` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_elv` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_installment` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_invoice` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_pq_full` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_pq_light` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_prepayment` tinyint(1) DEFAULT NULL,
          `interest_rate_merchant_towards_bank` double DEFAULT NULL,
          `interestrate_default` double DEFAULT NULL,
          `interestrate_max` double DEFAULT NULL,
          `interestrate_min` double DEFAULT NULL,
          `min_difference_dueday` int(11) DEFAULT NULL,
          `month_allowed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `month_longrun` int(11) DEFAULT NULL,
          `month_number_max` int(11) DEFAULT NULL,
          `month_number_min` int(11) DEFAULT NULL,
          `payment_amount` double DEFAULT NULL,
          `payment_firstday` int(11) DEFAULT NULL,
          `payment_lastrate` double DEFAULT NULL,
          `rate_min_longrun` double DEFAULT NULL,
          `rate_min_normal` double DEFAULT NULL,
          `service_charge` double DEFAULT NULL,
          `tx_limit_elv_max` double DEFAULT NULL,
          `tx_limit_elv_min` double DEFAULT NULL,
          `tx_limit_installment_max` double DEFAULT NULL,
          `tx_limit_installment_min` double DEFAULT NULL,
          `tx_limit_invoice_max` double DEFAULT NULL,
          `tx_limit_invoice_min` double DEFAULT NULL,
          `tx_limit_prepayment_max` double DEFAULT NULL,
          `txLimitPrepaymentMin` double DEFAULT NULL,
          `valid_payment_firstdays` int(11) DEFAULT NULL,
          OXTIMESTAMP CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ";

    public static $sQueryTableFcpoUserFlags = "
        CREATE TABLE IF NOT EXISTS `fcpouserflags` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPOCODE` int(11) NOT NULL,
          `FCPOEFFECT` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
          `FCPOFLAGDURATION` int(11) NOT NULL,
          `FCPONAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `FCPODESC` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`OXID`),
          UNIQUE KEY `FCPOCODE` (`FCPOCODE`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ";

    public static $sQueryTableFcpoUser2Flag = "
        CREATE TABLE IF NOT EXISTS `fcpouser2flag` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `OXUSERID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPOUSERFLAGID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPODISPLAYMESSAGE` text COLLATE utf8_unicode_ci NOT NULL,
          `FCPOTIMESTAMP` DATETIME NOT NULL,
          PRIMARY KEY (`OXID`),
          KEY `OXUSERID` (`OXUSERID`,`FCPOUSERFLAGID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;    
    ";

    public static $sQueryTableStatusForwardQueue = "
        CREATE TABLE `fcpostatusforwardqueue` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCSTATUSMESSAGEID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCSTATUSFORWARDID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCTRIES` int(11) NOT NULL,
          `FCLASTTRY` DATETIME NOT NULL,
          `FCLASTREQUEST` text NOT NULL,
          `FCLASTRESPONSE` text NOT NULL,
          `FCRESPONSEINFO` text NOT NULL,
          `FCFULFILLED` tinyint(1) NOT NULL,
          PRIMARY KEY (`OXID`),
          KEY `FCSTATUSMESSAGEID` (`FCSTATUSMESSAGEID`),
          KEY `FCSTATUSFORWARDID` (`FCSTATUSFORWARDID`),
          KEY `FCFULFILLED` (`FCFULFILLED`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";

    public static $sQueryAlterOxorderTxid = "ALTER TABLE oxorder ADD COLUMN FCPOTXID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderRefNr = "ALTER TABLE oxorder ADD COLUMN FCPOREFNR VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderAuthMode = "ALTER TABLE oxorder ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderMode = "ALTER TABLE oxorder ADD COLUMN FCPOMODE VARCHAR(8) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxpaymentsAuthMode = "ALTER TABLE oxpayments ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing1 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNTHOLDER VARCHAR(64) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing2 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNT VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing3 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKCODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing4 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKNAME VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing5 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKBIC VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing6 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKIBAN VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing7 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_LEGALNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing8 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_DUEDATE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing9 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_REFERENCE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing10 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_INSTRUCTIONNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxuserPersonalId = "ALTER TABLE oxuser ADD COLUMN FCPOPERSONALID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxuserCurrentMalus = "ALTER TABLE oxuser ADD COLUMN FCPOCURRMALUS INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterFcporefnr = "ALTER TABLE fcporefnr ADD COLUMN FCPO_REFPREFIX VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY(FCPO_REFNR, FCPO_REFPREFIX);";
    public static $sQueryChangeFcporequestlog = "ALTER TABLE fcporequestlog CHANGE FCPO_REFNR FCPO_REFNR VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL;";
    public static $sQueryAlterCampaign1 = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_LANGUAGE VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
    public static $sQueryAlterCampaign2 = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_CURRENCY VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
    public static $sQueryAlterOxpaymentsLiveMode = "ALTER TABLE oxpayments ADD COLUMN FCPOLIVEMODE TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxpaymentsIsPayone = "ALTER TABLE oxpayments ADD COLUMN FCPOISPAYONE TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesCapturedAmount = "ALTER TABLE oxorderarticles ADD COLUMN FCPOCAPTUREDAMOUNT INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesDebitedAmount = "ALTER TABLE oxorderarticles ADD COLUMN FCPODEBITEDAMOUNT INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderDelcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPODELCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderPaycostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOPAYCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderWrapcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOWRAPCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderGiftcardcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOGIFTCARDCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderVoucherdiscountDebited = "ALTER TABLE oxorder ADD COLUMN FCPOVOUCHERDISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderDiscountDebited = "ALTER TABLE oxorder ADD COLUMN FCPODISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderNotChecked = "ALTER TABLE oxorder ADD COLUMN FCPOORDERNOTCHECKED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderWorkOrderId = "ALTER TABLE oxorder ADD COLUMN FCPOWORKORDERID VARCHAR(16) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderClearingReference = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGREFERENCE VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderProfileIdent = "ALTER TABLE oxorder ADD COLUMN FCPOPROFILEIDENT VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryChangeToVarchar1 = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_USERID FCPO_USERID VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryChangeToVarchar2 = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_TXID FCPO_TXID VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryChangeToVarchar3 = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_REFERENCE FCPO_REFERENCE VARCHAR( 32 ) NOT NULL DEFAULT '0'";
    public static $sQueryAlterFcpoTransactionStatusForwardState = "ALTER TABLE fcpotransactionstatus ADD COLUMN `FCPO_FORWARD_STATE` VARCHAR(32)";
    public static $sQueryAlterFcpoTransactionStatusForwardTries = "ALTER TABLE fcpotransactionstatus ADD COLUMN `FCPO_FORWARD_TRIES` int(11) NOT NULL DEFAULT 0";
    public static $sQueryChangeRefNrToVarchar = "ALTER TABLE oxorder CHANGE FCPOREFNR FCPOREFNR VARCHAR( 32 ) NOT NULL DEFAULT '0'";
    public static $sQueryAlterFcpoTransactionStatusChangeToChar = "ALTER TABLE fcpotransactionstatus CHANGE OXID OXID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;";
    public static $sQueryAlterFcpoTransactionForwardingChangeToChar = "ALTER TABLE fcpostatusforwarding CHANGE OXID OXID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;";
    public static $sQueryChangeOxtimestampType = "ALTER TABLE [REPLACE_WITH_TABLE_NAME] CHANGE OXTIMESTAMP OXTIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp';";
    public static $sQueryAlterFcpopdfmandatesOxtimestamp = "ALTER TABLE fcpopdfmandates ADD COLUMN OXTIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp';";
    public static $sQueryAlterFcpouser2flagFcpotimestamp = "ALTER TABLE fcpouser2flag CHANGE FCPOTIMESTAMP OXTIMESTAMP TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp';";
    public static $sQueryFcporequestlogCopyTimestampData = "UPDATE fcporequestlog SET OXTIMESTAMP = FCPO_TIMESTAMP;";
    public static $sQueryFcpotransactionstatusCopyTimestampData = "UPDATE fcpotransactionstatus SET OXTIMESTAMP = FCPO_TIMESTAMP;";
    public static $sQueryFcpocheckedaddressesCopyTimestampData = "UPDATE fcpocheckedaddresses SET OXTIMESTAMP = fcpo_checkdate;";
    public static $sQueryAlterFcpoShadowBasketFcbasketChangeToLongText = "ALTER TABLE fcposhadowbasket MODIFY FCPOBASKET LONGTEXT;";
    public static $sQueryAlterOxorderTxidIndex = "ALTER TABLE oxorder ADD INDEX FCPOTXID (`FCPOTXID`)";
    public static $sQueryAlterFcpotransactionstatusTxidIndex = "ALTER TABLE fcpotransactionstatus ADD INDEX FCPO_TXID (`FCPO_TXID`)";
    public static $sQueryAlterFcpotransactionstatusTxactionIndex = "ALTER TABLE fcpotransactionstatus ADD INDEX FCPO_TXACTION (`FCPO_TXACTION`)";
    public static $sQueryAlterFcpotransactionstatusSequencenumberIndex = "ALTER TABLE fcpotransactionstatus ADD INDEX FCPO_SEQUENCENUMBER (`FCPO_SEQUENCENUMBER`)";
    public static $sQueryAlterFcporequestlogRefnrIndex = "ALTER TABLE fcporequestlog ADD INDEX FCPO_REFNR (`FCPO_REFNR`)";
    public static $sQueryAlterFcporequestlogRequesttypeIndex = "ALTER TABLE fcporequestlog ADD INDEX FCPO_REQUESTTYPE (`FCPO_REQUESTTYPE`)";
    public static $sQueryAlterOxorderClearingBankAccountHolder = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGBANKACCOUNTHOLDER VARCHAR(64) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderClearingBankIban = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGBANKIBAN VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderClearingBankBic = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGBANKBIC VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderClearingDueDate = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGDUEDATE VARCHAR(32) DEFAULT '' NOT NULL;";

    /**
     * List of all Payone payment methods to add to the database
     *
     * @var string[]
     */
    public static $aPaymentMethods = array(
        'fcpoinvoice' => 'PAYONE Rechnungskauf',
        'fcpopayadvance' => 'PAYONE Vorkasse',
        'fcpodebitnote' => 'PAYONE Lastschrift',
        'fcpocashondel' => 'PAYONE Nachnahme',
        'fcpocreditcard' => 'PAYONE Kreditkarte',
        'fcpopaypal' => 'PAYONE PayPal',
        'fcpopaypal_express' => 'PAYONE PayPal Express',
        'fcpopaypalv2' => 'PAYONE PayPal V2',
        'fcpopaypalv2_express' => 'PAYONE PayPal Express V2',
        'fcpoklarna_invoice' => 'PAYONE Klarna Rechnung',
        'fcpoklarna_installments' => 'PAYONE Klarna Ratenkauf',
        'fcpoklarna_directdebit' => 'PAYONE Klarna Sofort bezahlen',
        'fcpopo_bill' => 'PAYONE Unzer Rechnungskauf',
        'fcpopo_debitnote' => 'PAYONE Unzer Lastschrift',
        'fcpopo_installment' => 'PAYONE Unzer Ratenkauf',
        'fcporp_bill' => 'PAYONE Ratepay Rechnungskauf',
        'fcpoamazonpay' => 'PAYONE Amazon Pay',
        'fcpo_secinvoice' => 'PAYONE Gesicherter Rechnungskauf',
        'fcpo_sofort' => 'PAYONE Sofort Überweisung',
        'fcpo_eps' => 'PAYONE eps Überweisung',
        'fcpo_pf_finance' => 'PAYONE PostFinance E-Finance',
        'fcpo_pf_card' => 'PAYONE PostFinance Card',
        'fcpo_ideal' => 'PAYONE iDEAL',
        'fcpo_p24' => 'PAYONE Przelewy24',
        'fcpo_bancontact' => 'PAYONE Bancontact',
        'fcporp_debitnote' => 'PAYONE Ratepay Lastschrift',
        'fcpo_alipay' => 'PAYONE Alipay',
        'fcpo_wechatpay'=> 'PAYONE WeChat Pay',
        'fcpo_apple_pay'=> 'PAYONE Apple Pay',
        'fcporp_installment' => 'PAYONE Ratepay Ratenkauf',
        'fcpopl_secinvoice' => 'PAYONE Gesicherter Rechnungskauf (neu)',
        'fcpopl_secinstallment' => 'PAYONE Gesicherter Ratenkauf',
        'fcpopl_secdebitnote' => 'PAYONE Gesicherte Lastschrift',
    );

    /**
     * Execute action on activate event.
     *
     * @return void
     */
    public static function onActivate()
    {
        $sMessage = "";
        self::$_oFcpoHelper = oxNew('fcpohelper');
        self::addDatabaseStructure();
        $sMessage .= "Datenbankstruktur angepasst...<br>";
        self::addPayonePayments();
        $sMessage .= "Payone-Zahlarten hinzugef&uuml;gt...<br>";
        self::removeDeprecated();
        $sMessage .= "Veraltete Eintr&auml;ge entfernt...<br>";
        self::regenerateViews();
        $sMessage .= "Datenbank-Views erneuert...<br>";
        self::setDefaultConfigValues();
        self::clearTmp();
        $sMessage .= "Tmp geleert...<br>";
        $sMessage .= "Installation erfolgreich!<br>";
        // self::$_oFcpoHelper->fcpoGetUtilsView()->addErrorToDisplay($sMessage, false, true);
    }

    /**
     * Execute action on deactivate event.
     *
     * @return void
     */
    public static function onDeactivate()
    {
        self::$_oFcpoHelper = oxNew('fcpohelper');
        self::deactivatePaymethods();
        $sMessage = "Payone-Zahlarten deaktiviert!<br>";
        self::clearTmp();
        $sMessage .= "Tmp geleert...<br>";
        //self::$_oFcpoHelper->fcpoGetUtilsView()->addErrorToDisplay($sMessage, false, true);
    }

    /**
     * Regenerates database view-tables.
     *
     * @return void
     */
    public static function regenerateViews()
    {
        $oShop = oxNew('oxShop');
        $oShop->generateViews();
    }

    /**
     * Clear tmp dir and smarty cache.
     *
     * @return void
     */
    public static function clearTmp()
    {
        $sTmpDir = getShopBasePath() . "/tmp/";
        $sSmartyDir = $sTmpDir . "smarty/";

        foreach (glob($sTmpDir . "*.txt") as $sFileName) {
            @unlink($sFileName);
        }
        foreach (glob($sSmartyDir . "*.php") as $sFileName) {
            @unlink($sFileName);
        }
    }

    /**
     * Adding payone payments.
     *
     * @return void
     */
    public static function addPayonePayments()
    {
        $oDb = oxDb::getDb();

        $sShopId = self::$_oFcpoHelper->fcpoGetConfig()->getShopId();

        foreach (self::$aPaymentMethods as $sPaymentOxid => $sPaymentName) {
            //INSERT PAYMENT METHOD
            $blMethodCreated = self::insertRowIfNotExists('oxpayments', array('OXID' => $sPaymentOxid), "INSERT INTO oxpayments(OXID,OXACTIVE,OXDESC,OXADDSUM,OXADDSUMTYPE,OXFROMAMOUNT,OXTOAMOUNT,OXVALDESC,OXCHECKED,OXDESC_1,OXVALDESC_1,OXDESC_2,OXVALDESC_2,OXDESC_3,OXVALDESC_3,OXLONGDESC,OXLONGDESC_1,OXLONGDESC_2,OXLONGDESC_3,OXSORT,FCPOISPAYONE,FCPOAUTHMODE,FCPOLIVEMODE) VALUES ('{$sPaymentOxid}', 0, '{$sPaymentName}', 0, 'abs', 0, 1000000, '', 0, '{$sPaymentName}', '', '', '', '', '', '', '', '', '', 0, 1, 'preauthorization', 0);");

            // If method go created, user groups are assigned, otherwise we keep what is already set
            if ($blMethodCreated) {
                //INSERT PAYMENT METHOD CONFIGURATION
                $blInserted = self::insertRowIfNotExists('oxobject2group', array('OXSHOPID' => $sShopId, 'OXOBJECTID' => $sPaymentOxid), "INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidadmin');");
                if ($blInserted === true) {
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidcustomer');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxiddealer');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidforeigncustomer');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidgoodcust');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidmiddlecust');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidnewcustomer');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidnewsletter');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidnotyetordered');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidpowershopper');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidpricea');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidpriceb');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidpricec');");
                    $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (REPLACE(UUID(),'-',''), '{$sShopId}', '{$sPaymentOxid}', 'oxidsmallcust');");
                }
            }

            self::insertRowIfNotExists('oxobject2payment', array('OXPAYMENTID' => $sPaymentOxid, 'OXTYPE' => 'oxdelset'), "INSERT INTO oxobject2payment(OXID,OXPAYMENTID,OXOBJECTID,OXTYPE) values (REPLACE(UUID(),'-',''), '{$sPaymentOxid}', 'oxidstandard', 'oxdelset');");
        }
    }

    /**
     * Removing depreacted stuff.
     *
     * @return void
     */
    public static function removeDeprecated()
    {
        foreach (self::$_aRemovedPaymentMethods as $sRemovedPaymentMethod) {
            self::dropRowIfExists("oxpayments", array('OXID' => $sRemovedPaymentMethod), "DELETE FROM oxpayments WHERE OXID='" . $sRemovedPaymentMethod . "'");
            self::dropRowIfExists("oxobject2group", array('OXOBJECTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2group WHERE oxobjectid='" . $sRemovedPaymentMethod . "'");
            self::dropRowIfExists("oxobject2payment", array('OXPAYMENTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2payment WHERE oxpaymentid='" . $sRemovedPaymentMethod . "'");
        }
    }

    /**
     * Creating database structure changes.
     *
     * IMPORTANT: Any changes should NOT be made in the "create table" statement but in a new "add column" or
     * "change column" statement, which should be called in this method.
     *
     * @return void
     */
    public static function addDatabaseStructure()
    {
        //CREATE NEW TABLES
        self::addTableIfNotExists('fcporefnr', self::$sQueryTableFcporefnr);
        self::addTableIfNotExists('fcporequestlog', self::$sQueryTableFcporequestlog);
        self::addTableIfNotExists('fcpotransactionstatus', self::$sQueryTableFcpotransactionstatus);
        self::addTableIfNotExists('fcpopayment2country', self::$sQueryTableFcpopayment2country);
        self::addTableIfNotExists('fcpocheckedaddresses', self::$sQueryTableFcpocheckedaddresses);
        self::addTableIfNotExists('fcposhadowbasket', self::$sQueryTableFcpoShadowBasket);
        self::addTableIfNotExists('fcpostatusforwarding', self::$sQueryTableFcpoStatusForwarding);
        self::addTableIfNotExists('fcpostatusmapping', self::$sQueryTableFcpoStatusMapping);
        self::addTableIfNotExists('fcpoerrormapping', self::$sQueryTableFcpoErrorMapping);
        self::addTableIfNotExists('fcpoklarnastoreids', self::$sQueryTableFcpoklarnastoreids);
        self::addTableIfNotExists('fcpoklarnacampaigns', self::$sQueryTableFcpoklarnacampaigns);
        self::addTableIfNotExists('fcpopdfmandates', self::$sQueryTableFcpoPdfMandates);
        self::addTableIfNotExists('fcpopayoneexpresslogos', self::$sQueryTableFcpopaypalexpresslogos);
        self::addTableIfNotExists('fcporatepay', self::$sQueryTableRatePay);
        self::addTableIfNotExists('fcpouserflags', self::$sQueryTableFcpoUserFlags);
        self::addTableIfNotExists('fcpouser2flag', self::$sQueryTableFcpoUser2Flag);
        self::addTableIfNotExists('fcpostatusforwardqueue', self::$sQueryTableStatusForwardQueue);

        //ADD COLUMNS TO EXISTING TABLES
        self::addColumnIfNotExists('oxorder', 'FCPOTXID', self::$sQueryAlterOxorderTxid);
        self::addColumnIfNotExists('oxorder', 'FCPOREFNR', self::$sQueryAlterOxorderRefNr);
        self::addColumnIfNotExists('oxorder', 'FCPOAUTHMODE', self::$sQueryAlterOxorderAuthMode);
        self::addColumnIfNotExists('oxorder', 'FCPOMODE', self::$sQueryAlterOxorderMode);

        self::addColumnIfNotExists('oxorder', 'FCPODELCOSTDEBITED', self::$sQueryAlterOxorderDelcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOPAYCOSTDEBITED', self::$sQueryAlterOxorderPaycostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOWRAPCOSTDEBITED', self::$sQueryAlterOxorderWrapcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOGIFTCARDCOSTDEBITED', self::$sQueryAlterOxorderGiftcardcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOVOUCHERDISCOUNTDEBITED', self::$sQueryAlterOxorderVoucherdiscountDebited);
        self::addColumnIfNotExists('oxorder', 'FCPODISCOUNTDEBITED', self::$sQueryAlterOxorderDiscountDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOORDERNOTCHECKED', self::$sQueryAlterOxorderNotChecked);
        self::addColumnIfNotExists('oxorder', 'FCPOWORKORDERID', self::$sQueryAlterOxorderWorkOrderId);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGREFERENCE', self::$sQueryAlterOxorderClearingReference);
        self::addColumnIfNotExists('oxorder', 'FCPOPROFILEIDENT', self::$sQueryAlterOxorderProfileIdent);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGBANKACCOUNTHOLDER', self::$sQueryAlterOxorderClearingBankAccountHolder);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGBANKIBAN', self::$sQueryAlterOxorderClearingBankIban);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGBANKBIC', self::$sQueryAlterOxorderClearingBankBic);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGDUEDATE', self::$sQueryAlterOxorderClearingDueDate);

        self::addColumnIfNotExists('oxorderarticles', 'FCPOCAPTUREDAMOUNT', self::$sQueryAlterOxorderarticlesCapturedAmount);
        self::addColumnIfNotExists('oxorderarticles', 'FCPODEBITEDAMOUNT', self::$sQueryAlterOxorderarticlesDebitedAmount);

        self::addColumnIfNotExists('oxpayments', 'FCPOISPAYONE', self::$sQueryAlterOxpaymentsIsPayone);
        self::addColumnIfNotExists('oxpayments', 'FCPOAUTHMODE', self::$sQueryAlterOxpaymentsAuthMode);
        self::addColumnIfNotExists('oxpayments', 'FCPOLIVEMODE', self::$sQueryAlterOxpaymentsLiveMode);

        self::addColumnIfNotExists('oxuser', 'FCPOPERSONALID', self::$sQueryAlterOxuserPersonalId);
        self::addColumnIfNotExists('oxuser', 'FCPOCURRMALUS', self::$sQueryAlterOxuserCurrentMalus);

        self::addColumnIfNotExists('fcporefnr', 'FCPO_REFPREFIX', self::$sQueryAlterFcporefnr);

        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNTHOLDER', self::$sQueryAlterTxStatusClearing1);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNT', self::$sQueryAlterTxStatusClearing2);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKCODE', self::$sQueryAlterTxStatusClearing3);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKNAME', self::$sQueryAlterTxStatusClearing4);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKBIC', self::$sQueryAlterTxStatusClearing5);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKIBAN', self::$sQueryAlterTxStatusClearing6);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_LEGALNOTE', self::$sQueryAlterTxStatusClearing7);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_DUEDATE', self::$sQueryAlterTxStatusClearing8);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_REFERENCE', self::$sQueryAlterTxStatusClearing9);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_INSTRUCTIONNOTE', self::$sQueryAlterTxStatusClearing10);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_FORWARD_STATE', self::$sQueryAlterFcpoTransactionStatusForwardState);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_FORWARD_TRIES', self::$sQueryAlterFcpoTransactionStatusForwardTries);

        self::addColumnIfNotExists('fcpoklarnacampaigns', 'FCPO_CAMPAIGN_LANGUAGE', self::$sQueryAlterCampaign1);
        self::addColumnIfNotExists('fcpoklarnacampaigns', 'FCPO_CAMPAIGN_CURRENCY', self::$sQueryAlterCampaign2);

        self::addColumnIfNotExists('fcpopdfmandates', 'OXTIMESTAMP', self::$sQueryAlterFcpopdfmandatesOxtimestamp);

        //COPY DATA FROM OLD FCPO_TIMESTAMP COLUMN TO OXTIMESTAMP
        self::copyDataFromOldColumnIfExists('fcporequestlog', 'FCPO_TIMESTAMP', 'OXTIMESTAMP', self::$sQueryFcporequestlogCopyTimestampData);
        self::copyDataFromOldColumnIfExists('fcpotransactionstatus', 'FCPO_TIMESTAMP', 'OXTIMESTAMP', self::$sQueryFcpotransactionstatusCopyTimestampData);
        self::copyDataFromOldColumnIfExists('fcpocheckedaddresses', 'fcpo_checkdate', 'OXTIMESTAMP', self::$sQueryFcpocheckedaddressesCopyTimestampData);

        //DROP OLD FCPO_TIMESTAMP COLUMN
        self::dropColumnIfItAndReplacementExist('fcporequestlog', 'FCPO_TIMESTAMP', 'OXTIMESTAMP');
        self::dropColumnIfItAndReplacementExist('fcpotransactionstatus', 'FCPO_TIMESTAMP', 'OXTIMESTAMP');
        self::dropColumnIfItAndReplacementExist('fcpocheckedaddresses', 'fcpo_checkdate', 'OXTIMESTAMP');

        //CHANGE COLUMN NAMES
        self::changeColumnNameIfWrong('fcpouser2flag', 'FCPOTIMESTAMP', self::$sQueryAlterFcpouser2flagFcpotimestamp);

        //CHANGE COLUMN TYPES (AND NAMES)
        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'FCPO_USERID', 'varchar(32)', self::$sQueryChangeToVarchar1);
        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'FCPO_TXID', 'varchar(32)', self::$sQueryChangeToVarchar2);
        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'FCPO_REFERENCE', 'varchar(32)', self::$sQueryChangeToVarchar3);
        self::changeColumnTypeIfWrong('fcporequestlog', 'FCPO_REFNR', 'varchar(32)', self::$sQueryChangeFcporequestlog);
        self::changeColumnTypeIfWrong('oxorder', 'FCPOREFNR', 'varchar(32)', self::$sQueryChangeRefNrToVarchar);
        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'OXID', 'char(32)', self::$sQueryAlterFcpoTransactionStatusChangeToChar);
        self::changeColumnTypeIfWrong('fcpostatusforwarding', 'OXID', 'char(32)', self::$sQueryAlterFcpoTransactionForwardingChangeToChar);

        //UPDATE ALL OXTIMESTAMP COLUMN TYPES
        foreach (self::$updateOxtimestampTypeTable as $table) {
            self::changeColumnTypeIfWrong($table, 'OXTIMESTAMP', 'TIMESTAMP', str_replace('[REPLACE_WITH_TABLE_NAME]', $table, self::$sQueryChangeOxtimestampType));
        }

        self::dropIndexIfExists('fcporefnr', 'FCPO_REFNR');

        //ADD PAYPAL EXPRESS LOGOS
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '1'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(1, 1, 0, 'btn_xpressCheckout_de.gif', 1);");
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '2'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(2, 1, 1, 'btn_xpressCheckout_en.gif', 0);");
        // add available user flags
        self::insertRowIfNotExists('fcpouserflags', array('OXID' => 'fcporatepayrejected'), "INSERT INTO fcpouserflags (OXID, FCPOCODE, FCPOEFFECT, FCPOFLAGDURATION, FCPONAME, FCPODESC) VALUES ('fcporatepayrejected', 307, 'RPR', 24, 'Ratepay Rejected', 'CUSTOM');");

        // OX6-127: CHANGE SHADOW BASKET TYPE
        self::changeColumnTypeIfWrong('fcposhadowbasket', 'FCPOBASKET', 'LONGTEXT', self::$sQueryAlterFcpoShadowBasketFcbasketChangeToLongText);

        // OX6-147: ADD PERFORMANCE INDEXES
        self::addIndexIfNotExists('oxorder', 'FCPOTXID', self::$sQueryAlterOxorderTxidIndex);
        self::addIndexIfNotExists('fcpotransactionstatus', 'FCPO_TXID', self::$sQueryAlterFcpotransactionstatusTxidIndex);
        self::addIndexIfNotExists('fcpotransactionstatus', 'FCPO_TXACTION', self::$sQueryAlterFcpotransactionstatusTxactionIndex);
        self::addIndexIfNotExists('fcpotransactionstatus', 'FCPO_SEQUENCENUMBER', self::$sQueryAlterFcpotransactionstatusSequencenumberIndex);
        self::addIndexIfNotExists('fcporequestlog', 'FCPO_REFNR', self::$sQueryAlterFcporequestlogRefnrIndex);
        self::addIndexIfNotExists('fcporequestlog', 'FCPO_REQUESTTYPE', self::$sQueryAlterFcporequestlogRequesttypeIndex);
    }

    /**
     * Add a database table.
     *
     * @param string $sTableName table to add
     * @param string $sQuery     sql-query to add table
     *
     * @return boolean true or false
     */
    public static function addTableIfNotExists($sTableName, $sQuery)
    {
        $aTables = oxDb::getDb()->getAll("SHOW TABLES LIKE '{$sTableName}'");
        if (!$aTables || count($aTables) == 0) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Add a column to a database table.
     *
     * @param string $sTableName  table name
     * @param string $sColumnName column name
     * @param string $sQuery      sql-query to add column to table
     *
     * @return boolean true or false
     */
    public static function addColumnIfNotExists($sTableName, $sColumnName, $sQuery)
    {
        $aColumns = oxDb::getDb()->getAll("SHOW COLUMNS FROM {$sTableName} LIKE '{$sColumnName}'");

        if (!$aColumns || count($aColumns) === 0) {

            try {
                oxDb::getDb()->Execute($sQuery);
            } catch (Exception $e) {

            }
            return true;
        }
        return false;
    }

    /**
     * Copy data from old column.
     *
     * @param string $sTableName database table name
     * @param string $oldColumnName database old column name
     * @param string $newColumnName database new column name
     * @param string $copyDataQuery  sql query to execute
     * @return bool
     */
    public static function copyDataFromOldColumnIfExists($sTableName, $oldColumnName, $newColumnName, $copyDataQuery)
    {
        if (oxDb::getDb()->getOne("SHOW COLUMNS FROM {$sTableName} WHERE FIELD = '{$oldColumnName}'") &&
            oxDb::getDb()->getOne("SHOW COLUMNS FROM {$sTableName} WHERE FIELD = '{$newColumnName}'")
        ) {
            oxDb::getDb()->Execute($copyDataQuery);
            return true;
        }
        return false;
    }

    /**
     * Insert a database row to an existing table.
     *
     * @param string $sTableName database table name
     * @param array  $aKeyValue  keys of rows to add for existance check
     * @param string $sQuery     sql-query to insert data
     *
     * @return boolean true or false
     */
    public static function insertRowIfNotExists($sTableName, $aKeyValue, $sQuery)
    {
        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }
        $sCheckQuery = "SELECT * FROM {$sTableName} WHERE 1" . $sWhere;
        $sExisting = oxDb::getDb()->getOne($sCheckQuery);

        if (!$sExisting) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Check and change database table structure.
     *
     * @param string $sTableName    database table name
     * @param string $sColumnName   database column name
     * @param string $sExpectedType column structure type for comparison
     * @param string $sQuery        sql-query to execute
     *
     * @return boolean true or false
     */
    public static function changeColumnTypeIfWrong($sTableName, $sColumnName, $sExpectedType, $sQuery)
    {
        $sCheckQuery = "
            SHOW COLUMNS 
            FROM {$sTableName} 
            WHERE FIELD = '{$sColumnName}' 
            AND TYPE = '{$sExpectedType}'
        ";
        if (!oxDb::getDb()->getOne($sCheckQuery)) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Check and change database table structure.
     *
     * @param string $sTableName    database table name
     * @param string $sColumnName   database column name
     * @param string $sQuery        sql-query to execute
     *
     * @return boolean true or false
     */
    public static function changeColumnNameIfWrong($sTableName, $sColumnName, $sQuery)
    {
        $checkQuery = "SHOW COLUMNS FROM {$sTableName} WHERE FIELD = '{$sColumnName}'";
        if (oxDb::getDb()->getOne($checkQuery)) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Add a database index.
     *
     * @param string $sTable database table name
     * @param string $sIndex database index name
     * @param string $sQuery sql-query to execute
     *
     * @return boolean true or false
     */
    public static function addIndexIfNotExists($sTable, $sIndex, $sQuery)
    {
        $sExisting = oxDb::getDb()->getOne("SHOW KEYS FROM {$sTable} WHERE Key_name = '{$sIndex}'");
        if (!$sExisting) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }

        return false;
    }

    /**
     * Delete a database index.
     *
     * @param string $sTable database table name
     * @param string $sIndex database index name
     *
     * @return boolean true or false
     */
    public static function dropIndexIfExists($sTable, $sIndex)
    {
        if (oxDb::getDb()->getOne("SHOW KEYS FROM {$sTable} WHERE Key_name = '{$sIndex}'")) {
            oxDb::getDb()->Execute("ALTER TABLE {$sTable} DROP INDEX {$sIndex}");
            // echo "In Tabelle {$sTable} den Index {$sIndex} entfernt.<br>";
            return true;
        }
        return false;
    }

    /**
     * Delete a database column if the column itself and the new replacing column exist.
     *
     * @param string $sTable database table name
     * @param string $oldColumn database old column name
     * @param string $replacementColumn database replacement column name
     *
     * @return boolean true or false
     */
    public static function dropColumnIfItAndReplacementExist($sTable, $oldColumn, $replacementColumn)
    {
        if (oxDb::getDb()->getOne("SHOW COLUMNS FROM {$sTable} WHERE FIELD = '{$oldColumn}'") &&
            oxDb::getDb()->getOne("SHOW COLUMNS FROM {$sTable} WHERE FIELD = '{$replacementColumn}'")
        ) {
            oxDb::getDb()->Execute("ALTER TABLE {$sTable} DROP COLUMN {$oldColumn}");
            // echo "In Tabelle {$sTable} die Spalte {$sColumn} entfernt.<br>";
            return true;
        }
        return false;
    }

    /**
     * Drop a table entry.
     *
     * @param string $sTableName database table name
     * @param array  $aKeyValue  array of keys to drop
     * @param string $sQuery     sql-query to execute
     *
     * @return boolean
     */
    public static function dropRowIfExists($sTableName, $aKeyValue, $sQuery)
    {
        $blReturn = false;
        $sWhere = '';

        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }
        if (oxDb::getDb()->getOne("SELECT * FROM {$sTableName} WHERE 1" . $sWhere)) {
            oxDb::getDb()->Execute($sQuery);
            $blReturn = true;
        }

        return $blReturn;
    }

    /**
     * Get the OXID eShop version.
     *
     * @return string versionnumber
     */
    public static function getCurrentVersion()
    {
        return self::$_oFcpoHelper->fcpoGetConfig()->getActiveShop()->oxshops__oxversion->value;
    }

    /**
     * Checks if OXID eShop is below given version.
     *
     * @param string $sMaxVersion maximum allowed version
     *
     * @return boolean true or false
     */
    public static function isUnderVersion($sMaxVersion)
    {
        $sCurrVersion = self::getCurrentVersion();
        $blReturn = (version_compare($sCurrVersion, $sMaxVersion, '<')) ? true : false;

        return $blReturn;
    }

    /**
     * Checks if OXID eShop is above given version.
     *
     * @param string  $sMinVersion      minimum allowed version
     * @param boolean $blEqualOrGreater define if equal is allowed
     *
     * @return boolean true or false
     */
    public static function isOverVersion($sMinVersion, $blEqualOrGreater = false)
    {
        $sCompareOperator = ($blEqualOrGreater) ? '>=' : '>';
        $sCurrVersion = self::getCurrentVersion();

        $blReturn = (version_compare($sCurrVersion, $sMinVersion, $sCompareOperator)) ? true : false;

        return $blReturn;
    }

    /**
     * Checks if OXID eShop is between two given versions.
     *
     * @param string $sMinVersion minimum allowed version number
     * @param string $sMaxVersion maximum allowed version number
     *
     * @return boolean true or false
     */
    public static function isBetweenVersions($sMinVersion, $sMaxVersion)
    {
        if (!isOverVersion($sMinVersion, true)) {
            return false;
        }
        if (!isUnderVersion($sMaxVersion)) {
            return false;
        }
        return true;
    }

    /**
     * Copies a sourcefile to a destination.
     *
     * @param string $sSource      path to source-file
     * @param string $sDestination path to destination-file
     *
     * @return boolean true or false
     */
    public static function copyFile($sSource, $sDestination)
    {
        if (file_exists($sSource) === true) {
            self::deleteFileIfExists($sDestination);
            if (copy($sSource, $sDestination)) {
                echo 'Datei ' . $sDestination . ' in Theme kopiert.<br>';
            } else {
                echo '<span style="color:red;">ERROR:</span> Kopieren fehlgeschlagen. Bitte kopieren Sie die Datei manuell von "' . $sSource . '" nach "' . $sDestination . '"<br>';
            }
        }
    }

    /**
     * Checks if given file exists and deletes if it exists.
     *
     * @param string $sFile path to file
     *
     * @return void
     */
    public static function deleteFileIfExists($sFile)
    {
        if (file_exists($sFile)) {
            unlink($sFile);
        }
    }

    /**
     * Deactivates payone paymethods on module deactivation.
     *
     * @return void
     */
    public static function deactivatePaymethods()
    {
        $sPaymenthodIds = "'" . implode("','", array_keys(self::$aPaymentMethods)) . "'";
        $sQ = "update oxpayments set oxactive = 0 where oxid in ($sPaymenthodIds)";
        oxDB::getDB()->Execute($sQ);
    }

    /**
     * Sets default config values on activation.
     *
     * @return void
     */
    public static function setDefaultConfigValues()
    {
        $oConfig = self::$_oFcpoHelper->fcpoGetConfig();

        if (!$oConfig->getConfigParam('sFCPOAddresscheck')) {
            $oConfig->saveShopConfVar('str', 'sFCPOAddresscheck', 'NO');
        }
    }

    /**
     * If there is an existing merchant id we assume, that current activation
     * is an update
     *
     * @param void
     * @return bool
     */
    public static function isUpdate()
    {
        $oConfig = self::$_oFcpoHelper->fcpoGetConfig();

        return (bool) ($oConfig->getConfigParam('sFCPOMerchantID'));
    }

}
