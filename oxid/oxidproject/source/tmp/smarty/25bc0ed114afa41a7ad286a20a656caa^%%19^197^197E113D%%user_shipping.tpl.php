<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/user_shipping.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'form/fieldset/user_shipping.tpl', 13, false),array('modifier', 'default', 'form/fieldset/user_shipping.tpl', 18, false),array('modifier', 'oxmultilangassign', 'form/fieldset/user_shipping.tpl', 123, false),array('function', 'oxscript', 'form/fieldset/user_shipping.tpl', 20, false),array('function', 'oxmultilang', 'form/fieldset/user_shipping.tpl', 36, false),)), $this); ?>
<?php if ($this->_tpl_vars['oxcmp_user']): ?>
    <?php $this->assign('delivadr', $this->_tpl_vars['oxcmp_user']->getSelectedAddress()); ?>
<?php endif; ?>

<?php if (! $this->_tpl_vars['deladr']): ?>
    <?php $this->assign('oConfig', $this->_tpl_vars['oView']->getConfig()); ?>
    <?php $this->assign('deladr', $this->_tpl_vars['oConfig']->getRequestParameter('deladr')); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['oxcmp_user']): ?>
    <?php $this->assign('aUserAddresses', $this->_tpl_vars['oxcmp_user']->getUserAddresses()); ?>

    <?php if (count($this->_tpl_vars['aUserAddresses']) > 0): ?>
        <?php if ($this->_tpl_vars['onChangeClass'] == 'user'): ?>
            <div class="col-lg-9 col-lg-offset-3">
        <?php endif; ?>

            <input type="hidden" class="hidden" name="changeClass" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['onChangeClass'])) ? $this->_run_mod_handler('default', true, $_tmp, 'account_user') : smarty_modifier_default($_tmp, 'account_user')); ?>
">

            <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxusershipingaddressselect.min.js",'priority' => 10), $this);?>

            <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxequalizer.min.js",'priority' => 10), $this);?>

            <?php echo smarty_function_oxscript(array('add' => "$( '.dd-add-delivery-address' ).click( function() {  $('.dd-available-addresses .dd-action').remove(); $( this ).find( 'label.btn' ).button('toggle'); } );"), $this);?>

            <?php echo smarty_function_oxscript(array('add' => "$( 'input[name=\"oxaddressid\"]' ).oxUserShipingAddressSelect();"), $this);?>

            <?php echo smarty_function_oxscript(array('add' => "$( window ).load( function() { if( !isMobileDevice() ) { oxEqualizer.equalHeight( $( '.dd-available-addresses .panel .panel-body' ) ); } } );"), $this);?>

            <?php echo smarty_function_oxscript(array('add' => "$( '.dd-edit-shipping-address' ).click(function(){ $( '#shippingAddressForm' ).show(); $( 'html, body' ).animate( { scrollTop: $( '#shippingAddressForm' ).offset().top - 80 }, 600 ); } );"), $this);?>


            
                <div class="row dd-available-addresses" data-toggle="buttons">
                    <?php $_from = $this->_tpl_vars['aUserAddresses']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['shippingAdresses'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shippingAdresses']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['address']):
        $this->_foreach['shippingAdresses']['iteration']++;
?>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    
                                    <?php if ($this->_tpl_vars['address']->isSelected()): ?>
                                        
                                        <button class="btn btn-warning btn-xs hasTooltip pull-right dd-action dd-edit-shipping-address" title="<?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE'), $this);?>
">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        
                                        
                                        <button class="btn btn-danger btn-xs hasTooltip pull-right dd-action dd-delete-shipping-address"
                                                title="<?php echo smarty_function_oxmultilang(array('ident' => 'DD_DELETE'), $this);?>
"
                                                data-toggle="modal"
                                                data-target="#delete_shipping_address_<?php echo $this->_foreach['shippingAdresses']['iteration']; ?>
">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        
                                    <?php endif; ?>
                                    
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/address/shipping_address.tpl", 'smarty_include_vars' => array('delivadr' => $this->_tpl_vars['address'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                </div>
                                <div class="panel-footer" >
                                    <label class="btn btn-default btn-block<?php if ($this->_tpl_vars['address']->isSelected()): ?> active<?php endif; ?>">
                                        <input type="radio" name="oxaddressid" value="<?php echo $this->_tpl_vars['address']->oxaddress__oxid->value; ?>
" class="hidden" autocomplete="off" <?php if ($this->_tpl_vars['address']->isSelected()): ?>checked<?php endif; ?>><i class="fa fa-check"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'DD_USER_SHIPPING_SELECT_ADDRESS'), $this);?>

                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; endif; unset($_from); ?>

                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="panel panel-default dd-add-delivery-address">
                            <div class="panel-body text-center">
                                <i class="fa fa-plus-circle"></i><br/>
                                <?php echo smarty_function_oxmultilang(array('ident' => 'DD_USER_SHIPPING_ADD_DELIVERY_ADDRESS'), $this);?>

                            </div>
                            <div class="panel-footer" >
                                <label class="btn btn-default btn-block">
                                    <input type="radio" name="oxaddressid" value="-1" class="hidden" autocomplete="off"><i class="fa fa-check"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'DD_USER_SHIPPING_SELECT_ADDRESS'), $this);?>

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            

        <?php if ($this->_tpl_vars['onChangeClass'] == 'user'): ?>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>
    <?php endif; ?>
