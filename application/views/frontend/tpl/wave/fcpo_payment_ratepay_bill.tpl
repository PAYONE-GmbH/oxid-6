[{if $oView->fcpoRatePayAllowed('fcporp_bill')}]
<div class="well well-sm">
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}] activePayment[{else}]payment-option[{/if}]">
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
            <input type="hidden" name="dynvalue[fcporp_bill_profileid]" value="[{$oView->fcpoGetRatePayMatchedProfile('fcporp_bill')}]">
            <input type="hidden" name="dynvalue[fcporp_bill_device_fingerprint]" value="[{$oView->fcpoGetRatePayDeviceFingerprint()}]">
            <link href="[{$oViewConf->fcpoGetModuleCssPath('lightview.css')}]" rel="stylesheet">
            <script src="[{$oViewConf->fcpoGetModuleJsPath('jquery-3.6.0.min.js')}]"></script>
            <script src="[{$oViewConf->fcpoGetModuleJsPath()}]lightview/lightview.js"></script>
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
                <div class="form-group fcporp_bill_ustid">
                    <label for="fcporp_bill_ustid" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_RATEPAY_USTID"}]:</label>
                    <div class="col-lg-9">
                        <input id="fcporp_bill_ustid" placeholder="[{oxmultilang ident="FCPO_RATEPAY_USTID"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcporp_bill_ustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                    </div>
                </div>
            [{/if}]
            [{if $oView->fcpoRatePayShowBirthdate()}]
                <div class="form-group fcporp_bill_birthdate">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_RATEPAY_BIRTHDATE"}]:</label>
                    <div class="col-lg-9">
                        <select aria-label="[{oxmultilang ident="FCPO_DAY"}]" name="dynvalue[fcporp_bill_birthdate_day]">
                            [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                                <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcporp_bill_birthdate_month]">
                            [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                                <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcporp_bill_birthdate_year]">
                            [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                                <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                </div>
            [{/if}]
            [{if $oView->fcpoRatePayShowFon()}]
                <div class="form-group fcporp_bill_fon">
                    <label for="fcporp_bill_fon" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_RATEPAY_FON"}]:</label>
                    <div class="col-lg-9">
                        <input id="fcporp_bill_fon" placeholder="[{oxmultilang ident="FCPO_RATEPAY_FON"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcporp_bill_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]">
                    </div>
                </div>
            [{/if}]
            <div class="alert alert-info col-lg-offset-3 desc">
                [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS1"}] <a href='[{$oView->fcpoGetRatepayAgreementLink()}]' class='lightview fcpoRatepayAgreeRed' data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS2"}]</a> [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS3"}] <a href='[{$oView->fcpoGetRatepayPrivacyLink()}]' class='lightview fcpoRatepayAgreeRed' data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS4"}]</a> [{oxmultilang ident="FCPO_RATEPAY_ADD_TERMS5"}]
            </div>
            [{block name="checkout_payment_longdesc"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                    <div class="desc">
                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                    </div>
                [{/if}]
            [{/block}]
        </dd>
    </dl>
</div>
[{/if}]