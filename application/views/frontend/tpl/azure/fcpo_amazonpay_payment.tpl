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

    <div class="checkoutCollumns clear">
        <div class="lineBox clear">
            <h3 class="blockHead">
                [{oxmultilang ident="FCPO_AMAZON_SELECT_PAYMENT"}]
            </h3>
            <ul>
                <div id="walletWidgetDiv"></div>
            </ul>
            <ul>
                <a href="index.php?cl=basket&fcpoamzaction=logoff">[{oxmultilang ident="FCPO_AMAZON_LOGOFF"}]</a>
            </ul>
        </div>
    </div>

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
                onPaymentSelect: function(orderReference) {
                    console.log('triggered onPaymentSelect');
                    $("[id^=paymentNextStep]").each(function () {
                        $(this).attr("disabled", true);
                    });

                    var formParams = '{"fcpoAmazonReferenceId":"[{$oViewConf->fcpoGetAmazonPayReferenceId()}]"}';
                    $.ajax({
                        url: '[{$oViewConf->getBaseDir()}]modules/fc/fcpayone/application/models/fcpayone_ajax.php',
                        method: 'POST',
                        type: 'POST',
                        dataType: 'text',
                        data: { paymentid: "fcpoamazonpay", action: "get_amazon_reference_details", params: formParams },
                        success: function(Response) {
                            $("[id^=paymentNextStep]").each(function () {
                                $(this).attr("disabled", false);
                            });
                        }
                    });
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

    <div class="lineBox clear">
        <a href="[{oxgetseourl ident=$oViewConf->getOrderLink()}]" class="prevStep submitButton largeButton" id="paymentBackStepBottom">[{ oxmultilang ident="PREVIOUS_STEP" }]</a>
        <button type="submit" disabled name="userform" class="submitButton nextStep largeButton" id="paymentNextStepBottom">[{ oxmultilang ident="CONTINUE_TO_NEXT_STEP" }]</button>
    </div>
</form>
