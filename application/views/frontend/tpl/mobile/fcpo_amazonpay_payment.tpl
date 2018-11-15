<style type="text/css">
    #walletWidgetDiv {
        min-width: 300px;
        width: 100%;
        max-width: 900px;
        min-height: 228px;
        height: 240px;
        max-height: 400px;
    }
</style>

<form action="[{$oViewConf->getSslSelfLink()}]" class="form-horizontal js-oxValidate payment" id="payment" name="order" method="post" novalidate="novalidate">
    <div class="hidden">
        [{$oViewConf->getHiddenSid()}]
        [{$oViewConf->getNavFormParams()}]
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
        <input type="hidden" name="fnc" value="validateamazonpayment">
        <input type="hidden" name="paymentid" value="fcpoamazonpay">
    </div>

    <div class="payment-row">
        <ul class="form">
            <li><input type="button" id="paymentNextStepTop" name="userform" class="btn" value="[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]" /></li>
        </ul>
    </div>

    <h3>[{oxmultilang ident="FCPO_AMAZON_SELECT_ADDRESS"}]</h3>
    <ul class="form">
        <li>
            <div id="walletWidgetDiv"></div>
        </li>
    </ul>

    <script>
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId('[{$oViewConf->fcpoGetAmazonPayClientId()}]');
            [{if !$oViewConf->fcpoAmazonLoginSessionActive()}]
                amazon.Login.logout();
            [{/if}]
        };
        window.onAmazonPaymentsReady = function() {
            new OffAmazonPayments.Widgets.Wallet({
                sellerId: '[{$oViewConf->fcpoGetAmazonPaySellerId()}]',
                scope: 'profile payments:widget payments:shipping_address payments:billing_address',
                amazonOrderReferenceId: '[{$oViewConf->fcpoGetAmazonPayReferenceId()}]',
                onOrderReferenceCreate: function(orderReference) {
                },
                design: {
                    designMode: 'responsive'
                },
                onError: function(error) {
                }
            }).bind("walletWidgetDiv");
        };
    </script>
    <script async="async" src='[{$oViewConf->fcpoGetAmazonWidgetsUrl()}]'></script>

    [{block name="checkout_payment_nextstep"}]
        <ul class="form">
            [{if $oxcmp_basket->isBelowMinOrderPrice()}]
                <li><b>[{oxmultilang ident="MIN_ORDER_PRICE"}] [{oxprice price=$oxcmp_basket->getMinOrderPrice() currency=$currency}]</b></li>
            [{else}]
                <li><input type="submit" id="paymentNextStepBottom" name="userform" class="btn" value="[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]" /></li>
                <li><input type="button" id="paymentBackStepBottom" class="btn previous" value="[{oxmultilang ident="PREVIOUS_STEP"}]" onclick="window.open('[{oxgetseourl ident=$oViewConf->getOrderLink()}]', '_self');" /></li>
            [{/if}]
        </ul>
    [{/block}]
</form>
