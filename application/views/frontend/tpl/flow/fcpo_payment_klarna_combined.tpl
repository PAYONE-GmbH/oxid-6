<dl>
    [{assign var='checkedPaymentId' value=$oView->getCheckedPaymentId()}]
    <dt>
        <input id="payment_klarna_combined" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->fcpoIsKlarnaCombined($checkedPaymentId)}]checked[{/if}]>
        <label for="payment_klarna_combined"><b>Klarna</b></label>
    </dt>
    <div class="hidden">
        <input type="hidden" id="fcpo_klarna_auth_token" name="dynvalue[klarna_authorization_token]">
    </div>
    <dd class="[{if  $oView->fcpoIsKlarnaCombined($checkedPaymentId)}]activePayment[{/if}]">
        <select id="klarna_payment_selector">
            [{if $oView->fcpoPaymentActive('fcpoklarna_installments')}]
                <option value="fcpoklarna_installments" [{if $sPaymentID == 'fcpoklarna_installments'}]selected[{/if}]>Klarna Slice it</option>
            [{/if}]
            [{if $oView->fcpoPaymentActive('fcpoklarna_directdebit')}]
                <option value="fcpoklarna_directdebit" [{if $sPaymentID == 'fcpoklarna_directdebit'}]selected[{/if}]>Klarna Pay now</option>
            [{/if}]
            [{if $oView->fcpoPaymentActive('fcpoklarna_invoice')}]
                <option value="fcpoklarna_invoice" [{if $sPaymentID == 'fcpoklarna_invoice'}]selected[{/if}]>Klarna Pay later</option>
            [{/if}]
        </select>
        <br>

        <input id="fcpo_klarna_combined_agreed" type="checkbox" name="dynvalue[fcpo_klarna_combined_agreed]" value="agreed">
        <label for="fcpo_klarna_combined_agreed">[{oxmultilang ident="FCPO_KLARNA_COMBINED_DATA_AGREEMENT"}]</label>

        <div id="klarna_combined_js_inject"></div>

        <div id="klarna_widget_combined_container"></div>

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