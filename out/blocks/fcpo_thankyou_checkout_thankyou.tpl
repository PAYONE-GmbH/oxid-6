[{if $oView->fcpoGetBarzahlenHtml()}]
    [{$oView->fcpoGetBarzahlenHtml()}]
[{else}]
    [{$smarty.block.parent}]
    [{if $oView->fcpoIsAppointedError()}]
        <br><br>
        [{oxmultilang ident="FCPO_THANKYOU_APPOINTED_ERROR"}]
    [{/if}]
    [{assign var="sMandatePdfUrl" value=$oView->fcpoGetMandatePdfUrl()}]
    [{if $sMandatePdfUrl}]
        <br><br>
        <a href="[{$sMandatePdfUrl}]" class="link" target="_blank">[{oxmultilang ident="FCPO_THANKYOU_PDF_LINK"}]</a>
    [{/if}]
    [{if $oView->fcpoIsAmazonOrder()}]
        <script async="async" src='[{$oViewConf->fcpoGetAmazonWidgetsUrl()}]'></script>
        <script>
            window.onAmazonLoginReady = function () {
                amazon.Login.logout();
            };
        </script>
        [{if $oViewConf->fcpoIsAmazonAsyncMode()}]
            <br><br>
            <div>
                [{oxmultilang ident="FCPO_AMAZON_THANKYOU_MESSAGE"}]
            </div>
        [{/if}]
    [{/if}]
    [{if $oView->fcpoShowClearingData()}]
        <h3>
            [{oxmultilang ident="FCPO_EMAIL_BANK_DETAILS"}]
        </h3>
        <div>
            [{oxmultilang ident="FCPO_BANKACCOUNTHOLDER"}] [{$order->getFcpoBankaccountholder()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_BANK"}] [{$order->getFcpoBankname()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ROUTINGNUMBER"}] [{$order->getFcpoBankcode()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ACCOUNTNUMBER"}] [{$order->getFcpoBanknumber()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_BIC"}] [{$order->getFcpoBiccode()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_IBAN"}] [{$order->getFcpoIbannumber()}] <br>
            [{oxmultilang ident="FCPO_EMAIL_USAGE"}]: [{$order->oxorder__fcpotxid->value}]
        </div>
    [{/if}]
[{/if}]