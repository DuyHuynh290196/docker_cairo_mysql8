<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:12
         compiled from user_payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'user_payment.tpl', 1, false),array('modifier', 'cat', 'user_payment.tpl', 77, false),array('modifier', 'oxupper', 'user_payment.tpl', 78, false),array('function', 'oxmultilang', 'user_payment.tpl', 33, false),array('function', 'oxinputhelp', 'user_payment.tpl', 44, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="user_payment">
</form>


<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="user_payment">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxuserpayments__oxid]" value="<?php echo $this->_tpl_vars['oxpaymentid']; ?>
">
<input type="hidden" name="editval[oxuserpayments__oxuserid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<table cellspacing="0" cellpadding="0" border="0"  width="98%">

<tr>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
    <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext">
            <?php echo smarty_function_oxmultilang(array('ident' => 'USER_PAYMENT_PAYMENT'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <select name="oxpaymentid" class="editinput" style="width:320px;" onChange="document.myedit.submit();" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <option value="-1"><?php echo smarty_function_oxmultilang(array('ident' => 'USER_PAYMENT_NEWPAYMENT'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['userpayments']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment']):
?>
                    <option value="<?php echo $this->_tpl_vars['payment']->oxuserpayments__oxid->value; ?>
" <?php if ($this->_tpl_vars['payment']->selected): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['payment']->oxpayments__oxdesc->value; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_PAYMENT_METHODS'), $this);?>

            </td>

        </tr>
        </table>
    <?php endif; ?>
    </td>

    <td valign="top" class="edittext vr">
        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="70">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_PAYMENT_PAYMENTTYPE'), $this);?>

                </td>
                <td class="edittext">
                    <select name="editval[oxuserpayments__oxpaymentsid]" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['paymenttypes']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment']):
?>
                        <option value="<?php echo $this->_tpl_vars['payment']->oxpayments__oxid->value; ?>
" <?php if ($this->_tpl_vars['payment']->selected): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['payment']->oxpayments__oxdesc->value; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_PAYMENT_PAYMENTTYPE'), $this);?>

                </td>
            </tr>
            <!--tr>
                <td class="edittext" width="70">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_PAYMENT_VALUE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxuserpayments__oxvalue->fldmax_length; ?>
" name="editval[oxuserpayments__oxvalue]" value="<?php echo $this->_tpl_vars['edit']->oxuserpayments__oxvalue->value; ?>
">
                </td>
            </tr-->
            <?php $_from = $this->_tpl_vars['edit']->aDynValues; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
            <?php $this->assign('ident', ((is_array($_tmp='ORDER_OVERVIEW_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['value']->name) : smarty_modifier_cat($_tmp, $this->_tpl_vars['value']->name))); ?>
            <?php $this->assign('ident', ((is_array($_tmp=$this->_tpl_vars['ident'])) ? $this->_run_mod_handler('oxupper', true, $_tmp) : smarty_modifier_oxupper($_tmp))); ?>
            <tr>
                <td class="edittext" width="70">
                <?php echo smarty_function_oxmultilang(array('ident' => $this->_tpl_vars['ident']), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="20" maxlength="64" name="dynvalue[<?php echo $this->_tpl_vars['value']->name; ?>
]" value="<?php echo $this->_tpl_vars['value']->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?>
        
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php if ($this->_tpl_vars['oxpaymentid'] != "-1"): ?>
                <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_DELETE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='delpayment'"" <?php echo $this->_tpl_vars['readonly']; ?>
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