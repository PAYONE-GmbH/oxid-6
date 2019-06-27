[{$smarty.block.parent}]
[{assign var="payment" value=$oView->getPayment()}]
[{assign var="sMandateText" value=$payment->fcpoGetMandateText()}]
[{if $payment && method_exists($payment, 'fcpoGetMandateText') && $payment->fcpoGetMandateText()}]
    [{oxscript include=$oViewConf->fcpoGetModuleJsPath('fcPayOne.js')}]
    [{oxscript include=$oViewConf->fcpoGetModuleCssPath('fcpayone.css')}]
    <div id="fcpoSEPAMandate">
        <h3 class="section">
            <strong>SEPA-Lastschrift</strong>
        </h3>
        [{oxmultilang ident="FCPO_ORDER_MANDATE_INFOTEXT"}]
        <div class="fcpoSEPAMandate">
            [{$sMandateText}]
        </div>
        
        <div class="fcpoSEPAMandateCheckbox">
            <label style="float:left; padding-right:10px;" for="mandate_status" class="control-label">[{oxmultilang ident="FCPO_ORDER_MANDATE_CHECKBOX"}]</label>
            <input type="checkbox" onclick="fcpoHandleMandateCheckbox(this);">
            <div class="clear"></div>
        </div>
    </div>
[{/if}]