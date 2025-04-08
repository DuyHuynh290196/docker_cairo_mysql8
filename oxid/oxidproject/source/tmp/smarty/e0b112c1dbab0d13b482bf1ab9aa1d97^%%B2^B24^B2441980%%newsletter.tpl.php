<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from widget/footer/newsletter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/footer/newsletter.tpl', 18, false),)), $this); ?>

<div class="row">
    <div class="col-12 mx-auto col-md-8 mx-md-0 col-lg-12">
        <form class="newsletter-form" role="form" action="<?php echo $this->_tpl_vars['oViewConf']->getSslSelfLink(); ?>
" method="post">
            <div class="form-group">
                
                    <div class="hidden">
                        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

                        <input type="hidden" name="fnc" value="fill">
                        <input type="hidden" name="cl" value="newsletter">
                        <?php if ($this->_tpl_vars['oView']->getProduct()): ?>
                            <?php $this->assign('product', $this->_tpl_vars['oView']->getProduct()); ?>
                            <input type="hidden" name="anid" value="<?php echo $this->_tpl_vars['product']->oxarticles__oxnid->value; ?>
">
                        <?php endif; ?>
                    </div>

                    
                        <label class="sr-only" for="footer_newsletter_oxusername"><?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER'), $this);?>
</label>
                        <div class="input-group">
                            <input type="text" class="form-control" type="email" name="editval[oxuser__oxusername]" placeholder="<?php echo smarty_function_oxmultilang(array('ident' => 'EMAIL'), $this);?>
" aria-label="<?php echo smarty_function_oxmultilang(array('ident' => 'EMAIL'), $this);?>
">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><?php echo smarty_function_oxmultilang(array('ident' => 'SUBSCRIBE'), $this);?>
</button>
                            </div>
                        </div>
                    
                
            </div>
        </form>
    </div>
</div>