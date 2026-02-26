<div class="well well-sm">
    <dl id="fcpoCreditcardV2">
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{else}]payment-option[{/if}]">
            <input type="hidden" name="dynvalue[fcpo_kkv2number]" value="">
            <input type="hidden" name="dynvalue[fcpo_kkv2inputmode]" value="">
            <input type="hidden" name="dynvalue[fcpo_kkv2cardholder]" value="">
            <input type="hidden" name="dynvalue[fcpo_kkv2type]" value="">
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">

            <div class="form-group fcpo_entry_error" id="fcpo_ccv2_error" style="display: none">
                <div class="col-lg-9">
                        <span class="help-block">
                            <ul role="alert" class="list-unstyled text-danger">
                                <li>[{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_ccv2_error_content"></div></li>
                            </ul>
                        </span>
                </div>
            </div>

            <div id="fcpocreditcardv2-iframe"></div>

            [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_creditcardv2_script.tpl')}]
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