<td valign="top" class="listfilter first" align="right">
    <div class="r1">
        <div class="b1">
            [{oxmultilang ident="FCPO_ONLY_PAYONE"}]
            <input class="edittext" onchange="this.form.submit();" type="checkbox" name="where[oxpayments][fcpoispayone]" value='1' [{if $where.oxpayments.fcpoispayone == 1}]checked[{/if}]>
        </div>
    </div>
</td>

[{$smarty.block.parent}]