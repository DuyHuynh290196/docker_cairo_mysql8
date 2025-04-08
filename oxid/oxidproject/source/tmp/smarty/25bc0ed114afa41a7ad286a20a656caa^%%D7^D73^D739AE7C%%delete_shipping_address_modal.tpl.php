<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/delete_shipping_address_modal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'form/fieldset/delete_shipping_address_modal.tpl', 18, false),)), $this); ?>

    <?php if ($this->_tpl_vars['oxcmp_user']): ?>
        <?php $this->assign('userAddresses', $this->_tpl_vars['oxcmp_user']->getUserAddresses()); ?>
    <?php endif; ?>
    <?php $_from = $this->_tpl_vars['userAddresses']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['shippingAdresses'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shippingAdresses']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['shippingAddress']):
        $this->_foreach['shippingAdresses']['iteration']++;
?>
    <?php $this->assign('shippingAddressNumber', $this->_foreach['shippingAdresses']['iteration']); ?>
        <div class="modal fade"
             id="delete_shipping_address_<?php echo $this->_tpl_vars['shippingAddressNumber']; ?>
"
             tabindex="-1"
             role="dialog"
             aria-labelledby="delete_shipping_address_modal_label_<?php echo $this->_tpl_vars['shippingAddressNumber']; ?>
"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <span class="h4 modal-title" id="delete_shipping_address_modal_label_<?php echo $this->_tpl_vars['shippingAddressNumber']; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'DD_DELETE_SHIPPING_ADDRESS'), $this);?>
</span>
                        
                    </div>
                    <div class="modal-body">
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <form name="delete_shipping_address_modal_form_<?php echo $this->_tpl_vars['shippingAddressNumber']; ?>
"
                                          action="<?php echo $this->_tpl_vars['oViewConf']->getSelfActionLink(); ?>
"
                                          method="post">
                                        <div class="hidden">
                                            <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

                                            <input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveClassName(); ?>
">
                                            <input type="hidden" name="fnc" value="deleteShippingAddress">
                                            <input type="hidden" name="oxaddressid"
                                                   value="<?php echo $this->_tpl_vars['shippingAddress']->oxaddress__oxid->value; ?>
">
                                        </div>
                                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/address/shipping_address.tpl", 'smarty_include_vars' => array('delivadr' => $this->_tpl_vars['shippingAddress'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                    </form>
                                </div>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo smarty_function_oxmultilang(array('ident' => 'CANCEL'), $this);?>

                            </button>
                            <button type="button"
                                    class="btn btn-danger"
                                    onclick="window.delete_shipping_address_modal_form_<?php echo $this->_tpl_vars['shippingAddressNumber']; ?>
.submit();return false;">
                                <?php echo smarty_function_oxmultilang(array('ident' => 'DD_DELETE'), $this);?>

                            </button>
                        
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; endif; unset($_from); ?>



