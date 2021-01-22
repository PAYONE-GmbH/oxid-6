[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
    [{assign var="dynvalue" value=$oView->getDynValue()}]
    <div class="well well-sm">
        <dl id="fcpoCreditcard" style="display:none;">
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
                <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                <script type="text/javascript" src="[{$oViewConf->fcpoGetHostedPayoneJs()}]"></script>
                <input type="hidden" name="dynvalue[fcpo_kknumber]" value="">
                <input type="hidden" name="fcpo_cc_type" value="hosted">
                <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
                [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
                [{/foreach}]
                <div class="form-group fcpo_kktype">
                    <label for="cardtype" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CREDITCARD"}]:</label>
                    <div class="col-lg-9">
                        <select id="cardtype" name="dynvalue[fcpo_kktype]"  class="form-control selectpicker" required="required">
                            <option value="V" data-cardtype="none">[{oxmultilang ident="FCPO_CREDITCARD_CHOOSE"}]</option>
                            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                                <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" data-cardtype="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                </div>
                <div class="form-group fcpo_kknumber">
                    <label for="cardpanInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_NUMBER"}]:</label>
                    <div class="col-lg-9">
                        <span class="inputIframe" id="cardpan"></span>
                    </div>
                </div>

                [{if $oView->fcpoUseCVC()}]
                    <div class="form-group fcpo_kkpruef">
                        <label for="cvcInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]:</label>
                        <div class="col-lg-9">
                            <span id="cardcvc2" class="inputIframe"></span>
                        </div>
                    </div>        
                [{/if}]
                <div class="form-group fcpo_kkexpire">
                    <label for="expireInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_VALID_UNTIL"}]:</label>
                    <div class="col-lg-9">
                        <span id="expireInput" class="inputIframe">
                            <span id="cardexpiremonth"></span>
                            <span id="cardexpireyear"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group fcpo_kkcardholder">
                    <label for="fcpo_cc_cardholder" id="fcpo_cc_cardholder_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CC_CARDHOLDER"}]:</label>
                    <div class="col-lg-9">
                        <input autocomplete="off" type="text"
                               class="form-control"
                               size="20" maxlength="50" id="fcpo_cc_cardholder" name="dynvalue[fcpo_kkcardholder]"
                               value="[{$dynvalue.fcpo_kkcardholder}]"
                               onkeyup="validateCardholder()"
                        >
                        <span class="help-block">[{oxmultilang ident="FCPO_CC_CARDHOLDER_HELPTEXT"}]</span>
                        <div id="fcpo_cc_cardholder_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_CARDHOLDER_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-push-3">
                        <div id="errorOutput" class="alert-danger"></div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-9 col-lg-push-3">
                        <div id="errorCVC" style="display:none;" class="alert-danger">[{oxmultilang ident="FCPO_CC_HOSTED_ERROR_CVC"}]</div>
                        <div id="errorCardType" style="display:none;" class="alert-danger">[{oxmultilang ident="FCPO_CC_HOSTED_ERROR_CARDTYPE"}]</div>
                        <div id="errorIncomplete" style="display:none;" class="alert-danger">[{oxmultilang ident="FCPO_CC_HOSTED_ERROR_INCOMPLETE"}]</div>
                    </div>
                </div>

                [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_creditcard_script.tpl')}]
                [{block name="checkout_payment_longdesc"}]
                    [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                        <div class="alert alert-info col-lg-offset-3 desc">
                            [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                        </div>
                    [{/if}]
                [{/block}]
            </dd>
        </dl>
    </div>
[{/if}]