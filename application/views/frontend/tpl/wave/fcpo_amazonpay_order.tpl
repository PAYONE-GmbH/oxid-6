<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var sellerId = '[{$oViewConf->fcpoGetAmazonPaySellerId()}]';
        var Id = '[{$oViewConf->fcpoGetAmazonPayReferenceId()}]'; // use the Order Reference AmazonOrderReferenceId
        var orderForm = document.getElementById('orderConfirmAgbBottom');

        orderForm.addEventListener('submit', function (e) {
            e.preventDefault();
            OffAmazonPayments.initConfirmationFlow(sellerId, Id, function(confirmationFlow) {
                placeOrder(confirmationFlow);
            });
        });

        function placeOrder(confirmationFlow) {
            console.log('triggered placeOrder');
            var formParams = '{"fcpoAmazonReferenceId":"[{$oViewConf->fcpoGetAmazonPayReferenceId()}]","fcpoAmazonStoken":"[{$oViewConf->getSessionChallengeToken()}]","fcpoAmazonDeliveryMD5":"[{$oViewConf->fcpoGetDeliveryMD5()}]"}';
            $.ajax({
                url: '[{$oViewConf->fcpoGetAjaxControllerUrl()}]',
                method: 'POST',
                type: 'POST',
                dataType: 'text',
                data: { paymentid: "fcpoamazonpay", action: "confirm_amazon_pay_order", params: formParams },
                success: function (data) {
                    console.log('success triggering placeOrder');
                    confirmationFlow.success();
                },
                error: function (data) {
                    console.log('error triggering placeOrder');
                    confirmationFlow.error();
                    window.onAmazonLoginReady = function () {
                        amazon.Login.logout();
                    };
                    window.location.href = '[{$oViewConf->fcpoGetAmazonConfirmErrorUrl()}]';
                },
                timeout: "30000", //specify your timeout value (for example, 3000)
            });
        }
    });
</script>