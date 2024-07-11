<div class="well well-sm">
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{else}]payment-option[{/if}]">
            <div class="clearfix"></div>

            [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|strip_tags|trim}]
            <div class="alert alert-info col-lg-offset-3 desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
            [{/if}]
            [{/block}]
        </dd>
    </dl>
</div>