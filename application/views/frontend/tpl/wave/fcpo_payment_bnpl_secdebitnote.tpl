<div class="well well-sm">
	<dl>
	    <dt>
	        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
	        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b></label>
	    </dt>
	    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{else}]payment-option[{/if}]">
	        <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
        	<input type="hidden" name="dynvalue[fcpopl_secdebitnote_account_holder]" value="[{$oView->fcpoGetAccountHolder()}]" />
			[{if $oView->fcpoBNPLShowBirthdate()}]
	            <div class="form-group">
	                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_SECINVOICE_BIRTHDATE"}]:</label>
	                <div class="col-lg-9">
                            <select name="dynvalue[fcpopl_secdebitnote_birthdate_day]">
	                        [{foreach from=$oView->fcpoGetDayRange() item='sDay'}]
	                            <option value="[{$sDay}]" [{if $sDay == $oView->fcpoGetBirthdayField('day')}]selected[{/if}]>[{$sDay}]</option>
	                        [{/foreach}]
	                    </select>
	                    &nbsp;
                            <select name="dynvalue[fcpopl_secdebitnote_birthdate_month]">
	                        [{foreach from=$oView->fcpoGetMonthRange() item='sMonth'}]
	                            <option value="[{$sMonth}]" [{if $sMonth == $oView->fcpoGetBirthdayField('month')}]selected[{/if}]>[{$sMonth}]</option>
	                        [{/foreach}]
	                    </select>
	                    &nbsp;
                            <select name="dynvalue[fcpopl_secdebitnote_birthdate_year]">
	                        [{foreach from=$oView->fcpoGetYearRange() item='sYear'}]
	                            <option value="[{$sYear}]" [{if $sYear == $oView->fcpoGetBirthdayField('year')}]selected[{/if}]>[{$sYear}]</option>
	                        [{/foreach}]
	                    </select>
	                </div>
	            </div>
	        [{/if}]

	        [{if $oView->fcpoBNPLShowFon()}]
	            <div class="form-group">
	                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BNPL_FON"}]:</label>
	                <div class="col-lg-9">
	                    <input placeholder="[{oxmultilang ident="FCPO_BNPL_FON"}]" class="form-control" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secdebitnote_fon]" value="[{$oView->fcpoGetUserValue('oxfon')}]" required="required">
	                </div>
	            </div>
	        [{/if}]

                <div class="form-group">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_IBAN"}]:</label>
                    <div class="col-lg-9">
                        <input placeholder="[{oxmultilang ident="FCPO_BANK_IBAN"}]" class="form-control js-oxValidate js-oxValidate_notEmpty" type="text" size="20" maxlength="64" name="dynvalue[fcpopl_secdebitnote_iban]" value="[{$dynvalue.fcpopl_secdebitnote_iban}]" onkeyup="fcHandleDebitInputs();return false;" required="required">
                        <div id="fcpopl_secdebitnote_iban_invalid" class="fcpo_check_error">
                            <p class="oxValidateError" style="display: block;">
                                [{oxmultilang ident="FCPO_IBAN_INVALID"}]
                            </p>
                        </div>
                    </div>
                </div>
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
</div>

