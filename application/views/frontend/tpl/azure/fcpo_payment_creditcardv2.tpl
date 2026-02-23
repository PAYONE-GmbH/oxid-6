[{assign var="dynvalue" value=$oView->getDynValue()}]
<dl id="fcpoCreditcardV2">
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="dynvalue[fcpo_kkv2number]" value="">
        <input type="hidden" name="dynvalue[fcpo_kkv2inputmode]" value="">
        <input type="hidden" name="dynvalue[fcpo_kkv2cardholder]" value="">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">

        <ul class="form fcpo_kk_form">
            <li id="fcpo_ccv2_error">
                <div class="oxValidateError" style="display: block;padding: 0;">
                    [{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_ccv2_error_content"></div>
                </div>
            </li>
        </ul>

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
