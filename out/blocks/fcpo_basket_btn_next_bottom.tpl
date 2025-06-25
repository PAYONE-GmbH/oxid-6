[{$smarty.block.parent}]
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
    [{oxid_include_dynamic file="fcpo_paypal_express_v2.tpl" type="payone" position="BasketBottom" layout="horizontal"}]
[{/if}]

[{if $oViewConf->fcpoCanDisplayAmazonPayButton()}]
    [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpayone_amazon_paybutton.tpl'}]
    [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)
        sAmazonButtonId='LoginWithAmazonButtonBottom'
        sAmazonButtonClass='payone_basket_amazon_btn_flow'
    }]
[{/if}]

