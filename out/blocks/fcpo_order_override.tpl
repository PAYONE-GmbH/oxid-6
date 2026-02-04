[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]

[{if $oViewConf->fcpoAmazonLoginSessionActive()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]

<script type="text/javascript">
    function fcpoDisableAndSubmit(e, orderForm) {
        var submitButton = e.submitter;
        submitButton.disabled = true;
        orderForm.submit();
    }
</script>

[{if $oViewConf->fcpoUserHasSalutation()}]
    [{$smarty.block.parent}]
    [{if $oViewConf->fcpoIsKlarnaPaynow()}]
    <script type="text/javascript">
        window.addEventListener("load", function(){
            var orderForm = document.getElementById('orderConfirmAgbBottom');
            var klarna_client_token = '[{$oViewConf->fcpoGetClientToken()}]';
            var klarna_cancel_url = '[{$oViewConf->fcpoGetKlarnaCancelUrl()}]';
            // extend orer form with hidden field in order to submit the klarna auth token
            var input = document.createElement("input");
            input.type = "hidden";
            input.id = 'fcpo_klarna_auth_token'
            input.name = "dynvalue[klarna_authorization_token]";
            input.value= '[{$oViewConf->fcpoGetKlarnaAuthToken()}]';
            orderForm.appendChild(input);
            orderForm.addEventListener('submit', function (e) {
                var authToken = document.getElementById('fcpo_klarna_auth_token').value;
                if (typeof(authToken) === 'undefined' || authToken === '') {
                    e.preventDefault();
                    // obtain auth token from Klarna
                    Klarna.Payments.init({
                        client_token: klarna_client_token
                    });
                    Klarna.Payments.finalize({
                            payment_method_category: 'pay_now'
                        },{},
                        function(res) {
                            document.getElementById('fcpo_klarna_auth_token').value = res.authorization_token;
                            if (res.show_form === true && res.approved !== true && typeof(res.error) === 'undefined' ) {
                                // user canceled, so redirect back to payment and show error
                                window.location.replace(klarna_cancel_url);
                            } else if (res.show_form === false) {
                                window.location.replace(klarna_cancel_url);
                            } else {
                                fcpoDisableAndSubmit(e, orderForm);
                            }
                        })
                }
            });
        });
    </script>
    <script src="https://x.klarnacdn.net/kp/lib/v1/api.js" async></script>
    [{else}]
        [{if !$oViewConf->fcpoIsApplePay()}]
        <script type="text/javascript">
            window.addEventListener("load", function(){
                var orderForm = document.getElementById('orderConfirmAgbBottom');
                orderForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    fcpoDisableAndSubmit(e, orderForm);
                });
            });
        </script>
        [{/if}]
    [{/if}]
