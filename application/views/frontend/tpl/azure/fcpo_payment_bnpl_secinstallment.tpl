<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        <input type="hidden" name="dynvalue[fcpopl_secinstallment_account_holder]" value="[{$oView->fcpoGetAccountHolder()}]" />

        [{assign var="installmentOptions" value=$oView->fcpoGetBNPLInstallment()}]
        [{if $installmentOptions.status != 'OK' || $installmentOptions.plans|@count < 1}]
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function(event) {
                    var aPaymentSelectionButtons = document.getElementsByName("paymentid");
                    aPaymentSelectionButtons.forEach(function(oElem) {
                        oElem.addEventListener('click', function(event) {
                            var oPaymentConfirmButton = document.getElementById("paymentNextStepBottom");
                            oPaymentConfirmButton.disabled = (event.target.value === 'fcpopl_secinstallment');
                        });
                    });
                });
            </script>
            <div class="warning">
                [{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_UNAVAILABLE'}]
            </div>
        [{else}]
            <ul class="form">
            [{if $oView->fcpoBNPLShowBirthdate()}]
                <li>
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE"}]:</label>
                    <select aria-label="[{oxmultilang ident="FCPO_DAY"}]" name="dynvalue[fcpopl_secinstallment_birthdate_day]">
                        [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                        <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcpopl_secinstallment_birthdate_month]">
                        [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                        <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcpopl_secinstallment_birthdate_year]">
                        [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                        <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                        [{/foreach}]
                    </select>
                </li>
            [{/if}]

            [{if $oView->fcpoBNPLShowFon($sPaymentID)}]
                <li>
                    <label for="fcpopl_secinstallment_fon" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON"}]:</label>
                    <div class="col-lg-9">
                        <input id="fcpopl_secinstallment_fon" placeholder="[{oxmultilang ident="FCPO_BNPL_FON"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinstallment_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]" required="required">
                    </div>
                </li>
            [{/if}]

            <li>
                <label for="fcpopl_secinstallment_iban">[{oxmultilang ident="FCPO_BANK_IBAN"}]:</label>
                <input id="fcpopl_secinstallment_iban" placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinstallment_iban]" value="[{$dynvalue.fcpopl_secinstallment_iban}]" onkeyup="fcHandleDebitInputs('[{$oView->fcpoGetBICMandatory()}]]');return false;">
                <div id="fcpopl_secinstallment_iban_invalid" class="fcpo_check_error">
                    <p class="oxValidateError" style="display: block;">
                        [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                    </p>
                </div>
            </li>
            </ul>

            [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_bnpl_snippet.tpl')}]

            [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value}]
            <div desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
            [{/if}]
            [{/block}]

            <li>
                <label class="req control-label col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_SELECTION'}]:</label>
                <div class="col-lg-9">
                    <div></div>
                    [{foreach from=$installmentOptions.plans key=index item=plan}]
                    <div>
                        <input aria-label="[{oxmultilang ident="FCPO_BNPL_SECINSTALLMENT_SELECTION"}]" id="bnplPlan_[{$index}]" type="radio" name="dynvalue[fcpopl_secinstallment_plan]" value="[{$plan.installmentOptionId}]" onclick="fcpoSelectBNPLInstallmentPlan([{$index}])"/>
                        <a href="#" onclick="fcpoSelectBNPLInstallmentPlan([{$index}])">
                            [{$plan.monthlyAmountValue}] [{$plan.monthlyAmountCurrency}] [{oxmultilang ident='FCPO_PAYOLUTION_INSTALLMENT_PER_MONTH'}] - [{$plan.numberOfPayments}] [{oxmultilang ident='FCPO_PAYOLUTION_INSTALLMENT_RATES'}]
                        </a>
                    </div>
                    [{/foreach}]
                </div>
            </li>

            <li>
                <div class="col-lg-3"></div>
                <div class="col-lg-9">
                [{foreach from=$installmentOptions.plans key=index item=plan}]
                    <div id="bnpl_installment_overview_[{$index}]" class="bnpl_installment_overview" style="display: none">
                        <strong>[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_TITLE'}]</strong>
                        <br />
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_NBRATES'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$plan.numberOfPayments}]</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_TOTALFINANCING'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$installmentOptions.amountValue}] [{$installmentOptions.amountCurrency}]</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_TOTALAMOUNT'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$plan.totalAmountValue}] [{$plan.totalAmountCurrency}]</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_INTEREST'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$plan.nominalInterestRate}]%</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_EFFECTIVEINTEREST'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$plan.effectiveInterestRate}]%</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_MONTHLYRATE'}]:</div>
                                <div class="col-lg-4 fcpopl-secinstallment-table-value">[{$plan.monthlyAmountValue}] [{$plan.monthlyAmountCurrency}]</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <br />
                                    <a target="_blank" href="[{$plan.linkCreditInformationHref}]">[{oxmultilang ident='FCPO_BNPL_SECINSTALLMENT_OVW_DL_CREDINFO'}]</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    [{/foreach}]
                </div>
            </li>

            <div class="warning">
                [{oxmultilang ident='FCPO_BNPL_TNC_DATAPROTECTION_NOTICE'}]
            </div>
        [{/if}]

    </dd>
</dl>

