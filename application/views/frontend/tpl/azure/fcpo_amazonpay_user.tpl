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

        [{block name="user_checkout_change_next_step_top"}]
            <div class="lineBox clear">
                <a href="[{oxgetseourl ident=$oViewConf->getBasketLink()}]" class="prevStep submitButton largeButton" id="userBackStepTop">[{oxmultilang ident="PREVIOUS_STEP"}]</a>
                <button disabled id="userNextStepTop" class="submitButton largeButton nextStep" name="userform" type="submit">[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]</button>
            </div>
        [{/block}]

        <div class="checkoutCollumns clear">
            <div class="lineBox clear">
                <h3 class="blockHead">
                    [{oxmultilang ident="FCPO_AMAZON_SELECT_ADDRESS"}]
                </h3>
                <ul>
                    <div id="addressBookWidgetDiv"></div>
                </ul>
                <ul>
                    <a href="index.php?cl=basket&fcpoamzaction=logoff">[{oxmultilang ident="FCPO_AMAZON_LOGOFF"}]</a>
                </ul>
            </div>
        </div>

        <script>
            function fcpoGetCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for(var i = 0; i <ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            function fcpoGetURLParameter(name, source) {
                return decodeURIComponent((new RegExp('[?|&|#]' + name + '=' +
                    '([^&]+?)(&|#|;|$)').exec(source) || [,""])[1].replace(/\+/g,
                    '%20')) || null;
            }

            var sFcpoAccessToken = fcpoGetCookie('amazon_Login_accessToken');

            if (typeof sFcpoAccessToken === 'string' && sFcpoAccessToken === '') {
                var sFcpoAccessToken = fcpoGetURLParameter("access_token", location.hash);
                if (typeof sFcpoAccessToken === 'string' && sFcpoAccessToken.match(/^Atza/)) {
                    document.cookie = "amazon_Login_accessToken=" + sFcpoAccessToken +
                        ";secure";
                }
            }


            window.onAmazonLoginReady = function() {
                amazon.Login.setClientId('[{$oViewConf->fcpoGetAmazonPayClientId()}]');
                amazon.Login.setUseCookie(true);
            };
            window.onAmazonPaymentsReady = function() {
                new OffAmazonPayments.Widgets.AddressBook({
                    sellerId: '[{$oViewConf->fcpoGetAmazonPaySellerId()}]',
                    scope: 'profile payments:widget payments:shipping_address payments:billing_address',
                    onOrderReferenceCreate: function(orderReference) {
                        orderReferenceId = orderReference.getAmazonOrderReferenceId();
                    },
                    onAddressSelect: function(orderReference) {
                        var userNextStepButtons = $('[id^="userNextStep"]');
                        userNextStepButtons.each(function () {
                            this.setAttribute('disabled', true);
                        });

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
                                userNextStepButtons.each(function () {
                                    this.removeAttribute('disabled');
                                });
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

        <div class="lineBox clear">
            <a href="[{oxgetseourl ident=$oViewConf->getBasketLink()}]" class="prevStep submitButton largeButton" id="userBackStepTop">[{oxmultilang ident="PREVIOUS_STEP"}]</a>
            <button disabled id="userNextStepTop" class="submitButton largeButton nextStep" name="userform" type="submit">[{oxmultilang ident="CONTINUE_TO_NEXT_STEP"}]</button>
        </div>
    [{/block}]
</form>
