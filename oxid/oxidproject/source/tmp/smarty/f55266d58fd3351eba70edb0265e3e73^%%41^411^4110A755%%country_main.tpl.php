<?php /* Smarty version 2.6.33, created on 2025-04-01 12:23:54
         compiled from country_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'country_main.tpl', 1, false),array('function', 'oxmultilang', 'country_main.tpl', 49, false),array('function', 'oxinputhelp', 'country_main.tpl', 53, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="country_main">
    <input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="country_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="voxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="oxparentid" value="<?php echo $this->_tpl_vars['oxparentid']; ?>
">
<input type="hidden" name="editval[oxcountry__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>

    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
            
                <tr>
                    <td class="edittext" width="120">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVE'), $this);?>

                    </td>
                    <td class="edittext">
                    <input class="edittext" type="checkbox" name="editval[oxcountry__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxcountry__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TITLE'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="40" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxtitle->fldmax_length; ?>
" id="oLockTarget" name="editval[oxcountry__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxtitle->value; ?>
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
                    <input type="text" class="editinput" size="40" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxshortdesc->fldmax_length; ?>
" name="editval[oxcountry__oxshortdesc]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxshortdesc->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_SHORTDESC'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_ISO2'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxisoalpha2->fldmax_length; ?>
" name="editval[oxcountry__oxisoalpha2]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxisoalpha2->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_COUNTRY_MAIN_ISO2'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_ISO3'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxisoalpha3->fldmax_length; ?>
" name="editval[oxcountry__oxisoalpha3]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxisoalpha3->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_COUNTRY_MAIN_ISO3'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_ISOUNNUM'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxunnum3->fldmax_length; ?>
" name="editval[oxcountry__oxunnum3]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxunnum3->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_COUNTRY_MAIN_ISOUNNUM'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SORT'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcountry__oxorder->fldmax_length; ?>
" name="editval[oxcountry__oxorder]" value="<?php echo $this->_tpl_vars['edit']->oxcountry__oxorder->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_SORT'), $this);?>

                    </td>
                </tr>
                <?php if ($this->_tpl_vars['blForeignCountry']): ?>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_OXVATSTATUS'), $this);?>

                    </td>
                    <td class="edittext">
                    <fieldset style="margin: 5px 0 0 0;">
                        <input type="radio" name="editval[oxcountry__oxvatstatus]" value="0" <?php if ($this->_tpl_vars['edit']->oxcountry__oxvatstatus->value == 0): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_OXVATSTATUS_0'), $this);?>

                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_COUNTRY_MAIN_OXVATSTATUS_0'), $this);?>

                        <br />
                        <input type="radio" name="editval[oxcountry__oxvatstatus]" value="1" <?php if ($this->_tpl_vars['edit']->oxcountry__oxvatstatus->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_OXVATSTATUS_1'), $this);?>

                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_COUNTRY_MAIN_OXVATSTATUS_1'), $this);?>

                    </fieldset>
                    </td>
                </tr>
                <?php endif; ?>             
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
 <?php if (! $this->_tpl_vars['edit']->oxcountry__oxtitle->value && ! $this->_tpl_vars['oxparentid']): ?>disabled<?php endif; ?>><br>
            </td>
        </tr>


        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="55%">
        
            <?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_MAIN_OPDESCRIPTION'), $this);?>
<br>
            <textarea class="editinput" style="width:250;height:100;" wrap="VIRTUAL" name="editval[oxcountry__oxlongdesc]" <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['edit']->oxcountry__oxlongdesc->value; ?>
</textarea>
        
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