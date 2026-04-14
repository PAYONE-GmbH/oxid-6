<script src="https://sdk.tokenization.secure.payone.com/1.5.0/hosted-tokenization-sdk.js" 
        crossorigin="anonymous" 
        integrity="sha384-qV/kvLA3YM9ZkCKfcliuCeR0PkqumnwfDNDF2FwkbZCQTMFJ5kzJc/7dH4E3rT0L"></script>

<script type="text/javascript">
    let oHostedTokenizationConfig = JSON.parse('[{$oView->fcpoGetHostedTokenizationConfig()}]');

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