<?php endif; ?>

<div id="shippingAddressForm" <?php if ($this->_tpl_vars['delivadr']): ?>style="display: none;"<?php endif; ?>>
    <div class="form-group">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxsal')): ?> req<?php endif; ?>" for="deladr_oxaddress__oxsal"><?php echo smarty_function_oxmultilang(array('ident' => 'TITLE'), $this);?>
</label>
        <div class="col-lg-2">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/salutation.tpl", 'smarty_include_vars' => array('name' => "deladr[oxaddress__oxsal]",'value' => $this->_tpl_vars['delivadr']->oxaddress__oxsal->value,'value2' => $this->_tpl_vars['deladr']['oxaddress__oxsal'],'class' => "form-control selectpicker",'id' => 'deladr_oxaddress__oxsal')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxfname']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfname')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'FIRST_NAME'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfname')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" maxlength="255" name="deladr[oxaddress__oxfname]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxfname'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxfname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxfname->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfname')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfname')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxfname'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <div class="help-block"></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxlname']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxlname')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'LAST_NAME'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxlname')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" maxlength="255" name="deladr[oxaddress__oxlname]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxlname'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxlname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxlname->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxlname')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxlname')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxlname'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <div class="help-block"></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxcompany']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcompany')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'COMPANY'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcompany')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" size="37" maxlength="255" name="deladr[oxaddress__oxcompany]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxcompany'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxcompany']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxcompany->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcompany')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcompany')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxcompany'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxaddinfo']): ?> oxInValid<?php endif; ?>">
        <?php $this->assign('_address_addinfo_tooltip', ((is_array($_tmp='FORM_FIELDSET_USER_SHIPPING_ADDITIONALINFO2_TOOLTIP')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
        <label <?php if ($this->_tpl_vars['_address_addinfo_tooltip']): ?>title="<?php echo $this->_tpl_vars['_address_addinfo_tooltip']; ?>
"<?php endif; ?> class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxaddinfo')): ?> req<?php endif; ?><?php if ($this->_tpl_vars['_address_addinfo_tooltip']): ?> tooltip<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'ADDITIONAL_INFO'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxaddinfo')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" size="37" maxlength="255" name="deladr[oxaddress__oxaddinfo]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxaddinfo'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxaddinfo']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxaddinfo->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxaddinfo')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxaddinfo')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxaddinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxstreet']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-xs-12 col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreet') || $this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreetnr')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'STREET_AND_STREETNO'), $this);?>
</label>
        <div class="col-xs-8 col-lg-6">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreet')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" data-fieldsize="pair-xsmall" maxlength="255" name="deladr[oxaddress__oxstreet]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxstreet'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxstreet']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxstreet->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreet')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-xs-4 col-lg-3">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreetnr')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" data-fieldsize="xsmall" maxlength="16" name="deladr[oxaddress__oxstreetnr]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxstreetnr'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxstreetnr']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxstreetnr->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreetnr')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-lg-offset-3 col-lg-9 col-xs-12">
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreet') || $this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxstreetnr')): ?>
                <div class="help-block"></div>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxstreet'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxzip'] || $this->_tpl_vars['aErrors']['oxaddress__oxcity']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-xs-12 col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxzip') || $this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcity')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'POSTAL_CODE_AND_CITY'), $this);?>
