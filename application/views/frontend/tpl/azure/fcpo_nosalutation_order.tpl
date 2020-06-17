[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
[{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_nosalutation_address.tpl'}]

<div id="orderAddress">
    <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
        <div class="hidden">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="[{$oView->fcpoGetEditAddressTargetController()}]">
            <input type="hidden" name="fnc" value="[{$oView->fcpoGetEditAddressTargetAction()}]">
        </div>
        <h3 class="section">
            <strong>[{oxmultilang ident="BILLING_ADDRESS"}]</strong>
            <button type="submit" class="submitButton largeButton">
                [{oxmultilang ident="EDIT"}]
            </button>
        </h3>
        [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
    </form>

    <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
        <div class="hidden">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="[{$oView->fcpoGetEditAddressTargetController()}]">
            <input type="hidden" name="fnc" value="[{$oView->fcpoGetEditAddressTargetAction()}]">
        </div>
        <h3 class="section">
            <strong>[{oxmultilang ident="SHIPPING_ADDRESS"}]</strong>
            <button type="submit" class="submitButton largeButton">
                [{oxmultilang ident="EDIT"}]
            </button>
        </h3>
        [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath) delivadr=$oDelAdress}]
    </form>

    [{if $oView->getOrderRemark()}]
        <div class="orderRemarks">
            <h3 class="section">
                <strong>[{oxmultilang ident="WHAT_I_WANTED_TO_SAY"}]</strong>
            </h3>
            [{$oView->getOrderRemark()|@nl2br}]
        </div>
    [{/if}]
</div>
