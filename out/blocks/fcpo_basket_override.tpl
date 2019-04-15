[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
[{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_basket_errormessage.tpl'}]
[{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]

[{$smarty.block.parent}]
