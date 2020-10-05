<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">

        [{if $oView->fcpoGetSofoShowIban()}]
            [{if $oView->fcpoForceDeprecatedBankData()}]
                <div class="form-group" id="fcpo_ou_blz">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_CODE"}]:</label>
                    <div class="col-lg-9">
                        <input placeholder="[{oxmultilang ident="FCPO_BANK_CODE"}]" class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ou_blz]" value="[{$dynvalue.fcpo_ou_blz}]">
                        <div id="fcpo_ou_blz_invalid" class="fcpo_check_error">
                                    <span class="help-block ">
                                        <ul role="alert" class="list-unstyled text-danger">
                                            <li>[{oxmultilang ident="FCPO_BLZ_INVALID"}]</li>
                                        </ul>
                                    </span>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="fcpo_ou_ktonr" style="display: none;">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_ACCOUNT_NUMBER"}]:</label>
                    <div class="col-lg-9">
                        <input placeholder="[{oxmultilang ident="FCPO_BANK_ACCOUNT_NUMBER"}]" class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ou_ktonr]" value="[{$dynvalue.fcpo_ou_ktonr}]">
                        <div id="fcpo_ou_ktonr_invalid" class="fcpo_check_error">
                                    <span class="help-block ">
                                        <ul role="alert" class="list-unstyled text-danger">
                                            <li>[{oxmultilang ident="FCPO_KTONR_INVALID"}]</li>
                                        </ul>
                                    </span>
                        </div>
                    </div>
                </div>
            [{else}]
                <div class="form-group" id="fcpo_ou_iban">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_IBAN"}]:</label>
                    <div class="col-lg-9">
                        <input placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ou_iban]" value="[{$dynvalue.fcpo_ou_iban}]">
                        <div id="fcpo_ou_iban_invalid" class="fcpo_check_error">
                                    <span class="help-block ">
                                        <ul role="alert" class="list-unstyled text-danger">
                                            <li>[{oxmultilang ident="FCPO_IBAN_INVALID"}]</li>
                                        </ul>
                                    </span>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="fcpo_ou_bic">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_BIC"}]:</label>
                    <div class="col-lg-9">
                        <input placeholder="[{oxmultilang ident="FCPO_BANK_BIC"}]" class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_ou_bic]" value="[{$dynvalue.fcpo_ou_bic}]">
                        <div id="fcpo_ou_bic_invalid" class="fcpo_check_error">
                                    <span class="help-block ">
                                        <ul role="alert" class="list-unstyled text-danger">
                                            <li>[{oxmultilang ident="FCPO_BIC_INVALID"}]</li>
                                        </ul>
                                    </span>
                        </div>
                    </div>
                </div>
            [{/if}]
        [{/if}]

        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|trim}]
                <div class="desc">
                    [{ $paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
        [{/block}]
    </dd>
</dl>