[{assign var="buttonId" value='fcpoPayPalExpressV2'|cat:$_payone_position}]

<div id="[{$buttonId}]"></div>

<script>
    //require(['Payone_Core/js/action/startpaypalexpress'], function(startpaypalexpress) {
        var payonePayPalAttempts = 0;

        function loadPayPalScript() {
            if (window.paypalJsLoaded === undefined || window.paypalJsLoaded !== true) {
                var elemScript = document.createElement('script');
                elemScript.type = "text/javascript";
                elemScript.src = "[{$oViewConf->fcpoGetPayPalExpressV2JavascriptUrl()}]";
                document.body.appendChild(elemScript);

                window.paypalJsLoaded = true;
            }
        }

        function triggerPayPalButtonRender(buttonId) {
            if (payonePayPalAttempts > 10) {
                return; // abort
            }

            if (typeof paypal != 'object') {
                loadPayPalScript();
                setTimeout(function() {
                    window.requestAnimationFrame(function() {
                        triggerPayPalButtonRender(buttonId)
                    });
                }, 250);
            } else {
                initPayPalButton(buttonId);
            }
            payonePayPalAttempts++;
        }

        function initPayPalButton(buttonId) {
            if (document.getElementById(buttonId).childNodes.length > 0) { // button already created, no need to init another button
                return;
            }

            paypal.Buttons({
                style: {
                    layout: '[{$_payone_layout}]',
                    label:  'paypal',
                    height: 34,
                    color:  '[{$oViewConf->fcpoGetPayPalExpressButtonColor()}]',
                    shape:  '[{$oViewConf->fcpoGetPayPalExpressButtonShape()}]'
                },
                createOrder: function(data, actions) {
                    return fcpoStartPayPalExpress().then(function (res) {
                        var resJson = JSON.parse(res);
                        if (resJson.success === true) {
                            return resJson.order_id;
                        }
                        return false;
                    }).fail(function (res) {
                        alert("An error occured.");
                        return false;
                    });
                },
                onApprove: function(data, actions) {
                    // redirect to your serverside success handling script/page
                    window.location = '[{$oViewConf->fcpoGetPayPalExpressSuccessUrl()}]';
                },
                onCancel: function(data, actions) {
                    console.log("Customer cancelled the PayPal Checkout Flow");
                    // add your actions on cancellation
                },
                onError: function() {
                    console.log("An Error occurred as part of the PayPal JS SDK");
                    // add your actions if error occurs
                }
            }).render('#' + buttonId);
        }

        triggerPayPalButtonRender('[{$buttonId}]');
    //});
</script>