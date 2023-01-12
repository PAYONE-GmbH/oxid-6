[{if $blFCPOAmazonCatchHash}]
    <script>
        function fcpoGetURLParameter(name, source) {
            return decodeURIComponent((new RegExp('[?|&|#]' + name + '=' +
                '([^&]+?)(&|#|;|$)').exec(source) || [,""])[1].replace(/\+/g,
                '%20')) || null;
        }

        var sFcpoAccessToken = fcpoGetURLParameter("access_token", location.hash);
        if (typeof sFcpoAccessToken === 'string' && sFcpoAccessToken.match(/^Atza/)) {
            document.cookie = "amazon_Login_accessToken=" + sFcpoAccessToken +
                ";secure";
            // and do it again
            document.location.href = '[{$oViewConf->fcpoGetAmazonRedirectUrl()}]';
        }
    </script>
[{/if}]

[{if $oView->fcpoGetUserErrorMessage()}]
    [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_user_errormessage.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{/if}]

[{if $oViewConf->fcpoAmazonLoginSessionActive()}]
    [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
    [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_user.tpl'}]
    [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]