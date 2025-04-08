<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:53
         compiled from deliveryset_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'deliveryset_main.tpl', 1, false),array('modifier', 'oxformdate', 'deliveryset_main.tpl', 57, false),array('function', 'oxmultilang', 'deliveryset_main.tpl', 35, false),array('function', 'oxinputhelp', 'deliveryset_main.tpl', 39, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="deliveryset_main">
    <input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">
</form>


<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="deliveryset_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxdeliveryset__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="language" value="<?php echo $this->_tpl_vars['actlang']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>

    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="140">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                </td>
                <td class="edittext" width="250">
                <input type="text" class="editinput" size="50" maxlength="<?php echo $this->_tpl_vars['edit']->oxdeliveryset__oxtitle->fldmax_length; ?>
" name="editval[oxdeliveryset__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxdeliveryset__oxtitle->value; ?>
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
                <input class="edittext" type="checkbox" name="editval[oxdeliveryset__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxdeliveryset__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVFROMTILL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="27" name="editval[oxdeliveryset__oxactivefrom]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdeliveryset__oxactivefrom)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_vonbis')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>(<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FROM'), $this);?>
)<br>
                <input type="text" class="editinput" size="27" name="editval[oxdeliveryset__oxactiveto]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxdeliveryset__oxactiveto)) ? $this->_run_mod_handler('oxformdate', true, $_tmp) : smarty_modifier_oxformdate($_tmp)); ?>
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
                <td class="edittext" width="140">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SORT'), $this);?>

                </td>
                <td class="edittext" width="250">
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxdeliveryset__oxpos->fldmax_length; ?>
" name="editval[oxdeliveryset__oxpos]" value="<?php echo $this->_tpl_vars['edit']->oxdeliveryset__oxpos->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_DELIVERYSET_MAIN_POS'), $this);?>

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
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERYSET_MAIN_ASSIGNDELIVERY'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=deliveryset_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');"><br>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNCOUNTRIES'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=deliveryset_payment&aoc=2&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
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