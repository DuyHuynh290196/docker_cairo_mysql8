[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]


<script type="text/javascript">
<!--
window.onload = function ()
{
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
    top.reloadEditFrame();
}
//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]


<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="[{$class}]">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

        <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="category_mall">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="voxid" value="[{ $oxid }]">
        <input type="hidden" name="oxparentid" value="[{ $oxparentid }]">

        [{ oxmultilang ident="CATEGORY_MALL_ASSIGNONLYPARENTWARNING" }]<br>
        <a href="Javascript:top.oxid.admin.editThis('[{$edit->oxcategories__oxrootid->value}]');">[{ oxmultilang ident="CATEGORY_MALL_CLICKHEREFORPARENT" }]</a>


</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]