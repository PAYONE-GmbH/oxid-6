[{if !$order->isPayOnePaymentType() }]
    [{$smarty.block.parent}]
[{else}]
    [{if $payment->oxuserpayments__oxpaymentsid->value == "oxidpayadvance"}]
        <h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
            [{oxmultilang ident="FCPO_EMAIL_BANK_DETAILS"}]
        </h3>
        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            [{oxmultilang ident="FCPO_EMAIL_BANK"}] [{$shop->oxshops__oxbankname->value}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ROUTINGNUMBER"}] [{$shop->oxshops__oxbankcode->value}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ACCOUNTNUMBER"}] [{$shop->oxshops__oxbanknumber->value}]<br>
            [{oxmultilang ident="FCPO_EMAIL_BIC"}] [{$shop->oxshops__oxbiccode->value}]<br>
            [{oxmultilang ident="FCPO_EMAIL_IBAN"}] [{$shop->oxshops__oxibannumber->value}]
        </p>
    [{elseif $order->fcpoShowClearingData()}]
        <h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
            [{oxmultilang ident="FCPO_EMAIL_BANK_DETAILS"}]
        </h3>
        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            [{oxmultilang ident="FCPO_BANKACCOUNTHOLDER"}] [{$order->getFcpoBankaccountholder()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_BANK"}] [{$order->getFcpoBankname()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ROUTINGNUMBER"}] [{$order->getFcpoBankcode()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_ACCOUNTNUMBER"}] [{$order->getFcpoBanknumber()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_BIC"}] [{$order->getFcpoBiccode()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_IBAN"}] [{$order->getFcpoIbannumber()}]<br>
            [{oxmultilang ident="FCPO_EMAIL_USAGE"}]: [{$order->oxorder__fcpotxid->value}]
        </p>
[{elseif $payment->oxuserpayments__oxpaymentsid->value == "fcpopo_bill" || $payment->oxuserpayments__oxpaymentsid->value == "fcpopo_debitnote"}]
        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
            <b>[{oxmultilang ident="FCPO_PAYOLUTION_EMAIL_CLEARING"}]:</b> [{$smarty.session.payolution_clearing}]
        </p>
    [{else}]
        [{$smarty.block.parent}]
    [{/if}]
[{/if}]
