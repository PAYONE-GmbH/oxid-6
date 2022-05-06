[{if $oView->fcpoRatePayAllowed('fcporp_installment')}]
    [{assign var='sSettlementType' value=$oView->fcpoGetRatepaySettlementType('fcporp_installment')}]
    [{assign var="aFcPoRpCalcParam" value=$oView->fcpoGetRatepayCalculatorParams('fcporp_installment')}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
            <ul class="form fcporp_installment_form">
                <input type="hidden" name="dynvalue[fcporp_installment_profileid]" value="[{$oView->fcpoGetRatePayMatchedProfile('fcporp_installment')}]">
            	<input type="hidden" name="dynvalue[fcporp_installment_device_fingerprint]" value="[{$oView->fcpoGetRatePayDeviceFingerprint()}]">
                <input type="hidden" id="fcporp_installment_settlement_type" name="dynvalue[fcporp_installment_settlement_type]" value="[{if $sSettlementType=='both'}]debit[{else}][{$sSettlementType}][{/if}]">
                <script language="JavaScript">
                    var di = { t: '[{$oView->fcpoGetRatePayDeviceFingerprint()}]', v: '[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]', l: 'Checkout'};
                </script>

                <script type="text/javascript"
                        src="//d.ratepay.com/[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]/di.js"></script>
                <noscript><link rel="stylesheet" type="text/css"
                                href="//d.ratepay.com/di.css?t=[{$oView->fcpoGetRatePayDeviceFingerprint()}]&v=[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]&l=Check
                    out"></noscript>
                <object type="application/x-shockwave-flash"
                        data="//d.ratepay.com/[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]/c.swf" width="0" height="0">
                    <param name="movie" value="//d.ratepay.com/[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]/c.swf" />
                    <param name="flashvars"
                           value="t=[{$oView->fcpoGetRatePayDeviceFingerprint()}]&v=[{$oView->fcpoGetRatePayDeviceFingerprintSnippetId()}]"/><param
                            name="AllowScriptAccess" value="always"/>
                </object>
                [{if $oView->fcpoRatePayShowUstid()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_USTID"}]</label>
                        <input placeholder="[{oxmultilang ident="FCPO_RATEPAY_USTID"}]" type='text' name="dynvalue[fcporp_installment_ustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                    </li>
                [{/if}]
                [{if $oView->fcpoRatePayShowBirthdate()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_BIRTHDATE"}]</label>
                        <select name="dynvalue[fcporp_installment_birthdate_day]">
                            [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                                <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select name="dynvalue[fcporp_installment_birthdate_month]">
                            [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                                <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select name="dynvalue[fcporp_installment_birthdate_year]">
                            [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                                <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                            [{/foreach}]
                        </select>
                    </li>
                [{/if}]
                [{if $oView->fcpoRatePayShowFon()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_FON"}]</label>
                        <input placeholder="[{oxmultilang ident="FCPO_RATEPAY_FON"}]" type='text' name="dynvalue[fcporp_installment_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]">
                    </li>
                [{/if}]
                <li class="rpContainer">
                    <div class="col-lg-offset-0">
                        [{oxmultilang ident="FCPO_RATEPAY_CALCULATION_INTRO_PART1"}]
                        [{oxmultilang ident="FCPO_RATEPAY_CALCULATION_INTRO_PART2"}]
                        [{oxmultilang ident="FCPO_RATEPAY_CALCULATION_INTRO_PART3"}]
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center" id="fcporp_installment_firstInput">
                                    <h2>[{oxmultilang ident="FCPO_RATEPAY_RUNTIME_TITLE"}]</h2>
                                    [{oxmultilang ident="FCPO_RATEPAY_RUNTIME_DESCRIPTION"}]
                                </div>
                                <input type="hidden" id="fcporp_installment_rate_elv" name="rate_elv" value="[{$pi_rate_elv}]">
                                <input type="hidden" id="fcporp_installment_rate" name="rate" value="[{$pi_rate}]">
                                <input type="hidden" id="fcporp_installment_paymentFirstday" name="paymentFirstday" value="[{$pi_firstday}]">
                                <input type="hidden" id="fcporp_installment_month" name="month" value="">
                                <input type="hidden" id="fcporp_installment_mode" name="mode" value="">
                                <div class="panel-body">
                                    <div class="btn-group btn-group-justified" style="display: flex" role="group" aria-label="...">
                                        [{foreach from=$aFcPoRpCalcParam.monthAllowed item='sMonth'}]
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-default rp-btn-runtime" style="background-color: darkgray; margin-right: 2px" type="button" onclick="fcpoRatepayRateCalculatorAction('runtime', 'fcporp_installment', [{$sMonth}]);" id="fcporp_installment_button_month-[{$sMonth}]" role="group">[{$sMonth}]</button>
                                        </div>
                                        [{/foreach}]
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading text-center" id="fcporp_installment_secondInput">
                                    <h2>[{oxmultilang ident="FCPO_RATEPAY_RATE_TITLE"}]</h2>
                                    [{oxmultilang ident="FCPO_RATEPAY_RATE_DESCRIPTION"}]
                                </div>

                                <div class="panel-body">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon">&euro;</span>
                                        <input type="text" id="fcporp_installment_rate_value" name="dynvalue[fcporp_installment_rate_value]" class="form-control" aria-label="Amount" />
                                        <span class="input-group-btn">
                                    <button class="btn btn-default" style="background-color: darkgray; margin-right: 2px" onclick="fcpoRatepayRateCalculatorAction('rate', 'fcporp_installment');" type="button" id="fcporp_installment_button_runtime">[{oxmultilang ident="FCPO_RATEPAY_RATE_CALCULATE"}]</button>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="fcporp_installment_calculation_details"></div>
                </li>

                [{if (in_array($sSettlementType, array('both', 'debit')))}]
                    <div id="fcporp_installment_sepa_container">
                        [{if ($sSettlementType == 'both')}]
                            <strong class="rp-installment-header">[{oxmultilang ident='FCPO_RATEPAY_INSTALLMENT_TYPE_DEBIT_TITLE'}]</strong>
                            <div class="row rp-payment-type-switch" id="fcporp_installment_rp-switch-payment-type-bank-transfer" onclick="fcpoChangeInstallmentPaymentType(28, 'fcporp_installment')">
                                <a class="rp-link">[{oxmultilang ident='FCPO_RATEPAY_INSTALLMENT_SWITCH_TO_TRANSFER_LINK'}]</a>
                            </div><br>
                        [{/if}]
                        <div class="form-group fcpo_ratepay_installment_iban">
                            <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_IBAN"}]</label>
                            <div class="col-lg-9">
                                <input id="fcporp_installment_iban" placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" class="form-control js-oxValidate js-oxValidate_notEmpty" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ratepay_installment_iban]" value="[{$dynvalue.fcpo_ratepay_installment_iban}]" onkeyup="fcHandleDebitInputs();return false;" required="required">
                                <div id="fcpo_ratepay_iban_invalid" class="fcpo_check_error">
                                    <p class="oxValidateError" style="display: block;">
                                        [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info col-lg-offset-3 desc">
                            [{oxmultilang ident='FCPO_RATEPAY_MANDATE_IDENTIFICATION'}]
                        </div>

                        <li>
                            <input name="dynvalue[fcpo_ratepay_installment_sepa_agreed]" value="agreed" type="checkbox">&nbsp;[{oxmultilang ident="FCPO_RATEPAY_SEPA_AGREE"}]
                        </li>
                    </div>
                    [{if ($sSettlementType == 'both')}]
                        <div id="fcporp_installment_rp-switch-payment-type-direct-debit">
                            <strong class="rp-installment-header">[{oxmultilang ident='FCPO_RATEPAY_INSTALLMENT_TYPE_TRANSFER_TITLE'}]</strong>
                            <div class="row rp-payment-type-switch" id="fcporp_installment_rp-switch-payment-type-bank-transfer" onclick="fcpoChangeInstallmentPaymentType(2, 'fcporp_installment')">
                                <a class="rp-link">[{oxmultilang ident='FCPO_RATEPAY_INSTALLMENT_SWITCH_TO_DEBIT_LINK'}]</a>
                            </div><br>
                        </div>
                    [{/if}]
                [{/if}]
                <li>
                    <input name="dynvalue[fcpo_ratepay_installment_agreed]" value="agreed" type="checkbox"> [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS1"}] <a href='[{$oView->fcpoGetRatepayAgreementLink()}]' class='lightview fcpoRatepayAgreeRed' data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS2"}]</a> [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS3"}] <a href='[{$oView->fcpoGetRatepayPrivacyLink()}]' class='lightview fcpoRatepayAgreeRed' data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS4"}]</a> [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS5"}]
                </li>
            </ul>
            [{block name="checkout_payment_longdesc"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                    <div class="desc">
                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                    </div>
                [{/if}]
            [{/block}]
        </dd>
    </dl>
[{/if}]