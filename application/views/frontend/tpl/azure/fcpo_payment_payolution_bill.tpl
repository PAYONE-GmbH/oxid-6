<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        <link href="[{$oViewConf->fcpoGetModuleCssPath('lightview.css')}]" rel="stylesheet">
        <script src="[{$oViewConf->fcpoGetModuleJsPath('jquery-1.10.1.min.js')}]"></script>
        <script src="[{$oViewConf->fcpoGetModuleJsPath()}]lightview/lightview.js"></script>
        <ul class="form fcpo_payolution_bill_form">
            [{if $oView->fcpoShowPayolutionB2B()}]
                <li>
                    <label>[{oxmultilang ident="FCPO_PAYOLUTION_USTID"}]:</label>
                    <input placeholder="[{oxmultilang ident="FCPO_PAYOLUTION_USTID"}]" type="text" size="20" maxlength="64" name="dynvalue[fcpo_payolution_bill_oxustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                </li>
            [{elseif $oView->fcpoShowPayolutionB2C()}]
                <li>
                    <label>[{oxmultilang ident="FCPO_PAYOLUTION_BIRTHDATE"}]:</label>
                    <select name="dynvalue[fcpo_payolution_bill_birthdate_day]">
                        [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                            <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select name="dynvalue[fcpo_payolution_bill_birthdate_month]">
                        [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                            <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select name="dynvalue[fcpo_payolution_bill_birthdate_year]">
                        [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                            <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                        [{/foreach}]
                    </select>
                </li>
            [{/if}]
            [{if $oView->fcpoPayolutionBillTelephoneRequired()}]
                <li>
                    <label>[{oxmultilang ident="FCPO_PAYOLUTION_PHONE"}]:</label>
                    <input placeholder="[{oxmultilang ident="FCPO_PAYOLUTION_PHONE"}]" type="text" size="20" maxlength="64" name="dynvalue[fcpo_payolution_bill_oxfon]" value="[{$oView->fcpoGetUserValue('oxfon')}]">
                </li>
            [{/if}]
            <li>
                <input name="dynvalue[fcpo_payolution_bill_agreed]" value="agreed" type="checkbox">&nbsp;[{$oView->fcpoGetPoAgreementInit($sPaymentID)}] <a href='[{$oView->fcpoGetPayolutionAgreementLink()}]' class="lightview fcpoPayolutionAgreeRed" data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_PAYOLUTION_AGREE"}]</a> [{oxmultilang ident="FCPO_PAYOLUTION_AGREEMENT_PART_2"}]
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