[{else}]
    [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()|cat:'/fcpo_nosalutation_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
    [{if !$oViewConf->fcpoIsApplePay()}]
    <script type="text/javascript">
        window.addEventListener("load", function(){
            var orderForm = document.getElementById('orderConfirmAgbBottom');
            orderForm.addEventListener('submit', function (e) {
                e.preventDefault();
                fcpoDisableAndSubmit(e, orderForm);
            });
        });
    </script>
    [{/if}]
[{/if}]
    [{if $oViewConf->fcpoIsGooglePay()}]
    <script async
            src="https://pay.google.com/gp/p/js/pay.js"
            onload="onGooglePayLoaded()">
    </script>
    <script>
            const baseRequest = {
                apiVersion: 2,
                apiVersionMinor: 0
            };

            // const allowedCardNetworks = ["MASTERCARD", "VISA"];
            const allowedCardNetworks = [{$oViewConf->fcpoGooglePayGetSupportedNetworks()}];

            // const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];
            const allowedCardAuthMethods = [{$oViewConf->fcpoGooglePayGetAllowedCardAuthMethods()}];

            const tokenizationSpecification = {
                type: 'PAYMENT_GATEWAY',
                parameters: {
                    'gateway': 'payonegmbh',
                    'gatewayMerchantId': '[{$oViewConf->fcpoGooglePayGetMerchantId()}]'
                }
            };

            const baseCardPaymentMethod = {
                type: 'CARD',
                parameters: {
                    allowedAuthMethods: allowedCardAuthMethods,
                    allowedCardNetworks: allowedCardNetworks,
                    allowPrepaidCards: [{$oViewConf->fcpoGooglePayGetAllowPrepaidCards()}],
                    allowCreditCards: [{$oViewConf->fcpoGooglePayGetAllowCreditCards()}]
                }
            };

            const cardPaymentMethod = Object.assign(
                    {},
                baseCardPaymentMethod,
                {
                    tokenizationSpecification: tokenizationSpecification
                }
            );

            let paymentsClient = null;

            function getGoogleIsReadyToPayRequest() {
                return Object.assign(
                        {},
                    baseRequest,
                    {
                        allowedPaymentMethods: [baseCardPaymentMethod]
                    }
                );
            }

            function getGooglePaymentDataRequest() {
                const paymentDataRequest = Object.assign({}, baseRequest);
                paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
                paymentDataRequest.transactionInfo = getGoogleTransactionInfo();
                paymentDataRequest.merchantInfo = {
                    merchantId: '[{$oViewConf->fcpoGooglePayGetGoogleMerchantId()}]',
                    merchantName: '[{$oViewConf->fcpoGooglePayGetMerchantName()}]',
                };
                return paymentDataRequest;
            }

            function getGooglePaymentsClient() {
                if ( paymentsClient === null ) {
                    paymentsClient = new google.payments.api.PaymentsClient({ environment: '[{$oViewConf->fcpoGooglePayGetMode()}]' });
                }
                return paymentsClient;
            }

            function onGooglePayLoaded() {
                const paymentsClient = getGooglePaymentsClient();
                paymentsClient.isReadyToPay(getGoogleIsReadyToPayRequest())
                    .then(function(response) {
                        if (response.result) {
                            addGooglePayButton();
                        }
                    })
                    .catch(function(err) {
                        // show error in developer console for debugging
                        console.error(err);
                    });
            }
            function addGooglePayButton() {
                const paymentsClient = getGooglePaymentsClient();
                const button =
                    paymentsClient.createButton({
                            onClick: onGooglePaymentButtonClicked,
                            buttonColor: '[{$oViewConf->fcpoGooglePayGetButtonColor()}]',
                            buttonType: '[{$oViewConf->fcpoGooglePayGetButtonType()}]',
                            buttonLocale: '[{$oViewConf->fcpoGooglePayGetButtonLocale()}]',
                        }
                    );
                document.getElementById('orderConfirmAgbBottom').innerHTML = "<div id='payonegooglepaycontainer' class='pull-right'></div>";
                document.getElementById('payonegooglepaycontainer').appendChild(button);
            }

            function getGoogleTransactionInfo() {
                return {
                    countryCode: '[{$oViewConf->fcpoGooglePayGetButtonLocale()}]',
                    currencyCode: '[{$oViewConf->fcpoGooglePayGetCurrency()}]',
                    totalPriceStatus: 'FINAL',
                    // set to cart total
                    totalPrice: '[{$oViewConf->fcpoGooglePayGetBasketSum()}]',
                    totalPriceLabel: 'Gesamtsumme',
                    [{if $oViewConf->fcpoGooglePayGetShowDisplayItems()}]
                        displayItems: [{$oViewConf->getGooglePayDisplayItems()}]
                    [{/if}]
                };
            }

            function prefetchGooglePaymentData() {
                const paymentDataRequest = getGooglePaymentDataRequest();
                // transactionInfo must be set but does not affect cache
                paymentDataRequest.transactionInfo = {
                    totalPriceStatus: 'NOT_CURRENTLY_KNOWN',
                    currencyCode: '[{$oViewConf->fcpoGooglePayGetCurrency()}]'
                };
                const paymentsClient = getGooglePaymentsClient();
                paymentsClient.prefetchPaymentData(paymentDataRequest);
            }

            function onGooglePaymentButtonClicked() {
                const paymentDataRequest = getGooglePaymentDataRequest();
                paymentDataRequest.transactionInfo = getGoogleTransactionInfo();

                const paymentsClient = getGooglePaymentsClient();
                paymentsClient.loadPaymentData(paymentDataRequest)
                    .then(function(paymentData) {
                        // handle the response
                        processPayment(paymentData);
                    })
                    .catch(function(err) {
                        // show error in developer console for debugging
                        console.error(err);
                    });
            }

            function processPayment(paymentData) {
                // show returned data in developer console for debugging
                console.log(paymentData);
                // @todo pass payment token to your gateway to process payment
                paymentToken = paymentData.paymentMethodData.tokenizationData.token;
                console.log('PaymentToken:');
                console.log(btoa(paymentToken));
                var url = "[{$oViewConf->fcpoGooglePayGetRedirectUrl()}]" + "&stoken=[{$oViewConf->getSessionChallengeToken()}]&sDeliveryAddressMD5=[{$oView->getDeliveryAddressMD5()}]" + '&fnc=execute&googlepaytoken=' + btoa(paymentToken);
                // console.log(url);
                window.location = url;
            }
    </script>
    [{/if}]