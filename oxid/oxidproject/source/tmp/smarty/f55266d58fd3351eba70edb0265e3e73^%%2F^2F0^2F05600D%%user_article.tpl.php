<?php /* Smarty version 2.6.33, created on 2025-04-03 08:57:10
         compiled from user_article.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'user_article.tpl', 1, false),array('modifier', 'oxtruncate', 'user_article.tpl', 34, false),array('modifier', 'strip_tags', 'user_article.tpl', 34, false),array('function', 'oxmultilang', 'user_article.tpl', 22, false),)), $this); ?>
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
    <input type="hidden" name="cl" value="user_article">
</form>

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<form name="search" id="search" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="cl" value="article_main">
    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="fnc" value="">
<tr>
    <td class="listheader first"><?php echo smarty_function_oxmultilang(array('ident' => 'USER_ARTICLE_QUANTITY'), $this);?>
</td>
    <td class="listheader" height="15">&nbsp;&nbsp;&nbsp;<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ITEMNR'), $this);?>
</td>
    <td class="listheader">&nbsp;&nbsp;&nbsp;<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TITLE'), $this);?>
</td>
    <td class="listheader">&nbsp;&nbsp;&nbsp;<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TYPE'), $this);?>
</td>
    <td class="listheader">&nbsp;&nbsp;&nbsp;<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SHORTDESC'), $this);?>
</td>
</tr>
<?php $this->assign('blWhite', ""); ?>
<?php $_from = $this->_tpl_vars['oArticlelist']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listitem']):
?>
<tr>
    <?php $this->assign('listclass', "listitem".($this->_tpl_vars['blWhite'])); ?>
    <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['listitem']->oxorderarticles__oxamount->value; ?>
</td>
    <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
" height="15"><?php echo $this->_tpl_vars['listitem']->oxorderarticles__oxartnum->value; ?>
</td>
    <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['listitem']->oxorderarticles__oxtitle->value)) ? $this->_run_mod_handler('oxtruncate', true, $_tmp, 30, "") : smarty_modifier_oxtruncate($_tmp, 30, "")))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
    <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['listitem']->oxorderarticles__oxselvariant->value; ?>
</td>
    <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['listitem']->oxorderarticles__oxshortdesc->value)) ? $this->_run_mod_handler('oxtruncate', true, $_tmp, 30, "") : smarty_modifier_oxtruncate($_tmp, 30, "")))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
</td>
</tr>
<?php if ($this->_tpl_vars['blWhite'] == '2'): ?>
<?php $this->assign('blWhite', ""); ?>
<?php else: ?>
<?php $this->assign('blWhite', '2'); ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</form>
</table>


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