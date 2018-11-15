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
[{/if}]