<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/user_checkout_change.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxscript', 'form/user_checkout_change.tpl', 1, false),array('function', 'oxgetseourl', 'form/user_checkout_change.tpl', 39, false),array('function', 'oxmultilang', 'form/user_checkout_change.tpl', 39, false),array('modifier', 'count', 'form/user_checkout_change.tpl', 62, false),)), $this); ?>
<?php echo smarty_function_oxscript(array('include' => "js/libs/jqBootstrapValidation.min.js",'priority' => 10), $this);?>


<?php ob_start(); ?>
    <?php echo '$(\'input,select,textarea\').not(\'[type=submit]\').jqBootstrapValidation({filter: function(){if( $( \'#shippingAddress\' ).css( \'display\' ) == \'block\' ){return $(this).is(\':visible, .selectpicker\');}else{return $(this).is(\':visible, #addressForm .selectpicker\');}}});'; ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('sValidationJS', ob_get_contents());ob_end_clean(); ?>

<?php echo smarty_function_oxscript(array('add' => $this->_tpl_vars['sValidationJS']), $this);?>



    <form class="form-horizontal" action="<?php echo $this->_tpl_vars['oViewConf']->getSslSelfLink(); ?>
" name="order" method="post" novalidate="novalidate">
        
            <?php $this->assign('aErrors', $this->_tpl_vars['oView']->getFieldValidationErrors()); ?>
            <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

            <?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

            <input type="hidden" name="cl" value="user">
            <input type="hidden" name="option" value="<?php echo $this->_tpl_vars['oView']->getLoginOption(); ?>
">
            <input type="hidden" name="fnc" value="changeuser">
            <input type="hidden" name="lgn_cook" value="0">
            <input type="hidden" name="blshowshipaddress" value="1">

            
                <div class="well well-sm cart-buttons">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getBasketLink()), $this);?>
" class="btn btn-default pull-left prevStep submitButton largeButton" id="userBackStepTop"><i class="fa fa-caret-left"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS_STEP'), $this);?>
</a>
                    <button id="userNextStepTop" class="btn btn-primary pull-right submitButton largeButton nextStep" name="userform" type="submit"><?php echo smarty_function_oxmultilang(array('ident' => 'CONTINUE_TO_NEXT_STEP'), $this);?>
 <i class="fa fa-caret-right"></i></button>
                    <div class="clearfix"></div>
                </div>
            

            <div class="checkoutCollumns clear">
                <div class="panel panel-default">
                    
                        <div class="panel-heading">
                            
                                <h3 class="panel-title">
                                    <?php echo smarty_function_oxmultilang(array('ident' => 'BILLING_ADDRESS'), $this);?>

                                    <button id="userChangeAddress" class="btn btn-xs btn-warning pull-right submitButton largeButton" name="changeBillAddress" type="submit" title="<?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE'), $this);?>
">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </h3>
                                <?php echo smarty_function_oxscript(array('add' => "$('#userChangeAddress').click( function() { $('#addressForm').show();$('#addressText').hide();$('#userChangeAddress').hide();return false;});"), $this);?>

                            
                        </div>
                    
                    <div class="panel-body">
                        
                            <div <?php if (! count($this->_tpl_vars['aErrors'])): ?>style="display: none;"<?php endif; ?> id="addressForm">
                                <?php if (! $this->_tpl_vars['oxcmp_user']->oxuser__oxpassword->value): ?>
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/user_email.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                <?php endif; ?>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/user_billing.tpl", 'smarty_include_vars' => array('noFormSubmit' => true,'blSubscribeNews' => true,'blOrderRemark' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            </div>
                        
                        
                            <div class="col-lg-9 col-lg-offset-3" id="addressText">
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/address/billing_address.tpl", 'smarty_include_vars' => array('noFormSubmit' => true,'blSubscribeNews' => true,'blOrderRemark' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            </div>
                        
                    </div>
                </div>

                <div class="panel panel-default">
                    
                        <div class="panel-heading">
                            
                                <h3 class="panel-title">
                                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHIPPING_ADDRESS'), $this);?>

                                    <?php if ($this->_tpl_vars['oView']->showShipAddress() && $this->_tpl_vars['oxcmp_user']->getSelectedAddress()): ?>
                                        <button id="userChangeShippingAddress" class="btn btn-xs btn-warning pull-right submitButton largeButton" name="changeShippingAddress" type="submit" <?php if (! $this->_tpl_vars['oView']->showShipAddress() && $this->_tpl_vars['oxcmp_user']->getSelectedAddress()): ?> style="display: none;" <?php endif; ?> title="<?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE'), $this);?>
">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    <?php endif; ?>
                                </h3>
                                <?php echo smarty_function_oxscript(array('add' => "$('#userChangeShippingAddress').toggle($(this).is(':not(:checked)'));"), $this);?>

                            
                        </div>
                    
                    <div class="panel-body">
                        
                            <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                    <div class="checkbox">
                                        <label for="showShipAddress">
                                            <input type="checkbox" name="blshowshipaddress" id="showShipAddress" <?php if (! $this->_tpl_vars['oView']->showShipAddress()): ?>checked<?php endif; ?> value="0"> <?php echo smarty_function_oxmultilang(array('ident' => 'USE_BILLINGADDRESS_FOR_SHIPPINGADDRESS'), $this);?>

                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php echo smarty_function_oxscript(array('add' => "$('#showShipAddress').change( function() { $('#shippingAddress').toggle($(this).is(':not(:checked)'));});"), $this);?>

                        
                        
                            <div id="shippingAddress" <?php if (! $this->_tpl_vars['oView']->showShipAddress()): ?>style="display: none;"<?php endif; ?>>
                                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/user_shipping.tpl", 'smarty_include_vars' => array('noFormSubmit' => true,'onChangeClass' => 'user')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            </div>
                        
                        
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/order_newsletter.tpl", 'smarty_include_vars' => array('blSubscribeNews' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/order_remark.tpl", 'smarty_include_vars' => array('blOrderRemark' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        
                    </div>
                </div>
            </div>

            
                <div class="well well-sm cart-buttons">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getBasketLink()), $this);?>
" class="btn btn-default pull-left prevStep submitButton largeButton" id="userBackStepBottom"><i class="fa fa-caret-left"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS_STEP'), $this);?>
</a>
                    <button id="userNextStepBottom" class="btn btn-primary pull-right submitButton largeButton nextStep" name="userform" type="submit"><?php echo smarty_function_oxmultilang(array('ident' => 'CONTINUE_TO_NEXT_STEP'), $this);?>
 <i class="fa fa-caret-right"></i></button>
                    <div class="clearfix"></div>
                </div>
            
        
    </form>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/delete_shipping_address_modal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
