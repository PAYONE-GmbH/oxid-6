<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html lang="en">
<head>
    <title>[{ oxmultilang ident="GENERAL_ADMIN_TITLE_1" }]</title>
</head>

<!-- frames -->
<frameset  rows="40%,*" border="0">
    <frame src="[{$oViewConf->getSelfLink()}][{$oView->fcGetAdminSeperator()}][{ $listurl }][{ if $oxid }]&oxid=[{$oxid}][{/if}]" name="list" marginwidth="0" marginheight="0" scrolling="off" frameborder="0">
    <frame src="[{$oViewConf->getSelfLink()}][{$oView->fcGetAdminSeperator()}][{ $editurl }][{ if $oxid }]&oxid=[{$oxid}][{/if}]" name="edit" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0">
</frameset>


</html>