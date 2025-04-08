<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from message/errors.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'is_array', 'message/errors.tpl', 1, false),)), $this); ?>
<?php if (((is_array($_tmp=$this->_tpl_vars['Errors'])) ? $this->_run_mod_handler('is_array', true, $_tmp) : is_array($_tmp)) && ((is_array($_tmp=$this->_tpl_vars['Errors']['default'])) ? $this->_run_mod_handler('is_array', true, $_tmp) : is_array($_tmp)) && ! empty ( $this->_tpl_vars['Errors']['default'] )): ?>
    <?php $_from = $this->_tpl_vars['Errors']['default']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['oEr']):
?>
        <p class="alert alert-danger"><?php echo $this->_tpl_vars['oEr']->getOxMessage(); ?>
</p>
    <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
<?php if (((is_array($_tmp=$this->_tpl_vars['Errors']['popup'])) ? $this->_run_mod_handler('is_array', true, $_tmp) : is_array($_tmp)) && ! empty ( $this->_tpl_vars['Errors']['popup'] )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/errors_modal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>