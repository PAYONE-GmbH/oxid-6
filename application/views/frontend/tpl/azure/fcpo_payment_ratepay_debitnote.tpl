[{if $oView->fcpoRatePayAllowed('fcporp_debitnote')}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
            <ul class="form fcporp_debitnote_form">
                <input type="hidden" name="dynvalue[fcporp_debitnote_profileid]" value="[{$oView->fcpoGetRatePayMatchedProfile('fcporp_debitnote')}]">
                [{if $oView->fcpoRatePayShowUstid()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_USTID"}]</label>
                        <input placeholder="[{oxmultilang ident="FCPO_RATEPAY_USTID"}]" type='text' name="dynvalue[fcporp_debitnote_ustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                    </li>
                [{/if}]
                [{if $oView->fcpoRatePayShowBirthdate()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_BIRTHDATE"}]</label>
                        <select name="dynvalue[fcporp_debitnote_birthdate_day]">
                            [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                                <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select name="dynvalue[fcporp_debitnote_birthdate_month]">
                            [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                                <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                            [{/foreach}]
                        </select>
                        &nbsp;
                        <select name="dynvalue[fcporp_debitnote_birthdate_year]">
                            [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                                <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                            [{/foreach}]
                        </select>
                    </li>
                [{/if}]
                [{if $oView->fcpoRatePayShowFon()}]
                    <li>
                        <label>[{oxmultilang ident="FCPO_RATEPAY_FON"}]</label>
                        <input placeholder="[{oxmultilang ident="FCPO_RATEPAY_FON"}]" type='text' name="dynvalue[fcporp_debitnote_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]">
                    </li>
                [{/if}]
                <li>
                    <label>[{oxmultilang ident="FCPO_BANK_IBAN"}]</label>
                    <input placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ratepay_debitnote_iban]" value="[{$dynvalue.fcpo_ratepay_debitnote_iban}]" onkeyup="fcHandleDebitInputs();return false;">
                    <div id="fcpo_payolution_iban_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                        </p>
                    </div>
                </li>
                <li>
                    <label>[{oxmultilang ident="FCPO_BANK_BIC"}]</label>
                    <input placeholder="[{oxmultilang ident="FCPO_BANK_BIC"}]" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ratepay_debitnote_bic]" value="[{$dynvalue.fcpo_ratepay_debitnote_bic}]" onkeyup="fcHandleDebitInputs();return false;">
                    <div id="fcpo_payolution_bic_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_BIC_INVALID"}]
                        </p>
                    </div>
                </li>
                <li>
                    <input name="dynvalue[fcpo_ratepay_debitnote_agreed]" value="agreed" type="checkbox">[{oxmultilang ident="FCPO_RATEPAY_AGREE"}] [{oxmultilang ident="FCPO_RATEPAY_AGREEMENT_PART_2"}]
                </li>
                <li>
                    <input name="dynvalue[fcpo_ratepay_debitnote_sepa_agreed]" value="agreed" type="checkbox">&nbsp;[{oxmultilang ident="FCPO_RATEPAY_SEPA_AGREEMENT_PART_1"}] [{oxmultilang ident="FCPO_RATEPAY_SEPA_AGREE"}]
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