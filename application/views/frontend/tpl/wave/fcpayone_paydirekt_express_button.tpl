<form autocomplete="off" action="[{$oViewConf->getSslSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="basket">
    <input type="hidden" name="fnc" value="fcpoUsePaydirektExpress">
    <input type="image" src="[{$oView->fcpoGetPaydirektExpressPic()}]" style="float: right;margin-right:10px;">
</form>