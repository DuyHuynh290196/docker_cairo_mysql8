<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:11
         compiled from user_remark.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'user_remark.tpl', 1, false),array('modifier', 'oxformdate', 'user_remark.tpl', 29, false),array('function', 'oxmultilang', 'user_remark.tpl', 32, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="user_remark">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

<input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveClassName(); ?>
">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxuser__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="rem_oxid" value="<?php echo $this->_tpl_vars['rem_oxid']; ?>
">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">
        
            <select name="rem_oxid" size="17" class="editinput" style="width:180px;" onChange="Javascript:document.myedit.submit();" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php $_from = $this->_tpl_vars['allremark']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['allitem']):
?>
            <option value="<?php echo $this->_tpl_vars['allitem']->oxremark__oxid->value; ?>
" <?php if ($this->_tpl_vars['allitem']->selected): ?>SELECTED<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['allitem']->oxremark__oxheader)) ? $this->_run_mod_handler('oxformdate', true, $_tmp, 'datetime') : smarty_modifier_oxformdate($_tmp, 'datetime')); ?>

            
            
                <?php if ($this->_tpl_vars['allitem']->oxremark__oxtype->value == 'r'): ?><?php echo smarty_function_oxmultilang(array('ident' => 'USER_REMARK_REMARK'), $this);?>
<?php elseif ($this->_tpl_vars['allitem']->oxremark__oxtype->value == 'o'): ?><?php echo smarty_function_oxmultilang(array('ident' => 'USER_REMARK_ORDER'), $this);?>
<?php elseif ($this->_tpl_vars['allitem']->oxremark__oxtype->value == 'c'): ?><?php echo smarty_function_oxmultilang(array('ident' => 'USER_REMARK_USER'), $this);?>
<?php else: ?><?php echo smarty_function_oxmultilang(array('ident' => 'USER_REMARK_NEWS'), $this);?>
<?php endif; ?>
            
            
            </option>
            <?php endforeach; endif; unset($_from); ?>
            </select><br><br>
        
        <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
        <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_DELETE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='delete'"" <?php echo $this->_tpl_vars['readonly']; ?>
><br>

    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left">
    
        <input type="text" class="editinput" size="100" maxlength="128" value="<?php if ($this->_tpl_vars['remarkheader']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['remarkheader'])) ? $this->_run_mod_handler('oxformdate', true, $_tmp, 'datetime', true) : smarty_modifier_oxformdate($_tmp, 'datetime', true)); ?>
<?php endif; ?>" readonly disabled><br>
        <input type="hidden" name="remarkheader" value="<?php echo $this->_tpl_vars['remarkheader']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
        <textarea class="editinput" cols="100" rows="17" wrap="VIRTUAL" name="remarktext" <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['remarktext']; ?>
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