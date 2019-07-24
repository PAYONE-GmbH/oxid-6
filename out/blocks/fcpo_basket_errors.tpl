[{assign var="iPayError" value=$oViewConf->fcpoGetPaymentError()}]
[{if $iPayError == -20}]
    [{if method_exists($oViewConf, 'getActiveTheme') && $oViewConf->getActiveTheme() == 'flow'}]
        <div class="alert alert-danger">[{$oViewConf->fcpoGetPaymentErrorText()}]</div>
    [{else}]
        <div class="status error">[{$oViewConf->fcpoGetPaymentErrorText()}]</div>
    [{/if}]
[{/if}]
[{$smarty.block.parent}]