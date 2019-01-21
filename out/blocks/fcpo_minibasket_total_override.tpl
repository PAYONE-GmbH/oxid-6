[{$smarty.block.parent}]

[{assign var='sAmazonButtonId' value=$_prefix|cat:"LoginWithAmazonMiniBasket"}]

[{if $oViewConf->fcpoCanDisplayAmazonPayButton()}]
    <p class="functions clear text-right">
        [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
        [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpayone_amazon_paybutton.tpl'}]
        [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)
        sAmazonButtonId=$sAmazonButtonId
        sAmazonButtonClass="payone_basket_amazon_btn_flow_minibasket"
        }]
    </p>
[{/if}]

