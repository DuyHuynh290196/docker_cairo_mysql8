<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/user_billing.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'form/fieldset/user_billing.tpl', 7, false),array('modifier', 'oxmultilangassign', 'form/fieldset/user_billing.tpl', 67, false),array('modifier', 'cat', 'form/fieldset/user_billing.tpl', 199, false),array('function', 'oxmultilang', 'form/fieldset/user_billing.tpl', 33, false),)), $this); ?>
<?php $this->assign('invadr', $this->_tpl_vars['oView']->getInvoiceAddress()); ?>
<?php $this->assign('blBirthdayRequired', $this->_tpl_vars['oView']->isFieldRequired('oxuser__oxbirthdate')); ?>

<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['month'] )): ?>
    <?php $this->assign('iBirthdayMonth', $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['month']); ?>
<?php elseif ($this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value && $this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value != "0000-00-00"): ?>
    <?php $this->assign('iBirthdayMonth', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value)) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^([0-9]{4})[-]/", "") : smarty_modifier_regex_replace($_tmp, "/^([0-9]{4})[-]/", "")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[-]([0-9]{1,2})$/", "") : smarty_modifier_regex_replace($_tmp, "/[-]([0-9]{1,2})$/", ""))); ?>
<?php else: ?>
    <?php $this->assign('iBirthdayMonth', 0); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['day'] )): ?>
    <?php $this->assign('iBirthdayDay', $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['day']); ?>
<?php elseif ($this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value && $this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value != "0000-00-00"): ?>
    <?php $this->assign('iBirthdayDay', ((is_array($_tmp=$this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value)) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^([0-9]{4})[-]([0-9]{1,2})[-]/", "") : smarty_modifier_regex_replace($_tmp, "/^([0-9]{4})[-]([0-9]{1,2})[-]/", ""))); ?>
<?php else: ?>
    <?php $this->assign('iBirthdayDay', 0); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['year'] )): ?>
    <?php $this->assign('iBirthdayYear', $this->_tpl_vars['invadr']['oxuser__oxbirthdate']['year']); ?>
<?php elseif ($this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value && $this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value != "0000-00-00"): ?>
    <?php $this->assign('iBirthdayYear', ((is_array($_tmp=$this->_tpl_vars['oxcmp_user']->oxuser__oxbirthdate->value)) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[-]([0-9]{1,2})[-]([0-9]{1,2})$/", "") : smarty_modifier_regex_replace($_tmp, "/[-]([0-9]{1,2})[-]([0-9]{1,2})$/", ""))); ?>
<?php else: ?>
    <?php $this->assign('iBirthdayYear', 0); ?>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxsal'] )): ?>
    <?php $this->assign('oxuser__oxsal', $this->_tpl_vars['invadr']['oxuser__oxsal']); ?>
<?php else: ?>
    <?php $this->assign('oxuser__oxsal', $this->_tpl_vars['oxcmp_user']->oxuser__oxsal->value); ?>
<?php endif; ?>
<div class="form-group">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxsal')): ?> req<?php endif; ?>" for="invadr_oxuser__oxfname"><?php echo smarty_function_oxmultilang(array('ident' => 'TITLE'), $this);?>
</label>
    <div class="col-lg-9">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/salutation.tpl", 'smarty_include_vars' => array('name' => "invadr[oxuser__oxsal]",'value' => $this->_tpl_vars['oxuser__oxsal'],'class' => "form-control selectpicker",'id' => 'invadr_oxuser__oxfname')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxfname']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfname')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'FIRST_NAME'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxfname]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxfname'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxfname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfname->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfname')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxfname'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxlname']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxlname')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'LAST_NAME'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxlname]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxlname'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxlname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxlname->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxlname')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxlname'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxcompany']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcompany')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'COMPANY'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxcompany]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxcompany'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxcompany']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxcompany->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcompany')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxcompany'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxaddinfo']): ?> text-danger<?php endif; ?>">
    <?php $this->assign('_address_addinfo_tooltip', ((is_array($_tmp='FORM_FIELDSET_USER_BILLING_ADDITIONALINFO_TOOLTIP')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
    <label <?php if ($this->_tpl_vars['_address_addinfo_tooltip']): ?>title="<?php echo $this->_tpl_vars['_address_addinfo_tooltip']; ?>
"<?php endif; ?> class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxaddinfo')): ?> req<?php endif; ?><?php if ($this->_tpl_vars['_address_addinfo_tooltip']): ?> tooltip<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'ADDITIONAL_INFO'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxaddinfo]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxaddinfo'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxaddinfo']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxaddinfo->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxaddinfo')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxaddinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxstreet']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-xs-12 col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxstreet') || $this->_tpl_vars['oView']->isFieldRequired('oxuser__oxstreetnr')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'STREET_AND_STREETNO'), $this);?>
</label>
    <div class="col-xs-8 col-lg-6">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxstreet]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxstreet'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxstreet']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstreet->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxstreet')): ?> required=""<?php endif; ?>>
    </div>
    <div class="col-xs-4 col-lg-3">
        <input class="form-control" type="text" maxlength="16" name="invadr[oxuser__oxstreetnr]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxstreetnr'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxstreetnr']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstreetnr->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxstreetnr')): ?> required=""<?php endif; ?>>
    </div>
    <div class="col-lg-offset-3 col-lg-9 col-xs-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxstreet'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxstreetnr'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxzip']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-xs-12 col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxzip') || $this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcity')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'POSTAL_CODE_AND_CITY'), $this);?>
</label>
    <div class="col-xs-5 col-lg-3">
        <input class="form-control" type="text" maxlength="16" name="invadr[oxuser__oxzip]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxzip'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxzip']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxzip->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxzip')): ?> required=""<?php endif; ?>>
    </div>
    <div class="col-xs-7 col-lg-6">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxcity]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxcity'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxcity']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxcity->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcity')): ?> required=""<?php endif; ?>>
    </div>
    <div class="col-lg-offset-3 col-md-9 col-xs-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxzip'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxcity'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxustid']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxustid')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'VAT_ID_NUMBER'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="255" name="invadr[oxuser__oxustid]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxustid'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxustid']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxustid->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxustid')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxustid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>


    <div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxcountryid']): ?> text-danger<?php endif; ?>">
        <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcountryid')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY'), $this);?>
