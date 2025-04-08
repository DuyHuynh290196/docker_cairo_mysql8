<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/order_remark.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'form/fieldset/order_remark.tpl', 3, false),)), $this); ?>
<?php if ($this->_tpl_vars['blOrderRemark']): ?>
    <div class="form-group">
        <label for="orderRemark" class="control-label col-lg-3"><?php echo smarty_function_oxmultilang(array('ident' => 'WHAT_I_WANTED_TO_SAY'), $this);?>
</label>
        <div class="col-lg-9">
            <textarea id="orderRemark" rows="7" name="order_remark" class="form-control" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'HERE_YOU_CAN_ENETER_MESSAGE'), $this);?>
"><?php echo $this->_tpl_vars['oView']->getOrderRemark(); ?>
</textarea>
        </div>
    </div>
<?php endif; ?>