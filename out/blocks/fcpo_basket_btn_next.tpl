[{$smarty.block.parent}]
[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]

[{if $oView->fcpoGetPayPalExpressPic()}]
    <form autocomplete="off" action="[{$oViewConf->getSslSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="basket">
        <input type="hidden" name="fnc" value="fcpoUsePayPalExpress">
        <input alt="Paypal express picture" type="image" src="[{$oView->fcpoGetPayPalExpressPic()}]" style="float: right;margin-right:10px;">
    </form>
[{/if}]

[{if $oViewConf->fcpoCanDisplayPayPalExpressV2Button()}]
    [{oxstyle include=$oViewConf->fcpoGetModuleCssPath('fcpopaypalexpress.css')}]
    [{oxid_include_dynamic file="fcpo_paypal_express_v2.tpl" type="payone" position="BasketTop" layout="horizontal"}]
[{/if}]

[{if $oViewConf->fcpoCanDisplayAmazonPayButton()}]
    [{assign var="sFcPoTemplatePathAmazon" value=$sFcPoTemplatePath|cat:'/fcpayone_amazon_paybutton.tpl'}]
    [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePathAmazon)
        sAmazonButtonId='LoginWithAmazonButtonUp'
        sAmazonButtonClass='payone_basket_amazon_btn_flow'
    }]
[{/if}]
