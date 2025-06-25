<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        [{if $oView->fcpoBNPLShowBirthdate()}]
            <div class="form-group">
                [{if $oView->fcpoIsB2BPov()}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE_B2B"}]:</label>
                [{else}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE"}]:</label>
                [{/if}]
                <div class="col-lg-9">
                    <select aria-label="[{oxmultilang ident="FCPO_DAY"}]" name="dynvalue[fcpopl_secinvoice_birthdate_day]">
                        [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                            <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcpopl_secinvoice_birthdate_month]">
                        [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                            <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                        [{/foreach}]
                    </select>
                    &nbsp;
                    <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcpopl_secinvoice_birthdate_year]">
                        [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                            <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                        [{/foreach}]
                    </select>
                </div>
            </div>
        [{/if}]

        [{if $oView->fcpoBNPLShowFon($sPaymentID)}]
        <div class="form-group">
            [{if $oView->fcpoIsB2BPov()}]
            <label for="fcpopl_secinvoice_fon" id="fcpopl_secinvoice_fon_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON_B2B"}]:</label>
            [{else}]
            <label for="fcpopl_secinvoice_fon" id="fcpopl_secinvoice_fon_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON"}]:</label>
            [{/if}]
            <div class="col-lg-9">
                <input id="fcpopl_secinvoice_fon" placeholder="[{oxmultilang ident="FCPO_BNPL_FON"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinvoice_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]" required="required">
            </div>
        </div>
        [{/if}]

        [{if $oView->fcpoIsB2BPov()}]
            [{if ! $oView->fcpoGetUserValue('oxustid')}]
                <div class="form-group">
                    <label for="fcpopl_secinvoice_ustid" id="fcpopl_secinvoice_ustid_label" class="control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_USTID"}]:</label>
                    <div class="col-lg-7">
                        <input id="fcpopl_secinvoice_ustid" placeholder="[{oxmultilang ident="FCPO_BNPL_USTID"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinvoice_ustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                    </div>
                    <label class="col-lg-12" style="padding-left: 25px">
                        [{oxmultilang ident="FCPO_BNPL_NO_COMPANY"}]
                    </label>
                </div>
            [{/if}]
        [{/if}]

        [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_bnpl_snippet.tpl')}]
        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value}]
            <div class="alert alert-info col-lg-offset-3 desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
            [{/if}]
        [{/block}]

        <div class="alert alert-info col-lg-offset-3 desc">
            [{oxmultilang ident='FCPO_BNPL_TNC_DATAPROTECTION_NOTICE'}]
        </div>

    </dd>
</dl>

