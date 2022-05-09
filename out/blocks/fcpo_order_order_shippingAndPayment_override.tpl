[{$smarty.block.parent}]
[{assign var="payment" value=$oView->getPayment()}]
[{if $payment->oxpayments__oxid->value=='fcporp_installment'}]
    [{oxstyle  include=$oViewConf->fcpoGetModuleCssPath('fcpayone.css')}]
    <div class="rp-table-striped" style="width: 100%; margin-left: 0">
        <div>
            <div class="text-center text-uppercase" colspan="2">
                [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_TITLE'}]
            </div>
        </div>

        <div>
            <div class="warning small text-center" colspan="2"><br/>[{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_EXAMPLE'}]</div>
        </div>

        <div class="rp-menue">
            <div colspan="2" class="small text-right">
                <a class="rp-link" id="fcporp_installment_rp-show-installment-plan-details"
                   onclick="fcpoRpChangeDetails('fcporp_installment')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_SHOW'}]
                    <img src="modules/fc/fcpayone/out/img/icon-enlarge.png" class="rp-details-icon"/>
                </a>
                <a class="rp-link" id="fcporp_installment_rp-hide-installment-plan-details"
                   onclick="fcpoRpChangeDetails('fcporp_installment')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_HIDE'}]
                    <img src="modules/fc/fcpayone/out/img/icon-shrink.png" class="rp-details-icon"/>
                </a>
            </div>
        </div>

        <div id="fcporp_installment_rp-installment-plan-details">
            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_amount')"
                     onmouseout="fcpoMouseOut('fcporp_installment_amount')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_LABEL'}]&nbsp;
                    <p id="fcporp_installment_amount" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_basket_amount')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_serviceCharge')"
                     onmouseout="fcpoMouseOut('fcporp_installment_serviceCharge')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_LABEL'}]&nbsp;
                    <p id="fcporp_installment_serviceCharge" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_service_charge')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title"
                     onmouseover="fcpoMouseOver('fcporp_installment_annualPercentageRate')"
                     onmouseout="fcpoMouseOut('fcporp_installment_annualPercentageRate')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_LABEL'}]&nbsp;
                    <p id="fcporp_installment_annualPercentageRate" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_annual_percentage_rate')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_interestRate')"
                     onmouseout="fcpoMouseOut('fcporp_installment_interestRate')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DEBIT_RATE_LABEL'}]&nbsp;
                    <p id="fcporp_installment_interestRate" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DEBIT_RATE_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_interest_rate')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_interestAmount')"
                     onmouseout="fcpoMouseOut('fcporp_installment_interestAmount')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_LABEL'}]&nbsp;
                    <p id="fcporp_installment_interestAmount" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_interest_amount')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div colspan="2"></div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_rate')"
                     onmouseout="fcpoMouseOut('fcporp_installment_rate')">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_number_of_rate')}] [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DURATION_MONTH_LABEL'}]&nbsp;
                    <p id="fcporp_installment_rate" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DURATION_MONTH_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_amount')}]
                </div>
            </div>

            <div class="rp-installment-plan-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_lastRate')"
                     onmouseout="fcpoMouseOut('fcporp_installment_lastRate')">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_LAST_RATE_LABEL'}]&nbsp;
                    <p id="fcporp_installment_lastRate" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_LAST_RATE_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_last_amount')}]
                </div>
            </div>
        </div>

        <div id="fcporp_installment_rp-installment-plan-no-details">
            <div class="rp-installment-plan-no-details">
                <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_rate2')"
                     onmouseout="fcpoMouseOut('fcporp_installment_rate2')">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_number')}] [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DURATION_MONTH_LABEL'}]&nbsp;
                    <p id="fcporp_installment_rate2" class="rp-installment-plan-description small">
                        [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_DURATION_MONTH_DESC'}]
                    </p>
                </div>
                <div class="text-right">
                    [{$oView->fcpoCalculationParameter('fcporp_installment_amount')}]
                </div>
            </div>
        </div>
        <div class="rp-installment-plan-details">
            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver('fcporp_installment_totalAmount')"
                 onmouseout="fcpoMouseOut('fcporp_installment_totalAmount')">
                [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_LABEL'}]&nbsp;
                <p id="fcporp_installment_totalAmount" class="rp-installment-plan-description small">
                    [{oxmultilang ident='FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_DESC'}]
                </p>
            </div>
            <div class="text-right">
                [{$oView->fcpoCalculationParameter('fcporp_installment_total_amount')}]
            </div>
        </div>
    </div>
[{/if}]
