[{assign var='sEnvironment' value=$oViewConf->fcpoGetPayoneSecureEnvironment($sPaymentID)}]
[{assign var='sPaylaPartnerId' value="e7yeryF2of8X"}]
[{assign var='sPartnerMerchantId' value=$oViewConf->fcpoGetMerchantId()}]
[{assign var='sSnippetToken' value=$oViewConf->fcpoGetBNPLDeviceToken($sPaylaPartnerId, $sPartnerMerchantId)}]

<script id="paylaDcs" type="text/javascript" src="https://d.payla.io/dcs/[{$sPaylaPartnerId}]/[{$sPartnerMerchantId}]/dcs.js"></script>
<script>
    var paylaDcsT = paylaDcs.init("[{$sEnvironment}]", "[{$sSnippetToken}]");
</script>

<link id="paylaDcsCss" type="text/css" rel="stylesheet" href="https://d.payla.io/dcs/dcs.css?st=[{$sSnippetToken}]&pi=[{$sPaylaPartnerId}]&psi=[{$sPartnerMerchantId}]&e=[{$sEnvironment}]">
<input type="hidden" name="dynvalue[fcpopl_device_token]" value="[{$sSnippetToken}]">