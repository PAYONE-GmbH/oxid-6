[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
    <div class="well well-sm">
        <dl id="fcpoCreditcard" style="display:none;">
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
                <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}] activePayment[{else}]payment-option[{/if}]">
                <input type="hidden" name="fcpo_cc_type" value="ajax">
                [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
                [{/foreach}]
                <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
                <div class="form-group fcpo_entry_error" id="fcpo_cc_error">
                    <div class="col-lg-9">
                        <span class="help-block">
                            <ul role="alert" class="list-unstyled text-danger">
                                <li>[{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_cc_error_content"></div></li>
                            </ul>
                        </span>
                    </div>
                </div>
                <div class="form-group fcpo_kktype">
                    <label for="fcpo_kktype" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CREDITCARD"}]:</label>
                    <div class="col-lg-9">
                        <select id="fcpo_kktype" name="dynvalue[fcpo_kktype]" class="form-control" required="required">
                            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                                <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                </div>

                <div class="form-group fcpo_kknumber">
                    <label for="fcpo_kknumber" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_NUMBER"}]:</label>
                    <div class="col-lg-9">
                        <input id="fcpo_kknumber" placeholder="[{oxmultilang ident="FCPO_NUMBER"}]" autocomplete="off" type="text" class="form-control js-oxValidate js-oxValidate_notEmpty payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kknumber]" value="[{$dynvalue.fcpo_kknumber}]" required="required">
                        <div id="fcpo_cc_number_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_NUMBER_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group fcpo_kkname">
                    <label for="fcpo_cc_cardholder" id="fcpo_cc_cardholder_label" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CC_CARDHOLDER"}]:</label>
                    <div class="col-lg-9">
                        <input autocomplete="off" type="text"
                               class="form-control"
                               size="20" maxlength="50" id="fcpo_cc_cardholder" name="dynvalue[fcpo_kkcardholder]"
                               value="[{$dynvalue.fcpo_kkcardholder}]"
                               onkeyup="fcpoValidateCardholder()"
                        >
                        <span class="help-block form-group">[{oxmultilang ident="FCPO_CC_CARDHOLDER_HELPTEXT"}]</span>
                        <div id="fcpo_cc_cardholder_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_CARDHOLDER_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group fcpo_kkexpire">
                    <label class="req control-label col-xs-12 col-lg-3">[{oxmultilang ident="FCPO_VALID_UNTIL"}]:</label>
                    <div class="col-xs-6 col-lg-2">
                        <select aria-label="[{oxmultilang ident="FCPO_MONTH"}]" name="dynvalue[fcpo_kkmonth]" class="form-control" required="required">
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
                        </select>
                    </div>
                    <div class="col-xs-6 col-lg-2">
                        <select aria-label="[{oxmultilang ident="FCPO_YEAR"}]" name="dynvalue[fcpo_kkyear]" class="form-control">
                            [{foreach from=$oView->getCreditYears() item=year}]
                                <option [{if $dynvalue.fcpo_kkyear == $year}]selected[{/if}]>[{$year}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                    <div class="col-sm-3"></div>
                    <div class="col-lg-9">
                        <div id="fcpo_cc_date_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_DATE_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group fcpo_kkpruef">
                    <label for="fcpo_kkpruef" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]:</label>
                    <div class="col-lg-9">
                        <input id="fcpo_kkpruef" placeholder="[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]" autocomplete="off" type="text" class="form-control js-oxValidate js-oxValidate_notEmpty payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kkpruef]" value="[{$dynvalue.fcpo_kkpruef}]" required="required">
                        <div id="fcpo_cc_cvc2_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_CVC2_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                        <span class="help-block">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE_DESCRIPTION"}]</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                [{block name="checkout_payment_longdesc"}]
                    [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                        <div class="alert alert-info col-lg-offset-3 desc">
                            [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                        </div>
                    [{/if}]
                [{/block}]
            </dd>
        </dl>
    </div>
[{/if}]