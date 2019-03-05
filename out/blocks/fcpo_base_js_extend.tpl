[{$smarty.block.parent}]
[{capture assign="sPayoneBaseUrl"}]
    var payoneAjaxControllerUrl = '[{$oViewConf->fcpoGetAjaxControllerUrl()}]';
[{/capture}]
[{oxscript add=$sPayoneBaseUrl}]
[{oxscript include=$oViewConf->fcpoGetModuleJsPath('fcPayOne.js')}]