<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        [{assign var="aDynValues" value=$paymentmethod->getDynValues()}]
        [{if $aDynValues}]
        <ul>
            [{foreach from=$aDynValues item=value name=PaymentDynValues}]
            <li>
                <label>[{ $value->name}]</label>
                <input id="[{$sPaymentID}]_[{$smarty.foreach.PaymentDynValues.iteration}]" type="text" class="textbox" size="20" maxlength="64" name="dynvalue[[{$value->name}]]" value="[{ $value->value}]">
            </li>
            [{/foreach}]
        </ul>
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