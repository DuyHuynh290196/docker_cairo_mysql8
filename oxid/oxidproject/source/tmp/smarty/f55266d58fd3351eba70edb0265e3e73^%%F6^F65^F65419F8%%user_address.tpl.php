<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:12
         compiled from user_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'user_address.tpl', 1, false),array('modifier', 'lower', 'user_address.tpl', 63, false),array('function', 'oxmultilang', 'user_address.tpl', 32, false),array('function', 'oxinputhelp', 'user_address.tpl', 44, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

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
    <input type="hidden" name="cl" value="user_address">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="user_address">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxaddress__oxid]" value="<?php echo $this->_tpl_vars['oxaddressid']; ?>
">
<input type="hidden" name="editval[oxaddress__oxuserid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
    <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext">
            <?php echo smarty_function_oxmultilang(array('ident' => 'USER_ADDRESS_DELIVERYADDRESS'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">

                <select <?php echo $this->_tpl_vars['readonly']; ?>
 name="oxaddressid" class="editinput" style="width:320px;" onChange="document.myedit.submit();">
                    <option value="-1">-</option>
                    <?php $_from = $this->_tpl_vars['edituser']->getUserAddresses(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['address']):
?>
                    <option value="<?php echo $this->_tpl_vars['address']->oxaddress__oxid->value; ?>
" <?php if ($this->_tpl_vars['address']->selected): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['address']->oxaddress__oxfname->value; ?>
 <?php echo $this->_tpl_vars['address']->oxaddress__oxlname->value; ?>
, <?php echo $this->_tpl_vars['address']->oxaddress__oxstreet->value; ?>
, <?php echo $this->_tpl_vars['address']->oxaddress__oxcity->value; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_ADDRESS_DELIVERYADDRESS'), $this);?>

            </td>
        </tr>
        </table>

    <?php endif; ?>
    </td>

    <td valign="top" class="edittext vr">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_BILLSAL'), $this);?>

                </td>
                <td class="edittext">
                <!--<input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxuser__oxsal->fldmax_length; ?>
" name="editval[oxaddress__oxsal]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxsal->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>-->
                <select name="editval[oxaddress__oxsal]" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <option value="MR"  <?php if (((is_array($_tmp=$this->_tpl_vars['edit']->oxaddress__oxsal->value)) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mr'): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'MR'), $this);?>
</option>
                    <option value="MRS" <?php if (((is_array($_tmp=$this->_tpl_vars['edit']->oxaddress__oxsal->value)) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mrs'): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'MRS'), $this);?>
</option>
                  </select>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_BILLSAL'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfname->fldmax_length; ?>
" name="editval[oxaddress__oxfname]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfname->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <input type="text" class="editinput" size="20" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxlname->fldmax_length; ?>
" name="editval[oxaddress__oxlname]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxlname->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_NAME'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_COMPANY'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="37" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxcompany->fldmax_length; ?>
" name="editval[oxaddress__oxcompany]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxcompany->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_COMPANY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_STREETNUM'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="28" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstreet->fldmax_length; ?>
" name="editval[oxaddress__oxstreet]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstreet->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
> <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstreetnr->fldmax_length; ?>
" name="editval[oxaddress__oxstreetnr]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstreetnr->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_STREETNUM'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ZIPCITY'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxzip->fldmax_length; ?>
" name="editval[oxaddress__oxzip]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxzip->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxcity->fldmax_length; ?>
" name="editval[oxaddress__oxcity]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxcity->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ZIPCITY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_EXTRAINFO'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="37" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxaddinfo->fldmax_length; ?>
" name="editval[oxaddress__oxaddinfo]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxaddinfo->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_EXTRAINFO'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_STATE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstateid->fldmax_length; ?>
" name="editval[oxaddress__oxstateid]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxstateid->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_STATE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_COUNTRY'), $this);?>

                </td>
                <td class="edittext">
                 <select class="editinput" name="editval[oxaddress__oxcountryid]" <?php echo $this->_tpl_vars['readonly']; ?>
>
                   <?php $_from = $this->_tpl_vars['countrylist']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oCountry']):
?>
                   <option value="<?php echo $this->_tpl_vars['oCountry']->oxcountry__oxid->value; ?>
" <?php if ($this->_tpl_vars['oCountry']->oxcountry__oxid->value == $this->_tpl_vars['edit']->oxaddress__oxcountryid->value): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['oCountry']->oxcountry__oxtitle->value; ?>
</option>
                   <?php endforeach; endif; unset($_from); ?>
                 </select>
                 <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_COUNTRY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FON'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="12" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfon->fldmax_length; ?>
" name="editval[oxaddress__oxfon]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfon->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_FON'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FAX'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="12" maxlength="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfax->fldmax_length; ?>
" name="editval[oxaddress__oxfax]" value="<?php echo $this->_tpl_vars['edit']->oxaddress__oxfax->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_FAX'), $this);?>

                </td>
            </tr>
        
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php if ($this->_tpl_vars['oxaddressid'] != "-1"): ?>
                <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_DELETE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='deladdress'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php endif; ?>
            </td>
        </tr>

        </table>
    </td>

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