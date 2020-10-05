[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{assign var="confbools" value=$oView->fcpoGetConfBools()}]
[{assign var="confstrs" value=$oView->fcpoGetConfStrs()}]
[{assign var="confarrs" value=$oView->fcpoGetConfArrs()}]

[{oxscript include=$oViewConf->fcpoGetModuleJsPath('fcpayone_main.js')}]

<form autocomplete="off" name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fcpayone_main">
</form>



<form autocomplete="off" name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="fcpayone_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">

    [{oxmultilang ident="FCPO_MAIN_CONFIG_INFOTEXT"}]<br><br>

    [{oxmultilang ident="FCPO_MODULE_VERSION"}] [{$oView->fcpoGetModuleVersion()}]<br><br>

    [{if $oView->fcpoGetConfigErrors()}]
        [{foreach from=$oView->fcpoGetConfigErrors() item='sErrorMessage'}]
            <div style="padding:4px;background: red;color: white;font-weight: bold;">
                [{$sErrorMessage}]
            </div>
        [{/foreach}]
    [{/if}]
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_CONN"}]</b></a>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOMerchantID]" value="[{$confstrs.sFCPOMerchantID}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_MERCHANTID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_MERCHANT_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOPortalID]" value="[{$confstrs.sFCPOPortalID}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PORTALID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PORTAL_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOPortalKey]" value="[{$confstrs.sFCPOPortalKey}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PORTALKEY"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PORTAL_KEY"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOSubAccountID]" value="[{$confstrs.sFCPOSubAccountID}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_SUBACCOUNTID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_SUBACCOUNT_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPORefPrefix]" value="[{$confstrs.sFCPORefPrefix}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_REFPREFIX"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_REFPREFIX"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOHashMethod]">
                        <option value="md5" [{if $confstrs.sFCPOHashMethod == "md5"}]SELECTED[{/if}]>md5</option>
                        <option value="sha2-384" [{if $confstrs.sFCPOHashMethod == "sha2-384"}]SELECTED[{/if}]>sha2-384 (empfohlen)</option>
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_HASH_METHOD"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_HASH_METHOD"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_GENERAL"}]</b></a>
            <dl>
                <dt>
                    <input type="hidden" name="confbools[blFCPOSendArticlelist]" value="false">
                    <input type="checkbox" name="confbools[blFCPOSendArticlelist]" value="true" [{if ($confbools.blFCPOSendArticlelist)}]checked[{/if}]>
                    [{oxinputhelp ident="FCPO_HELP_SEND_ARTICLELIST"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_SEND_ARTICLELIST"}] 
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="hidden" name="confbools[blFCPOPresaveOrder]" value="false">
                    <input type="checkbox" name="confbools[blFCPOPresaveOrder]" value="true" [{if ($confbools.blFCPOPresaveOrder)}]checked[{/if}] onclick="handlePresaveOrderCheckbox(this);">
                    [{oxinputhelp ident="FCPO_HELP_PRESAVE_ORDER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PRESAVE_ORDER"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl id="reduce_stock" [{if !($confbools.blFCPOPresaveOrder)}]style="display: none;"[{/if}]>
                <dt></dt>
                <dd>
                    [{oxmultilang ident="FCPO_REDUCE_STOCK"}] [{oxinputhelp ident="FCPO_HELP_REDUCE_STOCK"}]<br>
                    <input type="radio" name="confbools[blFCPOReduceStock]" value="0" [{if $confbools.blFCPOReduceStock == '0' || !$confbools.blFCPOReduceStock}]checked[{/if}]> [{oxmultilang ident="FCPO_REDUCE_STOCK_BEFORE"}]<br>
                    <input type="radio" name="confbools[blFCPOReduceStock]" value="1" [{if $confbools.blFCPOReduceStock == '1'}]checked[{/if}]> [{oxmultilang ident="FCPO_REDUCE_STOCK_AFTER"}]                    
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>
    
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_ACTIVE_CREDITCARD_TYPES"}]</b></a>
            <dl>
                <dt></dt>
                <dd>
                    [{oxmultilang ident="FCPO_CREDITCARDBRANDS_INFOTEXT"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>Visa</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOVisaActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOVisaActivated]" value="true"  [{if ($confbools.blFCPOVisaActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=V&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCVLive]" value="1" [{if $confbools.blFCPOCCVLive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCVLive]" value="0" [{if $confbools.blFCPOCCVLive == '0' || !$confbools.blFCPOCCVLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl>            
            <dl>
                <dt>Mastercard</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOMastercardActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOMastercardActivated]" value="true"  [{if ($confbools.blFCPOMastercardActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=M&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCMLive]" value="1" [{if $confbools.blFCPOCCMLive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCMLive]" value="0" [{if $confbools.blFCPOCCMLive == '0' || !$confbools.blFCPOCCMLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>Amex</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOAmexActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOAmexActivated]" value="true"  [{if ($confbools.blFCPOAmexActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=A&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCALive]" value="1" [{if $confbools.blFCPOCCALive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCALive]" value="0" [{if $confbools.blFCPOCCALive == '0' || !$confbools.blFCPOCCALive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>Diners</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPODinersActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPODinersActivated]" value="true"  [{if ($confbools.blFCPODinersActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=D&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCDLive]" value="1" [{if $confbools.blFCPOCCDLive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCDLive]" value="0" [{if $confbools.blFCPOCCDLive == '0' || !$confbools.blFCPOCCDLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>JCB</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOJCBActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOJCBActivated]" value="true"  [{if ($confbools.blFCPOJCBActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=J&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCJLive]" value="1" [{if $confbools.blFCPOCCJLive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCJLive]" value="0" [{if $confbools.blFCPOCCJLive == '0' || !$confbools.blFCPOCCJLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl> 
            <dl>
                <dt>Maestro International</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOMaestroIntActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOMaestroIntActivated]" value="true"  [{if ($confbools.blFCPOMaestroIntActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=O&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCOLive]" value="1" [{if $confbools.blFCPOCCOLive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCOLive]" value="0" [{if $confbools.blFCPOCCOLive == '0' || !$confbools.blFCPOCCOLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>Maestro UK</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                    <input type="hidden" name="confbools[blFCPOMaestroUKActivated]" value="false">
                    <input type="checkbox" name="confbools[blFCPOMaestroUKActivated]" value="true"  [{if ($confbools.blFCPOMaestroUKActivated)}]checked[{/if}]>
                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=U&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                    [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                    <input type="radio" name="confbools[blFCPOCCULive]" value="1" [{if $confbools.blFCPOCCULive == '1'}]checked[{/if}]> <strong>Live</strong>
                    <input type="radio" name="confbools[blFCPOCCULive]" value="0" [{if $confbools.blFCPOCCULive == '0' || !$confbools.blFCPOCCULive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>Carte Bleue</dt>
                <dd style="margin-top: 4px; margin-left: 150px;">
                <input type="hidden" name="confbools[blFCPOCarteBleueActivated]" value="false">
                <input type="checkbox" name="confbools[blFCPOCarteBleueActivated]" value="true"  [{if ($confbools.blFCPOCarteBleueActivated)}]checked[{/if}]>
                <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=B&amp;type=cc');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES"}]
                <input type="radio" name="confbools[blFCPOCCBLive]" value="1" [{if $confbools.blFCPOCCBLive == '1'}]checked[{/if}]> <strong>Live</strong>
                <input type="radio" name="confbools[blFCPOCCBLive]" value="0" [{if $confbools.blFCPOCCBLive == '0' || !$confbools.blFCPOCCBLive}]checked[{/if}]> Test
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>
    
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_CREDITCARD"}]</b></a>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOCCType]">
                        <option value="hosted" [{if $confstrs.sFCPOCCType == "hosted"}]SELECTED[{/if}]>hosted-Iframe</option>
                        <option value="ajax" [{if $confstrs.sFCPOCCType == "ajax"}]SELECTED[{/if}]>AJAX</option>
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_CC_TYPE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_CC_TYPE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dd>
                    <a href="#" onclick="toggleHostedTemplate();" style="text-decoration: underline;">[{oxmultilang ident="FCPO_CC_CUSTOM_TEMPLATE"}]</a>
                    <div id="fcpoHostedCCTemplate" style="display:none;padding-top:10px;">
                        <table>
                            <tr>
                                <th style="text-align:left;width:120px;">[{oxmultilang ident="FCPO_CC_CONFIG"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_TYPE"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_DIGIT_COUNT"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_DIGIT_MAX"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_IFRAME"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_WIDTH"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_HEIGHT"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_STYLE"}]</th>
                                <th>[{oxmultilang ident="FCPO_CC_HEADER_CSS"}]</th>
                            </tr>
                            [{foreach from=$oView->getCCFields() item=sField}]
                                <tr>
                                    <td>
                                        [{assign var="sIdent" value="FCPO_CC_ROW_CC_"|cat:$sField}]
                                        [{oxmultilang ident=$sIdent}]
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"Type"}]
                                        <select name="confstrs[[{$sFieldIdent}]]">
                                            [{foreach from=$oView->getCCTypes($sField) key=sType item=sTitle}]
                                                <option value="[{$sType}]" [{if $sType == $confstrs.$sFieldIdent}]selected[{/if}]>[{$sTitle}]</option>
                                            [{/foreach}]
                                        </select>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"Count"}]                                        
                                        <input type="text" class="txt" size="4" name="confstrs[[{$sFieldIdent}]]" value="[{$confstrs.$sFieldIdent}]" [{$readonly}]>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"Max"}]
                                        <input type="text" class="txt" size="4" name="confstrs[[{$sFieldIdent}]]" value="[{$confstrs.$sFieldIdent}]" [{$readonly}]>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdentIframe" value="sFCPOCC"|cat:$sField|cat:"Iframe"}]
                                        <select name="confstrs[[{$sFieldIdentIframe}]]" onchange="handleSizeFields(this, '[{$sField}]')">
                                            [{foreach from=$oView->getCCStyles() key=sType item=sTitle}]
                                                <option value="[{$sType}]" [{if $sType == $confstrs.$sFieldIdentIframe}]selected[{/if}]>[{$sTitle}]</option>
                                            [{/foreach}]
                                        </select>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"Width"}]
                                        <input id="input_width_[{$sField}]" type="text" class="txt" size="4" name="confstrs[[{$sFieldIdent}]]" value="[{$confstrs.$sFieldIdent}]" [{$readonly}] [{if $confstrs.$sFieldIdentIframe != "custom"}]disabled[{/if}]>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"Height"}]
                                        <input id="input_height_[{$sField}]" type="text" class="txt" size="4" name="confstrs[[{$sFieldIdent}]]" value="[{$confstrs.$sFieldIdent}]" [{$readonly}] [{if $confstrs.$sFieldIdentIframe != "custom"}]disabled[{/if}]>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdentCSS" value="sFCPOCC"|cat:$sField|cat:"Style"}]
                                        <select name="confstrs[[{$sFieldIdentCSS}]]" onchange="handleCss(this, '[{$sField}]')">
                                            [{foreach from=$oView->getCCStyles() key=sType item=sTitle}]
                                                <option value="[{$sType}]" [{if $sType == $confstrs.$sFieldIdentCSS}]selected[{/if}]>[{$sTitle}]</option>
                                            [{/foreach}]
                                        </select>
                                    </td>
                                    <td>
                                        [{assign var="sFieldIdent" value="sFCPOCC"|cat:$sField|cat:"CSS"}]
                                        <input id="input_css_[{$sField}]" type="text" class="txt" size="50" name="confstrs[[{$sFieldIdent}]]" value="[{$confstrs.$sFieldIdent}]" [{$readonly}] [{if $confstrs.$sFieldIdentCSS != "custom"}]disabled[{/if}]>
                                        [{if $sFieldIdent=='sFCPOCCCVCCSS'}]
                                            &nbsp;
                                            <input type="hidden" name="confbools[blFCPOCCUseCvc]" value="false">
                                            <input type="checkbox" name="confbools[blFCPOCCUseCvc]" value="true"  [{if ($confbools.blFCPOCCUseCvc)}]checked[{/if}]> [{oxmultilang ident="FCPO_CC_USE_CVC"}]
                                        [{/if}]
                                    </td>
                                </tr>
                            [{/foreach}]
                        </table>
                        <br>
                        <table>
                            <tr>
                                <th style="text-align:left;width:120px;">[{oxmultilang ident="FCPO_CC_STANDARD_STYLE"}]</th>
                                <td>[{oxmultilang ident="FCPO_CC_STANDARD_INPUT"}]</td>
                                <td>[{oxmultilang ident="FCPO_CC_STANDARD_SELECTION"}]</td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_CC_STANDARD_FIELDS"}]</td>
                                <td><input type="text" class="txt" size="50" name="confstrs[sFCPOCCStandardInput]" value="[{$confstrs.sFCPOCCStandardInput}]" [{$readonly}]></td>
                                <td><input type="text" class="txt" size="50" name="confstrs[sFCPOCCStandardOutput]" value="[{$confstrs.sFCPOCCStandardOutput}]" [{$readonly}]></td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_CC_STANDARD_IFRAME"}]</td>
                                <td colspan="2">
                                    <table>
                                        <tr>
                                            <td>[{oxmultilang ident="FCPO_CC_HEADER_WIDTH"}]</td>
                                            <td>[{oxmultilang ident="FCPO_CC_HEADER_HEIGHT"}]</td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="txt" size="4" name="confstrs[sFCPOCCIframeWidth]" value="[{$confstrs.sFCPOCCIframeWidth}]" [{$readonly}]></td>
                                            <td><input type="text" class="txt" size="4" name="confstrs[sFCPOCCIframeHeight]" value="[{$confstrs.sFCPOCCIframeHeight}]" [{$readonly}]></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table>
                            <tr>
                                <th style="text-align:left;width:120px;">[{oxmultilang ident="FCPO_CC_ERRORS"}]</th>
                                <td></td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_CC_ACTIVE"}]</td>
                                <td>
                                    <input type="hidden" name="confbools[blFCPOCCErrorsActive]" value="false">
                                    <input type="checkbox" name="confbools[blFCPOCCErrorsActive]" value="true"  [{if ($confbools.blFCPOCCErrorsActive)}]checked[{/if}]>
                                </td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_CC_LANGUAGE"}]</td>
                                <td>
                                    <select name="confstrs[sFCPOCCErrorsLang]">
                                        <option value="de" [{if $confstrs.sFCPOCCErrorsLang == "de"}]selected[{/if}]>[{oxmultilang ident="FCPO_CC_ERRORLANG_DE"}]</option>
                                        <option value="en" [{if $confstrs.sFCPOCCErrorsLang == "en"}]selected[{/if}]>[{oxmultilang ident="FCPO_CC_ERRORLANG_EN"}]</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <a href="#" onclick="togglePreview();" style="text-decoration: underline;">[{oxmultilang ident="FCPO_CC_PREVIEW"}]</a>
                        <div id="fcpoHostedCCPreview" style="display:none;padding-top:10px;">
                            [{include file="fcpayone_cc_preview.tpl"}]
                        </div>
                    </div>
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>
    
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_DEBITNOTE"}]</b></a>
            <dl>
                <dt>[{oxmultilang ident="FCPO_CONFIG_DEBIT_BANKDATA"}]</dt>
            </dl>
            <dl style="border-top:0px;">
                <dt>
                    <select class="select" multiple size="4" name="confarrs[aFCPODebitCountries][]" [{$readonly}]>
                        [{foreach from=$oView->fcpoGetCountryList() item=oCountry}]
                            <option value="[{$oCountry->oxcountry__oxid->value}]"[{if $oCountry->selected}] selected[{/if}]>[{$oCountry->oxcountry__oxtitle->value}]</option>
                        [{/foreach}]
                    </select>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_MULTISELECT"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>[{oxmultilang ident="FCPO_CONFIG_DEBIT_GER"}]</dt>
            </dl>
            <dl style="border-top:0px;">
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_SHOW_OLD_FIELDS"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl style="border-top:0px;">
                <dt>
                    <input type="hidden" name="confbools[blFCPODebitBICMandatory]" value="false">
                    <input type="checkbox" name="confbools[blFCPODebitBICMandatory]" value="true"  [{if ($confbools.blFCPODebitBICMandatory)}]checked[{/if}]>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_BIC_MANDATORY"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>[{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE"}]</dt>
            </dl>
            <dl style="border-top:0px;">
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE_TEXT"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl style="border-top:0px;">
                <dt>
                    <input type="hidden" name="confbools[blFCPOMandateIssuance]" value="false">
                    <input type="checkbox" name="confbools[blFCPOMandateIssuance]" value="true"  [{if ($confbools.blFCPOMandateIssuance)}]checked[{/if}]>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE_ACTIVE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>[{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD"}]</dt>
            </dl>
            <dl style="border-top:0px;">
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD_TEXT"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl style="border-top:0px;">
                <dt>
                    <input type="hidden" name="confbools[blFCPOMandateDownload]" value="false">
                    <input type="checkbox" name="confbools[blFCPOMandateDownload]" value="true"  [{if ($confbools.blFCPOMandateDownload)}]checked[{/if}]>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD_ACTIVE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>
    
    <div class="groupExp">
        <div[{if $oView->fcpoIsStoreIdAdded()}] class="exp"[{/if}]>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_KLARNA"}]</b></a>
            <dl>
                <dt></dt>
                <dd>
                    [{oxmultilang ident="FCPO_KLARNA_STORE_ID_ADMIN"}]
                </dd>
                <div class="spacer"></div>
            </dl>            
            [{foreach from=$oView->fcpoGetStoreIds() item=sStoreId key=sKey}]
                <dl>
                    <dt style="padding-top: 10px;">
                        <input type="text" class="txt" name="aStoreIds[[{$sKey}]][id]" value="[{$sStoreId}]" [{$readonly}]>
                        [{oxinputhelp ident="FCPO_HELP_ASSIGNCOUNTRIES_3"}]
                    </dt>
                    <dd>
                        StoreID
                        <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=KLV&amp;type=[{$sKey}]');" class="" value="[{oxmultilang ident="GENERAL_ASSIGNCOUNTRIES"}]">
                        <input type="submit" class="edittext" name="aStoreIds[[{$sKey}]][delete]" value="[{oxmultilang ident="FCPO_KLARNA_DELETE_STORE_ID"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
                    </dd>
                    <div class="spacer"></div>
                </dl>
            [{/foreach}]   
            <dl>
                <dt><input type="submit" class="edittext" name="addStoreId" value="[{oxmultilang ident="FCPO_KLARNA_ADD_STORE_ID"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]></dt>
                <dd></dd>
                <div class="spacer"></div>
            </dl>      
        </div>
    </div>
    
    <div class="groupExp">
        <div[{if $oView->fcpoIsCampaignAdded()}] class="exp"[{/if}]>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_KLARNA_CAMPAIGNS"}]</b></a>
            <dl>
                <dt></dt>
                <dd>
                    [{oxmultilang ident="FCPO_KLARNA_CAMPAIGNS"}]
                </dd>
                <div class="spacer"></div>
            </dl>            
            [{foreach from=$oView->fcpoKlarnaCampaigns() item=aCampaign key=sId}]
                <dl>
                    <dd>
                        <table>
                            <tr>
                                <td>
                                    [{oxmultilang ident="FCPO_KLARNA_CAMPAIGN_CODE"}]
                                </td>
                                <td>
                                    <input type="text" class="txt" name="aCampaigns[[{$sId}]][code]" value="[{$aCampaign.code}]" size="8" [{$readonly}]>
                                    [{oxinputhelp ident="FCPO_HELP_KLARNA_CAMPAIGNS"}]
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    [{oxmultilang ident="FCPO_KLARNA_CAMPAIGN_TITLE"}]
                                </td>
                                <td>
                                    <input type="text" class="txt" name="aCampaigns[[{$sId}]][title]" value="[{$aCampaign.title}]" size="50" [{$readonly}]>
                                </td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_COUNTRIES"}]</td>
                                <td>
                                    <input type="button" onclick="JavaScript:showDialog('[{$oView->fcGetAdminSeperator()}]cl=fcpayone_main&amp;aoc=1&amp;oxid=KLR_[{$sId}]&amp;type=[{$sKey}]');" class="" value="[{oxmultilang ident="FCPO_ASSIGN_COUNTRIES"}]">
                                </td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_LANGUAGE"}]</td>
                                <td>
                                    [{assign var="aLanguages" value=$oView->fcGetLanguages()}]
                                    <select class="select" multiple size="[{$aLanguages|@count}]" name="aCampaigns[[{$sId}]][language][]" [{$readonly}]>
                                        [{foreach from=$aLanguages key=sLangId item=sLangTitle}]
                                            <option value="[{$sLangId}]" [{if $sLangId|in_array:$aCampaign.language}]selected[{/if}]>[{$sLangTitle}]</option>
                                        [{/foreach}]
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>[{oxmultilang ident="FCPO_CURRENCY"}]</td>
                                <td>
                                    [{assign var="aCurrencies" value=$oView->fcGetCurrencies()}]
                                    <select class="select" multiple size="[{$aCurrencies|@count}]" name="aCampaigns[[{$sId}]][currency][]" [{$readonly}]>
                                        [{foreach from=$aCurrencies key=sCurrId item=sCurrTitle}]
                                            <option value="[{$sCurrId}]" [{if $sCurrId|in_array:$aCampaign.currency}]selected[{/if}]>[{$sCurrTitle}]</option>
                                        [{/foreach}]
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="edittext" name="aCampaigns[[{$sId}]][delete]" value="[{oxmultilang ident="FCPO_KLARNA_DELETE_STORE_ID"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
                    </dd>
                    <div class="spacer"></div>
                </dl>
            [{/foreach}]
            <dl>
                <dt><input type="submit" class="edittext" name="addCampaign" value="[{oxmultilang ident="FCPO_KLARNA_ADD_CAMPAIGN"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]></dt>
                <dd></dd>
                <div class="spacer"></div>
            </dl>      
        </div>
    </div>
    
    <div class="groupExp">
        <div[{if $oView->fcpoIsLogoAdded()}] class="exp"[{/if}]>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_PP_EXPRESS_LOGOS"}]</b></a>
            <dl>
                <dt>
                    <input type="hidden" name="confbools[blFCPOPayPalDelAddress]" value="false">
                    <input type="checkbox" name="confbools[blFCPOPayPalDelAddress]" value="true" [{if ($confbools.blFCPOPayPalDelAddress)}]checked[{/if}]>
                    [{oxinputhelp ident="FCPO_HELP_PAYPAL_DELADDRESS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYPAL_DELADDRESS"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYPAL_LOGOS"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <table border="1" cellspacing="0"cellpadding="2">
                        <tr>
                            <th>[{oxmultilang ident="FCPO_PAYPAL_LOGOS_ACTIVE"}]</th>
                            <th>[{oxmultilang ident="FCPO_PAYPAL_LOGOS_LANG"}]</th>
                            <th>[{oxmultilang ident="FCPO_PAYPAL_LOGOS_LOGO"}]</th>
                            <th>[{oxmultilang ident="FCPO_PAYPAL_LOGOS_UPLOAD"}]</th>
                            <th>[{oxmultilang ident="FCPO_PAYPAL_LOGOS_DEFAULT"}]</th>
                        </tr>
                        [{foreach from=$oView->fcpoGetPayPalLogos() item=aLogo}]
                            <tr>
                                <td>
                                    <input type="hidden" name="logos[[{$aLogo.oxid}]][active]" value="0">
                                    <input type="checkbox" name="logos[[{$aLogo.oxid}]][active]" value="1" [{if ($aLogo.active)}]checked[{/if}]>
                                </td>
                                <td>
                                    <select name="logos[[{$aLogo.oxid}]][langid]" class="editinput">
                                        [{foreach from=$languages item=lang}]
                                            <option value="[{$lang->id}]" [{if $lang->id == $aLogo.langid}]SELECTED[{/if}]>[{$lang->name}]</option>
                                        [{/foreach}]
                                    </select>
                                </td>
                                <td>
                                    [{if $aLogo.logo == ''}]
                                        [{oxmultilang ident="FCPO_PAYPAL_LOGOS_NOT_EXISTING"}]
                                    [{else}]
                                        <img src="[{$aLogo.logo}]">
                                    [{/if}]
                                </td>
                                <td>
                                    <input type="file" name="logo_[{$aLogo.oxid}]">
                                </td>
                                <td>
                                    <input type="radio" name="defaultlogo" value="[{$aLogo.oxid}]" [{if $aLogo.default == 1}]CHECKED[{/if}]>
                                </td>
                            </tr>
                        [{/foreach}]
                    </table><br>
                    <input type="submit" class="edittext" name="addPayPalLogo" value="[{oxmultilang ident="FCPO_CONFIG_ADD_PP_EXPRESS_LOGO"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
                </dd>
                <div class="spacer"></div>
            </dl>    
        </div>
    </div>
                
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_PAYOLUTION"}]</b></a>
            <dl>
                <dt>
                    <input type="hidden" name="confbools[blFCPOPayolutionB2BMode]" value="false">
                    <input type="checkbox" class="txt" name="confbools[blFCPOPayolutionB2BMode]" value="true" [{if $confbools.blFCPOPayolutionB2BMode}]checked[{/if}] [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PAYOLUTION_B2BMODE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYOLUTION_B2BMODE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOPayolutionCompany]" value="[{$confstrs.sFCPOPayolutionCompany}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PAYOLUTION_COMPANY"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYOLUTION_COMPANY"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOPayolutionAuthUser]" value="[{$confstrs.sFCPOPayolutionAuthUser}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PAYOLUTION_AUTH_USER"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYOLUTION_AUTH_USER"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOPayolutionAuthSecret]" value="[{$confstrs.sFCPOPayolutionAuthSecret}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_PAYOLUTION_AUTH_SECRET"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYOLUTION_AUTH_SECRET"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_RATEPAY"}]</b></a>
            <dl>
                <dt></dt>
                <dd>
                    <h3>[{oxmultilang ident="FCPO_RATEPAY_GENERAL_SETTINGS"}]</h3>
                </dd>
            </dl>
            <dl>
                <dt>
                    <input type="hidden" name="confbools[blFCPORatePayB2BMode]" value="false">
                    <input type="checkbox" class="txt" name="confbools[blFCPORatePayB2BMode]" value="true" [{if $confbools.blFCPORatePayB2BMode}]checked[{/if}] [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_RATEPAY_B2BMODE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_RATEPAY_B2BMODE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPORatePaySnippetID]" [{if $confstrs.sFCPORatePaySnippetID == '' || $confstrs.sFCPORatePaySnippetID == 'ratepay'  }] value="ratepay" [{else}]  value="[{$confstrs.sFCPORatePaySnippetID}]" [{/if}]>
                </dt>
                <dd>
                    Ratepay Snippet ID
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <h3>[{oxmultilang ident="FCPO_PROFILES_RATEPAY"}]</h3>
                </dd>
                <div class="spacer"></div>
            </dl>
            [{foreach from=$oView->fcpoGetRatePayProfiles() item=aRatePayProfile key=sOxid}]
                <dl>
                    <dt style="padding-top: 10px;"></dt>
                    <dd>
                        Shop-ID: <input type="text" class="edittext" name="aRatepayProfiles[[{$sOxid}]][shopid]" value="[{$aRatePayProfile.shopid}]">&nbsp;
                        [{oxmultilang ident="FCPO_PROFILES_RATEPAY_CURRENCY"}]: 
                        <select class="edittext" name="aRatepayProfiles[[{$sOxid}]][currency]">
                            [{foreach from=$oView->fcpoGetCurrencyIso() item='sCurrentCurrencyIso'}]
                                <option value="[{$sCurrentCurrencyIso}]" [{if $aRatePayProfile.currency == $sCurrentCurrencyIso}]selected[{/if}]>[{$sCurrentCurrencyIso}]</option>
                            [{/foreach}]
                        </select>&nbsp;
                        [{oxmultilang ident="FCPO_PROFILES_RATEPAY_PAYMENT"}]: 
                        <select class="edittext" name="aRatepayProfiles[[{$sOxid}]][paymentid]">
                            <option value="fcporp_bill" [{if $aRatePayProfile.OXPAYMENTID == 'fcporp_bill'}]selected[{/if}]>RatePay Rechnung</option>
                            <option value="fcporp_debitnote" [{if $aRatePayProfile.OXPAYMENTID == 'fcporp_debitnote'}]selected[{/if}]>RatePay Lastschrift</option>
                        </select>
                        <input type="submit" class="edittext" name="aRatepayProfiles[[{$sOxid}]][delete]" value="[{oxmultilang ident="FCPO_RATEPAY_DELETE_PROFILE"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]><br>
                        [{if $aRatePayProfile.merchant_name != ''}]
                            <input type="checkbox" value="[{$sOxid}]" onclick="Javascript:handleRatePayShowDetails(this)"> [{oxmultilang ident="FCPO_RATEPAY_PROFILE_TOGGLE_DETAILS"}]
                        [{/if}]
                    </dd>
                    <div class="spacer"></div>
                </dl>
                <dl id="ratepay_profile_details_[{$sOxid}]" style="display: none;">
                    <dt></dt>
                    <dd>
                        <h3>[{oxmultilang ident="FCPO_RATEPAY_PROFILE_DETAILS_FOR_ID"}] [{$aRatePayProfile.shopid}]</h3>
                        <table>
                        [{foreach from=$aRatePayProfile item=sFieldValue key=sFieldName}]
                            <tr>
                                <td>[{$sFieldName}]</td>
                                <td>[{$sFieldValue}]</td>
                            </tr>
                        [{/foreach}]
                        </table>
                    </dd>
                    <div class="spacer"></div>
                </dl>
                        
            [{/foreach}]   
            <dl>
                <dt><input type="submit" class="edittext" name="addRatePayProfile" value="[{oxmultilang ident="FCPO_RATEPAY_ADD_PROFILE"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]></dt>
                <dd></dd>
                <div class="spacer"></div>
            </dl>      
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_AMAZONPAY"}]</b></a>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOAmazonPaySellerId]" value="[{$confstrs.sFCPOAmazonPaySellerId}]" disabled>
                    [{oxinputhelp ident="FCPO_HELP_AMAZONPAY_SELLERID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_SELLERID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOAmazonPayClientId]" value="[{$confstrs.sFCPOAmazonPayClientId}]" disabled>
                    [{oxinputhelp ident="FCPO_HELP_AMAZONPAY_CLIENTID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_CLIENTID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt><input type="submit" class="edittext" name="getAmazonPayConfiguration" value="[{oxmultilang ident="FCPO_AMAZONPAY_GET_CONFIG"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]></dt>
                <dd></dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOAmazonButtonType]">
                        <option value="PwA" [{if $confstrs.sFCPOAmazonButtonType == "PwA"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE_PwA"}]</option>
                        <option value="Pay" [{if $confstrs.sFCPOAmazonButtonType == "Pay"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE_Pay"}]</option>
                        <option value="A" [{if $confstrs.sFCPOAmazonButtonType == "A"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE_A"}]</option>
                    </select>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOAmazonButtonColor]">
                        <option value="Gold" [{if $confstrs.sFCPOAmazonButtonColor == "Gold"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_COLOR_GOLD"}]</option>
                        <option value="LightGray" [{if $confstrs.sFCPOAmazonButtonColor == "LightGray"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE_LIGHT_GRAY"}]</option>
                        <option value="DarkGray" [{if $confstrs.sFCPOAmazonButtonColor == "DarkGray"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_TYPE_DARKGRAY"}]</option>
                    </select>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_BUTTON_COLOR"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOAmazonMode]">
                        <option value="alwayssync" [{if $confstrs.sFCPOAmazonMode == "alwayssync"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_MODE_ALWAYSSYNC"}]</option>
                        <option value="firstsyncthenasync" [{if $confstrs.sFCPOAmazonMode == "firstsyncthenasync"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_MODE_FIRSTSYNCTHENSYNC"}]</option>
                    </select>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_MODE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sFCPOAmazonLoginMode]">
                        <option value="auto" [{if $confstrs.sFCPOAmazonLoginMode == "auto"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_LOGINMODE_AUTO"}]</option>
                        <option value="popup" [{if $confstrs.sFCPOAmazonLoginMode == "popup"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_LOGINMODE_POPUP"}]</option>
                        <option value="redirect" [{if $confstrs.sFCPOAmazonLoginMode == "redirect"}]selected[{/if}]>[{oxmultilang ident="FCPO_AMAZONPAY_LOGINMODE_REDIRECT"}]</option>
                    </select>
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_AMAZONPAY_LOGINMODE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_SECINVOICE"}]</b></a>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOSecinvoicePortalId]" value="[{$confstrs.sFCPOSecinvoicePortalId}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_SECINVOICE_PORTAL_ID"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_SECINVOICE_PORTAL_ID"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sFCPOSecinvoicePortalKey]" value="[{$confstrs.sFCPOSecinvoicePortalKey}]" [{$readonly}]>
                    [{oxinputhelp ident="FCPO_HELP_SECINVOICE_PORTAL_KEY"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_SECINVOICE_PORTAL_KEY"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_CONFIG_GROUP_PAYDIREKT"}]</b></a>
            <dl>
                <dt>
                    <select name="confstrs[sPaydirektExpressButtonType]">
                        <option value="none" [{if $confstrs.sPaydirektExpressButtonType == "none"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_BUTTON_NONE"}]</option>
                        <option value="green" [{if $confstrs.sPaydirektExpressButtonType == "green"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_BUTTON_GREEN"}]</option>
                        <option value="green2" [{if $confstrs.sPaydirektExpressButtonType == "green2"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_BUTTON_GREEN2"}]</option>
                        <option value="white" [{if $confstrs.sPaydirektExpressButtonType == "white"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_BUTTON_WHITE"}]</option>
                        <option value="white2" [{if $confstrs.sPaydirektExpressButtonType == "white2"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_BUTTON_WHITE2"}]</option>
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_PAYDIREKT_BUTTONTYPE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYDIREKT_BUTTONTYPE"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sPaydirektShippingTermsUrl]" value="[{$confstrs.sPaydirektShippingTermsUrl}]">
                    [{oxinputhelp ident="FCPO_HELP_PAYDIREKT_SHIPPING_TERMS_URL"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYDIREKT_SHIPPING_TERMS_URL"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sPaydirektExpressDeliverySetId]">
                        <option value="none" [{if $confstrs.sPaydirektExpressDeliverySetId == "none"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_PAYDIREKT_EXPRESS_DELIVERY_NONE"}]</option>
                        [{foreach from=$oView->fcpoGetDeliverySets() item="oDelivery"}]
                            [{assign var="sCurrentSetId" value=$oDelivery->getId()}]
                            [{assign var="sCurrentSetName" value=$oDelivery->oxdeliveryset__oxtitle->rawValue}]
                            <option value="[{$sCurrentSetId}]" [{if $confstrs.sPaydirektExpressDeliverySetId == $sCurrentSetId}]SELECTED[{/if}]>[{$sCurrentSetName}]</option>
                        [{/foreach}]
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_PAYDIREKT_DELIVERYSET"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_PAYDIREKT_DELIVERYSET"}]
                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="FCPO_FORWARD_REDIRECTS"}]</b></a>
            <dl>
                <dt>
                    <select name="confstrs[sTransactionRedirectLogging]">
                        <option value="none" [{if $confstrs.sTransactionRedirectLogging == "none"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_TRANSACTIONREDIRECTLOGGING_NONE"}]</option>
                        <option value="all" [{if $confstrs.sTransactionRedirectLogging == "all"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_TRANSACTIONREDIRECTLOGGING_ALL"}]</option>
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_TRANSACTIONREDIRECTLOGGING"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_TRANSACTIONREDIRECTLOGGING"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <select name="confstrs[sTransactionRedirectMethod]">
                        <option value="direct" [{if $confstrs.sTransactionRedirectMethod == "direct"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_TRANSACTIONREDIRECTMETHOD_DIRECT"}]</option>
                        <option value="cronjob" [{if $confstrs.sTransactionRedirectMethod == "cronjob"}]SELECTED[{/if}]>[{oxmultilang ident="FCPO_TRANSACTIONREDIRECTMETHOD_CRONJOB"}]</option>
                    </select>
                    [{oxinputhelp ident="FCPO_HELP_TRANSACTIONREDIRECTMETHOD"}]
                </dt>
                <dd>
                    [{oxmultilang ident="FCPO_TRANSACTIONREDIRECTMETHOD"}]
                </dd>
                <div class="spacer"></div>
            </dl>
            [{if $confstrs.sTransactionRedirectMethod != "cronjob"}]
                <dl>
                    <dt>
                        <input type="text" class="txt" name="confstrs[sTransactionRedirectTimeout]" value="[{$confstrs.sTransactionRedirectTimeout}]" [{$readonly}]>
                        [{oxinputhelp ident="FCPO_HELP_TRANSACTIONREDIRECT_TIMEOUT"}]
                    </dt>
                    <dd>
                        [{oxmultilang ident="FCPO_TRANSACTIONREDIRECT_TIMEOUT"}]
                    </dd>
                    <div class="spacer"></div>
                </dl>
            [{/if}]
        </div>
    </div>


    <br>
    <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
    <input type="submit" class="edittext" name="export" value="[{oxmultilang ident="FCPO_EXPORT_CONFIG"}]" target="_blank" onClick="Javascript:document.myedit.fnc.value='export'" [{$readonly}]>
</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]