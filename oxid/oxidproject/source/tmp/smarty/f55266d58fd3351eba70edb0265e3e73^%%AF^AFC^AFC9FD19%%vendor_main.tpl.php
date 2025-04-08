<?php /* Smarty version 2.6.33, created on 2025-04-03 13:11:59
         compiled from vendor_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'vendor_main.tpl', 1, false),array('function', 'oxmultilang', 'vendor_main.tpl', 43, false),array('function', 'oxinputhelp', 'vendor_main.tpl', 43, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
window.onload = function ()
{
    <?php if ($this->_tpl_vars['updatelist'] == 1): ?>
        top.oxid.admin.updateList('<?php echo $this->_tpl_vars['oxid']; ?>
');
    <?php endif; ?>
    var oField = top.oxid.admin.getLockTarget();
    oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
}

//-->
</script>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="oxidCopy" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="vendor_main">
    <input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">
</form>

<form name="myedit" id="myedit" enctype="multipart/form-data" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->_tpl_vars['iMaxUploadFileSize']; ?>
">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="vendor_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="voxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="oxparentid" value="<?php echo $this->_tpl_vars['oxparentid']; ?>
">
<input type="hidden" name="editval[oxvendor__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">

<?php if ($this->_tpl_vars['oViewConf']->isAltImageServerConfigured()): ?>
    <div class="warning"><?php echo smarty_function_oxmultilang(array('ident' => 'ALTERNATIVE_IMAGE_SERVER_NOTE'), $this);?>
 <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_ALTERNATIVE_IMAGE_SERVER_NOTE'), $this);?>
</div>
<?php endif; ?>

<table border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVE'), $this);?>

                </td>
                <td class="edittext">
                <input class="edittext" type="checkbox" name="editval[oxvendor__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxvendor__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TITLE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="40" maxlength="<?php echo $this->_tpl_vars['edit']->oxvendor__oxtitle->fldmax_length; ?>
" id="oLockTarget" name="editval[oxvendor__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxvendor__oxtitle->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_TITLE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SHORTDESC'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="40" maxlength="<?php echo $this->_tpl_vars['edit']->oxvendor__oxshortdesc->fldmax_length; ?>
" name="editval[oxvendor__oxshortdesc]" value="<?php echo $this->_tpl_vars['edit']->oxvendor__oxshortdesc->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_SHORTDESC'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ICON'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxvendor__oxicon->fldmax_length; ?>
" name="editval[oxvendor__oxicon]" value="<?php echo $this->_tpl_vars['edit']->oxvendor__oxicon->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ICON'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'VENDOR_MAIN_ICONUPLOAD'), $this);?>
 (<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_FILE_UPLOAD'), $this);?>
 <?php echo $this->_tpl_vars['sMaxFormattedFileSize']; ?>
, <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_PICTURE_DIMENSIONS'), $this);?>
):<br>
                </td>
                <td class="edittext">
                <input class="editinput" name="myfile[VICO@oxvendor__oxicon]" type="file" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_VENDOR_MAIN_ICONUPLOAD'), $this);?>

                </td>
            </tr>
        
        <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "language_edit.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="edittext"><br><br>
            </td>
            <td class="edittext"><br><br>
            <input type="submit" class="edittext" id="oLockButton" name="saveArticle" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
 <?php if (! $this->_tpl_vars['edit']->oxvendor__oxtitle->value && ! $this->_tpl_vars['oxparentid']): ?>disabled<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
><br>
            </td>
        </tr>


        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="55%">
        
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNARTICLES'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=vendor_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php endif; ?>
        
    </td>
    <!-- Ende rechte Seite -->

    </tr>
</table>

</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomnaviitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>