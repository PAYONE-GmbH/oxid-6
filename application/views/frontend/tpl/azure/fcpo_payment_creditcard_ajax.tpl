[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
    <dl id="fcpoCreditcard" style="display:none;">
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <input type="hidden" name="fcpo_cc_type" value="ajax">
            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
            [{/foreach}]
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">

            <ul class="form fcpo_kk_form">
                <li id="fcpo_cc_error">
                    <div class="oxValidateError" style="display: block;padding: 0;">
                        [{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_cc_error_content"></div>
                    </div>
                </li>
                <li>
                    <label for="fcpo_kktype">[{oxmultilang ident="FCPO_CREDITCARD"}]:</label>
                    <select id="fcpo_kktype" name="dynvalue[fcpo_kktype]">
                        [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                            <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                        [{/foreach}]
                    </select>
                </li>
                <li>
                    <label for="fcpo_kknumber">[{oxmultilang ident="FCPO_NUMBER"}]:</label>
                    <input id="fcpo_kknumber" placeholder="[{oxmultilang ident="FCPO_NUMBER"}]" autocomplete="off" type="text" class="payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kknumber]" value="[{$dynvalue.fcpo_kknumber}]">
                    <div id="fcpo_cc_number_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_CC_NUMBER_INVALID"}]
                        </p>
                    </div>
                </li>
                <li class="form-group">
                    <label for="fcpo_cc_cardholder" id="fcpo_cc_cardholder_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CC_CARDHOLDER"}]:</label>
                    <input autocomplete="off" type="text"
                           class="form-control"
                           size="20" maxlength="50" id="fcpo_cc_cardholder" name="dynvalue[fcpo_kkcardholder]"
                           value="[{$dynvalue.fcpo_kkcardholder}]"
                           onkeyup="fcpoValidateCardholder()"
                    >
                    <p style="display: block; padding: 5px 0 5px 150px">[{oxmultilang ident="FCPO_CC_CARDHOLDER_HELPTEXT"}]</p>
                    <div id="fcpo_cc_cardholder_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul class="oxValidateError" style="display: block;">
                                    <li>[{oxmultilang ident="FCPO_CC_CARDHOLDER_INVALID"}]</li>
                                </ul>
                            </span>
                    </div>
                </li>
                <li>
                    <label>[{oxmultilang ident="FCPO_VALID_UNTIL"}]:</label>
                    <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcpo_kkmonth]">
                        <option [{if $dynvalue.fcpo_kkmonth == "01"}]selected[{/if}]>01</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "02"}]selected[{/if}]>02</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "03"}]selected[{/if}]>03</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "04"}]selected[{/if}]>04</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "05"}]selected[{/if}]>05</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "06"}]selected[{/if}]>06</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "07"}]selected[{/if}]>07</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "08"}]selected[{/if}]>08</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "09"}]selected[{/if}]>09</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "10"}]selected[{/if}]>10</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "11"}]selected[{/if}]>11</option>
                        <option [{if $dynvalue.fcpo_kkmonth == "12"}]selected[{/if}]>12</option>
                    </select>&nbsp;/&nbsp;

                    <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcpo_kkyear]">
                        [{foreach from=$oView->getCreditYears() item=year}]
                            <option [{if $dynvalue.fcpo_kkyear == $year}]selected[{/if}]>[{$year}]</option>
                        [{/foreach}]
                    </select>
                    <div id="fcpo_cc_date_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_CC_DATE_INVALID"}]
                        </p>
                    </div>
                </li>
                <li>
                    <label for="fcpo_kkpruef">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]:</label>
                    <input id="fcpo_kkpruef" placeholder="[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]" autocomplete="off" type="text" class="payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kkpruef]" value="[{$dynvalue.fcpo_kkpruef}]">
                    <div id="fcpo_cc_cvc2_invalid" class="fcpo_check_error">
                        <p class="oxValidateError" style="display: block;">
                            [{oxmultilang ident="FCPO_CC_CVC2_INVALID"}]
                        </p>
                    </div>
                    <div class="clear"></div>
                    <div class="note">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE_DESCRIPTION"}]</div>
                </li>
            </ul>
            [{block name="checkout_payment_longdesc"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                    <div class="desc">
                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                    </div>
                [{/if}]
            [{/block}]
        </dd>
    </dl>
[{/if}]