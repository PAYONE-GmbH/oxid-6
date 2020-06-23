<dl>
    [{assign var='checkedPaymentId' value=$oView->getCheckedPaymentId()}]
    <dt>
        <input id="payment_klarna_combined" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->fcpoIsKlarnaCombined($checkedPaymentId)}]checked[{/if}]>
        <label for="payment_klarna_combined"><b>Klarna</b></label>
    </dt>
    <div class="hidden">
        <input type="hidden" id="fcpo_klarna_auth_token" name="dynvalue[klarna_authorization_token]">
        <input type="hidden" id="fcpo_klarna_auth_done" name="dynvalue[fcpo_klarna_auth_done]" value="false">
    </div>
    <dd class="[{if  $oView->fcpoIsKlarnaCombined($checkedPaymentId)}]activePayment[{/if}]">
        [{if $oView->fcpoKlarnaIsBirthdayNeeded()}]
            <div class="form-group">
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_BIRTHDAY"}]</label>
                <div class="col-lg-3">
                    <input class="form-control" placeholder="DD" autocomplete="off" type="text" size="3" maxlength="2" name="dynvalue[fcpo_klarna_birthday][day]">
                </div>
                <div class="col-lg-3">
                    <input class="form-control" placeholder="MM" autocomplete="off" type="text" size="3" maxlength="2" name="dynvalue[fcpo_klarna_birthday][month]">
                </div>
                <div class="col-lg-3">
                    <input class="form-control" placeholder="YYYY" autocomplete="off" type="text" size="8" maxlength="4" name="dynvalue[fcpo_klarna_birthday][year]">
                </div>
            </div>
        [{/if}]

        [{if $oView->fcpoKlarnaIsTelephoneNumberNeeded()}]
            <div class="form-group">
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_TELEPHONENUMBER"}]</label>
                <div class="col-lg-9">
                    <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_klarna_telephone]">
                </div>
            </div>
        [{/if}]

        [{if $oView->fcpoKlarnaIsPersonalIdNeeded()}]
            <div class="form-group">
                <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_PERSONALID"}]</label>
                <div class="col-lg-9">
                    <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" name="dynvalue[fcpo_klarna_personalid]">
                </div>
            </div>
        [{/if}]

        <select id="klarna_payment_selector" class="form-control">
            [{if $oView->fcpoPaymentActive('fcpoklarna_installments')}]
                <option value="fcpoklarna_installments" [{if $sPaymentID == 'fcpoklarna_installments'}]selected[{/if}]>Klarna Slice it</option>
            [{/if}]
            [{if $oView->fcpoPaymentActive('fcpoklarna_directdebit')}]
                <option value="fcpoklarna_directdebit" [{if $sPaymentID == 'fcpoklarna_directdebit'}]selected[{/if}]>Klarna Pay now</option>
            [{/if}]
            [{if $oView->fcpoPaymentActive('fcpoklarna_invoice')}]
                <option value="fcpoklarna_invoice" [{if $sPaymentID == 'fcpoklarna_invoice'}]selected[{/if}]>Klarna Pay later</option>
            [{/if}]
        </select>
        <br>

        <input id="fcpo_klarna_combined_agreed" type="checkbox" name="dynvalue[fcpo_klarna_combined_agreed]" value="agreed">
        <label for="fcpo_klarna_combined_agreed">[{oxmultilang ident="FCPO_KLARNA_COMBINED_DATA_AGREEMENT"}]</label>

        <div id="klarna_combined_js_inject"></div>

        <div id="klarna_widget_combined_container"></div>

        <div class="clearfix"></div>

        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|strip_tags|trim}]
                <div class="alert alert-info col-lg-offset-3 desc">
                    [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
        [{/block}]
    </dd>
</dl>