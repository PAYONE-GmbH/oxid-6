<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}] [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <div class="form-group" id="fcpo_ou_eps">
            <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANKGROUPTYPE"}]:</label>
            <div class="col-lg-9">
                <select name="dynvalue[fcpo_so_bankgrouptype_eps]" class="form-control selectpicker">
                    <option value="ARZ_OVB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_OVB"}]selected[{/if}]>Volksbanken</option>
                    <option value="ARZ_BAF" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_BAF"}]selected[{/if}]>Bank f&uuml;r &Auml;rzte und Freie Berufe</option>
                    <option value="ARZ_NLH" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_NLH"}]selected[{/if}]>Nieder&ouml;sterreichische Landes-Hypo</option>
                    <option value="ARZ_VLH" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_VLH"}]selected[{/if}]>Vorarlberger Landes-Hypo</option>
                    <option value="ARZ_BCS" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_BCS"}]selected[{/if}]>Bankhaus Carl Sp&auml;ngler & Co. AG</option>
                    <option value="ARZ_HTB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_HTB"}]selected[{/if}]>Hypo Tirol</option>
                    <option value="ARZ_HAA" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_HAA"}]selected[{/if}]>Hypo Alpe Adria</option>
                    <option value="ARZ_IKB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_IKB"}]selected[{/if}]>Investkreditbank</option>
                    <option value="ARZ_OAB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_OAB"}]selected[{/if}]>&Ouml;sterreichische Apothekerbank</option>
                    <option value="ARZ_IMB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_IMB"}]selected[{/if}]>Immobank</option>
                    <option value="ARZ_GRB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_GRB"}]selected[{/if}]>G&auml;rtnerbank</option>
                    <option value="ARZ_HIB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "ARZ_HIB"}]selected[{/if}]>HYPO Investment</option>
                    <option value="BA_AUS" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "BA_AUS"}]selected[{/if}]>Bank Austria</option>
                    <option value="BAWAG_BWG" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "BAWAG_BWG"}]selected[{/if}]>BAWAG</option>
                    <option value="BAWAG_PSK" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "BAWAG_PSK"}]selected[{/if}]>PSK Bank</option>
                    <option value="BAWAG_ESY" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "BAWAG_ESY"}]selected[{/if}]>easybank</option>
                    <option value="BAWAG_SPD" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "BAWAG_SPD"}]selected[{/if}]>Sparda Bank</option>
                    <option value="SPARDAT_EBS" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "SPARDAT_EBS"}]selected[{/if}]>Erste Bank</option>
                    <option value="SPARDAT_BBL" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "SPARDAT_BBL"}]selected[{/if}]>Bank Burgenland</option>
                    <option value="RAC_RAC" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "RAC_RAC"}]selected[{/if}]>Raiffeisen</option>
                    <option value="HRAC_OOS" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "HRAC_OOS"}]selected[{/if}]>Hypo Ober&ouml;sterreich</option>
                    <option value="HRAC_SLB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "HRAC_SLB"}]selected[{/if}]>Hypo Salzburg</option>
                    <option value="HRAC_STM" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "HRAC_STM"}]selected[{/if}]>Hypo Steiermark</option>
                    <option value="EPS_SCHEL" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_SCHEL"}]selected[{/if}]>Bankhaus Schelhammer & Schattera AG</option>
                    <option value="EPS_OBAG" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_OBAG"}]selected[{/if}]>Oberbank AG</option>
                    <option value="EPS_SCHOELLER" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_SCHOELLER"}]selected[{/if}]>Schoellerbank AG</option>
                    <option value="EPS_VRBB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_VRBB"}]selected[{/if}]>VR-Bank Braunau</option>
                    <option value="EPS_AAB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_AAB"}]selected[{/if}]>Austrian Anadi Bank AG</option>
                    <option value="EPS_BKS" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_BKS"}]selected[{/if}]>BKS Bank AG</option>
                    <option value="EPS_BKB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_BKB"}]selected[{/if}]>Brüll Kallmus Bank AG</option>
                    <option value="EPS_VLB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_VLB"}]selected[{/if}]>BTV VIER LÄNDER BANK</option>
                    <option value="EPS_CBGG" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_CBGG"}]selected[{/if}]>Capital Bank Grawe Gruppe AG</option>
                    <option value="EPS_DB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_DB"}]selected[{/if}]>Dolomitenbank</option>
                    <option value="EPS_NOEGB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_NOEGB"}]selected[{/if}]>HYPO NOE Gruppe Bank AG</option>
                    <option value="EPS_NOELB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_NOELB"}]selected[{/if}]>HYPO NOE Landesbank AG</option>
                    <option value="EPS_HBL" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_HBL"}]selected[{/if}]>HYPO-BANK BURGENLAND Aktiengesellschaft</option>
                    <option value="EPS_MFB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_MFB"}]selected[{/if}]>Marchfelder Bank</option>
                    <option value="EPS_SPDBW" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_SPDBW"}]selected[{/if}]>Sparda Bank Wien</option>
                    <option value="EPS_SPDBA" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_SPDBA"}]selected[{/if}]>SPARDA-BANK AUSTRIA</option>
                    <option value="EPS_VKB" [{if $dynvalue.fcpo_so_bankgrouptype_eps == "EPS_VKB"}]selected[{/if}]>Volkskreditbank AG</option>
                </select>
            </div>
        </div>
        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value|trim}]
                <div class="desc">
                    [{ $paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
        [{/block}]
    </dd>
</dl>