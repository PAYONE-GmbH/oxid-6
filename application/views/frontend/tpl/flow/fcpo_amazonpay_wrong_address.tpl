<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">[{oxmultilang ident="FCPO_AMAZON_PROBLEM"}]</h3></div>
    <div class="panel-body">
        <div>
            [{oxmultilang ident="FCPO_AMAZON_NO_SHIPPING_TO_COUNTRY"}]
        </div>
        <hr>
        <a href="index.php?cl=basket&fcpoamzaction=logoff">[{oxmultilang ident="FCPO_AMAZON_LOGOFF"}]</a>
    </div>
</div>

[{block name="checkout_payment_nextstep"}]
    <div class="well well-sm">
        <a href="[{oxgetseourl ident=$oViewConf->getOrderLink()}]" class="btn btn-default pull-left prevStep submitButton largeButton" id="paymentBackStepBottom"><i class="fa fa-caret-left"></i> [{oxmultilang ident="PREVIOUS_STEP"}]</a>
        <div class="clearfix"></div>
    </div>
[{/block}]
