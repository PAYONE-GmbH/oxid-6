<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <script src="[{$oViewConf->fcpoGetModuleJsPath('jquery-3.6.0.min.js')}]"></script>
        <script src="[{$oViewConf->fcpoGetModuleJsPath()}]lightview/lightview.js"></script>
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        
        [{if $oView->fcpoShowPayolutionB2C()}]
            <div class="form-group cpo_payolution_debitnote_birthdate">
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_PAYOLUTION_BIRTHDATE"}]:</label>
                <div class="col-lg-9">
                    <select aria-label="[{oxmultilang ident="FCPO_DAY"}]" name="dynvalue[fcpo_payolution_debitnote_birthdate_day]">
                        [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                            <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcpo_payolution_debitnote_birthdate_month]">
                        [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                            <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcpo_payolution_debitnote_birthdate_year]">
                        [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                            <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                        [{/foreach}]
                    </select>
                </div>
            </div>
        [{/if}]
        <div class="form-group fcpo_payolution_debitnote_accountholder">
            <label for="fcpo_payolution_debitnote_accountholder" id="fcpo_payolution_debitnote_accountholder_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_PAYOLUTION_ACCOUNTHOLDER"}]:</label>
            <div class="col-lg-9">
                <input id="fcpo_payolution_debitnote_accountholder" placeholder="[{oxmultilang ident="FCPO_PAYOLUTION_ACCOUNTHOLDER"}]" class="form-control js-oxValidate js-oxValidate_notEmpty"  type="text" size="20" maxlength="64" name="dynvalue[fcpo_payolution_debitnote_accountholder]" value="[{$dynvalue.fcpo_payolution_debitnote_accountholder}]" onkeyup="fcHandleDebitInputs();return false;" required="required">
            </div>
        </div> 
        <div class="form-group fcpo_payolution_debitnote_iban">
            <label for="fcpo_payolution_debitnote_iban" id="fcpo_payolution_debitnote_iban_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_IBAN"}]:</label>
            <div class="col-lg-9">
                <input id="fcpo_payolution_debitnote_iban" placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" class="form-control js-oxValidate js-oxValidate_notEmpty" type="text" size="20" maxlength="64" name="dynvalue[fcpo_payolution_debitnote_iban]" value="[{$dynvalue.fcpo_payolution_debitnote_iban}]" onkeyup="fcHandleDebitInputs();return false;" required="required">
                <div id="fcpo_payolution_iban_invalid" class="fcpo_check_error">
                    <p class="oxValidateError" style="display: block;">
                        [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                    </p>
                </div>
            </div>
        </div>
        <div class="form-group fcpo_payolution_debitnote_bic">
            <label for="fcpo_payolution_debitnote_bic" id="fcpo_payolution_debitnote_bic_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_BIC"}]:</label>
            <div class="col-lg-9">
                <input id="fcpo_payolution_debitnote_bic" placeholder="[{oxmultilang ident="FCPO_BANK_BIC"}]" class="form-control js-oxValidate js-oxValidate_notEmpty" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_payolution_debitnote_bic]" value="[{$dynvalue.fcpo_payolution_debitnote_bic}]" onkeyup="fcHandleDebitInputs();return false;" required="required">
                <div id="fcpo_payolution_bic_invalid" class="fcpo_check_error">
                    <p class="oxValidateError" style="display: block;">
                        [{oxmultilang ident="FCPO_BIC_INVALID"}]
                    </p>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info col-lg-offset-3 desc">
            <input aria-label="Payolution agreement" name="dynvalue[fcpo_payolution_debitnote_agreed]" value="agreed" type="checkbox">&nbsp;[{$oView->fcpoGetPoAgreementInit($sPaymentID)}] <a href='[{$oView->fcpoGetPayolutionAgreementLink()}]' class="lightview fcpoPayolutionAgreeRed" data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_PAYOLUTION_AGREE"}]</a> [{oxmultilang ident="FCPO_PAYOLUTION_AGREEMENT_PART_2"}]
        </div>
        <div class="alert alert-info col-lg-offset-3 desc">
            <input aria-label="Payolution SEPA agreement" name="dynvalue[fcpo_payolution_debitnote_sepa_agreed]" value="agreed" type="checkbox">&nbsp;[{oxmultilang ident="FCPO_PAYOLUTION_SEPA_AGREEMENT_PART_1"}] <a href='[{$oView->fcpoGetPayolutionSepaAgreementLink()}]' class="lightview fcpoPayolutionAgreeRed" data-lightview-type="iframe" data-lightview-options="width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'">[{oxmultilang ident="FCPO_PAYOLUTION_SEPA_AGREE"}]</a>
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