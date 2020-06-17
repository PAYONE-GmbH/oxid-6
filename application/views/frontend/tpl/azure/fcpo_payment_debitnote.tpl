<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        <ul class="form fcpo_elv_form">
            <li id="fcpo_elv_error">
                <div class="oxValidateError" style="display: block;padding: 0;">
                    [{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_elv_error_content"></div>
                </div>
            </li>
            <li id="fcpo_elv_error_blocked">
                <div class="oxValidateError" style="display: block;padding: 0;">
                    [{oxmultilang ident="FCPO_ERROR"}]
                    <div>[{oxmultilang ident="FCPO_ERROR_BLOCKED"}]</div>
                </div>
            </li>

            <li>
                <label>[{oxmultilang ident="FCPO_BANK_COUNTRY"}]:</label>
                <select name="dynvalue[fcpo_elv_country]" onchange="fcCheckDebitCountry(this);return false;">
                    [{foreach from=$oView->fcpoGetDebitCountries() key=sCountryId item=sCountry}]
                        <option value="[{$sCountryId}]" [{if $dynvalue.fcpo_elv_country == $sCountryId}]selected[{/if}]>[{$sCountry}]</option>
                    [{/foreach}]
                </select>
            </li>
            <li>
                <label>[{oxmultilang ident="FCPO_BANK_IBAN"}]:</label>
                <input placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_elv_iban]" value="[{$dynvalue.fcpo_elv_iban}]" onkeyup="fcHandleDebitInputs('[{$oView->fcpoGetBICMandatory()}]]');return false;">
                <div id="fcpo_elv_iban_invalid" class="fcpo_check_error">
                    <p class="oxValidateError" style="display: block;">
                        [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                    </p>
                </div>
            </li>
            [{if $oView->getConfigParam('blFCPODebitBICMandatory')}]
                <li>
                    <label>[{oxmultilang ident="FCPO_BANK_BIC"}]:</label>
                    <input placeholder="[{oxmultilang ident="FCPO_BANK_BIC"}]" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_elv_bic]" value="[{$dynvalue.fcpo_elv_bic}]" onkeyup="fcHandleDebitInputs('[{$oView->fcpoGetBICMandatory()}]]');return false;">
                    <div id="fcpo_elv_bic_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_BIC_INVALID"}]
                        </p>
                    </div>
                </li>
            [{/if}]
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