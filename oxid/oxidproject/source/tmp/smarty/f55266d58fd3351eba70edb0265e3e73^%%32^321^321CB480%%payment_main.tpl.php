<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:49
         compiled from payment_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'payment_main.tpl', 7, false),array('function', 'oxmultilang', 'payment_main.tpl', 34, false),array('function', 'oxinputhelp', 'payment_main.tpl', 38, false),array('block', 'oxhasrights', 'payment_main.tpl', 186, false),)), $this); ?>
<?php if ($this->_tpl_vars['shopid'] != '1' || $this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="payment_main">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post" onSubmit="copyLongDesc( 'oxpayments__oxlongdesc' );">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="payment_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxpayments__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxpayments__oxlongdesc]" value="">

<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>

    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="70">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVE'), $this);?>

                </td>
                <td class="edittext">
                <input class="edittext" type="checkbox" name="editval[oxpayments__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxpayments__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" width="100">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_NAME'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxdesc->fldmax_length; ?>
" name="editval[oxpayments__oxdesc]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxdesc->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_NAME'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDPRICE'), $this);?>
 (<?php echo $this->_tpl_vars['oActCur']->sign; ?>
)
                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxaddsum->fldmax_length; ?>
" name="editval[oxpayments__oxaddsum]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxaddsum->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <select name="editval[oxpayments__oxaddsumtype]" class="editinput" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'addsumtype')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php $_from = $this->_tpl_vars['sumtype']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sum']):
?>
                    <option value="<?php echo $this->_tpl_vars['sum']; ?>
" <?php if ($this->_tpl_vars['sum'] == $this->_tpl_vars['edit']->oxpayments__oxaddsumtype->value): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['sum']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_ADDPRICE'), $this);?>

                </td>
            </tr>
            <?php if ($this->_tpl_vars['noticeoxaddsumrules'] == 1): ?>
            <tr>
                <td colspan="2">
                    <div class="errorbox"><?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_NOTICEDEFAULTVALUESSELECTED'), $this);?>
</div>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="edittext" valign="top">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES'), $this);?>
:
                </td>
                <td class="edittext">
                  <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td><input type="checkbox" name="oxpayments__oxaddsumrules[]" value="1" <?php if (! $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value || $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value & 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
> <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES_ALLGOODS'), $this);?>
</td>
                        <td rowspan="5" valign="top"><?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_ADDSUMRULES'), $this);?>
</td>
                    </tr>
                    <tr><td><input type="checkbox" name="oxpayments__oxaddsumrules[]" value="2" <?php if (! $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value || $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value & 2): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
> <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES_DISCOUNTS'), $this);?>
</td></tr>
                    <tr><td><input type="checkbox" name="oxpayments__oxaddsumrules[]" value="4" <?php if (! $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value || $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value & 4): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
> <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES_VOUCHERS'), $this);?>
</td></tr>
                    <tr><td><input type="checkbox" name="oxpayments__oxaddsumrules[]" value="8" <?php if (! $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value || $this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value & 8): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
> <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES_SHIPCOSTS'), $this);?>
</td></tr>
                    <tr><td><input type="checkbox" name="oxpayments__oxaddsumrules[]" value="16" <?php if ($this->_tpl_vars['edit']->oxpayments__oxaddsumrules->value & 16): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
> <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_ADDSUMRULES_GIFTS'), $this);?>
</td></tr>
                  </table>
                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_FROMBONI'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxfromboni->fldmax_length; ?>
" name="editval[oxpayments__oxfromboni]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxfromboni->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_FROMBONI'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_AMOUNT'), $this);?>
 (<?php echo $this->_tpl_vars['oActCur']->sign; ?>
)
                </td>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_FROM'), $this);?>
 <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxfromamount->fldmax_length; ?>
" name="editval[oxpayments__oxfromamount]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxfromamount->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>  <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_TILL'), $this);?>
 <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxtoamount->fldmax_length; ?>
" name="editval[oxpayments__oxtoamount]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxtoamount->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_AMOUNT'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_SELECTED'), $this);?>

                </td>
                <td class="edittext">
                <input type="checkbox" name="editval[oxpayments__oxchecked]" value="1" <?php if ($this->_tpl_vars['edit']->oxpayments__oxchecked->value): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_SELECTED'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SORT'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxpayments__oxsort->fldmax_length; ?>
" name="editval[oxpayments__oxsort]" value="<?php echo $this->_tpl_vars['edit']->oxpayments__oxsort->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_PAYMENT_MAIN_SORT'), $this);?>

                </td>
            </tr>
        
        
            <tr>
                <td class="edittext" valign="top">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FIELDS'), $this);?>

                </td>
                <td class="edittext">
                <select name="aFields[]" size="3" multiple class="editinput" style="width: 150px;" <?php echo $this->_tpl_vars['readonly']; ?>
>
                   <?php $_from = $this->_tpl_vars['aFieldNames']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sField']):
?>
                    <option value="<?php echo $this->_tpl_vars['sField']->name; ?>
"><?php echo $this->_tpl_vars['sField']->name; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                 </select>
                 <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_FIELDS'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" valign="top">
                </td>
                <td class="edittext">
                    <input type="text" class="edittext" name="sAddField" value="" size="128" style="width: 150px;" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <br>
                    <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FIELDS_ADD'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='addfield'"" <?php echo $this->_tpl_vars['readonly']; ?>
 style="width: 125px;"><br>
                  <br>
                </td>
            </tr>
            <tr>
                <td class="edittext" valign="top">
                </td>
                <td class="edittext">
                    <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FIELDS_DELETE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='delfields'"" <?php echo $this->_tpl_vars['readonly']; ?>
 style="width: 150px;">
                </td>
            </tr>
        


        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
 style="width: 150px;">
            </td>
        </tr>
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

        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
        
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNGROUPS'), $this);?>
" class="edittext" style="margin-bottom:20px;" onclick="JavaScript:showDialog('&cl=payment_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');">
            <?php endif; ?>
        

        
            <?php $this->_tag_stack[] = array('oxhasrights', array('object' => $this->_tpl_vars['edit'],'field' => 'oxlongdesc','readonly' => $this->_tpl_vars['readonly'])); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                <div>
                    <p><?php echo smarty_function_oxmultilang(array('ident' => 'PAYMENT_MAIN_LONGDESC'), $this);?>
</p>
                    <?php echo $this->_tpl_vars['editor']; ?>

                    <div class="messagebox"><?php echo smarty_function_oxmultilang(array('ident' => 'EDITOR_PLAINTEXT_HINT'), $this);?>
</div>
                </div>
            <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        
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