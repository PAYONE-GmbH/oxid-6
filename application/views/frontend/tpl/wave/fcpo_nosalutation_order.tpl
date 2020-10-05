[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
[{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_nosalutation_address.tpl'}]

<div id="orderAddress" class="row">
    <div class="col-12 col-md-6">
        <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
            <div class="hidden">
                [{$oViewConf->getHiddenSid()}]
                <input type="hidden" name="cl" value="[{$oView->fcpoGetEditAddressTargetController()}]">
                <input type="hidden" name="fnc" value="[{$oView->fcpoGetEditAddressTargetAction()}]">
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        [{oxmultilang ident="BILLING_ADDRESS"}]
                        <button type="submit" class="btn btn-sm btn-warning float-right submitButton Button edit-button" title="[{oxmultilang ident="EDIT"}]">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </h3>
                </div>
                <div class="card-body">
                    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 col-md-6">
        <form action="[{$oViewConf->getSslSelfLink()}]" method="post">
            <div class="hidden">
                [{$oViewConf->getHiddenSid()}]
                <input type="hidden" name="cl" value="[{$oView->fcpoGetEditAddressTargetController()}]">
                <input type="hidden" name="fnc" value="[{$oView->fcpoGetEditAddressTargetAction()}]">
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        [{oxmultilang ident="SHIPPING_ADDRESS"}]
                        <button type="submit" class="btn btn-sm btn-warning float-right submitButton Button edit-button" title="[{oxmultilang ident="EDIT"}]">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </h3>
                </div>
                <div class="card-body">
                    [{assign var="oDelAdress" value=$oView->getDelAddress()}]
                    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath) delivadr=$oDelAdress}]
                </div>
            </div>
        </form>
    </div>
</div>
