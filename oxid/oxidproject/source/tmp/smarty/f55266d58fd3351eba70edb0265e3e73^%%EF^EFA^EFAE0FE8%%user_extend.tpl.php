<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:10
         compiled from user_extend.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'user_extend.tpl', 1, false),array('modifier', 'oxmultilangsal', 'user_extend.tpl', 107, false),array('function', 'oxmultilang', 'user_extend.tpl', 30, false),array('function', 'oxinputhelp', 'user_extend.tpl', 34, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="user_extend">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="user_extend">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxuser__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
<tr>
    <td width="15"></td>
    <td valign="top" class="edittext">
        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
                <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_PRIVATFON'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxuser__oxprivfon->fldmax_length; ?>
" name="editval[oxuser__oxprivfon]" value="<?php echo $this->_tpl_vars['edit']->oxuser__oxprivfon->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_EXTEND_PRIVATFON'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_MOBILFON'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxuser__oxmobfon->fldmax_length; ?>
" name="editval[oxuser__oxmobfon]" value="<?php echo $this->_tpl_vars['edit']->oxuser__oxmobfon->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_EXTEND_MOBILFON'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_NEWSLETTER'), $this);?>

                </td>
                <td class="edittext">
                    <input type="hidden" name="editnews" value='0'>
                    <input class="edittext" type="checkbox" name="editnews" value='1' <?php if ($this->_tpl_vars['edit']->sDBOptin == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_EXTEND_NEWSLETTER'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_EMAILFAILED'), $this);?>

                </td>
                <td class="edittext">
                    <input type="hidden" name="emailfailed" value='0'>
                    <input class="edittext" type="checkbox" name="emailfailed" value='1' <?php if ($this->_tpl_vars['edit']->sEmailFailed == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_EXTEND_EMAILFAILED'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_BONI'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxuser__oxboni->fldmax_length; ?>
" name="editval[oxuser__oxboni]" value="<?php echo $this->_tpl_vars['edit']->oxuser__oxboni->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_USER_EXTEND_BONI'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_URL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxuser__oxurl->fldmax_length; ?>
" name="editval[oxuser__oxurl]" value="<?php echo $this->_tpl_vars['edit']->oxuser__oxurl->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_URL'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'USER_EXTEND_CREDITPOINTS'), $this);?>

                </td>
                <td class="edittext">
                <?php echo $this->_tpl_vars['edit']->oxuser__oxpoints->value; ?>

                </td>
            </tr>
        
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
            </td>
        </tr>
        </table>
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td valign="top" class="edittext">
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="copypastetext" id="test_userAddress">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxuser__oxsal->value)) ? $this->_run_mod_handler('oxmultilangsal', true, $_tmp) : smarty_modifier_oxmultilangsal($_tmp)); ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxfname->value; ?>
 <?php echo $this->_tpl_vars['edit']->oxuser__oxlname->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxcompany->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxstreet->value; ?>
 <?php echo $this->_tpl_vars['edit']->oxuser__oxstreetnr->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->getStateId(); ?>

            <?php echo $this->_tpl_vars['edit']->oxuser__oxzip->value; ?>
 <?php echo $this->_tpl_vars['edit']->oxuser__oxcity->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxaddinfo->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxcountry->value; ?>
<br>
            <?php echo $this->_tpl_vars['edit']->oxuser__oxfon->value; ?>

            </td>
        </tr>
        </table>
   </td>
    <!-- Anfang rechte Seite -->
   <td valign="top" class="edittext" align="left" width="50%">
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