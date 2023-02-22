[{include file="headitem.tpl" title="[OXID Benutzerverwaltung]"}]
[{assign var="currStatus" value=$oView->fcpoGetCurrentStatus()}]
[{assign var="status_oxid" value=$oView->fcpoGetStatusOxid()}]
[{assign var="status" value=$oView->getStatus()}]
[{assign var="oOrderarticles" value=$edit->getOrderArticles()}]

[{oxscript include=$oViewConf->fcpoGetModuleJsPath('fcpayone_order.js')}]


[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form autocomplete="off" name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="status_oxid" value="[{$status_oxid}]">
    <input type="hidden" name="cl" value="fcpayone_order">
</form>

<form autocomplete="off" name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="fcpayone_order">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="status_oxid" value="[{$status_oxid}]">
    <input type="hidden" id="fc_error_message_capture_greater_null" value="[{oxmultilang ident="FCPO_CAPTURE_AMOUNT_GREATER_NULL"}]">
    <input type="hidden" id="fc_confirm_message" value="[{oxmultilang ident="FCPO_ARE_YOU_SURE"}]">
    
    <table border="0" width="98%">
        <tr>
            <td width="45%" valign="top">
                <table>
                    [{if $edit->oxorder__fcpoordernotchecked->value == 1}]
                        <tr>
                            <td class="edittext" colspan="2">
                                <strong style="color:red;">[{oxmultilang ident="FCPO_ORDERNOTCHECKED"}]</strong>
                            </td>
                        </tr>
                    [{/if}]
                    [{if $oView->fcpoGetRequestMessage()}]
                        <tr>
                            <td class="edittext" colspan="2">
                                <strong>[{$oView->fcpoGetRequestMessage()}]</strong>
                            </td>
                        </tr>
                    [{/if}]
                    <tr>
                        <td class="edittext" style="width: 200px;">
                            [{oxmultilang ident="FCPO_REFNR"}]
                        </td>
                        <td class="edittext">
                            [{$edit->oxorder__fcporefnr->value}]
                        </td>
                    </tr>

                    <tr>
                        <td class="edittext" >
                            [{oxmultilang ident="FCPO_TXID"}]
                        </td>
                        <td class="edittext">
                            [{$edit->oxorder__fcpotxid->value}]
                        </td>
                    </tr>
                    [{assign var=sMandateUrl value=$oView->fcpoGetMandatePdfUrl()}]
                    [{if $sMandateUrl}]
                        <tr>
                            <td class="edittext" >
                                [{oxmultilang ident="FCPO_MANDATE_PDF"}]
                            </td>
                            <td class="edittext">
                                <a href="[{$sMandateUrl}]" target="_blank" style="text-decoration:underline;">[{oxmultilang ident="FCPO_MANDATE_DOWNLOAD"}]</a>
                            </td>
                        </tr>                        
                    [{/if}]

                    [{assign var="lastStatus" value=$edit->getLastStatus()}]
                    [{if $lastStatus}]
                        <tr>
                            <td class="edittext">
                                [{oxmultilang ident="FCPO_PAYMENTTYPE"}]
                            </td>
                            <td class="edittext">
                                [{$lastStatus->getClearingtype()}]
                            </td>

                        </tr>

                        [{if $lastStatus->fcpotransactionstatus__fcpo_bankaccount->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_BANKACCOUNT"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->fcpotransactionstatus__fcpo_bankaccount->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $lastStatus->fcpotransactionstatus__fcpo_bankcode->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_BANKCODE"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->fcpotransactionstatus__fcpo_bankcode->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $lastStatus->fcpotransactionstatus__fcpo_bankaccountholder->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_BANKACCOUNTHOLDER"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->fcpotransactionstatus__fcpo_bankaccountholder->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $lastStatus->fcpotransactionstatus__fcpo_cardexpiredate->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_CARDEXPIREDATE"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->fcpotransactionstatus__fcpo_cardexpiredate->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $lastStatus->fcpotransactionstatus__fcpo_cardtype->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_CARDTYPE"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->getCardtype()}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $lastStatus->fcpotransactionstatus__fcpo_cardpan->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    [{oxmultilang ident="FCPO_CARDPAN"}]
                                </td>
                                <td class="edittext">
                                    [{$lastStatus->fcpotransactionstatus__fcpo_cardpan->value}]
                                </td>
                            </tr>
                        [{/if}]

                    [{/if}]

                    [{if $edit->allowCapture()}]
                        [{assign var="blShowCapture" value=true}]
                        [{if $edit->isDetailedProductInfoNeeded()}]
                            [{assign var="blShowCapture" value=false}]
                            [{assign var="oOrderarticles" value=$edit->getOrderArticles()}]
                            [{foreach from=$oOrderarticles item=oOrderArt}]
                                [{assign var="iLeftAmount" value=$oOrderArt->oxorderarticles__oxamount->value-$oOrderArt->oxorderarticles__fcpocapturedamount->value}]
                                [{if $iLeftAmount > 0}]
                                    [{assign var="blShowCapture" value=true}]
                                [{/if}]
                            [{/foreach}]
                            [{if $blShowCapture == true}]
                                <tr><td colspan="2" style="border-bottom: 1px solid black;"></td></tr>
                                <tr><td class="edittext" colspan="2"><strong>[{oxmultilang ident="FCPO_CAPTURE"}]</strong></td></tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <td class="edittext">
                                        <strong>[{oxmultilang ident="FCPO_PREAUTHORIZED_AMOUNT"}]</strong>
                                    </td>
                                    <td class="edittext">
                                        [{$edit->oxorder__oxtotalordersum->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]
                                    </td>
                                </tr>                                   
                                <tr>
                                    <td colspan="2">
                                        <table border="0" cellpadding="5" callspacing="5">
                                            <tr>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_CAPTURE"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_AMOUNT"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_PRICE"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_TITLE"}]</th>
                                            </tr>
                                            [{foreach from=$oOrderarticles item=oOrderArt}]
                                                [{assign var="iLeftAmount" value=$oOrderArt->oxorderarticles__oxamount->value-$oOrderArt->oxorderarticles__fcpocapturedamount->value}]
                                                [{if $iLeftAmount > 0}]
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="capture_positions[[{$oOrderArt->getId()}]][price]" value="[{$oOrderArt->oxorderarticles__oxbprice->value}]">
                                                            <input type="hidden" name="capture_positions[[{$oOrderArt->getId()}]][capture]" value="0">
                                                            <input type="checkbox" name="capture_positions[[{$oOrderArt->getId()}]][capture]" value="1" checked>
                                                        </td>
                                                        <td><input type="text" size="3" name="capture_positions[[{$oOrderArt->getId()}]][amount]" value="[{$iLeftAmount}]"></td>
                                                        <td>[{$oOrderArt->oxorderarticles__oxbprice->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                        <td>[{$oOrderArt->oxorderarticles__oxtitle->value}]</td>
                                                    </tr>
                                                [{/if}]
                                            [{/foreach}]
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext">
                                        <strong>[{oxmultilang ident="FCPO_COMPLETE_ORDER"}]</strong>
                                    </td>
                                    <td class="edittext">
                                        <input type="hidden" name="capture_completeorder" value="0">
                                        <input type="checkbox" name="capture_completeorder" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="edittext" colspan="2">
                                        <input type="button" onclick="document.myedit.fnc.value='capture';document.myedit.submit();return false;" value="[{oxmultilang ident="FCPO_EXECUTE"}]" style="padding: 0 4px 0 4px;">
                                    </td>
                                </tr>
                            [{/if}]
                        [{else}]
                            <tr><td colspan="2" style="border-bottom: 1px solid black;"></td></tr>
                            <tr><td class="edittext" colspan="2"><strong>[{oxmultilang ident="FCPO_CAPTURE"}]</strong></td></tr>
                            <tr>
                                <td class="edittext">
                                    <strong>[{oxmultilang ident="FCPO_PREAUTHORIZED_AMOUNT"}]</strong>
                                </td>
                                <td class="edittext">
                                    [{$edit->oxorder__oxtotalordersum->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]
                                </td>
                            </tr>
                            <tr>
                                <td class="edittext" >
                                    <strong>[{oxmultilang ident="FCPO_AMOUNT_IN"}] [{$edit->oxorder__oxcurrency->value}]</strong>
                                </td>
                                <td class="edittext">
                                    <input id="fc_capture_amount" type="text" name="capture_amount" value="0,00">
                                    <input type="button" onclick="fcpoOnClickCapture(this);" value="[{oxmultilang ident="FCPO_EXECUTE"}]" style="padding: 0 4px 0 4px;">
                                </td>
                            </tr>
                        [{/if}]
                    [{/if}]
                    [{if $edit->allowAccountSettlement() && $blShowCapture == true}]
                        <tr>
                            <td class="edittext">
                                <strong>[{oxmultilang ident="FCPO_SETTLE_ACCOUNT"}]</strong>
                            </td>
                            <td class="edittext">
                                <input type="hidden" name="capture_settleaccount" value="0">
                                <input type="checkbox" name="capture_settleaccount" value="1" checked>
                                [{oxinputhelp ident="FCPO_HELP_SETTLE_ACCOUNT"}]
                            </td>
                        </tr>
                    [{/if}]

                   [{if $edit->allowDebit()}]
                        [{assign var="blShowDebit" value=true}]
                        [{capture name=debit_block}]
                            <tr><td colspan="2" style="border-bottom: 1px solid black;"></td></tr>
                            <tr><td class="edittext" colspan="2"><strong>[{oxmultilang ident="FCPO_DEBIT"}]</strong></td></tr>
                            [{if $edit->debitNeedsBankData()}]
                                <tr>
                                    <td class="edittext" colspan="2" style="width: 300px;">[{oxmultilang ident="FCPO_HEADER_BANKACCOUNT"}] -
                                        <span id="fcShowBankaccount" style="width: 100px;">
                                            <a href="#" onclick="fcpoToggleBankaccount();return false;">[{oxmultilang ident="FCPO_SHOW"}]</a>
                                        </span>
                                        <span id="fcHideBankaccount" style="display: none;width: 100px;">
                                            <a href="#" onclick="fcpoToggleBankaccount();return false;">[{oxmultilang ident="FCPO_HIDE"}]</a>
                                        </span>
                                    </td>
                                </tr>
                                <tr id="fcBankAccount1" style="display: none;">
                                    <td class="edittext">
                                        [{oxmultilang ident="FCPO_BANKCOUNTRY"}]
                                    </td>
                                    <td>
                                        <select name="debit_bankcountry" class="editinput">
                                            <option value="DE">[{oxmultilang ident="FCPO_DE"}]</option>
                                            <option value="AT">[{oxmultilang ident="FCPO_AT"}]</option>
                                            <option value="NL">[{oxmultilang ident="FCPO_NL"}]</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="fcBankAccount2" style="display: none;">
                                    <td class="edittext">
                                        [{oxmultilang ident="FCPO_BANKACCOUNT"}]
                                    </td>
                                    <td>
                                        <input type="text" name="debit_bankaccount" value="">
                                    </td>
                                </tr>
                                <tr id="fcBankAccount3" style="display: none;">
                                    <td class="edittext">
                                        [{oxmultilang ident="FCPO_BANKCODE"}]
                                    </td>
                                    <td>
                                        <input type="text" name="debit_bankcode" value="">
                                    </td>
                                </tr>
                                <tr id="fcBankAccount4" style="display: none;">
                                    <td class="edittext">
                                        [{oxmultilang ident="FCPO_BANKACCOUNTHOLDER"}]
                                    </td>
                                    <td>
                                        <input type="text" name="debit_bankaccountholder" value="">
                                    </td>
                                </tr>
                            [{/if}]
                            [{if $edit->isDetailedProductInfoNeeded()}]
                                [{assign var="blShowDebit" value=false}]
                                <tr><td colspan="2">&nbsp;</td></tr>                                 
                                <tr>
                                    <td colspan="2">
                                        <table border="0" cellpadding="5" callspacing="5">
                                            <tr>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_CAPTURE"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_AMOUNT"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_PRICE"}]</th>
                                                <th>[{oxmultilang ident="FCPO_PRODUCT_TITLE"}]</th>
                                            </tr>
                                            [{foreach from=$oOrderarticles item=oOrderArt}]
                                                [{if $edit->oxorder__fcpoauthmode->value == 'preauthorization'}]
                                                    [{assign var="iLeftAmount" value=$oOrderArt->oxorderarticles__fcpocapturedamount->value-$oOrderArt->oxorderarticles__fcpodebitedamount->value}]
                                                [{else}]
                                                    [{assign var="iLeftAmount" value=$oOrderArt->oxorderarticles__oxamount->value-$oOrderArt->oxorderarticles__fcpodebitedamount->value}]
                                                [{/if}]
                                                [{if $iLeftAmount > 0}]
                                                    [{assign var="blShowDebit" value=true}]
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="debit_positions[[{$oOrderArt->getId()}]][price]" value="[{$oOrderArt->oxorderarticles__oxbprice->value}]">
                                                            <input type="hidden" name="debit_positions[[{$oOrderArt->getId()}]][debit]" value="0">
                                                            <input type="checkbox" name="debit_positions[[{$oOrderArt->getId()}]][debit]" value="1" checked>
                                                        </td>
                                                        <td>
                                                            [{if $edit->oxorder__fcpoauthmode->value == 'preauthorization'}]
                                                                <input type="text" size="3" name="debit_positions[[{$oOrderArt->getId()}]][amount]" value="[{$oOrderArt->oxorderarticles__fcpocapturedamount->value}]">
                                                            [{else}]
                                                                <input type="text" size="3" name="debit_positions[[{$oOrderArt->getId()}]][amount]" value="[{$iLeftAmount}]">
                                                            [{/if}]
                                                        </td>
                                                        <td>[{$oOrderArt->oxorderarticles__oxbprice->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                        <td>[{$oOrderArt->oxorderarticles__oxtitle->value}]</td>
                                                    </tr>
                                                [{/if}]
                                            [{/foreach}]
                                            [{if $edit->oxorder__oxdelcost->value != 0 && $edit->oxorder__fcpodelcostdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxdelcost][price]" value="[{$edit->oxorder__oxdelcost->value}]">
                                                        <input type="hidden" name="debit_positions[oxdelcost][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxdelcost][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxdelcost][amount]" value="1">1</td>
                                                    <td>[{$edit->oxorder__oxdelcost->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>
                                                        [{if $edit->oxorder__oxdelcost->value > 0}]
                                                            [{oxmultilang ident="FCPO_SURCHARGE"}]
                                                        [{else}]
                                                            [{oxmultilang ident="FCPO_DEDUCTION"}]
                                                        [{/if}]
                                                        [{oxmultilang ident="FCPO_SHIPPINGCOST"}]
                                                    </td>
                                                </tr>
                                            [{/if}]
                                            [{if $edit->oxorder__oxpaycost->value != 0 && $edit->oxorder__fcpopaycostdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxpaycost][price]" value="[{$edit->oxorder__oxpaycost->value}]">
                                                        <input type="hidden" name="debit_positions[oxpaycost][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxpaycost][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxpaycost][amount]" value="1">1</td>
                                                    <td>[{$edit->oxorder__oxpaycost->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>
                                                        [{if $edit->oxorder__oxpaycost->value > 0}]
                                                            [{oxmultilang ident="FCPO_SURCHARGE"}]
                                                        [{else}]
                                                            [{oxmultilang ident="FCPO_DEDUCTION"}]
                                                        [{/if}]
                                                        [{oxmultilang ident="FCPO_PAYMENTTYPE"}]
                                                    </td>
                                                </tr>
                                            [{/if}]
                                            [{if $edit->oxorder__oxwrapcost->value != 0 && $edit->oxorder__fcpowrapcostdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxwrapcost][price]" value="[{$edit->oxorder__oxwrapcost->value}]">
                                                        <input type="hidden" name="debit_positions[oxwrapcost][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxwrapcost][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxwrapcost][amount]" value="1">1</td>
                                                    <td>[{$edit->oxorder__oxwrapcost->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>[{oxmultilang ident="FCPO_WRAPPING"}]</td>
                                                </tr>
                                            [{/if}]
                                            [{if $edit->oxorder__oxgiftcardcost->value != 0 && $edit->oxorder__fcpogiftcardcostdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxgiftcardcost][price]" value="[{$edit->oxorder__oxgiftcardcost->value}]">
                                                        <input type="hidden" name="debit_positions[oxgiftcardcost][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxgiftcardcost][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxgiftcardcost][amount]" value="1">1</td>
                                                    <td>[{$edit->oxorder__oxgiftcardcost->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>[{oxmultilang ident="FCPO_GIFTCARD"}]</td>
                                                </tr>
                                            [{/if}]
                                            [{if $edit->oxorder__oxvoucherdiscount->value != 0 && $edit->oxorder__fcpovoucherdiscountdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                [{assign var="dNegativeAmount" value=$edit->oxorder__oxvoucherdiscount->value*-1}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxvoucherdiscount][price]" value="[{$dNegativeAmount}]">
                                                        <input type="hidden" name="debit_positions[oxvoucherdiscount][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxvoucherdiscount][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxvoucherdiscount][amount]" value="1">1</td>
                                                    <td>[{$dNegativeAmount|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>[{oxmultilang ident="FCPO_VOUCHER"}]</td>
                                                </tr>
                                            [{/if}]
                                            [{if $edit->oxorder__oxdiscount->value != 0 && $edit->oxorder__fcpodiscountdebited->value == 0}]
                                                [{assign var="blShowDebit" value=true}]
                                                [{assign var="dNegativeAmount" value=$edit->oxorder__oxdiscount->value*-1}]
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="debit_positions[oxdiscount][price]" value="[{$dNegativeAmount}]">
                                                        <input type="hidden" name="debit_positions[oxdiscount][debit]" value="0">
                                                        <input type="checkbox" name="debit_positions[oxdiscount][debit]" value="1" checked>
                                                    </td>
                                                    <td><input type="hidden" name="debit_positions[oxdiscount][amount]" value="1">1</td>
                                                    <td>[{$dNegativeAmount|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                                    <td>[{oxmultilang ident="FCPO_DISCOUNT"}]</td>
                                                </tr>
                                            [{/if}]
                                        </table>
                                    </td>
                                </tr>
                                [{if $edit->isCancellationReasonNeeded()}]
                                <tr>
                                    <td><strong>[{oxmultilang ident="FCPO_BNPL_ADMIN_DEBIT_REASON_TITLE"}]</strong></td>
                                    <td>
                                        <select name="bnpl_debit_cancellation_reason" class="editinput">
                                            <option value="consumer_request">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_CRQ"}]</option>
                                            <option value="duplicate">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_DUP"}]</option>
                                            <option value="fraudulent">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_FRD"}]</option>
                                            <option value="undeliverable">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_UND"}]</option>
                                        </select>
                                    </td>
                                </tr>
                                [{/if}]
                                <tr>
                                    <td class="edittext" colspan="2">
                                        <input type="button" onclick="document.myedit.fnc.value='debit';document.myedit.submit();return false;" value="[{oxmultilang ident="FCPO_EXECUTE"}]" style="padding: 0 4px 0 4px;">
                                    </td>
                                </tr>
                            [{else}]
                                [{if $edit->isCancellationReasonNeeded()}]
                                <tr>
                                    <td><strong>[{oxmultilang ident="FCPO_BNPL_ADMIN_DEBIT_REASON_TITLE"}]</strong></td>
                                    <td>
                                        <select name="bnpl_debit_cancellation_reason" class="editinput">
                                            <option value="consumer_request">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_CRQ"}]</option>
                                            <option value="duplicate">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_DUP"}]</option>
                                            <option value="fraudulent">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_FRD"}]</option>
                                            <option value="undeliverable">[{oxmultilang ident="FCPO_BNPL_CANCELLATION_REASON_UND"}]</option>
                                        </select>
                                    </td>
                                </tr>
                                [{/if}]
                                <tr>
                                    <td class="edittext" >
                                        <strong>[{oxmultilang ident="FCPO_AMOUNT_IN"}] [{$edit->oxorder__oxcurrency->value}]</strong>
                                    </td>
                                    <td>
                                        <input type="text" name="debit_amount" value="0,00">
                                        <input type="button" onclick="if(confirm('[{oxmultilang ident="FCPO_ARE_YOU_SURE"}]')) {this.form.fnc.value='debit';this.form.submit();}" value="[{oxmultilang ident="FCPO_EXECUTE"}]" style="padding: 0 4px 0 4px;">
                                    </td>
                                </tr>
                            [{/if}]
                        [{/capture}]
                        [{if $blShowDebit == true}]
                            [{$smarty.capture.debit_block}]
                        [{/if}]
                    [{/if}]

                    [{if $oShadowBasket}]
                        [{assign var='oShadowBasketPrice' value=$oShadowBasket->getPrice()}]
                        <tr>
                            <td class="edittext" colspan="2" style="padding-top:20px;">
                                <h3>[{oxmultilang ident="FCPO_SHADOW_BASKET"}]</h3><br>
                                <span style="color:red;font-weight: bold;">
                                        [{oxmultilang ident="FCPO_POSSIBLE_FRAUD_DETECTED"}]
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <strong>[{oxmultilang ident="FCPO_GENERAL_SHADOWBASKET_BRUTTO"}]</strong>
                            </td>
                            <td class="edittext">
                                <strong>[{$oShadowBasket->getFProductsPrice()}]&nbsp;[{$currency->sign}]</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext">
                                <strong>[{oxmultilang ident="FCPO_GENERAL_SHADOWBASKET_NETTO"}]</strong>
                            </td>
                            <td class="edittext">
                                <strong>[{$oShadowBasket->getProductsNetPrice()}]&nbsp;[{$currency->sign}]</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="edittext" colspan="2">
                                <table>
                                    [{foreach from=$oShadowBasket->getContents() item='oBasketItem'}]
                                    [{assign var='oBasketItemPrice' value=$oBasketItem->getPrice()}]
                                    <tr>
                                        <td>
                                            [{oxmultilang ident="FCPO_PRODUCT_TITLE"}]: [{$oBasketItem->getTitle()}]<br>
                                            [{oxmultilang ident="FCPO_PRODUCT_AMOUNT"}]: [{$oBasketItem->getAmount()}]<br>
                                            [{oxmultilang ident="FCPO_PRODUCT_PRICE"}]: [{$oBasketItem->getFTotalPrice()}]&nbsp;[{$currency->sign}]
                                        </td>
                                    </tr>
                                    [{/foreach}]
                                </table>
                            </td>
                        </tr>
                    [{/if}]

                    [{if $status_oxid != '-1' && $currStatus}]
                        <tr>
                            <td colspan="2" style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td class="edittext" colspan="2"><strong>TransactionStatus</strong></td>
                        </tr>

                        [{if $currStatus->fcpotransactionstatus__fcpo_txaction->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    TXACTION
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_txaction->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_portalid->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    PORTALID
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_portalid->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_aid->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    AID
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_aid->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_clearingtype->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CLEARINGTYPE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_clearingtype->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_txtime->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    TXTIME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_txtime->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_currency->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CURRENCY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_currency->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_userid->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    USERID
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_userid->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_accessname->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    ACCESSNAME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_accessname->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_accesscode->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    ACCESSCODE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_accesscode->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_mode->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    MODE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_mode->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_price->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    PRICE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_price->value|number_format:2:',':''}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_txid->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    TXID
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_txid->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_reference->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    REFERENCE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_reference->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_sequencenumber->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SEQUENCENUMBER
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_sequencenumber->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_company->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    COMPANY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_company->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_firstname->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    FIRSTNAME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_firstname->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_lastname->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    LASTNAME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_lastname->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_street->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    STREET
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_street->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_zip->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    ZIP
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_zip->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_city->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CITY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_city->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_email->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    EMAIL
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_email->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_country->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    COUNTRY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_country->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_company->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_COMPANY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_company->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_firstname->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_FIRSTNAME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_firstname->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_lastname->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_LASTNAME
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_lastname->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_street->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_STREET
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_street->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_zip->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_ZIP
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_zip->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_city->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_CITY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_city->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_shipping_country->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    SHIPPING_COUNTRY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_shipping_country->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_bankcountry->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    BANKCOUNTRY
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_bankcountry->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_bankaccount->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    BANKACCOUNT
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_bankaccount->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_bankcode->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    BANKCODE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_bankcode->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_bankaccountholder->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    BANKACCOUNTHOLDER
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_bankaccountholder->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_cardexpiredate->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CARDEXPIREDATE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_cardexpiredate->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_cardtype->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CARDTYPE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_cardtype->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_cardpan->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CARDPAN
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_cardpan->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_customerid->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    CUSTOMERID
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_customerid->value}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_balance->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    BALANCE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_balance->value|number_format:2:',':''}]
                                </td>
                            </tr>
                        [{/if}]

                        [{if $currStatus->fcpotransactionstatus__fcpo_receivable->value != ''}]
                            <tr>
                                <td class="edittext" >
                                    RECEIVABLE
                                </td>
                                <td class="edittext">
                                    [{$currStatus->fcpotransactionstatus__fcpo_receivable->value|number_format:2:',':''}]
                                </td>
                            </tr>
                        [{/if}]
                    [{/if}]

                </table>
            </td>
            <td valign="top">
                [{if $status}]
                    [{assign var="authorizationMethod" value=$oView->getAuthorizationMethod()}]
                    [{assign var="captureDebitEntries" value=$oView->getCaptureDebitEntries()}]
                    <table border="0" width="50%">
                        <tbody>
                            <tr>
                                <td><strong>[{oxmultilang ident="FCPO_AUTHORIZATION_METHOD"}]</strong></td>
                                <td style="text-align: right">
                                    [{if $authorizationMethod == 'authorization'}]
                                    <span><strong>[{oxmultilang ident="FCPO_AUTHORIZATION"}]</strong></span>
                                    [{else}]
                                    <span><strong>[{oxmultilang ident="FCPO_PREAUTHORIZATION"}]</strong></span>
                                    [{/if}]
                                </td>
                            </tr>
                            <tr style="border-bottom: solid 1px">
                                <td>
                                    [{if $authorizationMethod == 'authorization'}]
                                    <strong>[{oxmultilang ident="FCPO_AUTHORIZED_AMOUNT"}]</strong>
                                    [{else}]
                                    <strong>[{oxmultilang ident="FCPO_PREAUTHORIZED_AMOUNT"}]</strong>
                                    [{/if}]
                                </td>
                                <td style="text-align: right">
                                    <span><strong>[{$edit->oxorder__oxtotalordersum->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</strong></span>
                                </td>
                            </tr>

                            [{if $authorizationMethod == 'authorization' && $captureDebitEntries.paid|@count > 0}]
                                [{foreach from=$captureDebitEntries.paid item=entry name=paid}]
                                <tr class="paid-details" style="">
                                    <td>
                                        <span>[{oxmultilang ident="FCPO_PAID_AMOUNT"}]</span>
                                        <a href="Javascript:fcpoEditThisStatus('[{$entry.oxid}]', '[{$oxid}]');">[{$entry.date}]</a>
                                    </td>
                                    <td style="text-align: right; padding-right: 10px">[{$entry.amount|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                </tr>
                                [{/foreach}]
                            [{else}]
                                <tr class="preauth-remains-details" style="">
                                    <td>
                                        <span>[{oxmultilang ident="FCPO_LEFT_PREAUTHORIZED_AMOUNT"}]</span>
                                    </td>
                                    <td style="text-align: right; padding-right: 10px">[{$edit->oxorder__oxtotalordersum->value-$captureDebitEntries.totalCapture|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                </tr>
                            [{/if}]

                            [{if $captureDebitEntries.capture|@count > 0}]
                                <tr><td colspan="2" style="border-bottom:solid 1px black"></td></tr>
                                <tr>
                                    <td>
                                        <div id="capture-details-title" style="display: inline">
                                            <span onclick="fcpoToggleDetails('capture-details', true)" class="unfold-icon" style="cursor: pointer">&#8744;</span>
                                            <span onclick="fcpoToggleDetails('capture-details', false)" class="fold-icon" style="display: none; cursor: pointer">&#8743;</span>
                                        </div>
                                        <span><strong>[{oxmultilang ident="FCPO_CAPTURED_AMOUNT"}]</strong></span>
                                    </td>
                                    <td style="text-align: right">
                                        <span><strong>[{$captureDebitEntries.totalCapture|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</strong></span>
                                    </td>
                                </tr>
                                [{foreach from=$captureDebitEntries.capture item=entry name=captured}]
                                <tr class="capture-details" style="display: none">
                                    <td><a href="Javascript:fcpoEditThisStatus('[{$entry.oxid}]', '[{$oxid}]');">[{$entry.date}]</a></td>
                                    <td style="text-align: right; padding-right: 10px">[{$entry.amount|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                </tr>
                                [{/foreach}]
                            [{/if}]

                            [{if $captureDebitEntries.debit|@count > 0}]
                                <tr><td colspan="2" style="border-bottom:solid 1px black"></td></tr>
                                <tr>
                                    <td>
                                        <div id="debit-details-title" style="display: inline">
                                            <span onclick="fcpoToggleDetails('debit-details', true)" class="unfold-icon" style="cursor: pointer">&#8744;</span>
                                            <span onclick="fcpoToggleDetails('debit-details', false)" class="fold-icon" style="display: none; cursor: pointer">&#8743;</span>
                                        </div>
                                        <span><strong>[{oxmultilang ident="FCPO_REFUNDED_AMOUNT"}]</strong></span>
                                    </td>
                                    <td style="text-align: right">
                                        <span style="color: red"><strong>[{$captureDebitEntries.totalDebit|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</strong></span>
                                    </td>
                                </tr>
                                [{foreach from=$captureDebitEntries.debit item=entry name=refunded}]
                                <tr class="debit-details" style="display: none">
                                    <td><a href="Javascript:fcpoEditThisStatus('[{$entry.oxid}]', '[{$oxid}]');">[{$entry.date}]</a></td>
                                    <td style="text-align: right; padding-right: 10px; color: red">[{$entry.amount|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                                </tr>
                                [{/foreach}]
                            [{/if}]
                            <tr><td colspan="2" style="border-bottom:solid 1px black"></td></tr>
                            <tr>
                                <td><strong>[{oxmultilang ident="FCPO_BALANCE"}]</strong></td>
                                <td style="text-align: right; [{if $captureDebitEntries.totalBalance < 0}]color: red;[{/if}]"><strong>[{$captureDebitEntries.totalBalance|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</strong></td>
                            </tr>
                        </tbody>
                    </table>
                [{else}]
                    [{oxmultilang ident="FCPO_NO_TRANSACTION_STATUS_RECEIVED"}]
                [{/if}]

                <div style="clear: both; margin-bottom: 50px;"></div>

                <div style="margin-top: 20px">
                    <p style="border-bottom: solid 1px black;text-transform: uppercase;"><strong>[{oxmultilang ident="FCPO_PRODUCTS"}]</strong></p>
                    <table border="0" cellpadding="5" callspacing="5">
                        <colgroup>
                            <col width="70%">
                            <col width="10%">
                            <col width="5%">
                            <col width="10%">
                        </colgroup>
                        <tr style="text-align: right">
                            <th style="text-align: left">[{oxmultilang ident="FCPO_PRODUCT_TITLE"}]</th>
                            <th>[{oxmultilang ident="FCPO_PRODUCT_PRICE"}]</th>
                            <th>[{oxmultilang ident="FCPO_PRODUCT_AMOUNT"}]</th>
                            <th>[{oxmultilang ident="FCPO_PRODUCT_TOTAL"}]</th>
                        </tr>
                        [{foreach from=$oOrderarticles item=oOrderArt}]
                        [{assign var="articleTotal" value=$oOrderArt->oxorderarticles__oxamount->value*$oOrderArt->oxorderarticles__oxbprice->value}]
                        <tr style="text-align: right">
                            <td style="text-align: left">[{$oOrderArt->oxorderarticles__oxtitle->value}]</td>
                            <td>[{$oOrderArt->oxorderarticles__oxbprice->value|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                            <td style="text-align: center">[{$oOrderArt->oxorderarticles__oxamount->value}]</td>
                            <td>[{$articleTotal|number_format:2:",":""}] [{$edit->oxorder__oxcurrency->value}]</td>
                        </tr>
                        [{/foreach}]
                    </table>
                </div>
            </td>
        </tr>
    </table>
</form>

[{include file="bottomnaviitem.tpl"}]
</table>
[{include file="bottomitem.tpl"}]