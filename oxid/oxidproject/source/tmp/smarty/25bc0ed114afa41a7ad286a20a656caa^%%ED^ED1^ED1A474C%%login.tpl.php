<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:38
         compiled from form/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxscript', 'form/login.tpl', 1, false),array('function', 'oxmultilang', 'form/login.tpl', 13, false),array('function', 'oxgetseourl', 'form/login.tpl', 30, false),array('modifier', 'cat', 'form/login.tpl', 30, false),)), $this); ?>
<?php echo smarty_function_oxscript(array('include' => "js/libs/jqBootstrapValidation.min.js",'priority' => 10), $this);?>

<?php echo smarty_function_oxscript(array('add' => "$('input,select,textarea').not('[type=submit]').jqBootstrapValidation();"), $this);?>


<form class="js-oxValidate" name="login" action="<?php echo $this->_tpl_vars['oViewConf']->getSslSelfLink(); ?>
" method="post" role="form" novalidate="novalidate">
    <div class="hidden">
        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

        <?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

        <input type="hidden" name="fnc" value="login_noredirect">
        <input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveClassName(); ?>
">
    </div>
    <div class="panel panel-default" id="optionLogin">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo smarty_function_oxmultilang(array('ident' => 'ALREADY_CUSTOMER'), $this);?>
</h3>
        </div>
        <div class="panel-body">
            
                <p><?php echo smarty_function_oxmultilang(array('ident' => 'LOGIN_DESCRIPTION'), $this);?>
</p>
            
            <?php $this->assign('aErrors', $this->_tpl_vars['oView']->getFieldValidationErrors()); ?>
            <div class="form-group <?php if ($this->_tpl_vars['aErrors']): ?>oxInValid<?php endif; ?>">
                <label class="control-label"><?php echo smarty_function_oxmultilang(array('ident' => 'EMAIL_ADDRESS'), $this);?>
</label>
                <input type="text" name="lgn_usr" class="form-control textbox js-oxValidate js-oxValidate_notEmpty" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'EMAIL_ADDRESS'), $this);?>
" required="required">
                <div class="help-block"></div>
            </div>
            <div class="form-group <?php if ($this->_tpl_vars['aErrors']): ?>oxInValid<?php endif; ?>">
                <label class="control-label"><?php echo smarty_function_oxmultilang(array('ident' => 'PASSWORD'), $this);?>
</label>
                <div class="input-group">
                    <input type="password" name="lgn_pwd" class="form-control js-oxValidate js-oxValidate_notEmpty textbox stepsPasswordbox" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'PASSWORD'), $this);?>
" required="required">
                    <span class="input-group-btn">
                        <a class="btn btn-default forgotPasswordOpener" id="step2PswdOpener" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=forgotpwd") : smarty_modifier_cat($_tmp, "cl=forgotpwd"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'FORGOT_PASSWORD'), $this);?>
">?</a>
                    </span>
                </div>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button type="submit" class="btn btn-primary submitButton"><?php echo smarty_function_oxmultilang(array('ident' => 'LOGIN'), $this);?>
 <i class="fa fa-caret-right"></i></button>
        </div>
    </div>
</form>