<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var sellerId = '[{$oViewConf->fcpoGetAmazonPaySellerId()}]';
        var Id = '[{$oViewConf->fcpoGetAmazonPayReferenceId()}]'; // use the Order Reference AmazonOrderReferenceId
        var buyNowBtns = document.getElementsByClassName('[{$oViewConf->fcpoGetAmazonBuyNowButtonCssSelector()}]');

        buyNowBtns[0].addEventListener('click', function () {
            OffAmazonPayments.initConfirmationFlow(sellerId, Id, function(confirmationFlow) {
                placeOrder(confirmationFlow);
            });
        });

        function placeOrder(confirmationFlow) {
            console.log('triggered placeOrder');
            var formParams = '{"fcpoAmazonReferenceId":"[{$oViewConf->fcpoGetAmazonPayReferenceId()}]"}';
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
                },
                timeout: "3000", //specify your timeout value (for example, 3000)
            });
            console.log('finished placeOrder');
        }
    });
</script>