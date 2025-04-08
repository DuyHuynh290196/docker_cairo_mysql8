<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from widget/product/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'widget/product/details.tpl', 5, false),array('insert', 'oxid_tracker', 'widget/product/details.tpl', 5, false),array('function', 'oxscript', 'widget/product/details.tpl', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/details.tpl", 'smarty_include_vars' => array('blWorkaroundInclude' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('oDetailsProduct', $this->_tpl_vars['oView']->getProduct()); ?>

<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'oxid_tracker', 'title' => ((is_array($_tmp='PRODUCT_DETAILS')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)), 'product' => $this->_tpl_vars['oDetailsProduct'], 'cpath' => $this->_tpl_vars['oView']->getCatTreePath())), $this); ?>

<?php echo smarty_function_oxscript(array('widget' => $this->_tpl_vars['oView']->getClassName()), $this);?>
