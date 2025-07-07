<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input aria-label="Payment ID" id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked="checked"[{/if}] />
    <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
    <ul class="form">
        <li>
            <input aria-label="[{oxmultilang ident="FCPO_YEAR"}]" placceholder="[{oxmultilang ident="FCPO_PAYOLUTION_YEAR"}]" type="text" size="5" maxlength="4" name="dynvalue[fcpo_secinvoice_birthdate_year]" value="[{$oView->fcpoGetBirthdayField('year')}]">&nbsp;
            <input aria-label="[{oxmultilang ident="FCPO_MONTH"}]" placceholder="[{oxmultilang ident="FCPO_PAYOLUTION_MONTH"}]" type="text" size="3" maxlength="2" name="dynvalue[fcpo_secinvoice_birthdate_month]" value="[{$oView->fcpoGetBirthdayField('month')}]">&nbsp;
            <input aria-label="[{oxmultilang ident="FCPO_DAY"}]" placceholder="[{oxmultilang ident="FCPO_PAYOLUTION_DAY"}]" type="text" size="3" maxlength="2" name="dynvalue[fcpo_secinvoice_birthdate_day]" value="[{$oView->fcpoGetBirthdayField('day')}]">
        </li>
    </ul>
</div>