</label>
        <div class="col-xs-5 col-lg-3">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxzip')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" data-fieldsize="small" maxlength="50" name="deladr[oxaddress__oxzip]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxzip'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxzip']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxzip->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxzip')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-xs-7 col-lg-6">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcity')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" data-fieldsize="pair-small" maxlength="255" name="deladr[oxaddress__oxcity]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxcity'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxcity']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxcity->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcity')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-lg-offset-3 col-md-9 col-xs-12">
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxzip') || $this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcity')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxzip'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxcity'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <div class="help-block"></div>
            <?php endif; ?>
        </div>
    </div>
    
        <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxcountryid']): ?> oxInValid<?php endif; ?>">
            <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcountryid')): ?> req<?php endif; ?>" for="delCountrySelect"><?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY'), $this);?>
</label>
            <div class="col-lg-9">
                <select class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcountryid')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?> selectpicker" id="delCountrySelect" name="deladr[oxaddress__oxcountryid]"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcountryid')): ?> required=""<?php endif; ?>>
                    <option value="">-</option>
                    <?php $this->assign('blCountrySelected', false); ?>
                    <?php $_from = $this->_tpl_vars['oViewConf']->getCountryList(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['country_id'] => $this->_tpl_vars['country']):
?>
                        <?php $this->assign('sCountrySelect', ""); ?>
                        <?php if (! $this->_tpl_vars['blCountrySelected']): ?>
                            <?php if (( isset ( $this->_tpl_vars['deladr']['oxaddress__oxcountryid'] ) && $this->_tpl_vars['deladr']['oxaddress__oxcountryid'] == $this->_tpl_vars['country']->oxcountry__oxid->value ) || ( ! isset ( $this->_tpl_vars['deladr']['oxaddress__oxcountryid'] ) && ( $this->_tpl_vars['delivadr']->oxaddress__oxcountry->value == $this->_tpl_vars['country']->oxcountry__oxtitle->value || $this->_tpl_vars['delivadr']->oxaddress__oxcountry->value == $this->_tpl_vars['country']->oxcountry__oxid->value || $this->_tpl_vars['delivadr']->oxaddress__oxcountryid->value == $this->_tpl_vars['country']->oxcountry__oxid->value ) )): ?>
                                <?php $this->assign('blCountrySelected', true); ?>
                                <?php $this->assign('sCountrySelect', 'selected'); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option value="<?php echo $this->_tpl_vars['country']->oxcountry__oxid->value; ?>
" <?php echo $this->_tpl_vars['sCountrySelect']; ?>
><?php echo $this->_tpl_vars['country']->oxcountry__oxtitle->value; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
                <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxcountryid')): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxcountryid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <div class="help-block"></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3" for="<?php echo $this->_tpl_vars['countrySelectId']; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'DD_USER_SHIPPING_LABEL_STATE'), $this);?>
</label>
            <div class="col-lg-9">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/state.tpl", 'smarty_include_vars' => array('countrySelectId' => 'delCountrySelect','stateSelectName' => "deladr[oxaddress__oxstateid]",'selectedStateIdPrim' => $this->_tpl_vars['deladr']['oxaddress__oxstateid'],'selectedStateId' => $this->_tpl_vars['delivadr']->oxaddress__oxstateid->value,'class' => "form-control selectpicker")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <div class="help-block"></div>
            </div>
        </div>
    
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxfon']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfon')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'PHONE'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfon')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfon]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxfon'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxfon']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxfon->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfon')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfon')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxfon'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxaddress__oxfax']): ?> oxInValid<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfax')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'FAX'), $this);?>
</label>
        <div class="col-lg-9">
            <input class="form-control<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfax')): ?> js-oxValidate js-oxValidate_notEmpty<?php endif; ?>" type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfax]" value="<?php if (isset ( $this->_tpl_vars['deladr']['oxaddress__oxfax'] )): ?><?php echo $this->_tpl_vars['deladr']['oxaddress__oxfax']; ?>
<?php else: ?><?php echo $this->_tpl_vars['delivadr']->oxaddress__oxfax->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfax')): ?> required=""<?php endif; ?>>
            <?php if ($this->_tpl_vars['oView']->isFieldRequired('oxaddress__oxfax')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxaddress__oxfax'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
            <div class="help-block"></div>
        </div>
    </div>
</div>
<?php if (! $this->_tpl_vars['noFormSubmit']): ?>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9 col-xs-12">
            <p class="alert alert-info"><?php echo smarty_function_oxmultilang(array('ident' => 'COMPLETE_MARKED_FIELDS'), $this);?>
</p>
            <button id="accUserSaveBottom" type="submit" class="submitButton" name="save"><?php echo smarty_function_oxmultilang(array('ident' => 'SAVE'), $this);?>
</button>
        </div>
    </div>
<?php endif; ?>