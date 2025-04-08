<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:24
         compiled from attribute_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'attribute_main.tpl', 1, false),array('function', 'oxmultilang', 'attribute_main.tpl', 31, false),array('function', 'oxinputhelp', 'attribute_main.tpl', 35, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="attribute_main">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="attribute_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxattribute__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="20" maxlength="<?php echo $this->_tpl_vars['edit']->oxattribute__oxtitle->fldmax_length; ?>
" name="editval[oxattribute__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxattribute__oxtitle->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_NAME'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'ATTRIBUTE_MAIN_SORTING'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="20" maxlength="<?php echo $this->_tpl_vars['edit']->oxattribute__oxpos->fldmax_length; ?>
" name="editval[oxattribute__oxpos]" value="<?php echo $this->_tpl_vars['edit']->oxattribute__oxpos->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_ATTRIBUTE_MAIN_SORTING'), $this);?>

                </td>
            </tr>
            
            <tr>
                <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'ATTRIBUTE_MAIN_DISPLAYINBASKET'), $this);?>

                </td>
                <td class="edittext">
                       <input type="hidden" name="editval[oxattribute__oxdisplayinbasket]" value='0' <?php echo $this->_tpl_vars['readonly']; ?>
>
                       <input class="edittext" type="checkbox" name="editval[oxattribute__oxdisplayinbasket]" value='1' <?php if ($this->_tpl_vars['edit']->oxattribute__oxdisplayinbasket->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_ATTRIBUTE_MAIN_DISPLAYINBASKET'), $this);?>

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
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
        
            <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
                <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNARTICLES'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=attribute_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');">
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