<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:50
         compiled from discount_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'discount_main.tpl', 1, false),array('modifier', 'oxformdate', 'discount_main.tpl', 92, false),array('function', 'oxmultilang', 'discount_main.tpl', 61, false),array('function', 'oxinputhelp', 'discount_main.tpl', 65, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
function ChangeDiscountType(oObj)
{   var oHObj = document.getElementById("itmart");
    var oDObj = document.getElementById("editval[oxdiscount__oxaddsum]");
    if ( oDObj != null && oHObj != null && oObj != null)
    {   if ( oObj.value == "itm")
        {   oHObj.style.display = "";
            oDObj.style.display = "none";
        }
        else
        {   oHObj.style.display = "none";
            oDObj.style.display = "";
        }
    }
}
window.onload = function ()
{
    <?php if ($this->_tpl_vars['updatelist'] == 1): ?>
    top.oxid.admin.updateList('<?php echo $this->_tpl_vars['oxid']; ?>
');
    <?php endif; ?>
    var oField = top.oxid.admin.getLockTarget();
    top.oxid.admin.unlockSave();
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
    <input type="hidden" name="cl" value="discount_main">
    <input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="discount_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxdiscount__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
            
                <tr>
                    <td class="edittext" width="120">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                    </td>
                    <td class="edittext" width="250">
                    <input type="text" class="editinput" size="50" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxtitle->fldmax_length; ?>
" name="editval[oxdiscount__oxtitle]" value="<?php if ($this->_tpl_vars['oxid'] == "-1"): ?><?php echo $this->_tpl_vars['discount_title']; ?>
<?php else: ?><?php echo $this->_tpl_vars['edit']->oxdiscount__oxtitle->value; ?>
<?php endif; ?>" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_NAME'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        <?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_SORT'), $this);?>

                    </td>
                    <td class="edittext" colspan="2">
                        <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxsort->fldmax_length; ?>
" id="oLockTarget" name="editval[oxdiscount__oxsort]" value="<?php if ($this->_tpl_vars['oxid'] == "-1"): ?><?php echo $this->_tpl_vars['oView']->getNextOxsort(); ?>
<?php else: ?><?php echo $this->_tpl_vars['edit']->oxdiscount__oxsort->value; ?>
<?php endif; ?>" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DISCOUNT_MAIN_SORT'), $this);?>

                    </td>
                </tr>
                <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <tr>
                    <td class="edittext" width="120">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ALWAYS_ACTIVE'), $this);?>

                    </td>
                    <td class="edittext">
                    <input class="edittext" type="checkbox" name="editval[oxdiscount__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxdiscount__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVFROMTILL'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="27" name="editval[oxdiscount__oxactivefrom]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdiscount__oxactivefrom)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_vonbis')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_AFROM'), $this);?>
<br>
                    <input type="text" class="editinput" size="27" name="editval[oxdiscount__oxactiveto]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdiscount__oxactiveto)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_vonbis')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_ATILL'), $this);?>

                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVFROMTILL'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_AMOUNT'), $this);?>

                    </td>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FROM'), $this);?>
 <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxamount->fldmax_length; ?>
" name="editval[oxdiscount__oxamount]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxamount->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TILL'), $this);?>
 <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxamountto->fldmax_length; ?>
" name="editval[oxdiscount__oxamountto]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxamountto->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DISCOUNT_MAIN_AMOUNT'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_PRICE'), $this);?>
 (<?php echo $this->_tpl_vars['oActCur']->sign; ?>
)
                    </td>
                    <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FROM'), $this);?>
 <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxprice->fldmax_length; ?>
" name="editval[oxdiscount__oxprice]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxprice->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TILL'), $this);?>
 <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxpriceto->fldmax_length; ?>
" name="editval[oxdiscount__oxpriceto]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxpriceto->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DISCOUNT_MAIN_PRICE'), $this);?>

                    </td>
                </tr>
                <tr>
                    <td class="edittext" height="30">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_REBATE'), $this);?>

                    </td>
                    <td class="edittext">
                    <input type="text" class="editinput" size="15" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxaddsum->fldmax_length; ?>
" name="editval[oxdiscount__oxaddsum]" id="editval[oxdiscount__oxaddsum]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxaddsum->value; ?>
" <?php if ($this->_tpl_vars['edit']->oxdiscount__oxaddsumtype->value == 'itm'): ?> style="display:none;"<?php endif; ?><?php echo $this->_tpl_vars['readonly']; ?>
>
                        <select name="editval[oxdiscount__oxaddsumtype]" class="editinput" onChange="ChangeDiscountType(this);" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['sumtype']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sum']):
?>
                        <option value="<?php echo $this->_tpl_vars['sum']; ?>
" <?php if ($this->_tpl_vars['sum'] == $this->_tpl_vars['edit']->oxdiscount__oxaddsumtype->value): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['sum']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                        </select>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DISCOUNT_MAIN_REBATE'), $this);?>

                    </td>
                </tr>
                <tr id="itmart"<?php if ($this->_tpl_vars['edit']->oxdiscount__oxaddsumtype->value != 'itm'): ?> style="display:none;"<?php endif; ?>>
                  <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_EXTRA'), $this);?>

                  </td>
                  <td class="edittext">
                    <table>
                        
                          <tr>
                            <td><?php echo $this->_tpl_vars['oView']->getItemDiscountProductTitle(); ?>
</td>
                            <td>
                              <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_CHANGEPRODUCT'), $this);?>
" class="edittext" onclick="showDialog('&cl=discount_main&aoc=2&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');">
                              <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DISCOUNT_MAIN_EXTRA'), $this);?>

                            </td>
                          </tr>
                          <tr>
                            <td><?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_MULTIPLY_DISCOUNT_AMOUNT'), $this);?>
</td>
                            <td><input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxitmamount->fldmax_length; ?>
" name="editval[oxdiscount__oxitmamount]" value="<?php echo $this->_tpl_vars['edit']->oxdiscount__oxitmamount->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
></td>
                          </tr>
                          <tr>
                            <td><?php echo smarty_function_oxmultilang(array('ident' => 'DISCOUNT_MAIN_MULTIPLY_DISCOUNT_ARTICLES'), $this);?>
</td>
                            <td>
                              <input type="hidden" name="editval[oxdiscount__oxitmmultiple]" value="0">
                              <input class="edittext" type="checkbox" name="editval[oxdiscount__oxitmmultiple]" value='1' <?php if ($this->_tpl_vars['edit']->oxdiscount__oxitmmultiple->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                            </td>
                          </tr>
                        
                    </table>
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
        <?php endif; ?>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                <input type="submit" class="edittext" id="oLockButton" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
 <?php if (! $this->_tpl_vars['edit']->oxdiscount__oxsort->value): ?>disabled<?php endif; ?>><br>
            </td>
        </tr>
        </table>
    </td>
    <td valign="top" width="50%">
        
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNCOUNTRIES'), $this);?>
" class="edittext" onclick="showDialog('&cl=discount_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
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