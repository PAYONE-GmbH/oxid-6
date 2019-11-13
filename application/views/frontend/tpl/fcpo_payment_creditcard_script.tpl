[{foreach from=$oxcmp_lang item=_lng}]
    [{if $_lng->selected}]
        [{assign var="activeLng" value=$_lng->abbr}]
    [{/if}]
[{/foreach}]

<script>
    var request, config;
    config = {
        fields: {
            cardpan: {
                selector: "cardpan", // put name of your div-container here
                type: "[{$oView->getConfigParam('sFCPOCCNumberType')}]", // text (default), password, tel
                size: "[{$oView->getConfigParam('sFCPOCCNumberCount')}]",
                maxlength: "[{$oView->getConfigParam('sFCPOCCNumberMax')}]",
                [{if $oView->getConfigParam('sFCPOCCNumberStyle') == "custom"}]
                    style: "[{$oView->getConfigParam('sFCPOCCNumberCSS')}]",
                [{/if}]
                [{if $oView->getConfigParam('sFCPOCCNumberIframe') == "custom"}]
                    iframe: {
                        width: "[{$oView->getConfigParam('sFCPOCCNumberWidth')}]",
                        height: "[{$oView->getConfigParam('sFCPOCCNumberHeight')}]"
                    }
                [{/if}]
            },
            [{if $oView->getConfigParam('blFCPOCCUseCvc')}]
                cardcvc2: {
                    selector: "cardcvc2", // put name of your div-container here
                    length: {"A": 4, "V": 3, "M": 3, "J": 3, "P": 3},
                    type: "[{$oView->getConfigParam('sFCPOCCCVCType')}]", // select(default), text, password, tel
                    size: "[{$oView->getConfigParam('sFCPOCCCVCCount')}]",
                    maxlength: "[{$oView->getConfigParam('sFCPOCCCVCMax')}]",
                    [{if $oView->getConfigParam('sFCPOCCCVCStyle') == "custom"}]
                        style: "[{$oView->getConfigParam('sFCPOCCCVCCSS')}]",
                    [{/if}]
                    [{if $oView->getConfigParam('sFCPOCCCVCIframe') == "custom"}]
                        iframe: {
                            width: "[{$oView->getConfigParam('sFCPOCCCVCWidth')}]",
                            height: "[{$oView->getConfigParam('sFCPOCCCVCHeight')}]"
                        }
                    [{/if}]
                },
            [{/if}]
            cardexpiremonth: {
                selector: "cardexpiremonth", // put name of your div-container here
                type: "select", // select(default), text, password, tel
                size: "[{$oView->getConfigParam('sFCPOCCMonthCount')}]",
                maxlength: "[{$oView->getConfigParam('sFCPOCCMonthMax')}]",
                [{if $oView->getConfigParam('sFCPOCCMonthIframe') == "custom"}]
                    style: "[{$oView->getConfigParam('sFCPOCCMonthCSS')}]",
                [{/if}]
                [{if $oView->getConfigParam('sFCPOCCMonthIframe') == "custom"}]
                    iframe: {
                        width: "[{$oView->getConfigParam('sFCPOCCMonthWidth')}]",
                        height: "[{$oView->getConfigParam('sFCPOCCMonthHeight')}]"
                    }
                [{/if}]
            },
            cardexpireyear: {
                selector: "cardexpireyear", // put name of your div-container here
                type: "select", // select(default), text, password, tel
                size: "[{$oView->getConfigParam('sFCPOCCYearCount')}]",
                maxlength: "[{$oView->getConfigParam('sFCPOCCYearMax')}]",
                [{if $oView->getConfigParam('sFCPOCCYearIframe') == "custom"}]
                    style: "[{$oView->getConfigParam('sFCPOCCYearCSS')}]",
                [{/if}]
                [{if $oView->getConfigParam('sFCPOCCYearIframe') == "custom"}]
                    iframe: {
                        width: "[{$oView->getConfigParam('sFCPOCCYearWidth')}]",
                        height: "[{$oView->getConfigParam('sFCPOCCYearHeight')}]"
                    }
                [{/if}]
            }
        },
        defaultStyle: {
            input: "[{$oView->getConfigParam('sFCPOCCStandardInput')}]",
                select: "[{$oView->getConfigParam('sFCPOCCStandardOutput')}]",
                iframe: {
                    width: "[{$oView->getConfigParam('sFCPOCCIframeWidth')}]",
                    height: "[{$oView->getConfigParam('sFCPOCCIframeHeight')}]"
                }
        },
        [{if $oView->getConfigParam('blFCPOCCErrorsActive')}]
            error: "errorOutput", // area to display error-messages (optional)
            language: Payone.ClientApi.Language.en
        [{/if}]
    };
    [{* use the translation available for the current shop language, if available *}]
    if(Payone.ClientApi.Language.hasOwnProperty('[{$activeLng}]')) {
        config.language = Payone.ClientApi.Language['[{$activeLng}]'];
    }
    [{capture name="fcpoCCIframes"}]
        [{foreach from=$oViewConf->fcpoGetIframeMappings() item='oMapping'}]
            [{assign var='sLangId' value=$oMapping->sLangId}]
            [{assign var='sLangAbbr' value=$oViewConf->fcpoGetLangAbbrById($sLangId)}]
            Payone.ClientApi.Language.[{$sLangAbbr}].[{$oMapping->sErrorCode}] = '[{$oMapping->sMappedMessage}]';
        [{/foreach}]
        var iframes = fcInitCCIframes();
    [{/capture}]
    [{oxscript add=$smarty.capture.fcpoCCIframes}]
</script>
