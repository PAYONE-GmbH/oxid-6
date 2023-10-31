<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{oxmultilang ident=$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
    </dt>
    <dd>
        <div class="form-group" id="fcpo_ou_idl">
            <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANKGROUPTYPE"}]:</label>
            <div class="col-lg-9">
                <select name="dynvalue[fcpo_so_bankgrouptype_idl]" class="form-control selectpicker">
                    <option value="ABNANL2A" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "ABNANL2A"}]selected[{/if}]>ABN AMRO</option>
                    <option value="ASNBNL21" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "ASNBNL21"}]selected[{/if}]>ASN Bank</option>
                    <option value="BUNQNL2A" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "BUNQNL2A"}]selected[{/if}]>bunq</option>
                    <option value="INGBNL2A" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "INGBNL2A"}]selected[{/if}]>ING</option>
                    <option value="KNABNL2H" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "KNABNL2H"}]selected[{/if}]>Knab</option>
                    <option value="NTSBDEB1" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "NTSBDEB1"}]selected[{/if}]>N26</option>
                    <option value="NNBANL2G" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "NNBANL2G"}]selected[{/if}]>Nationale-Nederlanden</option>
                    <option value="RABONL2U" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "RABONL2U"}]selected[{/if}]>Rabobank</option>
                    <option value="RBRBNL21" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "RBRBNL21"}]selected[{/if}]>RegioBank</option>
                    <option value="REVOLT21" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "REVOLT21"}]selected[{/if}]>Revolut</option>
                    <option value="SNSBNL2A" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "SNSBNL2A"}]selected[{/if}]>SNS</option>
                    <option value="TRIONL2U" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "TRIONL2U"}]selected[{/if}]>Triodos Bank</option>
                    <option value="FVLBNL22" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "FVLBNL22"}]selected[{/if}]>Van Lanschot Kempen</option>
                    <option value="BITSNL2A" [{if $dynvalue.fcpo_so_bankgrouptype_idl == "BITSNL2A"}]selected[{/if}]>Yoursafe</option>
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