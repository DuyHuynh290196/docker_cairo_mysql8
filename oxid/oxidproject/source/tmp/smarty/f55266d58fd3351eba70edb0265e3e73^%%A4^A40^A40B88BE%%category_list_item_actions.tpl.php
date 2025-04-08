<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:55
         compiled from include/category_list_item_actions.tpl */ ?>
<?php if ($this->_tpl_vars['listitem']->oxcategories__oxleft->value + 1 == $this->_tpl_vars['listitem']->oxcategories__oxright->value): ?>
    <a href="Javascript:top.oxid.admin.deleteThis('<?php echo $this->_tpl_vars['listitem']->oxcategories__oxid->value; ?>
');" class="delete" id="del.<?php echo $this->_tpl_vars['_cnt']; ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'item_delete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></a>
<?php endif; ?>