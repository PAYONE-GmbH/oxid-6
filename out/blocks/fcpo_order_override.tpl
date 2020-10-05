[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]

[{if $oViewConf->fcpoAmazonLoginSessionActive()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]

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
                                orderForm.submit();
                            }
                        })
                }
            });
        });
    </script>
    <script src="https://x.klarnacdn.net/kp/lib/v1/api.js" async></script>
    [{/if}]
[{else}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_nosalutation_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]