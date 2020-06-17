<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
    </dt>
    <dd>
        <div class="form-group" id="fcpo_ou_idl">
            <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANKGROUPTYPE"}]:</label>
            <div class="col-lg-9">
                <select name="dynvalue[fcpo_so_bankgrouptype_idl]" class="form-control selectpicker">
                    <option value="ABN_AMRO_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "ABN_AMRO_BANK"}]selected[{/if}]>ABN Amro</option>
                    <option value="BUNQ_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "BUNQ_BANK"}]selected[{/if}]>Bunq</option>
                    <option value="ING_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "ING_BANK"}]selected[{/if}]>ING Bank</option>
                    <option value="RABOBANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "RABOBANK"}]selected[{/if}]>Rabobank</option>
                    <option value="SNS_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "SNS_BANK"}]selected[{/if}]>SNS Bank</option>
                    <option value="ASN_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "ASN_BANK"}]selected[{/if}]>ASN Bank</option>
                    <option value="SNS_REGIO_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "SNS_REGIO_BANK"}]selected[{/if}]>Regio Bank</option>
                    <option value="TRIODOS_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "TRIODOS_BANK"}]selected[{/if}]>Triodos Bank</option>
                    <option value="KNAB_BANK" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "KNAB_BANK"}]selected[{/if}]>Knab</option>
                    <option value="VAN_LANSCHOT_BANKIERS" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "VAN_LANSCHOT_BANKIERS"}]selected[{/if}]>van Lanschot</option>
                    <option value="MONEYOU" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "MONEYOU"}]selected[{/if}]>Moneyou</option>
                    <option value="HANDELSBANKEN" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "HANDELSBANKEN"}]selected[{/if}]>Handelsbanken</option>
                </select>
            </div>
        </div>
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