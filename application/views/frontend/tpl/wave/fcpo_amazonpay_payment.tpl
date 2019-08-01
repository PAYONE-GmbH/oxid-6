<style type="text/css">
    #walletWidgetDiv {
        min-width: 300px;
        width: 100%;
        max-width: 900px;
        min-height: 228px;
        height: 240px;
        max-height: 400px;
    }
    #readOnlyWalletWidgetDiv {
        min-width: 266px;
        width: 100%;
        max-width: 900px;
        min-height: 145px;
        height: 165px;
        max-height: 180px;
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

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">[{oxmultilang ident="FCPO_AMAZON_SELECT_PAYMENT"}]</h3></div>
        <div class="panel-body">
            <div id="walletWidgetDiv"></div>
            <hr>
            <a href="index.php?cl=basket&fcpoamzaction=logoff">[{oxmultilang ident="FCPO_AMAZON_LOGOFF"}]</a>
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
                        url: "[{$oViewConf->getBaseDir()}]modules/fc/fcpayone/application/models/fcpayone_ajax.php",
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

    [{block name="checkout_payment_nextstep"}]
        <div class="well well-sm">
            <a href="[{oxgetseourl ident=$oViewConf->getOrderLink()}]" class="btn btn-default pull-left prevStep submitButton largeButton" id="paymentBackStepBottom"><i class="fa fa-caret-left"></i> [{oxmultilang ident="PREVIOUS_STEP"}]</a>
            <button disabled type="submit" name="userform" class="btn btn-primary pull-right submitButton nextStep largeButton" id="paymentNextStepBottom">[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}] <i class="fa fa-caret-right"></i></button>
            <div class="clearfix"></div>
        </div>
    [{/block}]
</form>
