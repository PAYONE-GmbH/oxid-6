<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        <ul class="form">
        [{if $oView->fcpoBNPLShowBirthdate()}]
            <li>
                [{if $oView->fcpoIsB2BPov()}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE_B2B"}]:</label>
                [{else}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE"}]:</label>
                [{/if}]
                &nbsp;
                <select style="width:15%" name="dynvalue[fcpopl_secinvoice_birthdate_day]">
                    [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
                        <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
                    [{/foreach}]
                </select>
                &nbsp;
                <select style="width:15%" name="dynvalue[fcpopl_secinvoice_birthdate_month]">
                    [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
                        <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
                    [{/foreach}]
                </select>
                &nbsp;
                <select style="width:15%" name="dynvalue[fcpopl_secinvoice_birthdate_year]">
                    [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
                        <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
                    [{/foreach}]
                </select>
                </li>
        [{/if}]

        [{if $oView->fcpoBNPLShowFon($sPaymentID)}]
            <li>
                [{if $oView->fcpoIsB2BPov()}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON_B2B"}]:</label>
                [{else}]
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON"}]:</label>
                [{/if}]
                <div class="col-lg-9">
                    <input placeholder="[{oxmultilang ident="FCPO_BNPL_FON"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinvoice_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]" required="required">
                </div>
            </li>
        [{/if}]

        [{if $oView->fcpoIsB2BPov()}]
            [{if ! $oView->fcpoGetUserValue('oxustid')}]
            <li>
                <label class="control-label col-lg-3" style="word-break: break-word">[{oxmultilang ident="FCPO_BNPL_USTID"}]:</label>
                <div class="col-lg-7">
                    <input placeholder="[{oxmultilang ident="FCPO_BNPL_USTID"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secinvoice_ustid]" value="[{$oView->fcpoGetUserValue('oxustid')}]">
                </div>
            </li>
            <li>
                <p class="col-lg-12" style="padding-left: 25px">
                    [{oxmultilang ident="FCPO_BNPL_NO_COMPANY"}]
                </p>
            </li>
            [{/if}]
        [{/if}]

        </ul>

        [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_bnpl_snippet.tpl')}]
        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value}]
            <div class="desc">
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
            [{/if}]
        [{/block}]
        <div class="warning">
            [{oxmultilang ident='FCPO_BNPL_TNC_DATAPROTECTION_NOTICE'}]
        </div>

    </dd>
</dl>

