[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]

[{if $oViewConf->fcpoAmazonLoginSessionActive()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]

[{if $oViewConf->fcpoUserHasSalutation()}]
    [{$smarty.block.parent}]
[{else}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_nosalutation_order.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]