</label>
        <div class="col-lg-9">
            <select class="form-control selectpicker" id="invCountrySelect" name="invadr[oxuser__oxcountryid]"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxcountryid')): ?> required=""<?php endif; ?>>
                <option value="">-</option>
                <?php $this->assign('blCountrySelected', false); ?>
                <?php $_from = $this->_tpl_vars['oViewConf']->getCountryList(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['country_id'] => $this->_tpl_vars['country']):
?>
                    <?php $this->assign('sCountrySelect', ""); ?>
                    <?php if (! $this->_tpl_vars['blCountrySelected']): ?>
                        <?php if (( isset ( $this->_tpl_vars['invadr']['oxuser__oxcountryid'] ) && $this->_tpl_vars['invadr']['oxuser__oxcountryid'] == $this->_tpl_vars['country']->oxcountry__oxid->value ) || ( ! isset ( $this->_tpl_vars['invadr']['oxuser__oxcountryid'] ) && $this->_tpl_vars['oxcmp_user']->oxuser__oxcountryid->value == $this->_tpl_vars['country']->oxcountry__oxid->value )): ?>
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
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxcountryid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3" for="<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstateid->value; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'DD_USER_BILLING_LABEL_STATE'), $this);?>
</label>
        <div class="col-lg-9">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/fieldset/state.tpl", 'smarty_include_vars' => array('countrySelectId' => 'invCountrySelect','stateSelectName' => "invadr[oxuser__oxstateid]",'selectedStateIdPrim' => $this->_tpl_vars['invadr']['oxuser__oxstateid'],'selectedStateId' => $this->_tpl_vars['oxcmp_user']->oxuser__oxstateid->value,'class' => "form-control selectpicker")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="help-block"></div>
        </div>
    </div>


<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxfon']): ?> text-danger"<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfon')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'PHONE'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="128" name="invadr[oxuser__oxfon]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxfon'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxfon']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfon->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfon')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxfon'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxfax']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfax')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'FAX'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="128" name="invadr[oxuser__oxfax]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxfax'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxfax']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfax->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxfax')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxfax'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxmobfon']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxmobfon')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'CELLUAR_PHONE'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="64" name="invadr[oxuser__oxmobfon]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxmobfon'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxmobfon']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxmobfon->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxmobfon')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxmobfon'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<div class="form-group<?php if ($this->_tpl_vars['aErrors']['oxuser__oxprivfon']): ?> text-danger<?php endif; ?>">
    <label class="control-label col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxprivfon')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'PERSONAL_PHONE'), $this);?>
</label>
    <div class="col-lg-9">
        <input class="form-control" type="text" maxlength="64" name="invadr[oxuser__oxprivfon]" value="<?php if (isset ( $this->_tpl_vars['invadr']['oxuser__oxprivfon'] )): ?><?php echo $this->_tpl_vars['invadr']['oxuser__oxprivfon']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxprivfon->value; ?>
<?php endif; ?>"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxprivfon')): ?> required=""<?php endif; ?>>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxprivfon'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <div class="help-block"></div>
    </div>
</div>

<?php if ($this->_tpl_vars['oViewConf']->showBirthdayFields()): ?>
    <div class="form-group oxDate<?php if ($this->_tpl_vars['aErrors']['oxuser__oxbirthdate']): ?> text-danger<?php endif; ?>">
        <label class="control-label col-xs-12 col-lg-3<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxbirthdate')): ?> req<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'BIRTHDATE'), $this);?>
