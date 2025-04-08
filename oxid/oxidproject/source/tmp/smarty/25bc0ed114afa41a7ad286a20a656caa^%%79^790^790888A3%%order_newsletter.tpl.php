<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/order_newsletter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'form/fieldset/order_newsletter.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['blSubscribeNews']): ?>
    <div class="form-group">
        
            <div class="col-lg-9 col-lg-offset-3">
                <input type="hidden" name="blnewssubscribed" value="0">
                <div class="checkbox">
                    <label for="subscribeNewsletter">
                        <input type="checkbox" name="blnewssubscribed" id="subscribeNewsletter" <?php if ($this->_tpl_vars['oView']->isNewsSubscribed()): ?>checked<?php endif; ?> value="1"> <?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER_SUBSCRIPTION'), $this);?>

                    </label>
                </div>
                <span class="help-block"><?php echo smarty_function_oxmultilang(array('ident' => 'MESSAGE_NEWSLETTER_SUBSCRIPTION'), $this);?>
</span>
            </div>
        
    </div>
<?php endif; ?>