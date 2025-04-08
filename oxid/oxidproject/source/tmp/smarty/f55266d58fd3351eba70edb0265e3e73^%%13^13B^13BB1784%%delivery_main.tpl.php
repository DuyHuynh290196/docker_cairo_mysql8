<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:53
         compiled from delivery_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'delivery_main.tpl', 1, false),array('modifier', 'oxformdate', 'delivery_main.tpl', 57, false),array('function', 'oxmultilang', 'delivery_main.tpl', 35, false),array('function', 'oxinputhelp', 'delivery_main.tpl', 39, false),)), $this); ?>
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
    <input type="hidden" name="oxidCopy" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="delivery_main">
    <input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="delivery_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxdelivery__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>
    <td width="15"></td>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="140">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                </td>
                <td class="edittext" width="250">
                <input type="text" class="editinput" size="50" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxtitle->fldmax_length; ?>
" name="editval[oxdelivery__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxtitle->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_NAME'), $this);?>

                </td>
            </tr>
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ALWAYS_ACTIVE'), $this);?>

                </td>
                <td class="edittext">
                <input class="edittext" type="checkbox" name="editval[oxdelivery__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxdelivery__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVFROMTILL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="27" name="editval[oxdelivery__oxactivefrom]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdelivery__oxactivefrom)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_vonbis')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>(<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FROM'), $this);?>
)<br>
                <input type="text" class="editinput" size="27" name="editval[oxdelivery__oxactiveto]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdelivery__oxactiveto)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_vonbis')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>(<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TILL'), $this);?>
)
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVFROMTILL'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_CONDITION'), $this);?>

                </td>
                <td class="edittext" nowrap>
                    <select name="editval[oxdelivery__oxdeltype]" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php $_from = $this->_tpl_vars['deltypes']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['deltyp']):
?>
                    <option value="<?php echo $this->_tpl_vars['deltyp']->sType; ?>
" <?php if ($this->_tpl_vars['deltyp']->selected): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['deltyp']->sDesc; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
                    &gt;=
                    <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparam->fldmax_length; ?>
" name="editval[oxdelivery__oxparam]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparam->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                     <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_AND'), $this);?>
&lt;= <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparamend->fldmax_length; ?>
" name="editval[oxdelivery__oxparamend]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparamend->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                     <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERY_MAIN_CONDITION'), $this);?>

                </td>
            </tr>
            <!--tr>
                <td class="edittext" height="30">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_CONDITION'), $this);?>

                </td>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_PARAM'), $this);?>
 > <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparam->fldmax_length; ?>
" name="editval[oxdelivery__oxparam]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxparam->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                </td>
            </tr-->
            <tr>
                <td class="edittext" height="30">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_PRICE'), $this);?>
 (<?php echo $this->_tpl_vars['oActCur']->sign; ?>
)
                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxaddsum->fldmax_length; ?>
" name="editval[oxdelivery__oxaddsum]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxaddsum->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <select name="editval[oxdelivery__oxaddsumtype]" class="editinput" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'addsumtype')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php $_from = $this->_tpl_vars['sumtype']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sum']):
?>
                    <option value="<?php echo $this->_tpl_vars['sum']; ?>
" <?php if ($this->_tpl_vars['sum'] == $this->_tpl_vars['edit']->oxdelivery__oxaddsumtype->value): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['sum']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERY_MAIN_PRICE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_COUNTRULES'), $this);?>

                </td>
                <td class="edittext">
                <input name="editval[oxdelivery__oxfixed]" value='0' type="radio" <?php if ($this->_tpl_vars['edit']->oxdelivery__oxfixed->value == 0 || ! $this->_tpl_vars['edit']->oxdelivery__oxfixed->value): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_ONETIMEPERWK'), $this);?>
<br>
                <input name="editval[oxdelivery__oxfixed]" value='1' type="radio" <?php if ($this->_tpl_vars['edit']->oxdelivery__oxfixed->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_ONETIMEPERITEM'), $this);?>
<br>
                <input name="editval[oxdelivery__oxfixed]" value='2' type="radio" <?php if ($this->_tpl_vars['edit']->oxdelivery__oxfixed->value == 2): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_ONETIMEPERITEMINWK'), $this);?>

                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERY_MAIN_COUNTRULES'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_ORDER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="23" maxlength="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxsort->fldmax_length; ?>
" name="editval[oxdelivery__oxsort]" value="<?php echo $this->_tpl_vars['edit']->oxdelivery__oxsort->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERY_MAIN_ORDER'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext wrap">
                <?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERY_MAIN_FINALIZE'), $this);?>

                </td>
                <td class="edittext">
                <input class="edittext" type="checkbox" name="editval[oxdelivery__oxfinalize]" value='1' <?php if ($this->_tpl_vars['edit']->oxdelivery__oxfinalize->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERY_MAIN_FINALIZE'), $this);?>

                </td>
            </tr>
        
        <tr><td colspan="2">&nbsp;</td></tr>
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
        <?php endif; ?>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
><br>
            </td>
        </tr>
        </table>
    </td>
    <td valign="top" width="50%">
        
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNCOUNTRIES'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=delivery_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');">
            <?php endif; ?>
        
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