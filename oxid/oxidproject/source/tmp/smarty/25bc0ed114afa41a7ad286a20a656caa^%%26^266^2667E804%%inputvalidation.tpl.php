<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from message/inputvalidation.tpl */ ?>
<?php $_from = $this->_tpl_vars['aErrors']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oError']):
?>
    <div class="alert alert-danger"><?php echo $this->_tpl_vars['oError']->getMessage(); ?>
</div>
<?php endforeach; endif; unset($_from); ?>