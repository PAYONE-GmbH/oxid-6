<!-- FCPAYONE BEGIN -->
<td valign="top" class="[{$listclass}]" height="15">
    <div class="listitemfloating">
        <a href="Javascript:top.oxid.admin.editThis('[{$listitem->oxorder__oxid->value}]');" class="[{$listclass}]">
            [{$listitem->oxorder__fcporefnr->value}]
        </a>
    </div>
</td>

[{$smarty.block.parent}]

<script type="text/javascript">
    top.oxid.admin.getDeleteMessage = function () {
        return '[{oxmultilang ident="FCPO_ORDER_LIST_YOUWANTTODELETE"}]';
    }
</script>