</label>
        <div class="col-xs-3 col-lg-3">
            <input id="oxDay" class="oxDay form-control" name="invadr[oxuser__oxbirthdate][day]" type="text" maxlength="2" value="<?php if ($this->_tpl_vars['iBirthdayDay'] > 0): ?><?php echo $this->_tpl_vars['iBirthdayDay']; ?>
<?php endif; ?>" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'DAY'), $this);?>
"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxbirthdate')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-xs-6 col-lg-3">
            <select class="oxMonth form-control selectpicker" name="invadr[oxuser__oxbirthdate][month]"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxbirthdate')): ?> required=""<?php endif; ?>>
                <option value="" label="-">-</option>
                <?php unset($this->_sections['month']);
$this->_sections['month']['name'] = 'month';
$this->_sections['month']['start'] = (int)1;
$this->_sections['month']['loop'] = is_array($_loop=13) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['month']['show'] = true;
$this->_sections['month']['max'] = $this->_sections['month']['loop'];
$this->_sections['month']['step'] = 1;
if ($this->_sections['month']['start'] < 0)
    $this->_sections['month']['start'] = max($this->_sections['month']['step'] > 0 ? 0 : -1, $this->_sections['month']['loop'] + $this->_sections['month']['start']);
else
    $this->_sections['month']['start'] = min($this->_sections['month']['start'], $this->_sections['month']['step'] > 0 ? $this->_sections['month']['loop'] : $this->_sections['month']['loop']-1);
if ($this->_sections['month']['show']) {
    $this->_sections['month']['total'] = min(ceil(($this->_sections['month']['step'] > 0 ? $this->_sections['month']['loop'] - $this->_sections['month']['start'] : $this->_sections['month']['start']+1)/abs($this->_sections['month']['step'])), $this->_sections['month']['max']);
    if ($this->_sections['month']['total'] == 0)
        $this->_sections['month']['show'] = false;
} else
    $this->_sections['month']['total'] = 0;
if ($this->_sections['month']['show']):

            for ($this->_sections['month']['index'] = $this->_sections['month']['start'], $this->_sections['month']['iteration'] = 1;
                 $this->_sections['month']['iteration'] <= $this->_sections['month']['total'];
                 $this->_sections['month']['index'] += $this->_sections['month']['step'], $this->_sections['month']['iteration']++):
$this->_sections['month']['rownum'] = $this->_sections['month']['iteration'];
$this->_sections['month']['index_prev'] = $this->_sections['month']['index'] - $this->_sections['month']['step'];
$this->_sections['month']['index_next'] = $this->_sections['month']['index'] + $this->_sections['month']['step'];
$this->_sections['month']['first']      = ($this->_sections['month']['iteration'] == 1);
$this->_sections['month']['last']       = ($this->_sections['month']['iteration'] == $this->_sections['month']['total']);
?>
                    <option value="<?php echo $this->_sections['month']['index']; ?>
" label="<?php echo $this->_sections['month']['index']; ?>
" <?php if ($this->_tpl_vars['iBirthdayMonth'] == $this->_sections['month']['index']): ?> selected="selected" <?php endif; ?>>
                        <?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp='MONTH_NAME_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_sections['month']['index']) : smarty_modifier_cat($_tmp, $this->_sections['month']['index']))), $this);?>

                    </option>
                <?php endfor; endif; ?>
            </select>
        </div>
        <div class="col-xs-3 col-lg-3">
            <input id="oxYear" class="oxYear form-control" name="invadr[oxuser__oxbirthdate][year]" type="text" maxlength="4" value="<?php if ($this->_tpl_vars['iBirthdayYear']): ?><?php echo $this->_tpl_vars['iBirthdayYear']; ?>
<?php endif; ?>" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'YEAR'), $this);?>
"<?php if ($this->_tpl_vars['oView']->isFieldRequired('oxuser__oxbirthdate')): ?> required=""<?php endif; ?>>
        </div>
        <div class="col-lg-offset-3 col-lg-9 col-xs-12">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/inputvalidation.tpl", 'smarty_include_vars' => array('aErrors' => $this->_tpl_vars['aErrors']['oxuser__oxbirthdate'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="help-block"></div>
        </div>
    </div>
<?php endif; ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9 col-xs-12">
        <p class="alert alert-info"><?php echo smarty_function_oxmultilang(array('ident' => 'COMPLETE_MARKED_FIELDS'), $this);?>
</p>
    </div>
</div>

<?php if (! $this->_tpl_vars['noFormSubmit']): ?>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9 col-xs-12">
            <button id="accUserSaveTop" type="submit" name="save" class="btn btn-primary"><?php echo smarty_function_oxmultilang(array('ident' => 'SAVE'), $this);?>
</button>
        </div>
    </div>
<?php endif; ?>