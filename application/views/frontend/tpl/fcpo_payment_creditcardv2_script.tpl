<script src="https://sdk.tokenization.secure.payone.com/1.2.1/hosted-tokenization-sdk.js" integrity="sha384-oga+IGWvy3VpUUrebY+BnLYvsNZRsB3NUCMSa+j3CfA9ePHUZ++8/SVyim9F7Jm3" crossorigin="anonymous"></script>

<script type="text/javascript">
    let oHostedTokenizationConfig = JSON.parse('[{$oView->fcpoGetHostedTokenizationConfig()}]');
    console.log(oHostedTokenizationConfig);

    document.addEventListener('DOMContentLoaded', function() {
        if (window.HostedTokenizationSdk) {
            console.log('HTP-SDK initialized successfully');
            async function loadSDK() {
                try {
                    console.log('HTP-SDK loaded successfully');
                    await window.HostedTokenizationSdk.init();
                    window.HostedTokenizationSdk.getPaymentPage(oHostedTokenizationConfig, fcpoCCV2HostedInitCallback);
                } catch (error) {
                    console.error('Error initializing HTP-SDK:', error);
                }
            }
            loadSDK();
        } else {
            console.log("HTP-SDK failed to load");
        }
    });
</script>