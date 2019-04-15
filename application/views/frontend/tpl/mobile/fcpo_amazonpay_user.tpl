<style type="text/css">
    #addressBookWidgetDiv {
        min-width: 300px;
        width: 100%;
        max-width: 900px;
        min-height: 228px;
        height: 240px;
        max-height: 400px;
        [{if $oViewConf->fcpoGetAmazonPayAddressWidgetIsReadOnly()}]
            displayMode: "Read";
        [{/if}]
    }
</style>

<form class="form-horizontal" action="[{$oViewConf->getSslSelfLink()}]" name="order" method="post" novalidate="novalidate">
    [{block name="user_checkout_change_form"}]
        [{assign var="aErrors" value=$oView->getFieldValidationErrors()}]
        [{$oViewConf->getHiddenSid()}]
        [{$oViewConf->getNavFormParams()}]
        <input type="hidden" name="cl" value="payment">
        <input type="hidden" name="option" value="[{$oView->getLoginOption()}]">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="lgn_cook" value="0">
        <input type="hidden" name="blshowshipaddress" value="1">

        <ul class="form">
            <li>
                <input type="button" id="userNextStepTop" name="userform" class="btn" value="[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]" />
            </li>
        </ul>

        <h3>[{oxmultilang ident="FCPO_AMAZON_SELECT_ADDRESS"}]</h3>
        <ul id="addressForm" class="form">
            <li>
                <div id="addressBookWidgetDiv"></div>
            </li>
        </ul>

        <script>
            window.onAmazonLoginReady = function() {amazon.Login.setClientId('[{$oViewConf->fcpoGetAmazonPayClientId()}]'); };
            window.onAmazonPaymentsReady = function() {
                new OffAmazonPayments.Widgets.AddressBook({
                    sellerId: '[{$oViewConf->fcpoGetAmazonPaySellerId()}]',
                    scope: 'profile payments:widget payments:shipping_address payments:billing_address',
                    onOrderReferenceCreate: function(orderReference) {
                        orderReferenceId = orderReference.getAmazonOrderReferenceId();
                    },
                    onAddressSelect: function(orderReference) {
                        var formParams = "{";
                                formParams += '"fcpoAmazonReferenceId":"' + orderReferenceId + '"';
                                formParams += "}";
                        $.ajax({
                            url: '[{$oViewConf->getBaseDir()}]modules/fc/fcpayone/application/models/fcpayone_ajax.php',
                            method: 'POST',
                            type: 'POST',
                            dataType: 'text',
                            data: { paymentid: "fcpoamazonpay", action: "get_amazon_reference_details", params: formParams },
                            success: function(Response) {
                            }
                        });
                    },
                    design: {
                        designMode: 'responsive'
                    },
                    onReady: function(orderReference) {
                    },
                    onError: function(error) {
                        console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                    }
                }).bind("addressBookWidgetDiv");
            };
        </script>
        <script async="async" src='[{$oViewConf->fcpoGetAmazonWidgetsUrl()}]'></script>

        <ul class="form">
            <li><input type="submit" id="userNextStepBottom" class="btn" name="userform" value="[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]" /></li>
            <li><input type="button" class="btn previous" value="[{oxmultilang ident="PREVIOUS_STEP"}]" onclick="window.open('[{oxgetseourl ident=$oViewConf->getBasketLink()}]', '_self');" /></li>
        </ul>
    [{/block}]
</form>
